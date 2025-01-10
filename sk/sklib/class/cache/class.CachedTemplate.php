<?php
/*
 * Class CachedTemplate
 * by Jesus M. Castagnetto (jmcastagnetto@zkey.com)
 * (c) 2000-2001. Version 1.4
 * License: GPL - http://www.fsf.org/copyleft/gpl.txt
 *
 * $Id: class.CachedTemplate.php,v 1.20 2001/02/07 01:27:21 jesus Exp $
 *
 * Description:
 * This general class implements methods to cache the output to a file. 
 * This would be useful for cases in which a template is used for both, 
 * the static pages in a web site, and pages created from PHP scripts. 
 * In this way we will avoid processing pages that do not change too often.
 *
 * This class extends the TemplateAdaptor class which is the one implementing
 * the interface between the original template class and this class.
 *
 * This class assumes that the adaptor class implements the getTemplatesList()
 * and the init() methods.
 *
 * Changes:
 * 2000/06/10 - Initial release.
 * 2000/07/17 - Documentation, added support for GET query strings, new release.
 * 2000/07/18 - Added support for external data validation using timestamp or
 *              md5 hash signatures - not released.
 * 2000/07/30 - Finally got time to implement a general use of variables and 
 *              values when generating cache filenames, GET string support is
 *              a case.
 * 2000/08/04 - Minor typo in $release var name in _key_in_array() method
 * 2000/10/06 - Added lock file support to avoid run conditions while
 *              generating a cache file.
 * 2000/02/06 - Added method to read from a cached file into a variable, to
 *              allow for partial caching of content in a page (or pages)
 * 
 */


class CachedTemplate extends TemplateAdaptor {

    var $CACHEDIR = "./cache";      // directory to save cached files
    var $CACHELENGTH = 24;          // length of caching, 30 units.
	var $TIMEUNIT = "hour";			// time unit
	var $TIMEUNITSARR = array ("sec"=>1, "min"=>60, "hour"=>3600, "day"=>86400);
	var $USEVARS = false;
	var $VARSVAL = false;
    var $USELOCK = true;
    var $WAITLOCK = 100000;     // 100,000 microseconds = 100 ms
    var $MAXWAITLOCKCYCLES = 20;

	/******************/
	/* public methods */
	/******************/

	/* the constructor */
    function CachedTemplate($cachedir="", $cachelen="", $timeunit="") {
        if (!empty($cachedir))
            $this->set_cache_dir($cachedir);
        if (!empty($cachelen))
            $this->set_cache_length($cachelen);
        if (!empty($timeunit))
            $this->set_time_unit($timeunit);
    }

	/* sets the cache directory */
    function set_cache_dir($dir) {
        if (substr($dir,(strlen($dir) - 1), 1) == "/") {
            $dir = substr($dir,0,-1);
        }
        $this->CACHEDIR = $dir;
    }

	/* sets the cache time length */
    function set_cache_length($length) {
        $this->CACHELENGTH = $length;
    }

	/* sets the unit for measuring the lifetime of the cached file */
	function set_time_unit($str) {
		$this->TIMEUNIT = $this->_is_valid_time_unit($str) ? $str : "day";
	}

	function use_get ($how = "in_name") {
		$this->use_vars("QUERY_STRING",$how);
	}

	/* use var/val pairs when generating caching data */
	function use_vars ($vars = "QUERY_STRING", $how = "in_name") {
		$this->USEVARS = $how;
		if ($vars == "QUERY_STRING") {
			$vars = $GLOBALS["QUERY_STRING"];
			$this->VARSVAL = $vars;
		} elseif (is_array($vars)) {
			$this->VARSVAL = $this->_gen_var_val($vars);
		} else {
			$this->VARSVAL = $vars;
		}
	}

	/* write file to cache */
    function write_to_cache($content, $datacheck="", $filename="") {
        if (empty($filename))
            $filename = $this->_gen_filename();
	
		// check if we need to store the var/val string in a file
		if ($this->USEVARS == "store" && !empty($this->VARSVAL)) {
			$fp = fopen($this->CACHEDIR."/".$filename.".vars", "w");
			fwrite($fp,$this->VARSVAL);
			fclose($fp);
		}

        // if there is a lock, some other instance is updating this
        // file, so there is no need for updating it again
        if (!$this->mk_lock($filename)) {
            return true;
        }
        
        // write the contents
        $fp = fopen($this->CACHEDIR."/".$filename.".cache", "w");
        fwrite($fp,$content);
        fclose($fp);
        // write the cache control file
        $timestamp = $this->_mktimestamp();
		if (empty($datacheck))
			$datacheck = $timestamp;
        $fp = fopen($this->CACHEDIR."/".$filename.".cntrl", "w");
        fwrite($fp, $timestamp.":".$this->CACHELENGTH.":".$this->TIMEUNIT.":".$datacheck);
        fclose($fp);

        $this->rm_lock($filename);
    }

	/* read cached file */
    function read_from_cache($filename="") {
        if (empty($filename))
            $filename = $this->_gen_filename();

        if ($this->USELOCK) {
            $wait_lock_cycles = 0;
            $max_wait = ($this->MAXWAITLOCKCYCLES * $this->WAITLOCK)/1000;
            $lockfile = $this->CACHEDIR."/".$filename.".lock";
        }

        if ($this->is_cached($filename)) {
            // if it is locked, sleep for $WAITLOCK microseconds and try again
            // and if after $MAXWAITLOCKCYCLES the lock has not been released
            // produce and error
            while ($this->is_locked($filename)) {
                usleep($this->WAITLOCK);
                $wait_lock_cycles++;
                if ($wait_lock_cycles > $this->MAXWAITLOCKCYCLES) {
                    $err = "<b>*FATAL ERROR* Cannot read cached file, ";
                    $err .= "lock exists and has not been cleared ";
                    $err .= "after ".$max_wait." milliseconds</b> ";
                    $err .= "(lock file: ".$lockfile.")";
                    echo $err;
                    return false;
                }
            }
            readfile($this->CACHEDIR."/".$filename.".cache");
            return true;
        } else {
            return false;
        }
    }


	/* modified from code contributed by M. Casanova */
	/* get the contents of the cached file into a variable*/
	function get_from_cache($filename="") {
        if (empty($filename))
            $filename = $this->_gen_filename();

        if ($this->USELOCK) {
            $wait_lock_cycles = 0;
            $max_wait = ($this->MAXWAITLOCKCYCLES * $this->WAITLOCK)/1000;
            $lockfile = $this->CACHEDIR."/".$filename.".lock";
        }

       // if it is locked, sleep for $WAITLOCK microseconds and try again
       // and if after $MAXWAITLOCKCYCLES the lock has not been released
       // produce and error
       while ($this->is_locked($filename)) {
           usleep($this->WAITLOCK);
           $wait_lock_cycles++;
           if ($wait_lock_cycles > $this->MAXWAITLOCKCYCLES) {
               $err = "<b>*FATAL ERROR* Cannot read cached file, ";
               $err .= "lock exists and has not been cleared ";
               $err .= "after ".$max_wait." milliseconds</b> ";
               $err .= "(lock file: ".$lockfile.")";
               echo $err;
               return "";
           }
       }
        if ($this->is_cached($filename)) {
			return implode('',file($this->CACHEDIR."/".$filename.".cache"));
        } else {
            return "";
        }
	} 
	

	/* if a file is in cache */
    function is_cached($filename) {
		$files_exist = is_file($this->CACHEDIR."/".$filename.".cache") &&
            		    is_file($this->CACHEDIR."/".$filename.".cntrl");
		if ($this->USEVARS == "store" && !empty($this->VARSVAL))
			$files_exist = $files_exist && is_file($this->CACHEDIR."/".$filename.".vars");
		return $files_exist;
    }

	/* if a cached file is a valid one */
    function valid_cache_file($filename="") {
        if (empty($filename)) 
            $filename = $this->_gen_filename();

		// if we are using variables stored as a url query string, compare them
		if ($this->USEVARS == "store" && is_file($this->CACHEDIR."/".$filename.".vars") ) {
			$stored_qstr = implode("", file($this->CACHEDIR."/".$filename.".vars")); 
			if ($this->VARSVAL != $stored_qstr)
				return false;
		}

		// look at the included template files
        if ($this->is_cached($filename)) {
            $info = file($this->CACHEDIR."/".$filename.".cntrl");
			
			//$val array: 0 = timestamp, 1 = cache length, 2 = time unit
            $val = explode(":", $info[0]);

			// check if we got a valid time unit
			if (!$this->_is_valid_time_unit($val[2]))
				return false;
			
			// check fileHandles time vs. control time
 			if (filemtime($GLOBALS["SCRIPT_FILENAME"]) >= $val[0])
 				return false;
			// the TemplateAdaptor class needs to implement the method below
			$tpls = $this->getTemplatesList();
 			if (count($tpls) > 0 ) {
 				while (list($index, $fn) = each($tpls)) {
 					if ( is_file($fn) && (filemtime($fn) >= $val[0]) )
 						return false;
 				}
 			}
 			// end check fileHandles
 
            $today = $this->_mktimestamp();
            return ( $this->_diff_time($today, $val[0], $val[2]) <= $val[1]);
        } else {
            return false;
        }
    }

	/* to check if the data is stale */
	function is_data_valid ($datacheck, $type="timestamp", $filename="") {
        if (empty($filename))
			$filename = $this->_gen_filename();
		
		$fpath = $this->CACHEDIR."/".$filename;
		if (!is_file($fpath.".cntrl"))
			return false;
		$info = file($fpath.".cntrl");

		// Description
		// $val array: 0 = timestamp, 1 = cache length, 
		//             2 = time unit, 3 = data check value

		list($ts, $cl, $tu, $cached_dcheck) = explode(":", $info[0]);
		if ($type == "timestamp") {
			return ($datacheck <= $cached_dcheck);
		} elseif ($type == "md5") {
			return ($datacheck == $cached_dcheck);
		} else {
			return false;
		}
	}

    /* methods to handle cache file locking */

    /* set or unset the locking mechanism */
    function set_use_lock () {
        $this->USELOCK = true;
    }

    function unset_use_lock () {
        $this->USELOCK = false;
    }

    /* check is lock file exists */
    function is_locked ($filename) {
        $lockfile = $this->CACHEDIR."/".$filename.".lock";
        return ($this->USELOCK && file_exists($lockfile) && is_file($lockfile) );
    }

    /* create a lock file */
    function mk_lock ($filename) {
        if ($this->is_locked($filename)) {
            return false;
        } else {
            $lockfile = $this->CACHEDIR."/".$filename.".lock";
            return touch($lockfile);
        }
    }

    /* remove the lock file */
    function rm_lock ($filename) {
        if ($this->is_locked($filename)) {
            $lockfile = $this->CACHEDIR."/".$filename.".lock";
            return unlink($lockfile);
        } else {
            return false;
        }
    }


	/* for benchmarking, idea ripped off shamelessly from CDI's FastTemplate */
	function utime () {
		list($usec, $sec) = explode( " ", microtime());
		return (double)$sec + (double)$usec;
    }

	/*******************/
	/* private methods */
	/*******************/

	/* generate var/val pairs in QUERY_STRING form */
	function _gen_var_val ($vars) {
  		while (list($k,$v) = each($vars))
			$out[] = $k."=".rawurlencode($v);
		return implode("&",$out);
	}

	/* generates a unique filename based on the file to be cached */
	function _gen_filename () {
		$filename = str_replace("/", "_", $GLOBALS["PHP_SELF"]);
		if ($this->USEVARS == "in_name" && !empty($this->VARSVAL))
			$filename .= rawurlencode($this->VARSVAL);
		return $filename;
	}

	// To support old PHP3 versions ( older than 3.0.12 )
	function _key_in_array($element, $arr) {
		 // figure out version
		 list($major, $minor, $release) = explode(".", phpversion());
		 if (($major == 3 && $minor == 0 && $release >= 12) || $major == 4) {
			 return in_array($element, array_keys($arr));
		 } else {
			 // assumes that we want to compare element value
			 while (list($key, $val) = each($arr)) {
				 if ($key == $element)
					 return true;
			 }
			 return false;
		 }
	}

	function _is_valid_time_unit($str) {
		return $this->_key_in_array($str, $this->TIMEUNITSARR);
	}	

    function _mktimestamp() {
		return time();
    }

    function _diff_time($end, $start, $timeunit="day") {
		$factor = $this->TIMEUNITSARR[$timeunit];
        return intval(($end - $start)/$factor);
    }

} // end of class definition
?>
