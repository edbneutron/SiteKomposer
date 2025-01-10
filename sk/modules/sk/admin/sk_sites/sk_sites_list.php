<? session_start();
require_once("../admin_top.inc.php");
if(!isset($_SESSION["sess_hash"])) header("Location:../index.php");





$title1 = "Site-Verwaltung";
$title2 = "Site-Verwaltung";
$titleimg = "icon.gif";


/*SQL-Updates to update/insert/delete data*/
$table="sk_sites";
if ($GLOBALS["debug"]>0) $Messages .= "action=".$action." "; //.print_array($HTTP_POST_FILES); //All Vars Output

if (isset($action)){
//$saField[site_id]=$saFieldid;
connectadm();
if ($GLOBALS["debug"]>0) $Messages .= print_array($saField); //All Vars Output

IF ($action =="delete"){ // ***************** DELETE the record ***************************
    // Delete complete site
    $this_tree= new sknavtree();
    $this_tree->delete(1,0,$saField[site_id]);
    connectadm();
    // delete permissions for page
        $delSQL = "DELETE FROM sk_groups_sites_link WHERE site_id ='".$saField['site_id']."'";
        DEBUG_out(3,"query","DB-DELETE:<br>$delSQL");
        $result=$skdbadm->Execute($delSQL);
        if ($result == false) DEBUG_out(3,"error3","DB-DELETE failed!<br>$delSQL".$skdbadm->Errormsg()."");


    $delSQL = "DELETE FROM $table WHERE site_id = '".$saField['site_id']."'";
        DEBUG_out(3,"query","DB-DELETE:<br>$delSQL");
        $result=$skdbadm->Execute($delSQL);
        if ($result == false) DEBUG_out(3,"error3","DB-DELETE failed!<br>$delSQL".$skdbadm->Errormsg()."");

}ELSE{ // ******************** UPDATE or INSERT a RECORD ********************

// CHECK for magic_quotes-setting and strip-slashes when set *********************

if (get_magic_quotes_gpc() == 1){ 
     while (list ($key, $val) = each ($saField)){
            $saField[$key] = stripslashes($val);
        }
}


IF (empty($saField[site_id])) $saField[site_id]=-1;
$sql = "SELECT * FROM $table  where site_id = '$saField[site_id]'"; //SELECT a record
$rs = $skdb->Execute($sql);

    IF ($saField[site_id]==-1): // *********** INSERT *************
        unset($saField[site_id]);
        
        $insertSQL = $skdbadm->GetInsertSQL($rs, $saField);
        if ($GLOBALS["debug"]>0) $Messages .= $insertSQL;
        $result=$skdbadm->Execute($insertSQL);
        
        if($result == false): $Messages.=$table." DB-insert failed! ".$skdbadm->Errormsg()."<br>";endif;
        
    ELSE:                    // *********** UPDATE *************
        $updateSQL = $skdbadm->GetUpdateSQL($rs, $saField);
        if ($GLOBALS["debug"]>0) $Messages .= $updateSQL;
        $result=$skdbadm->Execute($updateSQL);
        if ($result == false) $Messages .= $table." DB-update failed! ".$skdbadm->Errormsg()."<br>";

    ENDIF;
    
} // IF ($action =="delete"):
closeadm();
} //if (isset($action)):

/* ****************** SQL-Selects to fill data********************* */
$curr_page = $current_page;
$num_of_rows_per_page=10;

if (empty($curr_page)) $curr_page = 1;

$sql_select = "select * from $table ";
$result1 = $skdb->Execute($sql_select);

if (!$result1->EOF):
    $result = $skdb->PageExecute($sql_select, $num_of_rows_per_page, $curr_page);

if ($result === false) $Messages.= "DB-select failed! ".$skdb->Errormsg()."<br>";
ENDIF;

?>
<form action="" method="post" name="editform" id="editform">
<table cellpadding="0" cellspacing="0" width="100%" border="0"  class="thingrid">
<? while (!$result->EOF AND !$result === false) {?>
  <tr height="25" onmouseover="this.style.backgroundColor = '#ffffff'" onmouseout="this.style.backgroundColor = '#eeeeee'">
    <td width="13" valign="middle" align="left"  height="25" class="small">
    <? echo $result->CurrentRow()+($num_of_rows_per_page * ($curr_page-1))+1;?></td>
    <td width="13" valign="middle" align="left"  height="25"  class="small">&nbsp;</td>
    <td width="320" valign="middle" align="left"  height="25"  class="listitem">
      <b><? echo $result->fields[name];?></b>
    </td>
    <td valign="middle" align="right" width="80" height="25" class="leadtext"><b>&nbsp;<? //echo $result->fields[];?></b></SPAN>
    </td>
    <td valign="middle" align="right"  width="120" height="25">
    <a href="sk_sites_edit.php?current_page=<? echo $result->AbsolutePage();?>&saField[site_id]=<? echo $result->fields[site_id];?>&alvis=<?=$alvis?>"><?echo $GLOBALS['edit_button'];?> </a>
    <?echo "<a href=\"javascript:void(0);\" OnClick=\"frage_s('saFieldid',".$result->fields[site_id].");return false\"><img src=\"".SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/del_icon.gif\"  border=\"0\" align=\"baseline\"></a>";?>
    </td>
  </tr>
   <tr>
    <td colspan="5" valign="top" align="left" bgcolor="#666666" height="1"><img src="<?echo SKRES_URL."img/";?>shim.gif" width="100" height="1"></td>
  </tr> 
<? $result->MoveNext(); }//while?>


<? if (!$result == false){?>
  <? //-----------Page-Navigation---------------
  if (!$result->AtFirstPage() || !$result->AtLastPage()) {?>
    <tr>
     <td colspan="3" valign="top" align="left" bgcolor="#aaaaaa" height="1" class="small">
    <? if (!$result->atFirstPage()) {?> <a href="<? echo $PHP_SELF;?>?current_page=<? echo $result->AbsolutePage() - 1 ?>"><-Previous page</a>
    <?}?>&nbsp;
    </td>
    <td colspan="2" valign="top" align="right" bgcolor="#aaaaaa" height="1"class="small">
    <?  if (!$result->atLastPage()) {?> <a href="<? echo $PHP_SELF;?>?current_page=<? echo $result->AbsolutePage() + 1 ?>">Next page-></a>
    <?}?>&nbsp;
    </td>
    </tr>
<? }ELSE{?>
   <tr>
    <td colspan="5" valign="top" align="left" bgcolor="#aaaaaa" height="1"><SPAN class="small">&nbsp;</SPAN>
    </td>
    </tr>
<? } // !atfirst & last-page?>
<? } //!result false?>

   <tr>
      <td colspan ="5" class="text" bgcolor="#EEEEEE" align="right">&nbsp;
      <b><a href="sk_sites_edit.php?alvis=<?=$alvis?>">neuer Eintrag <?echo $GLOBALS['add_button'];?></a></b>
      </td>

      </tr>
    </table>
 <input type="hidden" name="action" value="delete">
<input type="hidden" name="alvis" value="<? echo $alvis?>">
<input type="hidden" name="current_page" value="<? echo $current_page;?>">
<input type="hidden" name="saFieldid" value="0">
</form>

<? include(MODULE_PATH."sk/admin/admin_foot.inc.php");?>