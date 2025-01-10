<?php
/***************************************
** Title........: Site-Komposer Utility-functions
** Filename.....: sk_util.inc
** Author.......: Edgar Bueltemeyer
** Version......: 1.2
** Notes........:
** Last changed.: 15/12/2003
** Last change..: 
***************************************/

// function to print all elements of an array

  function print_array($arrayvar,$entities=0) {
	  $array=$arrayvar;
    $output="";
    if(gettype($array)=="object") $array=get_object_vars($array);
    if(gettype($array)=="array") {    
    $output.="<ul>";
    foreach( $array as $index => $subarray){
      if(!is_numeric($index) && $index=='DEBUG_OUTPUT') continue;
      $output.="<li>$index=";
      $output.=print_array($subarray,$entities);
      $output.="</li>";
    }
    $output.="</ul>";
    } elseif(gettype($arrayvar)=="string") {
    	$entities==1 ?$output.=htmlentities($array, ENT_QUOTES, "UTF-8") : $output.=$array;
    }
    return $output;
  }

// ************************************************
// *   function for uploading, overwriting, deleting  files
// * usage:
// * if $oldfilename is set -> deletes oldfilename
// * if $file_name exists uploads file
// * returns actual filename
// * last modified: 14.October 2001
// ************************************************

  function skfile(&$file,$file_name ="",$path,$oldfilename ="") {
      $newfilename="";

	  DEBUG_out(3,"debug3","skfile:".$file." filename:".$file_name);
	  
      if ($oldfilename > "" AND file_exists($path.$oldfilename)) unlink($path.$oldfilename);

      if ($file_name >''):
        $extension=strrchr ($file_name, ".");
        $basename=str_replace(" ","_",substr ($file_name, 0, -strlen($extension))); 
        $tempname=$basename.$extension;
        $i=1;
        WHILE (file_exists($path.$tempname)) {
          $tempname=$basename."$i".$extension;
          $i++;
        }
        $newfilename=$tempname;
        //echo $newfilename;
        copy($file, $path.$newfilename);
        chmod($path.$newfilename,0775);
      endif;
      return $newfilename;
  }

  function skfile2(&$file,$file_name ="",$path,$oldfilename ="") {
      $newfilename="";

      DEBUG_out(3,"debug3","skfile:".$file." filename:".$file_name);

      if ($oldfilename > "" AND file_exists($path.$oldfilename)) unlink($path.$oldfilename);

      if ($file_name >''):
        $extension=strrchr ($file_name, ".");
        $basename=str_replace(" ","_",substr ($file_name, 0, -strlen($extension))); 
        $tempname=$basename.$extension;
        $i=1;
        WHILE (file_exists($path.$tempname)) {
          $tempname=$basename."$i".$extension;
          $i++;
        }
        $newfilename=$tempname;
        //echo $newfilename;
        //copy($file, $path.$newfilename);
        //chmod($path.$newfilename,0775);
      endif;
      return $newfilename;
  }
// ************************************************
// * reads files in directory and
// * returns file-select formfield
// * last modified: 10.Mar 2002
// ************************************************

 function skfileselect($dir="",$currfile="",$selectvar="file",$extensions="",$noselectmsg=0,$hideemtydir=0,$default_file="",$default_title="Datei wählen...") {

    if($extensions>"") $ext_ary=explode(",",$extensions);
    else $ext_ary=array(".html",".php");

    if(file_exists($dir)){
    $output='<select name="'.$selectvar.'" size="1">';
    if($noselectmsg==0) $output.="<option value=\"".$default_file."\">".$default_title."</option>";
    /* Get Templates from template-dir */
    $templateoptions="";
    $handle=opendir($dir);
    $i=0;
    DEBUG_out(3,"debug3","skfileselect:$dir<br> $file -".print_array($ext_ary));
    while ($file = readdir($handle)) {
        DEBUG_out(4,"debug4","skfileselect:$file -".strrchr($file,"."));
        if ($file != "." && $file != ".." && strpos($file,"._")===false && in_array(strrchr(strtolower($file),"."),$ext_ary)) {
        $selected="";
        if ($file==$currfile ) $selected="selected";
            $templateoptions_ary[$i].="<OPTION value=\"$file\" $selected>$file</OPTION>\n";
        $i++;
        }
    }
    if(is_array($templateoptions_ary)){
    sort($templateoptions_ary);
    $templateoptions=implode("",$templateoptions_ary);
    }
    closedir($handle);
    $output.=$templateoptions."</select>";

    if($i==0 && $hideemtydir==1)return; else return $output;
    }elseif($hideemtydir==0){
	    return "supplied directory $dir not found!";
    }else{
	    return;
    }

}

// /////////////////////
// Function Name   : connectadm
// Function Purpose: connects to db system as admin user
// Recieves        : 
// Returns         : 
// * last modified: 23.June 2004
// /////////////////////

function connectadm(){
              global $dbhost;
              global $dbname;
              global $db_admin_name;
              global $db_admin_pw;
              global $db_user_name;
              global $db_user_pw;
              global $skdbadm;
              global $skdb;
            
              DEBUG_out(2,"debug2","open admin db connection!:");
              if($db_admin_name==$db_user_name && $db_admin_pw==$db_user_pw) {
              	DEBUG_out(2,"debug2","OPEN ADMIN DB-Conn: admin db connection the same, skipping...:");
              	//$skdbadm=&$skdb;
              	$GLOBALS['skdbadm']=$GLOBALS['skdb'];
              }else{
              
              $skdbadm = NewADOConnection();
              $skdbadm->Connect($dbhost, $db_admin_name, $db_admin_pw, $dbname);
              $skdbadm->debug = false;
              $skdbadm->EXECUTE("SET NAMES 'UTF8'");
              }
       }


// /////////////////////
// Function Name   : closeadm
// Function Purpose: disconnects from db system as admin user
//                   checks if persistant_db connections are allowed
//                   if not reconnect normal db connection
// Recieves        : 
// Returns         : 
// * last modified: 23.June 2004
// /////////////////////

function closeadm(){
              global $dbhost;
              global $dbname;
              global $db_admin_name;
              global $db_admin_pw;
              global $db_user_name;
              global $db_user_pw;
              global $skdbadm;
              global $skdb;
              global $db_type;

              DEBUG_out(2,"debug2","close admin db connection!");
              if($db_admin_name==$db_user_name && $db_admin_pw==$db_user_pw) {
              	// Connections are the same. Do nothing.....
              	DEBUG_out(2,"debug2","CLOSE ADMIN DB-Conn: admin db connection the same, skipping...:");
              }else{
	              $skdbadm->close();
	
	              if(!ini_get($db_type.'.allow_persistent')) {
	                 DEBUG_out(2,"error2","persistent connections not allowed! Doing workaround...");
	                /* Connect to databse */
	                global $skdb;
	                /* Initial Connection to database-object $skdb. Should be used for all outputs!  */
	                //$skdb = NewADOConnection();
	                $skdb->Connect($dbhost, $db_user_name, $db_user_pw, $dbname);
	                $skdb->debug = false;
	                $skdb->EXECUTE("SET NAMES 'UTF8'");
	              }
	              else
	              {
	               DEBUG_out(2,"debug2","persistent connections allowed, OK!");
	              }
              }
       }

// /////////////////////
// Function Name   : validate
// Function Purpose: Validates the user's ticket and makes sure they are logged in
// Recieves        : $username - the username of the current user;
//                   $session_key - the session key of the user;
//                   $expriation_time - how long till the ticket expires?;
//                   $hash - hash of all three of those + server secret
// Returns         : 1 if validated, 0 otherwise
// /////////////////////
function validate($username,$session_key,$expiration_time,$hash) {

  $secret = md5("sdfkjsdkflhkh23hkjsdk#x@%x#%DSF");

  $h = md5($username.$session_key.$expiration_time.$secret);

  if($hash == $h && time() < $expiration_time):
    return 1;
  else:
    return 0;
  endif;
}


/********************************************************************
*    Date - form field function.
*    Its return a drop-down-menu form-field
*   Syntax :
*        - string listbox_date(string name, int selected-date);
*        - string listbox_month(string name, int selected-month);
*        - string listbox_year(string name, int start-year, int end-year, int selected-year);
*
*    Copyright (C) 2001 Wibisono Sastrodiwiryo.
*       This program is free software licensed under the
*       GNU General Public License (GPL).
*
*   CyberGL => Application Service Provider
*   http://www.cybergl.co.id
*    office@cybergl.co.id
*
*   $Id: date.php3,v 0.1 2001/04/24 21:6:31 wibi Exp $
*********************************************************************/

function listbox_date ($name, $default=0) {
    $result="<select name=\"$name\" size=1>\n";
    for ($d=1;$d<=31;$d++) {
        if ($default  == $d) {$selected="selected";} else {$selected="";}
        $result.="<option value=\"$d\" $selected>$d</option>\n";
    }
    $result.="</select>\n";
return $result;
}

function listbox_month ($name, $default=0) {
    $result="<select name=\"$name\" size=1>\n";
    for ($m=1;$m<=12;$m++) {
        if ($default  == $m) {$selected="selected";} else {$selected="";}
        $result.="<option value=\"$m\" $selected>".date(M, mktime(0,0,0,$m,1,2000))."</ option>\n";
    }
    $result.="</select>\n";
return $result;
}

function listbox_year ($name, $start, $end, $default=0) {
    $result="<select name=\"$name\" size=1>\n";
    for ($y=$start;$y<=$end;$y++) {
        if ($default  == $y) {$selected="selected";} else {$selected="";}
        $result.="<option value=\"$y\" $selected>$y</option>\n";
    }
    $result.="</select>\n";
return $result;
}

  /*
    ** Function: DateSelector
    ** Version v2.0
    ** Last Updated: 2000-05-01
    ** Author: Leon Atkinson <leon@leonatkinson.com>
    ** Creates three form fields for get month/day/year
    ** Input: Prefix to name of field, default date
    ** Output: HTML to define three date fields
    */
    function DateSelector($inName, $useDate=0)
    {
        //create array so we can name months
        $monthName = array(1=> "January",  "February",  "March",
            "April",  "May",  "June",  "July",  "August",
            "September",  "October",  "November",  "December");

        //if date invalid or not supplied, use current time
        if($useDate == 0)
        {
            $useDate = Time();
        }

        /*
        ** make month selector
        */
        print("<select name=" . $inName .  "Month>\n");
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++)
        {
            print("<option value=\"");
            print(intval($currentMonth));
            print("\"");
            if(intval(date( "m", $useDate))==$currentMonth)
            {
                print(" selected");
            }
            print(">" . $monthName[$currentMonth] .  "\n");
        }
        print("</select>");


        /*
        ** make day selector
        */
        print("<select name=" . $inName .  "Day>\n");
        for($currentDay=1; $currentDay <= 31; $currentDay++)
        {
            print("<option value=\"$currentDay\"");
            if(intval(date( "d", $useDate))==$currentDay)
            {
                print(" selected");
            }
            print(">$currentDay\n");
        }
        print("</select>");


        /*
        ** make year selector
        */
        print("<select name=" . $inName .  "Year>\n");
        $startYear = date( "Y", $useDate);
        for($currentYear = $startYear - 5; $currentYear <= $startYear+5;$currentYear++)
        {
            print("<option value=\"$currentYear\"");
            if(date( "Y", $useDate)==$currentYear)
            {
                print(" selected");
            }
            print(">$currentYear\n");
        }
        print("</select>");
    }
    
    function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
    }
    
    function timer($time_start) {
        $time_end = getmicrotime();
        $time = $time_end - $time_start;
        $milli = $time * 1000;
        //return "Duration ".sprintf("%01.4f",$time)." seconds<br>".sprintf("%01.2f",$milli)." milliseconds";
        return " ".sprintf("%01.2f",$milli)."ms";
    }
    

function replace( $matches )
{
    $text = htmlentities( $matches[0] );
    return $text[1];
}

function noaccent($string='') {

$string = preg_replace_callback( '/[\\xc0-\\xff]/', replace, $string );
return $string;
}

function safeHTML($text) {
       $text = stripslashes($text);
       $text = strip_tags($text, '<b><i><u><a>');
       $text = ereg_replace ("<a[^>]+href *= *([^ ]+)[^>]*>", "<a href=\\1>", $text);
       $text = ereg_replace ("<([b|i|u])[^>]*>", "<\\1>", $text);
       return $text;
}

function safeURL($text) {
       $text = stripslashes($text);
       $text = strip_tags($text, '');
	   $search  = array ('ä', 'ö', 'ü', 'ß');
	   $replace = array ('ae', 'oe', 'ue', 'ss');
	   $text = str_replace($search, $replace, $text);
	   $text = preg_replace('/[^A-Za-z0-9]/', '_', $text); 
       return $text;
}
function html_compress_mask($x)
{
$x = str_replace("\t", "    ", $x);
$x = str_replace(" ", "\x01", $x);
$x = str_replace("\n", "\r", $x);
 
return $x;
}
 
function html_compress($x)
{
$x = preg_replace("/\r/is", "", $x);   // remove \r
$x = preg_replace("/(<script.*?".">)<!--(.*?)-->(<\/script>)/is", "$1$2$3", $x);   // remove <!-- --> from script tags
$x = preg_replace("/<!--.*?-->/is", "", $x);   // remove comments
$x = preg_replace("/(<pre.*?".">)(.*?)(<\/pre>)/is", "\"$1\".html_compress_mask(\"$2\").\"$3\"", $x);   // prevent <pre> tags from being reformatted
$x = preg_replace("/\t/is", " ", $x);   // replace tabs by spaces
$x = preg_replace("/\n/is", " ", $x);   // replace \n by spaces
$x = str_replace("  ", " ", $x);   // replace double spaces by single spaces
$x = str_replace("  ", " ", $x);   // get away remaining double spaces
$x = str_replace("  ", " ", $x);   // get away remaining double spaces
$x = str_replace("/<br \/>/is", "<br>", $x);   // replace <br /> tag
 
// remove spaces around tags
$x = preg_replace("/<(br|div|p|t[dr]|table|[uo]l)(.*?)> /is", "<$1$2>", $x);
$x = preg_replace("/ <\/(br|div|p|t[dr]|table|[uo]l)>/is", "</$1>", $x);
 
$x = preg_replace("/\x01/is", " ", $x);   // un-mask <pre> conversions
$x = preg_replace("/\r/is", "\r\n", $x);   // un-mask <pre> conversions
 
return $x;
}

// /////////////////////
// Function Name   : debug_out
// Function Purpose: Debug-Output with style and level
// Recieves        : 
//                   $level - the debug level;
//                   $style - the output style;
//                     $dstring - the string to output;
// Returns         : 
// /////////////////////


function DEBUG_out($level=1, $style="debug1", $dstring="") {

if ($GLOBALS['debug']>($level-1)) {
    $GLOBALS['DEBUG_OUTPUT'].="<div class=\"".$style."\">".$dstring."</div>";
if (isset($GLOBALS['debug_inline']) && $GLOBALS['debug_inline']==1) echo "<div class=\"".$style."\">".$dstring."</div>";
}

}

// /////////////////////
// Function Name   : debug_window
// Function Purpose: Debug-Output in popup window
// Recieves        : 
//                 
// Returns         : 
// /////////////////////


function DEBUG_window() {

 if($GLOBALS['debug']>0) {
 	
   if(!in_array($_SERVER['REMOTE_ADDR'],$GLOBALS['debug_ips']))	exit;
   $debug_content=strip_tags($GLOBALS['DEBUG_OUTPUT'], '<b><i><u><li><ul><ol><div><span>');
   $debug_content='<img src="'.SKRES_URL.'img/debugcolors.gif"><br>'.$debug_content;
   $debug_content='<link rel="stylesheet" href="'.SKRES_URL.'skinz/'.$GLOBALS['theme'].'/css/debug.css" type="text/css">'.$debug_content;
   $debug_content=html_compress($debug_content);
   
   echo "<script type=\"text/javascript\">
   var debug='".preg_replace("/'/", "\"", preg_replace("/(\r\n|\n|\r)/", "<br>", $debug_content))."';
   openDebugWindow(debug,'debug_site_".$GLOBALS['current_site']->attributes['site_id']."');</script>";
 }
}



// /////////////////////
// Function Name   : div
// Function Purpose: emulates a DIV Arithmetic function
// Recieves        : $a $b
// Returns         : $a DIV $b
// /////////////////////
function div($a,$b){
 if($b==0) return 0;
 return ($a-($a % $b))/$b;
}

// /////////////////////
// Function Name   : URLopen
// Function Purpose: opens a http-url with fopen and returns result
// Recieves        : $url
// Returns         : $result
// /////////////////////
function URLopen($url)
{
       // Fake the browser type
       ini_set('user_agent','MSIE 4\.0b2;');
       DEBUG_out(3,"debug3","<b>URLOpen:</b>".$url);
       $dh = fopen("$url",'r');
       $result = fread($dh,8192);                                                                                                                           
       return $result;
}

// /////////////////////
// Function Name   : get_exec
// Function Purpose: executes command via http_get-wrapper
// Recieves        : $execute_cmd
// Returns         : $result
// /////////////////////
function get_exec($execute_cmd)
{      
       global $exec_wrapper;
       $url_cmd=urlencode($execute_cmd);
       if(!$exec_wrapper) $exec_wrapper='shell_exec.phpx.php';
       $retval=URLopen(SKRES_URL.'util/'.$exec_wrapper.'?execute_cmd='.$url_cmd.'&key=SK_EXC%$asxd');
       DEBUG_out(3,"debug3","<b>execute cmd:</b>".$execute_cmd);
       DEBUG_out(3,"debug3","execute returnval:".$retval);
       return $retval;
}


?>
