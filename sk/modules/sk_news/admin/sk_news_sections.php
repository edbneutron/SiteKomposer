<?
$title1 = "News-Bereiche";
$title2 = "News-Bereiche";
$titleimg = "news.gif";
require_once("../../sk/admin/admin_top.inc.php");


        
/*SQL-Updates to update/insert/delete data*/
$table="sk_newssections";
DEBUG_out(3,"debug3","action=".$action);


/*
if (isset($action)){
while (list ($key, $val) = each ($saField)){
            $req_field="saField_".$key;
            if ($$req_field=="required" && $saField[$key] == ""){
            unset($action);
            $Messages.="<font size=\"2\"><strong>$key eingeben!</strong></font>";
            }
        }
}*/

if (isset($action)){

while (list ($key, $val) = each ($saField)){
            $req_field="saField_".$key;
            if ($$req_field=="required" || $saField[$key] == ""){
            unset($action);
            $Messages.="<strong>$key eingeben!</strong>";
            }
        }
        

DEBUG_out(3,"debug3","saField:".print_array($saField));

IF ($action =="delete"){ // ***************** DELETE the record ***************************

    $delSQL = "DELETE FROM $table WHERE id ='".$saField[id]."'";
        DEBUG_out(3,"query",$delSQL);
        $result=$skdbadm->Execute($delSQL);
        if ($result == false) $Messages .= " DB-DELETE failed! ".$skdbadm->Errormsg()."<br>";

}else{ // ******************** UPDATE or INSERT a RECORD ********************

// CHECK for magic_quotes-setting and strip-slashes when set *********************

if (get_magic_quotes_gpc() == 1){ 
     while (list ($key, $val) = each ($saField)){
            $saField[$key] = stripslashes($val);
        }
}


IF (empty($saField[id])) $saField[id]=-1;
$sql = "SELECT * FROM $table WHERE id = $saField[id]"; //SELECT a record
$rs = $skdb->Execute($sql);

IF ($saField[id]==-1){ // *********** INSERT *************
        unset($saField[id]);
        $saField[site_id]=$sess_site_id;
        $insertSQL = $skdbadm->GetInsertSQL($rs, $saField);
        if ($GLOBALS[debug]>0) $Messages .= $insertSQL;
        $result=$skdbadm->Execute($insertSQL);
        if ($result == false) $Messages .= "DB-insert failed! ".$skdbadm->Errormsg()."<br>";
        
}else{                   // *********** UPDATE *************
        $updateSQL = $skdbadm->GetUpdateSQL($rs, $saField);
        if ($GLOBALS[debug]>0) $Messages .= $updateSQL;
        $result=$skdbadm->Execute($updateSQL);
        if ($result == false) $Messages .= "DB-update failed! ".$skdbadm->Errormsg()."<br>";
        
}
    
} // IF ($action =="delete"):
} //if (isset($action)):


/* ****************** SQL-Selects to fill data********************* */
$curr_page = $current_page;
$num_of_rows_per_page=10;

if (empty($curr_page)) $curr_page = 1;

$sql_select = "select * from  sk_newssections WHERE site_id=".$sess_site_id." order by name";
$result1 = $skdb->Execute($sql_select);

if (!$result1->EOF){
    $result = $skdb->PageExecute($sql_select, $num_of_rows_per_page, $curr_page);

if ($result === false) $Messages .= "DB-select failed! ".$skdb->Errormsg()."<br>";
}



?>
<table cellpadding="0" cellspacing="0" width="100%" border="0"  class="thingrid">
<?while (!$result->EOF AND !$result === false) {?>

  <tr height="25"> 
    <td width="13" valign="middle" align="left" bgcolor="#eeeeee" height="25"  ><SPAN class="small">
    <?echo $result->CurrentRow()+($num_of_rows_per_page * ($curr_page-1))+1;?></SPAN></td>
    
    <td width="13" valign="middle" align="left" bgcolor="#eeeeee" height="25"  class=><SPAN class="small">
    <!-- <?echo $result->fields["id"];?> -->&nbsp;</SPAN></td>
    
    <td width="320" align="center" valign="middle" align="left" bgcolor="#eeeeee" height="25" ><SPAN class="listitem">
      <a href="sk_news_article_list.php?section_id=<?echo $result->fields["id"];?>&alvis=<?=$alvis?>" class="listitem"><b><?echo $result->fields["name"];?></b></a></SPAN></td>
    
    <td valign="middle" align="right" bgcolor="#eeeeee" width="80" height="25" class=><SPAN class="leadtext"><b> 
      <?echo $result->fields["ndate"];?>
      </b></SPAN></td>
      <form action="<?$PHP_SELF?>" method="post" name="editform<?echo $result->fields["id"];?>" id="editform<?echo $result->fields["id"];?>">
    <td valign="middle" align="right" bgcolor="#eeeeee" width="120" height="25" >
    <a href="sk_news_sections.php?current_page=<?php echo $result->AbsolutePage()?>&section_id=<?echo $result->fields["id"];?>&edit=1" class="listitem">
 <?echo $GLOBALS['edit_button'];?> </a>
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
<? if (!$result === false){?>
  <tr> 
  <?php //-----------Page-Navigation---------------
  if (!$result->AtFirstPage() || !$result->AtLastPage()) {?>
    <td colspan="2" valign="top" align="left" bgcolor="#aaaaaa" height="1"><SPAN class="small">
    <? if (!$result->atFirstPage()) {?>
    <a href="<?php echo $PHP_SELF;?>?current_page=<?php echo $result->AbsolutePage() - 1 ?>"><-Previous page</a>
    <?}?>&nbsp;
    </SPAN></td>
    <td colspan="3" valign="top" align="right" bgcolor="#aaaaaa" height="1"><SPAN class="small">
    <?  if (!$result->atLastPage()) {?>
    <a href="<?php echo $PHP_SELF;?>?current_page=<?php echo $result->AbsolutePage() + 1 ?>">Next page-></a>
    <?}?>&nbsp;
    </SPAN></td>
  <?}else{?>
    <td colspan="5" valign="top" align="left" bgcolor="#aaaaaa" height="1"><SPAN class="small">&nbsp;</SPAN></td>
  <?}?>
</font>
</td>
  </tr>
<?}?>

    <tr>
      <td colspan ="5" width="100%" class="text" bgcolor="#EEEEEE" align="right">&nbsp;
      <b><a href="sk_news_sections.php?insert=1">neuer News-Bereich <?echo $GLOBALS['add_button'];?></a></b>
      </td>
      </tr>  
      
    </table>
<?//News-Sections Edit/Insert Forms
if (isset($edit) || isset($insert)):

if ($edit==1):
    $sql_select = "select * from sk_newssections WHERE id = '$section_id'";
    if ($GLOBALS[debug]>0) $Messages .= $sql_select;
    $recordSet = $skdb->Execute($sql_select);
    if ($recordSet == false) $Messages .= "DB-select failed! ".$skdb->Errormsg()."<br>";
endif;?>

<form name="editform" method="post" action="sk_news_sections.php?section_id=<?=$section_id?>" enctype="multipart/form-data">
  <table bgcolor="#EEEEEE" border="1" cellpadding="3" cellspacing="3">
    <tr>
      <td valign="top" align="left"  width="550" bgcolor="#FFFFFF">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">

  <input type="hidden" 
             name="saField[id]"  
             value="<?php echo $recordSet->fields["id"] ?>"
             >
  <input type="hidden" 
             name="saField[anon_com]"  
             value="0"
             >
   <tr>
      <td valign="top"> Name </td>
   <td>
      <input type="text" class="text" size="30" 
             maxlength="255" 
             name="saField[name]"  
             value="<?php echo $recordSet->fields["name"] ?>"
             >
      <input type="hidden" 
             name="saField_name"  
             value="required"
             >
  </td>
</tr>
<tr>
      <td valign="top"> Beschreibung </td>
   <td>
      <textarea name="saField[descr]"
                cols=60 
                rows=5><?php echo $recordSet->fields["descr"] ?></textarea>
  </td>
</tr>
<!--
<tr>
      <td valign="top">&Ouml;ffentliches posten von Kommentaren</td>
   <td>
       <input 
             name="saField[anon_com]" type="checkbox"  
             value="1" <? if($recordSet->fields["anon_com"]==1):?>checked<? endif;?>
             >
  </td>
</tr>-->
<tr><td colspan="2">
  <table><tr>
    <td><input type="submit" name="ok" value="OK" ></td>
    <td><?IF ($recordSet->fields["id"] > 0):?><?echo $GLOBALS['del_button'];?><?ELSE:?>&nbsp;<?ENDIF;?></td>
  </tr></table>
</td></tr>
</table>
</td></tr>        
</table>
<input type="hidden" name="section_id" value="<?=$section_id?>">    
<input type="hidden" name="action" value="do">
</form>
<? endif; ?>

<? include(MODULE_PATH."sk/admin/admin_foot.inc.php"); ?>