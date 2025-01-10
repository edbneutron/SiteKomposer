<?
/***************************************
** Title........: SK-Group class
** Filename.....: class.skgroup.inc.php
** Author.......: Edgar Bueltemeyer
** Version......: 0.1
** Notes........:
** Last changed.: 9/10/2002
** Last change..: 
************************************** */


class skgroup {
        var $debug = 0;
        var $skdbadm;        



// init-function
function skgroup() {

         $this->skdbadm=&$GLOBALS['skdbadm'];

    }
//-------------------------------------------
//----- function getgroups
//----- returns group_ids in different styles
//----- $format=1 - comma seperated list of group_ids
//----- $format=2 - generate <OPTION>-tags
//----- for a specific user (if $uid<> 0) or acl 
//----- @param integer $uid User-id
//----- @param integer $format Output-Format
//----- @param integer $groupid Group-id (for select_boxes entries matching groupid are selected)
//----- @param String $acl Access-Control_List (module, nav_tree,sites)
//----- @param integer $acl_id The id of the corresponding entry (id for nav_tree, mid for modules, site_id for sites)
//----- @param String $type Type of Right ("A"=Admin,"R"=Read)
//-------------------------------------------

    function getgroups($uid=0, $format=1, $groupid=0, $acl="", $acl_id=0, $type="R"){    
              global $skdb;
              
              if($uid !== 0){
              
              
                  if($acl<>"" && $acl_id>0 && $type>""){
                  switch($acl){
                      case 'modules':
                        $groupqry = "SELECT * from sk_groups
                                      INNER JOIN sk_groups_modules_link ON sk_groups.groupid=sk_groups_modules_link.groupid
                                     WHERE sk_groups_modules_link.mid=$acl_id AND sk_groups_modules_link.type='$type'";
                    
                        break;
                    case 'nav_tree':
                        $groupqry = "SELECT * from sk_groups
                                      INNER JOIN sk_groups_nav_tree_link ON sk_groups.groupid=sk_groups_nav_tree_link.groupid
                                     INNER JOIN sk_groups_users_link ON sk_groups.groupid = sk_groups_users_link.groupid
                                     INNER JOIN sk_users ON sk_groups_users_link.uid = sk_users.uid
                                     WHERE sk_groups_nav_tree_link.nat_id=$acl_id
                                     AND sk_groups_nav_tree_link.type='$type'
                                     AND sk_users.uid = $uid";
                    
                        break;
                    case 'sites':
                        $groupqry = "SELECT * from sk_groups
                                      INNER JOIN sk_groups_sites_link ON sk_groups.groupid=sk_groups_sites_link.groupid
                                     WHERE sk_groups_sites_link.site_id=$acl_id AND sk_groups_sites_link.type='$type'";
                    
                        break;
                   
                    }
                  }elseif($acl<>"" && $type>""){
                  	  /* Check group membership */
                  		$groupqry = "SELECT * FROM sk_groups 
				                    INNER JOIN sk_groups_users_link ON sk_groups.groupid = sk_groups_users_link.groupid
				                    INNER JOIN sk_users ON sk_groups_users_link.uid = sk_users.uid
				                    WHERE sk_users.uid = ".$uid." AND sk_groups.type='".$type."'";
                  }else{
                      $groupqry = "SELECT * FROM sk_groups 
                      INNER JOIN sk_groups_users_link ON sk_groups.groupid = sk_groups_users_link.groupid
                    INNER JOIN sk_users ON sk_groups_users_link.uid = sk_users.uid
                    WHERE sk_users.uid = ".$uid;
                           
                           
                           
                 }           
                           
                           
                           
              }
              elseif($acl<>"" && $acl_id>0 && $type>""){
                  switch($acl){
                      case 'modules':
                        $groupqry = "SELECT * from sk_groups
                                      INNER JOIN sk_groups_modules_link ON sk_groups.groupid=sk_groups_modules_link.groupid
                                     WHERE sk_groups_modules_link.mid=$acl_id AND sk_groups_modules_link.type='$type'";
                    
                        break;
                    case 'nav_tree':
                        $groupqry = "SELECT * from sk_groups
                                      INNER JOIN sk_groups_nav_tree_link ON sk_groups.groupid=sk_groups_nav_tree_link.groupid
                                     WHERE sk_groups_nav_tree_link.nat_id=$acl_id AND sk_groups_nav_tree_link.type='$type'";
                    
                        break;
                    case 'sites':
                        $groupqry = "SELECT * from sk_groups
                                      INNER JOIN sk_groups_sites_link ON sk_groups.groupid=sk_groups_sites_link.groupid
                                     WHERE sk_groups_sites_link.site_id=$acl_id AND sk_groups_sites_link.type='$type'";
                    
                        break;
                        
                  }
              
              
              }elseif($acl==""){
              $groupqry = "SELECT * FROM sk_groups ORDER BY name";
              }
              
              DEBUG_out(3,"query","getgroups():".$groupqry);
              
              $result = $skdb->Execute($groupqry);
              if ($result === false) DEBUG_out(2,"error2","getgroups():group-select failed! ".$skdb->Errormsg()."<br>$groupqry");
              $list="";
              switch($format){
               /* Return Array */
              	case 0:
              		$list=Array();
                    while (!$result->EOF AND !$result == false) {
                    $list[]=$result->fields["groupid"];
                    $result->MoveNext(); 
                    }
                      
                  break;
              /* Return comma seperated list */    
              case 1:
                    while (!$result->EOF AND !$result == false) {
                    $list.=$result->fields["groupid"].",";
                    $result->MoveNext(); 
                    }
                      $list=substr($list,0,(strlen($list)-1));
                  break;
              /* Return OPTION tags for form */ 
              case 2:
                    while (!$result->EOF AND !$result == false) {
                    $list.="<OPTION value=\"".$result->fields["groupid"]."\"";
                    if($result->fields["groupid"]==$groupid) $list.=" selected ";
                    $list.=">".$result->fields["name"];
                    $result->MoveNext(); 
                    }
                  break;
                  
              }
              DEBUG_out(3,"debug3","getgroups():".var_export($list,1));
              if(strlen($list)==0) return FALSE;
              return $list;
    
    
    } // function getgroups



//-------------------------------------------
//----- function add_permission
//----- returns 1 if successful
//----- @param integer $groupids Group-ids
//----- @param String $acl Access-Control_List (module, nav_tree,sites)
//----- @param integer $acl_id The id of the corresponding entry (id for nav_tree, mid for modules, site_id for sites)
//----- @param String $type Type of Right ("A"=Admin,"R"=Read)
//-------------------------------------------

    function add_permission($groupids=0, $acl="", $acl_id=0, $type="R"){    
             connectadm();
             // check for the array
             if (!is_array($groupids)) {
             // make array
               $groupids=explode(",",$groupids);
             }
             DEBUG_out(3,"debug3",print_array($groupids));
             if($acl<>"" && $acl_id>0 && $type>""){
             
                  switch($acl){
                      case 'modules':
                        foreach($groupids as $group){
                        $insertSQL = "INSERT INTO sk_groups_modules_link (groupid,mid,type) VALUES ($group,$acl_id,'$type')";
                        $result = $this->skdbadm->Execute($insertSQL);
                        if ($result == false) DEBUG_out(1,"error1","add_permission DB-INSERT failed!");
                        DEBUG_out(3,"debug3",$insertSQL);
                        }
                        break;
                    case 'nav_tree':
                        foreach($groupids as $group){
                        $insertSQL = "INSERT INTO sk_groups_nav_tree_link (groupid,nat_id,type) VALUES ($group,$acl_id,'$type')";
                        $result = $this->skdbadm->Execute($insertSQL);
                        if ($result == false) DEBUG_out(1,"error1","add_permission DB-INSERT failed!");
                        DEBUG_out(3,"debug3",$insertSQL);
                        }
                        break;
                    case 'sites':
                        foreach($groupids as $group){
                        $insertSQL = "INSERT INTO sk_groups_sites_link (groupid,site_id,type) VALUES ($group,$acl_id,'$type')";
                        $result = $this->skdbadm->Execute($insertSQL);
                        if ($result == false) DEBUG_out(1,"error1","add_permission DB-INSERT failed!");
                        DEBUG_out(3,"debug3",$insertSQL);
                        }
                        
                        break;
                        
                  }
              
              return 1;
              
              }              
              closeadm();
    
    } // function add_permission
    
//-------------------------------------------
//----- function remove_permission
//----- returns 1 if successful
//----- @param integer $groupids Group-ids
//----- @param String $acl Access-Control_List (module, nav_tree,sites)
//----- @param integer $acl_id The id of the corresponding entry (id for nav_tree, mid for modules, site_id for sites)
//----- @param String $type Type of Right ("A"=Admin,"R"=Read)
//-------------------------------------------

    function remove_permission($groupids=0, $acl="", $acl_id=0, $type="R"){    
             connectadm();
             // check for the array
             if (!is_array($groupids)) {
             // make array
               $groupids=explode(",",$groupids);
             }
             if($acl<>"" && $acl_id>0 && $type>""){
             
                  switch($acl){
                      case 'modules':
                        foreach($groupids as $group){
                        $deleteSQL = "DELETE FROM sk_groups_modules_link WHERE groupid=$group AND mid=$acl_id AND type='$type'";
                        $result = $this->skdbadm->Execute($deleteSQL);
                        if ($result == false) $GLOBALS['DEBUG_OUTPUT'] .= "sk_groups_modules_link<br>DB-DELETE failed! <br>";
                        IF ($GLOBALS['debug']>0) $GLOBALS['DEBUG_OUTPUT'] .= $deleteSQL;
                        }
                        break;
                    case 'nav_tree':
                        foreach($groupids as $group){
                        $deleteSQL = "DELETE FROM sk_groups_nav_tree_link WHERE groupid=$group AND nat_id=$acl_id AND type='$type'";
                        $result = $this->skdbadm->Execute($deleteSQL);
                        if ($result == false) $GLOBALS['DEBUG_OUTPUT'] .= "sk_groups_nav_tree_link<br>DB-DELETE failed! <br>";
                        IF ($GLOBALS['debug']>0) $GLOBALS['DEBUG_OUTPUT'] .= $deleteSQL;
                        }
                        break;
                    case sites:
                        foreach($groupids as $group){
                        $deleteSQL = "DELETE FROM sk_groups_sites_link WHERE groupid=$group AND site_id=$acl_id AND type='$type'";
                        $result = $this->skdbadm->Execute($deleteSQL);
                        if ($result == false) $GLOBALS['DEBUG_OUTPUT'] .= "sk_groups_sites_link<br>DB-DELETE failed!<br>";
                        IF ($GLOBALS['debug']>0) $GLOBALS['DEBUG_OUTPUT'] .= $deleteSQL;
                        }
                        break;
                        
                  }
              
              return 1;
              
              }              
              closeadm();
    
    } // function remove_permission
    

} //class skgroup