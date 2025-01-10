<?
/***************************************
** Title........: SK-Site class
** Filename.....: class.sksite.inc.php
** Author.......: Edgar Bueltemeyer
** Version......: 0.8
** Notes........:
** Last changed.: 8/Feb/2005
** Last change..: 
************************************** */


class sksite {
        var $debug = 0;
        var $name="sksite";
        var $skdbadm;
        var $attributes = array();
        var $table="sk_sites";
        var $db_id="site_id";
        
function sksite($site_id=0) {
        if($site_id<>0){
        $this->attributes['site_id']=$site_id;
        $this->get();
        }
        
      } //function sksite
      
function get(){
              global $skdb;
              $GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;
              $siteqry = "SELECT * FROM ".$this->table."
                          WHERE ".$this->db_id."= ".$this->attributes["$this->db_id"];

              $result = $skdb->Execute($siteqry);
              $GLOBALS['ADODB_FETCH_MODE'] = "";
              //IF ($GLOBALS['debug']>0) $GLOBALS['DEBUG_OUTPUT'].=$siteqry.print_array($result->fields)."<br>";
              if ($result == false){ $GLOBALS['DEBUG_OUTPUT'].=$this->table." DB-select failed! ".$this->skdb->Errormsg()."<br>";
              }else{
              $this->init($result);}
       } //End function get

function init($result){
             while (list($index, $value) = each($result->fields) ) {
             $this->attributes[$index]=$value;
             }
             DEBUG_out(2,"debug2",$this->name." ".print_array($this->attributes));

       } //End function init
        
 function insert(){
        global $messages;
        global $sess_uid;
        $this->skdbadm=&$GLOBALS['skdbadm'];
                  DEBUG_out(1,"debug1","site insert!:".print_array($this->attributes)."<br>");
                  connectadm();

                  $siteqry = "INSERT into ".$this->table." (site_id,name,dirname,site_url,description,meta,lang,last_mod,user_id,group_id,port)
                             VALUES ('".$this->attributes['site_id']."', '".
                                    $this->attributes["name"]."', '".
                                    $this->attributes['dirname']."' ,'".
                                    $this->attributes['site_url']."' ,'".
                                    $this->attributes[description]."', '".
                                    $this->attributes['meta']."', ".
                                    $this->attributes['lang']."', ".
                                    $this->skdbadm->DBDate(time()).",".
                                    $this->attributes['user_id'].", ".
                                    $this->attributes[group_id].", '".
                                    $this->attributes[port]."')";


                  $result = $this->skdbadm->Execute($siteqry);
                  if ($result == false){
                   DEBUG_out(1,"error1","DB-insert failed! ".$this->skdbadm->Errormsg()."<br>".$siteqry."<br>");
                   closeadm();
                   return false;
                  }else{

                  $new_id = $this->skdbadm->Insert_ID();
                  $this->attributes['site_id']=$new_id;
                  closeadm();
                  return $new_id;
                  }


       } //End function insert

//-------------------------------------------
//----- function copy
//----- copy whole website including all objects to new site
//----- @param integer $src_site_id  Id of Source-Website
//-------------------------------------------


function copy_from($src_site_id){
         global $skdb;
	 global $obj_id_array; //stores object id-translations
	 global $ca_id_array; //stores object id-translations
	 
	 $obj_id_store=new skvarstore('obj_id_array');
	 if($obj_id_store->attributes[vardata] > '') $obj_id_array=unserialize($obj_id_store->attributes[vardata]);
	 DEBUG_out(3,"debug3","obj_id_array from db:".print_array($obj_id_array));
	 
	 $ca_id_store=new skvarstore('ca_id_array');
	 if($ca_id_store->attributes[vardata] > '') $ca_id_array=unserialize($ca_id_store->attributes[vardata]);
	 DEBUG_out(3,"debug3","ca_id_array from db:".print_array($ca_id_array));
	 
         echo "<hr><b>Copy entire Site(".$this->attributes["name"]."):</b><br>";
         //get attributes from source site
         //insert into DB
         $src_site=new sksite($src_site_id);
         $src_site->get();
         $name=$this->attributes["name"];
         $this->attributes=$src_site->attributes;
         $this->attributes['site_id']="";
         $this->attributes["name"]=$name;
         $this->attributes['site_id']=$this->insert();

         //copy permissions
         echo "<br>Copy Permissions...";
         $site_groups=skgroup::getgroups(0,1,0,"sites",$src_site_id,"A");
         $tmp_obj=new skgroup;
         $tmp_obj->add_permission($site_groups,"sites",$this->attributes['site_id'],"A");
         echo "done...<br>";
         //copy navigation structure
         $new_tree= new sknavtree;
         $new_tree_array=$new_tree->copy($src_site_id,$this->attributes['site_id']);
         $cnt=count($new_tree_array);

         echo "<hr><b>Copy Content:</b><br>";
         //copy content
         for($i=1;$i<=$cnt;$i++){ // fetch areas for each node

           $ca_qry="SELECT * from sk_content
                             WHERE mid = ".$new_tree_array[$i][9];
           DEBUG_out(4,"query","ca_qry:".$ca_qry);
           $result = $skdb->Execute($ca_qry);
           DEBUG_out(4,"debug4","ca_result:".print_array($result->fields));
           if($result)
           while(!$result->EOF && $result != false) { // get content_areas

               if($result->fields['content_area']>0) {
               $src_ca=new skcontent_area($result->fields['content_area'],$result->fields['mid']);
               $src_ca->copy_to($new_tree_array[$i][3]);
               }
               $result->MoveNext();
           } //while

         }
	 DEBUG_out(3,"debug3","obj_id_array:".print_array($obj_id_array));
	 $vardata=serialize($obj_id_array);
	 echo $vardata."<br>";
	 $obj_id_store->attributes[vardata]=$vardata;
	 $obj_id_store->update();
	 
	 DEBUG_out(3,"debug3","ca_id_array:".print_array($ca_id_array));
	 $vardata=serialize($ca_id_array);
	 echo $vardata."<br>";
	 $ca_id_store->attributes[vardata]=$vardata;
	 $ca_id_store->update();
       } //End function copy_from
       
       
//-------------------------------------------
//----- function change_links
//----- copy whole website including all objects to new site
//----- @param integer $src_site_id  Id of Source-Website
//-------------------------------------------


function change_links(){
         global $skdb;
	 global $obj_id_array; //stores object id-translations
	 global $ca_id_array; //stores object id-translations
	 
	 
	 $obj_id_store=new skvarstore('obj_id_array');
	 if($obj_id_store->attributes[vardata] > '') $obj_id_array=unserialize($obj_id_store->attributes[vardata]);
	 DEBUG_out(3,"debug3","obj_id_array from db:".print_array($obj_id_array));
	 
	 $ca_id_store=new skvarstore('ca_id_array');
	 if($ca_id_store->attributes[vardata] > '') $ca_id_array=unserialize($ca_id_store->attributes[vardata]);
	 DEBUG_out(3,"debug3","ca_id_array from db:".print_array($ca_id_array));
	 
	 $obj_qry="SELECT * 
			FROM sk_content, sk_nav_tree, sk_objects
			WHERE content_id = sk_content.id AND
			mid = sk_nav_tree.id AND
			sk_objects.type='link' AND
			site_id = ".$this->attributes['site_id'];
			
           DEBUG_out(4,"query","objects_qry:".$obj_qry);
           $result = $skdb->Execute($obj_qry);
           DEBUG_out(4,"debug4","objects_result:".print_array($result->fields));
           if($result)
           while(!$result->EOF && $result != false) { // get content_areas

               if($result->fields['id']>0) {
		       
		       $tmp_object=new skobject();
		       $tmp_object->get($result->fields['id']);
		       $tmp_object->attributes2vars();
		       
		       $old_id=$tmp_object->attributes_vars['object_id'];
		       $old_ca_id=$tmp_object->attributes_vars['content_id'];
		       
		       
		       if($obj_id_array[$old_id]) {
			       $tmp_object->attributes_vars['object_id']=$obj_id_array[$old_id];
			       echo "<br>object: old_id: $old_id -> new_id: ".$obj_id_array[$old_id];
		       }
		       if($ca_id_array[$old_ca_id]){
			       $tmp_object->attributes_vars['content_id']=$ca_id_array[$old_ca_id];
			       echo "<br>content: old_id: $old_ca_id -> new_id: ".$ca_id_array[$old_ca_id];
		       }
		       
		       $tmp_object->vars2attributes();
		       $tmp_object->update();
	       
               }
               $result->MoveNext();
           } //while
	 
	 
}//End function

}