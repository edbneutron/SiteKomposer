<?php
/***************************************
** Title........: SK-Object class
** Filename.....: class.skobject.inc
** Author.......: Edgar Bueltemeyer
** Version......: 0.9
** Notes........:
** Last changed.: 11/Mar/2005
** Last change..: remove debug var
***************************************/



class skobject {
             var $name="skobject";
             var $table="sk_objects";
             var $db_id="id";
             var $attributes = array();
             var $attributes_vars = array();
             var $skdbadm;

       function skobject($attributes=array(),$debug=0){
           $this->skdbadm=&$GLOBALS['skdbadm'];
           IF (is_array($attributes)):
           $this->init($attributes);
           ENDIF;
       }

       
       function init($attributes){

             $this->attributes = array( "content_id"=>"$attributes[0]",
                                "object_id"=>"$attributes[1]",
                                "type"=>"$attributes[2]",
                                "file"=>"$attributes[3]",
                                "attributes"=>stripslashes($attributes[4]),
                                "objtext"=>stripslashes($attributes[5]),
                                "sort_nr"=>"$attributes[6]",
                                "parent_node"=>"$attributes[7]",
                                "user_id"=>"$attributes[8]",
                                "last_mod"=>"$attributes[9]",
                                "by_user"=>"$attributes[10]");

            DEBUG_out(3,"debug3",$this->name." ".print_array($this->attributes,1)."<br>");
            
       } //End function init
       
       function attributes2vars(){
                IF ($this->attributes['attributes']>""):
                  $temp = array();
                  $temp2 = array();

                  $temp = explode(" ",$this->attributes['attributes']);
                  for($i=0,$max=count($temp);$i<$max;$i++){
                      $temp2 = explode("=\"",$temp[$i]);
		      if(!isset($temp2[1])) $temp2[1]=0;
                      $this->attributes_vars[$temp2[0]]=substr($temp2[1],0,-1);

                  }
                  DEBUG_out(5,"debug3","attributes2vars->".print_array($this->attributes_vars,1));
                ENDIF;

       } //End function attributes2vars

       function vars2attributes(){

                $this->attributes['attributes']="";

                while (list($key, $value) = each($this->attributes_vars) ) {
                  $this->attributes['attributes'].=$key."=\"".$value."\" ";
                }

                DEBUG_out(3,"debug3","vars2attributes->".htmlentities($this->attributes['attributes']));



       } //End function vars2attributes

       function display($editable=0){
                global $skdb;
                global $documentpath;
                global $docrelpath;
                global $news_tpl_path;
                global $imagepath1;
                global $edit_button;
                global $edit_button_small;
                global $add_button_small;
                global $formtarget;
                global $outputformat;
                $output="";
                $t_timer1=0;$t_timer2=0;$time_start=0;
                if($GLOBALS['debug']>2)$t_timer1=timer($time_start);
                
                if($this->attributes['type']>""){
                $this->attributes2vars();
                if($editable==1 && $this->attributes['user_id']!=0){
                    $author= new skuser($this->attributes['user_id']);
                    $editor= new skuser($this->attributes['by_user']);
                }
                
                IF ($editable==1){
                    include(INCLUDE_PATH."objects/editview.".$outputformat.".inc.php");
                    $output.=$edit_top;
                    }
                $includefile=INCLUDE_PATH."objects/".$this->attributes['type'].".".$outputformat.".inc.php";
                if(file_exists($includefile)) {include($includefile);}else{$output.="object_display include not found";}
                IF ($editable==1) $output.=$edit_bottom;
                }
        //IF ($this->attributes['type']=="link") $output.="<br>";
                if($GLOBALS['debug']>2)$t_timer2=timer($time_start);
                DEBUG_out(3,"bench3","display_object:".$this->attributes['type']." ".($t_timer2-$t_timer1)."ms");
                return $output;
                

       } //End function display




       function get($object_id=0){
              global $skdb;
              global $messages;
              global $time_start;
              
          $t_timer1=0;$t_timer2=0;
          if($object_id<>0) $this->attributes['object_id']=$object_id;
              if (isset($this->attributes['object_id'])){
              if($GLOBALS['debug']>3)$t_timer1=timer($time_start);
              $objlistqry = "SELECT sk_content.id, sk_objects.id, type, file, attributes, objtext,sort_nr,mid
                             FROM sk_objects LEFT JOIN sk_content ON sk_objects.content_id=sk_content.id
                             WHERE sk_objects.id = ".$this->attributes['object_id'];

              $result = $skdb->Execute($objlistqry);
              if ($result == false) 
                  DEBUG_out(1,"error1","Object DB-select failed!<br>");
              $this->init($result->fields);
              if($GLOBALS['debug']>3)$t_timer2=timer($time_start);
                DEBUG_out(4,"bench4","object get:".$this->attributes['type']." ".($t_timer2-$t_timer1)."ms");
              }

       } //End function get


       function insert(){
        global $messages;
        global $sess_uid;
                  DEBUG_out(1,"debug1","object insert!:".print_array($this->attributes,1)."<br>");
                  connectadm();

                  $objqry = "INSERT into sk_objects (content_id,type,file,attributes,objtext,sort_nr,last_mod,by_user,user_id)
                             VALUES (".$this->attributes['content_id'].", '".$this->attributes['type']."', '".$this->attributes['file']."' ,'".
                                    addslashes($this->attributes['attributes'])."' ,'".
                                    $this->attributes['objtext']."', ".
                                    $this->attributes['sort_nr'].", ".
                                    $this->skdbadm->DBDate(time()).",'".
                                    $sess_uid."','".
                                    $sess_uid."')";


                  $result = $this->skdbadm->Execute($objqry);
                  if ($result == false){
                   DEBUG_out(1,"error1","DB-insert failed! ".$this->skdbadm->Errormsg()."<br>".$objqry."<br>");
                   closeadm();
                   return false;
                  }else{
                  	DEBUG_out(3,"query","object insert!:".$objqry);
          $new_id = $this->skdbadm->Insert_ID();
                  $this->attributes['id']=$new_id;
                  closeadm();
                  return $new_id;
                  }


       } //End function insert

       function update($usermod=1){
        global $messages;
        global $sess_uid;
                  connectadm();
                  $objqry = "UPDATE sk_objects
                          SET type='".$this->attributes['type']."',
                  content_id='".$this->attributes['content_id']."',
                              file='".$this->attributes['file']."',
                              attributes='".addslashes($this->attributes['attributes'])."',
                              objtext='".$this->attributes['objtext']."',
                              sort_nr=".$this->attributes['sort_nr'];

                  if($usermod)
                  $objqry.=  ", last_mod=".$this->skdbadm->DBDate(time()).",
                              by_user=".$sess_uid;

                  $objqry.=   " WHERE sk_objects.id = ".$this->attributes['object_id'];
                  DEBUG_out(3,"query","object update!:".$objqry);
                  $result = $this->skdbadm->Execute($objqry);
                  if ($result == false) DEBUG_out(1,"error1","DB-update failed! ".$this->skdbadm->Errormsg()."<br>");

                  closeadm();


       } //End function update

       //function delete()
       //deletes object from db
       //
       function delete(){
        global $messages;
        global $sess_uid;
        
        $contentpath=ROOT_PATH.$GLOBALS['current_site']->attributes['dirname']."content/";
        $filepath=$contentpath.$this->attributes['type']."/";
        if ($this->attributes['type']!="news"){
        
        if ($this->attributes['type']=="text_image") $filepath=$contentpath."image/";
        DEBUG_out(1,"debug1","file: ".$filepath.$this->attributes['file']." delete<br>");
        IF (is_file($this->attributes['file']) && file_exists($filepath.$this->attributes['file'])) unlink($filepath.$this->attributes['file']);
           if (is_file($this->attributes['file']) >"" && file_exists($filepath."thumbnails/".$this->attributes['file'])) unlink($filepath."thumbnails/".$this->attributes['file']);
           
        } // ! news
                connectadm();
        
                  $objqry = "DELETE FROM sk_objects
                             WHERE sk_objects.attributes LIKE '%object_id=\"".$this->attributes['object_id']."\"%'";

                  $result = $this->skdbadm->Execute($objqry);
                  if ($result == false) DEBUG_out(1,"error1","DB-Object-Link-delete failed! ".$this->skdbadm->Errormsg()."<br>");
                
                  $objqry = "DELETE FROM sk_objects
                             WHERE sk_objects.id = ".$this->attributes['object_id'];

                  $result = $this->skdbadm->Execute($objqry);
                  DEBUG_out(1,"error1","object(id:".$this->attributes['object_id'].") <b>deleted!</b><br>");
                  if ($result == false) DEBUG_out(1,"error1","DB-delete failed! ".$this->skdbadm->Errormsg()."<br>");
              closeadm();
       } //End function delete
       
       
       //function icon($size=0)
       //displays object icon
       //size 0=normal, 1=small
       function icon($size=0, $view_type=0){
        global $messages;
        global $sess_uid;
        global $skdb;
        if ($view_type==1){
            $link1='<a title="das ist ein -'.$this->attributes['type'].'- Objekt" href="#">';
            $link2='</a>';
        }
        else
        {$link1="";$link2="";}
        $icon="<img src=\"".SKRES_URL."skinz/".$GLOBALS['theme']."/object_icons/".$this->attributes['type'].".gif\" border=\"0\" alt=\"".$this->attributes['type']."\" align=\"baselineabsmiddle\">";
        $small_icon="<img src=\"".SKRES_URL."skinz/".$GLOBALS['theme']."/object_icons/small/".$this->attributes['type'].".gif\" border=\"0\" align=\"baselineabsmiddle\">";
        if ($size==0) return $link1.$icon.$link2;
        elseif ($size==1)  return $link1.$small_icon.$link2;
       } //End function icon
       
       
       //function get_links()
       //retrieves pages with links to this object
       //
       function get_links(){
        global $messages;
        global $sess_uid;
        global $skdb;
        
    $linklistqry = "SELECT sk_content.mid,sk_nav_tree.title, sk_objects.id
                             FROM sk_objects LEFT JOIN sk_content ON sk_objects.content_id=sk_content.id
                                 LEFT JOIN sk_nav_tree ON sk_nav_tree.id=sk_content.mid 
                             WHERE sk_objects.attributes LIKE '%object_id=\"".$this->attributes['object_id']."\"%'
                             ORDER by sk_nav_tree.sort_nr,p,depth";
                 
    $result = $skdb->Execute($linklistqry); 
    if ($result == false){ 
                  DEBUG_out(1,"error1","Object DB-select failed!<br>".$linklistqry);
          //exit;
        }
    
    $page_ids=array();
    $rownr=1;
        while (!$result->EOF && $result != false) {
                
                 $page_ids[$rownr]['id']=$result->fields["sk_content.mid"];
         $page_ids[$rownr]['title']=$result->fields["sk_nav_tree.title"];
                
              $result->MoveNext();
              $rownr++;
              }
      
                 
                 
       } //End function get_links()


       //function update_imgsize()
       //updates image-sizes stored in db
       //
       function update_imgsize(){
        global $messages;
        global $sess_uid;
        
        DEBUG_out(4,"debug4","update_imgsize: ".$this->attributes_vars['width']."x".$this->attributes_vars['height']." / ".$this->attributes_vars['twidth']."x".$this->attributes_vars['theight']);
        $contentpath=ROOT_PATH.$GLOBALS['current_site']->attributes['dirname']."content/";
        $filepath=$contentpath."image/";
        $imgsize = GetImagesize($filepath.$this->attributes['file']);
                //print_array($imgsize);
                $this->attributes_vars['width']=$imgsize[0];
                $this->attributes_vars['height']=$imgsize[1];
                IF($this->attributes_vars['thumbnail']==1){
                  $thumbfile = $filepath."thumbnails/".$this->attributes['file'];
                  $timgsize = GetImagesize($thumbfile);
                  $this->attributes_vars['twidth']=$timgsize[0];
                  $this->attributes_vars['theight']=$timgsize[1];
                  }

       $this->vars2attributes();
       $this->update(0);
       DEBUG_out(4,"debug4","updated! imgsize: ".$this->attributes_vars['width']."x".$this->attributes_vars['height']." / ".$this->attributes_vars['twidth']."x".$this->attributes_vars['theight']);
                 
       } //End function update_imgsize()


       //-------------------------------------------
       //----- function copy_to
       //----- copies the object to a new content_area
       //----- and searches for link objects, copies them with new target_id
       //----- @param new_caid Id of new Content_area
       //-------------------------------------------

       function copy_to($new_caid){

           DEBUG_out(1,"debug1","copy object(id:".$this->attributes['object_id'].") to new Content_area");

           if($this->attributes['type']=="link123" || $this->attributes['type']=="") return;
           $type=$this->attributes['type'];
           // get filepath
           $contentpath=CONTENT_PATH;
           if ($type=="text_image")
                {$filepath=$contentpath."image/";}
           elseif($this->attributes_vars['clsid']=="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" || $this->attributes_vars['clsid']=="clsid:166B1BCA-3F9C-11CF-8075-444553540000")
                {$filepath=$contentpath."flash/";}
           elseif($this->attributes_vars['clsid']>"") {$filepath=$contentpath."video/";}
           else {$filepath=$contentpath.$type."/";}


           if($this->attributes['file']>'' && file_exists($filepath.$this->attributes['file'])) { //file exists; copy it
              $oldfile=$filepath.$this->attributes['file'];
              $newfilename=skfile($oldfile,$this->attributes['file'],$filepath);
              echo "copy ".$this->attributes['file']." -> ".$newfilename."<br>";
              if(file_exists($filepath."thumbnails/".$this->attributes['file'])) {
                    copy($filepath."thumbnails/".$this->attributes['file'],$filepath."thumbnails/".$newfilename);
                    chmod($filepath."thumbnails/".$newfilename,0775);
                    }
           } //endif file_exists

           $target_obj=new skobject();
           $target_obj->attributes=$this->attributes;
           $target_obj->attributes['file']=$newfilename;
           $target_obj->attributes['content_id']=$new_caid;
           $new_id=$target_obj->insert();
       echo "Copy ".$this->attributes['type']." Object id:".$new_id." to new id:".$target_obj->attributes['object_id']."<br>";
           return $new_id;
       }// End copy_to

} //End class skobject



?>