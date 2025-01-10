<?php
/***************************************
** Title........: SK-Template class
** Filename.....: class.sktemplate.inc
** Author.......: Edgar Bueltemeyer
** Version......: 0.4
** Notes........: extends template class from richard hayes
** Last changed.: 16/08/2002
** Last change..: 
***************************************/
require_once(CLASS_PATH."template_class/class.template.inc.php");
require_once(CLASS_PATH."cache/class.RHTemplateAdaptor.php");
require_once(CLASS_PATH."cache/class.CachedTemplate.php");

        class sktemplate extends CachedTemplate{

                var $debug = 0;
                var $editable = 0;

        /***************************************
        ** Function for parsing Site-Komposer - content
        ***************************************/

               function sk_parse($file_id){
                        global $mid;
                        $file_ids = explode(',', $file_id);
                        for(reset($file_ids); $file_id = trim(current($file_ids)); next($file_ids)){
				//parse content areas
                                while(is_long($pos = strpos(strtolower($this->files[$file_id]), "sk_content("))){
                                        $pos += 11;
                                        $endpos = strpos($this->files[$file_id], ")", $pos);
                                        $area = substr($this->files[$file_id], $pos, $endpos-$pos);
                                        $sk_content = new skcontent_area($area,$mid);

                                        $tag = substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), "{sk_content(".$area.")}"), strlen("{sk_content(".$area.")}"));
                                        IF ($this->editable==1): $this->files[$file_id] = str_replace($tag, $sk_content->editable_objects(), $this->files[$file_id]);
                                        ELSE: $this->files[$file_id] = str_replace($tag, $sk_content->display_objects(), $this->files[$file_id]);
                                        ENDIF;

                                }
				
                        }
			for(reset($file_ids); $file_id = trim(current($file_ids)); next($file_ids)){
			//parse objects
				while(is_long($pos = strpos(strtolower($this->files[$file_id]), "sk_object("))){
                                        $pos += 10;
                                        $endpos = strpos($this->files[$file_id], ")", $pos);
                                        $object_id = substr($this->files[$file_id], $pos, $endpos-$pos);
                                        
					$sk_object = new skobject();
					$sk_object->get($object_id);

                                        $tag = substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), "{sk_object(".$object_id.")}"), strlen("{sk_object(".$object_id.")}"));
                                        $this->files[$file_id] = str_replace($tag, $sk_object->display(0), $this->files[$file_id]);

                                }
			}
               }
          /***************************************
          ** Function for parsing Site-Komposer Live - content
          ***************************************/

               function sk_live_parse($file_id){
                        global $mid;
                        $file_ids = explode(',', $file_id);
                        for(reset($file_ids); $file_id = trim(current($file_ids)); next($file_ids)){
                                while(is_long($pos = strpos(strtolower($this->files[$file_id]), "sk_live_content("))){
                                        $pos += 16;
                                        $endpos = strpos($this->files[$file_id], ")", $pos);
                                        $area = substr($this->files[$file_id], $pos, $endpos-$pos);
                                        $sk_content = new skcontent_area($area,$mid);

                                        $tag = substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), "{sk_live_content(".$area.")}"), strlen("{sk_live_content(".$area.")}"));
                                        IF ($this->debug == 1) $GLOBALS['DEBUG_OUTPUT'].="editable:".$this->editable;
                                        IF ($this->editable==1): $this->files[$file_id] = str_replace($tag, $sk_content->editable_objects(), $this->files[$file_id]);
                                        ELSE: $this->files[$file_id] = str_replace($tag, $sk_content->display_objects(), $this->files[$file_id]);
                                        ENDIF;

                                }
                        }
               }
               
          /***************************************
          ** Function for parsing Site-Komposer News - content
          ***************************************/

               function sk_news_parse($file_id){
                        global $mid;
                        $file_ids = explode(',', $file_id);
                        for(reset($file_ids); $file_id = trim(current($file_ids)); next($file_ids)){
                                while(is_long($pos = strpos(strtolower($this->files[$file_id]), "sk_news_content("))){
                                        $pos += 16;
                                        $endpos = strpos($this->files[$file_id], ")", $pos);
                                        $vars = substr($this->files[$file_id], $pos, $endpos-$pos);
                                        $variables = explode(",",$vars);
                                        $sk_news = new skobject();
                                        $sk_news->attributes['type']="news";
                                        $sk_news->attributes_vars['template']=$variables[0];
                                        $sk_news->attributes_vars[section_id]=$variables[1];
                                        $sk_news->attributes_vars[detail_link]=$variables[2];
                                        $tag = substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), "{sk_news_content(".$vars.")}"), strlen("{sk_news_content(".$vars.")}"));
                                        IF ($this->debug == 1) $GLOBALS['DEBUG_OUTPUT'].="sk_news_object, editable:".$this->editable;$sk_news->debug=1;
                                        $this->files[$file_id] = str_replace($tag, $sk_news->display(), $this->files[$file_id]);
                                        

                                }
                        }
               }
        /***************************************
        ** Function for parsing Site-Komposer - menus
        ***************************************/

               function sk_menu_parse($file_id){
                        global $mid;
                        global $menu_footer;
                        global $menu_header;
                        global $menu_links;
                        $file_ids = explode(',', $file_id);
                        // ********** Menu - Header *********
                        for(reset($file_ids); $file_id = trim(current($file_ids)); next($file_ids)){
                                while(is_long($pos = strpos(strtolower($this->files[$file_id]), "menu_header"))){

                                        
                                        $tag = "{menu_header}";
                                        $this->files[$file_id] = str_replace($tag, $menu_header, $this->files[$file_id]);
                               }
                        }
                        $pos=0;
                        //*********** Menu - Links ************
                        for(reset($file_ids); $file_id = trim(current($file_ids)); next($file_ids)){
                                while(is_long($pos = strpos(strtolower($this->files[$file_id]), "menu_links"))){
                                        
                                        $tag = "{menu_links}";
                                        $this->files[$file_id] = str_replace($tag, $menu_links, $this->files[$file_id]);
                               }
                        }
                        $pos=0;
                        //*********** Menu - Footer ************
                        for(reset($file_ids); $file_id = trim(current($file_ids)); next($file_ids)){
                                while(is_long($pos = strpos(strtolower($this->files[$file_id]), "menu_footer"))){
                                        
                                        $tag = "{menu_footer}";
                                        $this->files[$file_id] = str_replace($tag, $menu_footer, $this->files[$file_id]);
                               }
                        }
               }

    /* read cached file back into template_engine */
    function read_from_cache2tpl($filename="",$file_id) {
        $this->files[$file_id]=$this->get_from_cache($filename,$file_id);
    }

/* flush cached file */
    function flush_from_cache($filename="") {
        if (empty($filename))
            $filename = $this->_gen_filename();
            DEBUG_out(1,"bench",$filename." for delete");
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
            //echo "hallo".$this->CACHEDIR."/".$filename.".cntrl";
            unlink($this->CACHEDIR."/".$filename.".cntrl");
            unlink($this->CACHEDIR."/".$filename.".cache");
            return true;
        } else {
            return false;
        }
    }

        } // End of class
?>