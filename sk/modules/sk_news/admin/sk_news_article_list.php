<?
session_start();

$docrelpath="../../../../";

$debug=0;

if(!$_SESSION['sess_hash']) header("Location:".$docrelpath."sk/admin/index.php");
$title1 = "Artikel verwalten";
$title2 = "News verwalten&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"sk_news_sections.php\">&gt;Bereiche</a>";
$titleimg = "news.gif";

require_once("../../sk/admin/admin_top.inc.php");
/*SQL-Updates to update/insert/delete data*/
if (!isset($section_id)) $section_id=1; /* Test_id*/
IF (empty($saField[section_id])) $saField[section_id]=$section_id;
$table="sk_news";
if ($GLOBALS[debug]>0) $Messages .= "action=".$action."<br>section_id:".$section_id; // .print_array($HTTP_POST_FILES); //All Vars Output

if (isset($action)):
{ 
  $saField=$_POST[saField];
  $saField[last_mod] = time();
  DEBUG_out(1,"debug2","saField:".print_array($saField));
 }
//$saField[last_mod]=$skdbadm->DBTimestamp(time());
$saField[by_user]=$sess_uid;

$saField[newstext]=$objtext;

// ***************** FILE UPLOADS **************************
    $saField[image] = $old_saField[image];
    $saField[file1] = $old_saField[file1];
    $saField[file2] = $old_saField[file2];
    $saField[file3] = $old_saField[file3];
    $newsimgpath=ROOT_PATH."/".$current_site->attributes[dirname]."/content/news/images/";
    $newsfilepath=ROOT_PATH."/".$current_site->attributes[dirname]."/content/news/files/";
    if ($HTTP_POST_FILES[saField][name][image] OR $del_saField[image] ==1):
     $saField[image] = skfile($HTTP_POST_FILES[saField][tmp_name][image],$HTTP_POST_FILES[saField][name][image],$newsimgpath,$old_saField[image]);
    endif;
    if ($HTTP_POST_FILES[saField][name][file1] OR $del_saField[file1] ==1):
     $saField[file1] = skfile($HTTP_POST_FILES[saField][tmp_name][file1],$HTTP_POST_FILES[saField][name][file1],$newsfilepath,$old_saField[file1]);
    endif;
    if ($HTTP_POST_FILES[saField][name][file2] OR $del_saField[file2] ==1):
     $saField[file2] = skfile($HTTP_POST_FILES[saField][tmp_name][file2],$HTTP_POST_FILES[saField][name][file2],$newsfilepath,$old_saField[file2]);
    endif;
    
// ****************** Date - Conversion ********************
/*if (strlen($saField[ndate]) > 0 )
{ $l1 = explode("-",$saField[ndate]);
  $saField[ndate] = mktime(0,0,0,$l1[1],$l1[0],$l1[2]);
 }*/
  $saField[ndate] = mktime(0,0,0,$nmonth,$nday,$nyear);
/*if (strlen($saField[duedate]) > 0 )
{ $l1 = explode("-",$saField[duedate]);
  $saField[duedate] = mktime(0,0,0,$l1[1],$l1[0],$l1[2]);
 }*/
  $saField[duedate] = mktime(0,0,0,$dmonth,$dday,$dyear);


if ($GLOBALS[debug]>0) $Messages .= print_array($saField); //All Vars Output

IF ($action =="delete"): // ***************** DELETE the record ***************************

    $delSQL = "DELETE FROM $table WHERE id =$saField[id]";
        if ($GLOBALS[debug]>0) $Messages .= $delSQL;
        $result=$skdbadm->Execute($delSQL);
        if ($result == false):
           $Messages .= " DB-DELETE failed! ".$skdbadm->Errormsg()."<br>";
        endif;

ELSE: // ******************** UPDATE or INSERT a RECORD ********************

// CHECK for magic_quotes-setting and strip-slashes when set *********************

if (get_magic_quotes_gpc() == 1){ 
     while (list ($key, $val) = each ($saField)){
            $saField[$key] = stripslashes($val);
        }
}


IF (empty($saField[id])) $saField[id]=-1;

$sql = "SELECT * FROM $table WHERE id = $saField[id]"; //SELECT a record
$rs = $skdb->Execute($sql);

    IF ($saField[id]==-1): // *********** INSERT *************
        unset($saField[id]);
        $saField[uid]=$sess_uid;
        $insertSQL = $skdbadm->GetInsertSQL($rs, $saField);
        if ($GLOBALS[debug]>0) $Messages .= $insertSQL;
        $result=$skdbadm->Execute($insertSQL);
        
        if ($result == false): $Messages .= "DB-insert failed! ".$skdbadm->Errormsg()."<br>";
        else: $current_user->increaseposts();
        endif;
        
    ELSE:                    // *********** UPDATE *************
        $updateSQL = $skdbadm->GetUpdateSQL($rs, $saField);
        if ($GLOBALS[debug]>0) $Messages .= $updateSQL;
        $result=$skdbadm->Execute($updateSQL);
        
        if ($result == false): $Messages .= "DB-update failed! ".$skdbadm->Errormsg()."<br>";endif;

    ENDIF;
    
ENDIF; // IF ($action =="delete"):
ENDIF; //if (isset($action)):


/* ****************** SQL-Selects to fill data********************* */
$curr_page = $current_page;
$num_of_rows_per_page=10;

if (empty($curr_page)) $curr_page = 1;

$sql_select = "select sk_news.id, title, lead, name,ndate as sortdate, DATE_FORMAT(ndate, '%d-%m-%Y') as ndate from sk_news LEFT JOIN sk_newssections ON section_id=sk_newssections.id
  WHERE section_id=$section_id order by sortdate desc";
$result1 = $skdb->Execute($sql_select);

if (!$result1->EOF):
    $result = $skdb->PageExecute($sql_select, $num_of_rows_per_page, $curr_page);

if ($result === false) $Messages.= "DB-select failed! ".$skdb->Errormsg()."<br>";
ENDIF



?>
 <table bgcolor="#EEEEEE" border="0" cellpadding="3" cellspacing="3">
 <tr>
 <td valign="top" align="left"  width="550" bgcolor="#FFFFFF">
 <font size="2"><a href="sk_news_sections.php?section_id=<?=$section_id?>&alvis=<?=$alvis?>">
            zur&uuml;ck zur Bereichs-Liste</a></font>&nbsp;&nbsp;&nbsp;
 <table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
      <td valign="top" align="left"  width="550" bgcolor="#EEEEEE">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr height="25">
            <td width="13" valign="top" align="left" bgcolor="#eeeeee" height="25">&nbsp;</td>
            <td width="400" valign="middle" align="left" bgcolor="#eeeeee" height="25">Eintr&auml;ge in diesem Bereich:</td>
            <td valign="middle" align="right" bgcolor="#eeeeee" width="161" height="25"><font size="2">
           &nbsp;</font></td>
          </tr></table>
    </td>
</tr>
<tr><td>
<table cellpadding="0" cellspacing="0" width="100%" border="0"  class="thingrid">
<? while (!$result->EOF AND !$result === false) {?>
  <tr height="25" onmouseover="this.style.backgroundColor = '#ffffff'" onmouseout="this.style.backgroundColor = '#eeeeee'">
    <td width="13" valign="middle" align="left"  height="25"><SPAN class="small">
    <? echo $result->CurrentRow()+($num_of_rows_per_page * ($curr_page-1))+1;?></SPAN></td>
    
    <td width="13" valign="middle" align="left"  height="25"  class=><SPAN class="small">
    <!-- <?echo $result->fields["id"];?> --></SPAN></td>
    
    <td width="320" valign="middle" align="left"  height="25"  class=><SPAN class="listitem">
      <b><? echo $result->fields["title"];?></b><br>
      <? echo substr($result->fields["lead"],0,100);?>...
      </SPAN></td>
    <td valign="middle" align="right" width="80" height="25" class=><SPAN class="leadtext"><b>
      <? echo $result->fields["ndate"];?>
      </b></SPAN></td>
      <form action="<?$PHP_SELF?>?section_id=<?=$section_id?>" method="post" name="editform<?echo $result->fields["id"];?>" id="editform<?echo $result->fields["id"];?>">
    <td valign="middle" align="right"  width="120" height="25">
    <a href="sk_news_article_edit.php?current_page=<?php echo $result->AbsolutePage()?>&news_id=<?echo $result->fields["id"];?>&section_id=<?echo $section_id;?>&alvis=<?=$alvis?>"><?echo $GLOBALS['edit_button'];?> </a>
    <?echo skitem::delbutton("editform".$result->fields["id"],"action","delete")?>    
      </td>
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="current_page" value="<?echo $current_page;?>">
      <input type="hidden" name="saField[id]" value="<?echo $result->fields["id"];?>"></form>
    
  </tr>

   <tr> 
    <td colspan="5" valign="top" align="left" bgcolor="#666666" height="1"><img src="<?echo SKRES_URL."img/";?>shim.gif" width="100" height="1"></td>
  </tr> 
<? $result->MoveNext(); } ?> 

<? if (!$result === false):?>
  <tr> 
  <?php //-----------Page-Navigation---------------
  if (!$result->AtFirstPage() || !$result->AtLastPage()) :?>
    <td colspan="3" valign="top" align="left" bgcolor="#aaaaaa" height="1"><SPAN class="small">
    <? if (!$result->atFirstPage()) {?>
    <a href="<?php echo $PHP_SELF;?>?current_page=<?php echo $result->AbsolutePage() - 1 ?>&section_id=<?echo $section_id;?>"><-Previous page</a>
    <?}?>&nbsp;
    </SPAN></td>
    <td colspan="2" valign="top" align="right" bgcolor="#aaaaaa" height="1"><SPAN class="small">
    <?    if (!$result->atLastPage()) {?>
    <a href="<?php echo $PHP_SELF;?>?current_page=<?php echo $result->AbsolutePage() + 1 ?>&section_id=<?echo $section_id;?>">Next page-></a>
    <?}?>&nbsp;
    </SPAN></td>
<?ELSE:?>
    <td colspan="5" valign="top" align="left" bgcolor="#aaaaaa" height="1"><SPAN class="small">&nbsp;</SPAN></td>
<?ENDIF;?>
  </tr>
<?ENDIF;?>
    <tr>
      <td colspan ="5"  class="text" bgcolor="#EEEEEE" align="right">&nbsp;
      <b><a href="sk_news_article_edit.php?section_id=<?=$section_id?>">neuer Artikel <?echo $GLOBALS['add_button'];?></a></b>
      </td>

      </tr>
    </table>
    </td>
</tr>
</table>
</td></tr>
                  
</table>
<? include(MODULE_PATH."sk/admin/admin_foot.inc.php"); ?>