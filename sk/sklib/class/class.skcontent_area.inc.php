<?php
/***************************************
** Title........: SK Content_area class
** Filename.....: class.skcontent_area.inc
** Author.......: Edgar Bueltemeyer
** Version......: 1.2
** Notes........:
** Last changed.: 11/Mar/2005
** Last change..: remove debug var
***************************************/

      class skcontent_area{

                var $object_list = array();
                var $sk_objects = array();
                var $object_count = 0;   // number of objects in this content area
                var $parent_node = 0;    // id of navigation_tree entry
                var $identifier = 0;     // identifier number of this content_area (used in template)
                var $last_sort_nr =0;
                var $skdbadm;
                var $content_id = 0;
                var $is_link = 0;


     /***************************************
      ** Constructor-Function to set initial variables
      **
      ***************************************/

     function skcontent_area ($identifier=0, $parent_node=0,$debug=0){
              $this->identifier = $identifier;
              $this->parent_node = $parent_node;
              if($this->identifier>0) $this->build_objectlist();
              $this->skdbadm=&$GLOBALS['skdbadm'];
              DEBUG_out(2,"debug2","content_area alive!: identifier=".$this->identifier." parent_node=".$this->parent_node);



     }

      /***************************************
      ** Function to retrieve sk_content.id
      **
      ***************************************/

     function get_id(){
              global $skdb;
              $output="";
              $objlistqry = "SELECT id FROM sk_content
                             WHERE mid = ".$this->parent_node." AND content_area = ".$this->identifier;
              $result = $skdb->Execute($objlistqry);
              if ($result == false || $result->EOF ){ 
                  DEBUG_out(2,"error2","content_area query failed ".$objlistqry.$skdb->Errormsg());
                }
              if (!isset($result->fields[0])):
                 $this->insert();
                 $content_id = $this->get_id();
              else: $content_id = $result->fields[0];
              endif;

              return $content_id;

     }
     
      /***************************************
      ** Function to retrieve sk_content.parent_node & content_area
      **
      ***************************************/

     function get_byid(){
              global $skdb;
              $output="";
              $objlistqry = "SELECT * FROM sk_content
                             WHERE id = ".$this->content_id;
              $result = $skdb->Execute($objlistqry);
              if ($result == false || $result->EOF ){ 
                  DEBUG_out(2,"error2","content_area query failed ".$objlistqry.$skdb->Errormsg());
                }
              else{ 
              $this->content_id = $result->fields['id'];
              $this->parent_node = $result->fields['mid'];
              $this->identifier = $result->fields['content_area'];
              }
              

             

     }


      /***************************************
      ** Function to insert new content_area
      ** into database
      ***************************************/
     function insert(){

                  connectadm();

                  $objqry = "INSERT into sk_content (mid, content_area)
                             VALUES (".$this->parent_node.", ".$this->identifier.")";

                  $result = $this->skdbadm->Execute($objqry);
                  if ($result == false){ DEBUG_out(1,"error1","DB insert failed".$this->skdbadm->Errormsg()); echo "DB insert failed".$this->skdbadm->Errormsg(); exit; }
                  DEBUG_out(3,"debug3","new content_area: identifier=".$this->identifier." parent_node=".$this->parent_node);
          
          if ($result == false){
                   DEBUG_out(1,"error1","DB-insert failed! ".$this->skdbadm->Errormsg()."<br>".$objqry."<br>");
                   closeadm();
                   return false;
                  }else{
          $new_id = $this->skdbadm->Insert_ID();
                  $this->attributes['id']=$new_id;
                  closeadm();
                  return $new_id;
                  }
          

       } //End function insert
       
       /***************************************
      ** Function to delete content_area
      ** from database
      ***************************************/
     function delete(){

                  connectadm();

                  $delqry = "DELETE FROM sk_content WHERE id=".$this->get_id();

                  $result = $this->skdbadm->Execute($delqry);
                  if ($result == false) DEBUG_out(1,"error1","delete failed".$this->skdbadm->Errormsg());
                  DEBUG_out(3,"debug3","deleted content_area: identifier=".$this->identifier." parent_node=".$this->parent_node);

              closeadm();

       } //End function insert

       /***************************************
        ** Function to retrieve and build list of objects
        **
        ***************************************/

     function build_objectlist (){
              global $skdb;
              $output="";
              
              $objlistqry = "SELECT sk_content.id, sk_objects.id, type, file, attributes, objtext,sort_nr,sk_content.mid, user_id, UNIX_TIMESTAMP(last_mod) as last_mod, by_user
                             FROM sk_content LEFT JOIN sk_objects ON sk_objects.content_id=sk_content.id
                             WHERE sk_content.mid = ".$this->parent_node." AND sk_content.content_area = ".$this->identifier."
                             ORDER by sort_nr";

              $result = $skdb->Execute($objlistqry);
              
              if ($result == false || $result->EOF ){ 
                  DEBUG_out(3,"error3","<b>object list empty</b> ".$objlistqry.$skdb->Errormsg());
                return;
                }
              $result->MoveFirst();
              $rownr=0;
              while (!$result->EOF && $result != false) {
                
                 $this->object_list[$rownr]=$result->fields;
                 $this->last_sort_nr=$result->fields["sort_nr"]; 
                 DEBUG_out(4,"debug4","objectlist current row: ".var_export($this->object_list[$rownr],1));
              $result->MoveNext();
              $output.= "<br>\n";
              $rownr++;
              }
              $this->objectcount=$rownr;
              DEBUG_out(2,"query",$objlistqry);
              DEBUG_out(4,"debug4","Objectlist:".print_array($this->object_list,1));
              
              //echo $this->last_sort_nr;

     } //End function build_objectlist


       /***************************************
        ** Function to display all objects in
        ** this content_area
        ***************************************/
     function display_objects($editable=0){
              global $add_button_small;
              global $documentpath;
              global $formtarget;
              
              $output="";
              IF (count($this->object_list)>0):
                for($i=0; $i < $this->objectcount; $i++){
                    $sk_objects[$i] = new skobject($this->object_list[$i]);
                    $output .= $sk_objects[$i]->display($editable);
                    if($this->is_link==1 && $GLOBALS['edit']==1) $output .= "<br>"; 
                }
              ENDIF;
              IF ($editable==1){
               /* Standard popup */
               /*
                        $output.="
                        <div id=\"sk_edit1\" align=\"right\"><a title=\"Objekt hinzufügen\" href=\"#\"".
                        "onClick=\"MM_openBrWindow('$formtarget?identifier=".$this->identifier."&parent_node=".$this->parent_node.
                        "&sort_nr=".($this->last_sort_nr+10)."&form_window=1','objectprop','scrollbars=yes,toolbar=no,width=500,height=300');return false\" class=\"small\">".$add_button_small."</a></div>";
                   */     
              /* Mootools Mediabox */
              			/*$output.="
                        <div id=\"sk_edit1\" align=\"right\">" .
                        		"<a href=\"$formtarget?identifier=".$this->identifier."&parent_node=".$this->parent_node."&sort_nr=".($this->last_sort_nr+10)."&form_window=1\" rel=\"lightbox[external 640 360]\" title=\"Objekt hinzufügen\" class=\"small\">".$add_button_small."</a>" .
                        		"</div>"; */
                        $output.="
                        <div id=\"sk_edit1\" align=\"right\">" .
                        "<a href=\"\" onclick=\"Mediabox.open('$formtarget?identifier=".$this->identifier."&parent_node=".$this->parent_node."&sort_nr=".($this->last_sort_nr+10)."&form_window=1', 'Objekt hinzufügen', '640 340');return false;\"  title=\"Objekt hinzufügen\" class=\"small\">".$add_button_small."</a>" .
                        "</div>";
                        		
                        
                        
              }
                DEBUG_out(3,"debug3","content_display_objects(editable:".$editable.")<br>next sort_nr:".($this->last_sort_nr+10));
      
              return $output;
     }

       /***************************************
        ** Function to display all objects in
        ** this content_area and make them editable
        ***************************************/

     function editable_objects(){

              $output.=$this->display_objects(1);
              return $output;

     }

       /***************************************
        ** Function to delete all objects in
        ** this content_area
        ***************************************/
     function delete_objects(){
              global $add_button;
              global $documentpath;
              global $formtarget;
              IF (count($this->object_list)>0):
                for($i=0; $i < $this->objectcount; $i++){
                    $sk_object = new skobject($this->object_list[$i]);
                    $sk_object->delete();
                }
              ENDIF;
              DEBUG_out(1,"debug1",count($this->object_list)." content objects deleted!");
              return $output;
     }

       /***************************************
       **  function copy_objects
       **  copy objects to a new content_area
       **  and searches for link objects, copies them with new target_id
       ***************************************/

     function copy_objects($new_caid){
         global $obj_id_array;
         
              IF (count($this->object_list)>0):
                for($i=0; $i < $this->objectcount; $i++){
                    $sk_object = new skobject($this->object_list[$i]);
                    $new_id=$sk_object->copy_to($new_caid);
            $obj_id_array[$this->object_list[$i][id]]=$new_id;
                }
              ENDIF;
              DEBUG_out(1,"debug1",count($this->object_list)." content objects copied!");
              return $output;
     }
        /***************************************
        ** Function to copy this content_area to a new
        ** and copy all objects in
        ** this content_area to it
        ***************************************/
      function copy_to($new_parentnode) {
          global $ca_id_array;
          
         $new_ca= new skcontent_area($this->identifier,$new_parentnode);
     $old_ca_id=$this->get_id();
         $new_caid=$new_ca->get_id(); // inserts into DB
         //$new_caid=30000; // for test
     $ca_id_array[$old_ca_id]=$new_caid;
         $this->copy_objects($new_caid);

      } // end copy


     } // End of class
?>