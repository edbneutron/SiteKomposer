<?
/***************************************
** Title........: SK-NavTree class
** Filename.....: class.sknavtree.inc.php
** Author.......: Edgar Bueltemeyer
** Version......: 0.1
** Notes........:
** Last changed.: 10/10/2003
** Last change..: 
************************************** */


class sknavtree {
        var $debug = 0;
        var $name="sk_nav_tree";
        var $skdbadm;
        var $attributes = array();
        var $table="sk_nav_tree";
        var $db_id="id";
        
function sknavtree($id=0) {
        if($id<>0){
        $this->attributes['id']=$id;
        $this->get();
        
        }
        $this->skdbadm=&$GLOBALS['skdbadm'];
      } //function sksite
      
function get(){
              global $skdb;
              $GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;
              $navqry = "SELECT * FROM ".$this->table."
                          WHERE ".$this->db_id."= ".$this->attributes["$this->db_id"];

              $result = $skdb->Execute($navqry);
              $GLOBALS['ADODB_FETCH_MODE'] = "";
              if ($result == false || $result->EOF ){ 
                DEBUG_out(2,"error2","sknavtree query failed ".$navqry.$skdb->Errormsg());
                }
              else
                {
              $this->init($result);
              }
       } //End function get

function init($result){
             while (list($index, $value) = each($result->fields) ) {
             $this->attributes[$index]=$value;
             }
             DEBUG_out(2,"debug2",$this->name." ".print_array($this->attributes));

       } //End function init
        
// Insert one node into DB
function insert(){
        global $messages;
        global $sess_uid;

                  DEBUG_out(1,"debug1","nav_tree insert!:".print_array($this->attributes)."<br>");
                  connectadm();

                  $insertqry = "INSERT into ".$this->table."
                  (id,p,title,depth,group_id,site_id,template,path,filename,linkname,mview,icon,xpos,ypos,nolink,sort_nr,user_id,last_mod,by_user)
                          VALUES (".$this->attributes['id'].", ".
                                    $this->attributes['p'].", '".
                                    $this->attributes['title']."' ,".
                                    $this->attributes['depth']." ,".
                                    $this->attributes['group_id'].", ".
                                    $this->attributes['site_id'].", '".
                                    $this->attributes['template']."', '".
                                    $this->attributes['path']."', '".
                                    $this->attributes['filename']."', '".
                                    $this->attributes['linkname']."', '".
                                    $this->attributes['mview']."', '".
                                    $this->attributes['icon']."', ".
                                    $this->attributes['xpos'].", ".
                                    $this->attributes['ypos'].", ".
                                    $this->attributes['nolink'].", ".
                                    $this->attributes['sort_nr'].", ".
                                    $this->attributes['user_id'].", ".
                                    $this->skdbadm->DBDate(time()).", ".
                                    $this->attributes['by_user'].")";


                  $result = $this->skdbadm->Execute($insertqry);
                  if ($result == false){
                   DEBUG_out(1,"error1","DB-insert failed! ".$this->skdbadm->Errormsg()."<br>".$insertqry."<br>");
                   closeadm();
                   return false;
                  }else{

                  
          $new_id = $this->skdbadm->Insert_ID();
                  $this->attributes['id']=$new_id;
                  closeadm();
                  return $new_id;
                  }


       } //End function insert
       
       
       
       
function delete($delsubtree=0,$id=0,$current_site_id=0){
    global $skdb;
    global $Messages;       
    
    
    if($id==0) $id=$this->attributes['id'];

    if($delsubtree==1){
        
          $getSQL="";

      if($current_site_id==0) $current_site_id=$GLOBALS['current_site']->attributes['site_id'];
      //$current_site_id=$this->attributes['site_id'];

      $Menu = new skmenu(1);
      $Menu->getfromdb($current_site_id);
      // If id is set to 0 delete complete tree
      if($id!=0) $sub_tree=$Menu->getSubtree($id); else $sub_tree=$Menu->tree;
      if($id==0) $id=$sub_tree[1][3];
      DEBUG_out(1,"debug1","DELETE SUB-TREE! current site_id:".$current_site_id.print_array($sub_tree));
      
      for($cnt=1;$cnt<count($sub_tree);$cnt++){
        DEBUG_out(3,"debug3","DELETE TREE-ITEM! title:".$sub_tree[$cnt][1]." mid:".$sub_tree[$cnt][3]);  
        $this->delete(0,$sub_tree[$cnt][3]); //call this function for each tree_item

      } 
        
    }

    //Delete current nav_id
    
    //get all content_areas
    
    unset($getresult);
    $getSQL = "SELECT id,content_area FROM sk_content WHERE mid =".$id;
        DEBUG_out(1,"query","DB-SELECT (get content_areas)<br> ".$getSQL);
        $getresult=$skdb->Execute($getSQL);
        if ($getresult == false || !$getresult):
       DEBUG_out(1,"error1","DB-SELECT (get content_areas) failed! ".$skdb->Errormsg());
       
        endif;
        
    //delete all objects inside content_area
        while (!$getresult->EOF && $getresult != false) {
            $sk_content = new skcontent_area($getresult->fields['content_area'],$id);
            $sk_content->debug=1;
            $sk_content->delete_objects();
        $sk_content->delete();           
        $getresult->MoveNext();
        }
    DEBUG_out(3,"debug3","DB-DELETE content_areas_end.");
        
    connectadm();
    // delete permissions for page
        $delSQL = "DELETE FROM sk_groups_nav_tree_link WHERE nat_id =$id";
        DEBUG_out(3,"query","DB-DELETE:<br>$delSQL");
        if ($GLOBALS['debug']>0) $Messages .= $delSQL.$this->skdbadm->Errormsg()."<br>";
        $result=$this->skdbadm->Execute($delSQL);
        if ($result == false):
       DEBUG_out(3,"error3","DB-DELETE failed!<br>$delSQL");
        endif;
     
    
       //finally delete nav_tree-item
       $delSQL = "DELETE FROM sk_nav_tree WHERE id =$id";
       DEBUG_out(3,"query","DB-DELETE:<br>$delSQL");
        $result=$this->skdbadm->Execute($delSQL);
        if ($result == false):
           DEBUG_out(3,"error3","DB-DELETE failed!<br>$delSQL");
        endif;         
           
   closeadm();
           
           
           
}// End function delete
               
// function copy
// copy whole nav_tree with increased id_s and updated Pointers
//


function copy($src_site_id,$new_site_id){
         global $skdb;

         //get menu structure
         $src_menu=new skmenu(1);
         $src_menu->getfromdb($src_site_id);

         echo "<hr><b>Copy Navigation Structure:</b><p>";
         echo "<b>src_menu:</b> ";
         //echo $src_menu->treemenu("default","",1,0,0,0,0,0,1,1);
         DEBUG_out(2,"debug2","src_menu: ".print_array($src_menu->tree));

         //get last id
         $GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;
         $navqry = "SELECT MAX(id) as id FROM ".$this->table;
         $result = $skdb->Execute($navqry);
         $last_id=$result->fields['id'];
         $GLOBALS['ADODB_FETCH_MODE'] = "";
         echo "<br>";
         echo "last id from DB: ".$last_id;
         echo "<p>";

         $target_menu=new skmenu(1);
         $cnt=count($src_menu->tree);

         //copy each tree-node to new array
         // set new attributes
         // insert into DB

         for($i=1;$i<=$cnt;$i++){
            //mofify tree_array
            $target_menu->tree[$i]=$src_menu->tree[$i];
            $target_menu->tree[$i][3]=$src_menu->tree[$i][3]+$last_id;
            $target_menu->tree[$i][4]=$src_menu->tree[$i][4]+$last_id;
            $target_menu->tree[$i][9]=$src_menu->tree[$i][3];
            $target_menu->tree[$i][10]=$src_menu->tree[$i][4];

            $src_node=new sknavtree($src_menu->tree[$i][3]);
            echo "src_node:".$src_menu->tree[$i][3]; //." ".print_array($src_node->attributes);
            $new_node=new sknavtree();
            $new_node->attributes=$src_node->attributes;

            $new_node->attributes['id']=$target_menu->tree[$i][3];
            $new_node->attributes[p]=$target_menu->tree[$i][4];
            $new_node->attributes['site_id']=$new_site_id;
            echo "->target_node:".$new_node->attributes['id']."<br>"; //print_array($new_node->attributes);
            $new_id=$new_node->insert();
            // permission_copy
            $nav_groups="";
            echo "<br>Copy Permissions...";
            $nav_groups=skgroup::getgroups(0,1,0,"nav_tree",$src_node->attributes['id'],"A");
        echo $nav_groups;
            $tmp_obj=new skgroup;
            $tmp_obj->add_permission($nav_groups,"nav_tree",$new_id,"A");
            echo "done...<br>";
         }
         echo "<br><b>target_menu:</b> ";
         //echo $target_menu->treemenu("default","",1,0,0,0,0,0,1,1);
         DEBUG_out(2,"debug2","target_menu: ".print_array($target_menu->tree));

         return $target_menu->tree;
       } //End function copy        
        
}