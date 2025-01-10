<? 
// **************************************
// Title........: SK-User class
// Filename.....: class.skuser.inc.php
// Author.......: Edgar Bueltemeyer
// Version......: 0.1
// Notes........:
// Last changed.: 10/10/2002
// Last change..: 
//***************************************


class skuser {
        var $debug = 0;
        var $name="skuser";
        var $skdbadm;        
        var $uid;
        var $uname;
        var $attributes = array();
        var $table="sk_users";
        var $db_id="uid";
//-------------------------------------------
//----- function skuser - Init Function
//----- gets variables from session
//----- 
//-------------------------------------------

function skuser($uid=0,$uname="") {
  $this->skdbadm=&$GLOBALS['skdbadm'];
  if($uid==0) { $this->attributes["uid"]=$_SESSION["sess_uid"]; }else{$this->attributes["uid"]=$uid;}
  if($uname=="") { $this->attributes["uname"]=$_SESSION["sess_username"]; }else{$this->attributes["uname"]=$uname;}
  if($this->attributes["uid"]!=0)$this->get();
  DEBUG_out(3,"debug3","User-Session for: ".$this->attributes["uname"]." id:".$this->attributes["uid"]."<br>");


} // function skuser


       function get(){
              global $skdb;
              $GLOBALS['ADODB_FETCH_MODE'] = ADODB_FETCH_ASSOC;
              $siteqry = "SELECT * FROM ".$this->table."
                          WHERE ".$this->db_id."= ".$this->attributes[$this->db_id];

              $result = $skdb->Execute($siteqry);
              $GLOBALS['ADODB_FETCH_MODE'] ='';
              if ($result == false) $GLOBALS['DEBUG_OUTPUT'].=$this->table." DB-select failed! ".$skdb->Errormsg()."<br>";
              $this->init($result->fields);
       } //End function get

       function init($attributes){
       		if(is_array($attributes))
             while (list($index, $value) = each($attributes) ) {
             $this->attributes[$index]=$value;
             }
             $this->uid=$this->attributes["uid"];
             DEBUG_out(3,"debug2",$this->name." ".print_array($this->attributes));

       } //End function init       
       
//-------------------------------------------
//----- function increaseposts
//----- increases article post counter
//----- returns db_result
//-------------------------------------------

    function increaseposts($number=1){    
              connectadm();
             
              $userqry = "UPDATE sk_users SET posts=".($this->attributes["posts"]+$number)." WHERE sk_users.uid=".$this->attributes["uid"];
                           
              $result = $this->skdbadm->Execute($userqry);
              if ($result == false) $GLOBALS['DEBUG_OUTPUT'].="increaseposts failed! ".$userqry.$this->skdbadm->Errormsg()."<br>";
              DEBUG_out(2,"debug2","increaseposts + ".$number);
              return $result;
    
    } // function increaseposts

    //-------------------------------------------
//----- function loglogins
//----- increases login counter and set last login-date
//----- returns db_result
//-------------------------------------------

    function loglogins($number=1){    
              connectadm();
              $Now=time();
              
              $userqry = "UPDATE sk_users SET logins=".($this->attributes["logins"]+$number).", last_login=".$Now." WHERE uid=".$this->attributes["uid"];
                           
              $result = $this->skdbadm->Execute($userqry);
              if ($result == false) $GLOBALS['DEBUG_OUTPUT'].="increaselogins failed! ".$userqry.$this->skdbadm->Errormsg()."<br>";
              DEBUG_out(2,"debug2","increaselogins + ".$number);
              return $result;
    
    } // function increaseposts

//-------------------------------------------
//----- function getusers
//----- returns user_ids in different styles
//----- $format=1 - comma seperated list of user_ids
//----- $format=2 - generate <OPTION>-tags
//----- for a specific group (if $groupid<> 0) or all
//----- 
//-------------------------------------------

    function getusers($groupid=0, $format=1,$uid=0 ){    
              global $skdb;
              if($groupid<>0){
              $userqry = "SELECT * FROM sk_users 
                             INNER JOIN sk_groups_users_link ON sk_users.uid = sk_groups_users_link.uid
                           INNER JOIN sk_groups ON sk_groups_users_link.groupid = sk_groups.groupid
                           WHERE sk_groups.groupid = ".$groupid;
              }else{
              $userqry = "SELECT * FROM sk_users ORDER BY name";
              }
              
              $result = $skdb->Execute($userqry);
              
              if ($result == false) $GLOBALS['DEBUG_OUTPUT'].="user-select failed! ".$skdb->Errormsg()."<br>";
              $list="";
              switch($format){
              
              case 1:
                      while (!$result->EOF AND !$result == false) {
                    $list.=$result->fields["uid"].",";
                    $result->MoveNext(); 
                    }
                      $list=substr($list,0,(strlen($list)-1));
                  break;
              case 2:
                      while (!$result->EOF AND !$result == false) {
                    $list.="<OPTION value=\"".$result->fields["uid"]."\"";
                    if($result->fields["uid"]==$uid) $list.=" selected ";
                    $list.=">".$result->fields["uname"];
                    $result->MoveNext(); 
                    }
                  break;
                  
              }
              DEBUG_out(2,"debug2","getusers():".$list);
              return $list;
    
    
    } // function getusers
    
//----------------------------------------
//----- function getsites
//----- returns site_ids in different styles
//----- $format=1 - comma seperated list of site_ids
//----- $format=2 - generate <OPTION>-tags
//----- $format=3 - returns array [id] [name]
//----- for a specific user (if $uid<> 0) or acl 
//----- @param integer $uid User-id
//----- @param integer $format Output-Format
//----- @param integer $site_id Site-id (for select_boxes; entries matching site_id are selected)
//----- @param String $type Type of Right ("A"=Admin,"R"=Read)
//-------------------------------------------
    function getsites($uid=0, $format=1, $type="R", $site_id=0){    
              global $skdb;
              if ($uid==0) $uid=$this->uid;
              $group = new skgroup;
              $grouplist=$group->getgroups($uid,1);
              DEBUG_out(2,"debug2","uid:$uid grouplist():".$grouplist."<br>");
              
              $siteqry = "SELECT DISTINCTROW * FROM sk_sites 
                             INNER JOIN sk_groups_sites_link ON sk_sites.site_id = sk_groups_sites_link.site_id
                           WHERE sk_groups_sites_link.type='".$type."' AND sk_groups_sites_link.groupid IN (".$grouplist.")
                           GROUP BY name
                           ORDER BY sk_sites.site_id";
              
                           
              $result = $skdb->Execute($siteqry);
              
              if ($result == false) $GLOBALS['DEBUG_OUTPUT'].="$siteqry site-select failed! ".$skdb->Errormsg()."<br>";
              $list="";
              switch($format){
              
              case 1:
                      while (!$result->EOF AND !$result == false) {
                    $list.=$result->fields["site_id"].",";
                    $result->MoveNext(); 
                    }
                      $list=substr($list,0,(strlen($list)-1));
                  break;
              case 2:
                      while (!$result->EOF AND !$result == false) {
                    $list.="<OPTION value=\"".$result->fields["site_id"]."\"";
                    if($result->fields["site_id"]==$site_id) $list.=" selected ";
                    $list.=">".$result->fields["name"];
                    $result->MoveNext(); 
                    }
                  break;
              case 3:
                   $sitecnt=0;
                   while (!$result->EOF AND !$result == false) {
                    $list[$sitecnt][site_id]=$result->fields["site_id"];
                    $list[$sitecnt][name]=$result->fields["name"];
                    $sitecnt++;
                    $result->MoveNext(); 
                    }
                  break;
                  
              }
              DEBUG_out(2,"debug2","uid:$uid getsites():".$list."<br>");
              return $list;
    
    
    } // function getsites

//-----------------------------------------
//----- function checkpermission
//----- PROTOTYPE maybe obsolete!!!!!
//----- checks permission
//----- for a specific user (if $userid<> 0) on a acl
//----- @param integer $uid User-id
////----- @param String $acl Access-Control_List (module, nav_tree,sites)
//----- @param integer $acl_id The id of the corresponding entry (id for nav_tree, mid for modules, site_id for sites)
//----- @param String $type Type of Right ("A"=Admin,"R"=Read)
//-------------------------------------------
    function checkpermission($uid=0, $acl="",$acl_id=0, $type="R"){
    
        /*group = new skgroup;
        $grouplist=$group->getgroups($uid,1);
        $aclgroups=$group->getgroups(0,1,0,$acl,$acl_id,$type);
        
        if (sizeof($arr_to_searchin) == 0) {

            foreach ($myArray as $currentValue) {
                if (in_array ($newValue, $currentValue)) {
                   $itBeInThere = 1;
             }

        }*/
        
    } // function checkpermission
    




} //class skgroup