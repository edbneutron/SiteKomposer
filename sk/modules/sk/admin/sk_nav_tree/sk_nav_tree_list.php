<?
$docrelpath="../../../../../";

$title1 = "Struktur verwalten";
$title2 = "Struktur verwalten&nbsp;";
$titleimg = "nav_tree.gif";

require_once("../admin_top.inc.php");
/*SQL-Updates to update/insert/delete data*/
$table="sk_nav_tree";
DEBUG_out(1,"debug1","action=".$action);
// ********* get group-list for access ***********
$group = new skgroup;
$grouplist="1";
$grouplist2=$group->getgroups($sess_uid,1);
if($grouplist2>"") $grouplist.=",".$grouplist2;


if (isset($action)):
{ $l1 = explode("-",$skdbadm->DBDate(time()));
  $saField[last_mod] = mktime(0,0,0,intval($l1[2]),intval($l1[1]),intval($l1[0]));
 }
//$saField[last_mod]=$skdbadm->DBTimestamp(time());
$saField[by_user]=$sess_uid;
$display_qstr=$GLOBALS[QUERY_STRING];
DEBUG_out(2,"debug2",print_array($saField)); //All Vars Output
if ($saField[p]!=$saField[id]):
    $sql = "SELECT * FROM $table WHERE id = $saField[p]"; //SELECT a record
    $rs = $skdb->Execute($sql);
    $saField[depth]=$rs->fields[depth]+1;
else:
    $saField[depth]=1;
endif;
if(!isset($saField[mview])) $saField[mview]=0;
if(!isset($saField[nolink])) $saField[nolink]=0;
DEBUG_out(2,"query",$display_qstr);


IF ($action =="delete"): // ***************** DELETE the record ***************************

   $nav_tree=new sknavtree($saField[id]);
   $nav_tree->delete(1,$saField[id]);


ELSE: // ******************** UPDATE or INSERT a RECORD ********************

// CHECK for magic_quotes-setting and strip-slashes when set *********************

if (get_magic_quotes_gpc() == 1){ 
     while (list ($key, $val) = each ($saField)){
            $saField[$key] = stripslashes($val);
        }
}
IF (empty($saField[id])) $saField[id]=-1;
$p=$saField[p];

$sql = "SELECT * FROM $table WHERE id = $saField[id]"; //SELECT a record
$rs = $skdb->Execute($sql);

    IF ($saField[id]==-1): // *********** INSERT *************
        unset($saField[id]);
        if ($p=="self") unset($saField[p]);
        $saField[user_id]=$sess_uid;
        $insertSQL = $skdbadm->GetInsertSQL($rs, $saField);
        DEBUG_out(2,"query",$insertSQL);
        $result=$skdbadm->Execute($insertSQL);
        if ($result == false) $Messages .= "DB-insert failed! ".$skdbadm->Errormsg()."<br>";
        
        $myNewID = $skdbadm->Insert_ID();
        if ($p=="self"):
         $updateSQL="UPDATE sk_nav_tree set p=$myNewID WHERE id=$myNewID";
         $result=$skdbadm->Execute($updateSQL);
         if ($result == false) $Messages .= "DB-update failed! ".$skdbadm->Errormsg()."<br>";
        endif;
        // Add Administration right to webmasters-group
        $pgroup = new skgroup();
        $pgroup->add_permission($grouplist,'nav_tree',$myNewID,'A');
        $display_qstr="mid=".$myNewID."&tmv_value=".$tmv_value."&framed=".$framed;
        
    ELSE:                    // *********** UPDATE *************
        if($saField[p]=="self") $saField[p]=$saField[id];
        $updateSQL = $skdbadm->GetUpdateSQL($rs, $saField);
        DEBUG_out(2,"query",$updateSQL);
        $result=$skdbadm->Execute($updateSQL);
        
        if ($result == false) $Messages .= "DB-update failed! ".$skdbadm->Errormsg()."<br>";
    ENDIF;
    
ENDIF; // IF ($action =="delete"):
ENDIF; //if (isset($action)):


/* ****************** SQL-Selects to fill data********************* */


if (!isset($mid)) $noadd=1;
if (!isset($sess_site_id)) $sess_site_id=1;

$site_name=$current_site->attributes["name"];

?>
<table cellpadding="0" cellspacing="0" width="100%" border="0"  >
  
  <tr> 
    <td  valign="top" align="left" height="1">
    <table width="400" border="0" cellspacing="2" cellpadding="0">
      <tr>
        <td><iframe class="admframe" name="navigation" width="280" height="450" src="navigationframe.php?<?=$GLOBALS[QUERY_STRING]?>&site_name=<?=$site_name?>"> 
      </iframe></td>
        <td><iframe class="admframe" name="editform" width="345" height="450" src="sk_nav_tree_edit.php?<?=$display_qstr?>&framed=1"> 
      </iframe></td>
      </tr>
    </table>

       </td>
  </tr>
</table>

<?

include(MODULE_PATH."sk/admin/admin_foot.inc.php");?>