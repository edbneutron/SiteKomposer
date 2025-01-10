<?
$docrelpath="../../../../";

if (!isset($section_id)) $section_id=1; /* Test_id*/

$title1 = "News verwalten";
$title2 = "News verwalten&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"sk_news_article_list.php?section_id=".$section_id."\" >&gt;Liste</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"sk_news_sections.php\">&gt;Bereiche</a>";
$titleimg = "news.gif";
require_once("../../sk/admin/admin_top.inc.php");

if (isset($news_id)):

$sql_select = "select *, DATE_FORMAT(ndate, '%d-%m-%Y') as ndate, DATE_FORMAT(duedate, '%d-%m-%Y') as duedate from sk_news WHERE sk_news.id = '$news_id' AND section_id = $section_id";
if ($GLOBALS[debug]>0) $Messages .= $sql_select;
$recordSet = $skdb->Execute($sql_select);
if ($recordSet == false) $Messages .= "DB-select failed! ".$skdb->Errormsg()."<br>";
endif;


   
require_once(WYSIWYG_EDITOR);

?>




<form name="editform" method="post" action="sk_news_article_list.php?section_id=<?=$section_id?>&alvis=<?=$alvis?>" enctype="multipart/form-data" onSubmit="">
  <table bgcolor="#EEEEEE" border="0" cellpadding="3" cellspacing="3">
    <tr>
      <td valign="top" align="left"  width="550" bgcolor="#FFFFFF">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">

  <input type="hidden" 
             name="saField[id]"  
             value="<?php echo htmlspecialchars($recordSet->fields["id"]) ?>"
             >
    <tr>
      <td valign="top" align="left" colspan="2" width="550" bgcolor="#EEEEEE">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr height="25">
            <td width="13" valign="top" align="left" bgcolor="#eeeeee" height="25">&nbsp;</td>
            <td width="400" valign="middle" align="left" bgcolor="#eeeeee" height="25"><font size="2"><a href="sk_news_article_list.php?section_id=<?=$section_id?>&alvis=<?=$alvis?>">zur&uuml;ck 
              zur Liste</a></font></td>
            <td valign="middle" align="right" bgcolor="#eeeeee" width="161" height="25"><font size="2">
           &nbsp;</font></td>
          </tr></table>
    </td>
</tr>
<tr>
   <td>
      Titel   </td>
   <td>
      <input type="text" class="text" size="30" 
             maxlength="255" 
             name="saField[title]"  
             value="<?php echo htmlspecialchars($recordSet->fields["title"]) ?>"
             >
      Datum:<?
             if(!$recordSet->fields["ndate"]) { 
              $today = getdate(); 
              $dtemp[0] = $today['mday']; 
              $dtemp[1] = $today['mon'];
              $dtemp[2] = $today['year']; 
              }else{
              $dtemp = explode("-",$recordSet->fields["ndate"]);
              }
             echo listbox_date("nday", $dtemp[0]);?> -
             <? echo listbox_month("nmonth",  $dtemp[1]);?> -
             <? echo listbox_year("nyear", 2002, 2010,  $dtemp[2]);?>
   </td>
</tr>
<tr>
      <td valign="top"> Einleitung </td>
   <td>
      <textarea name="saField[lead]"
                  cols=60 
                rows=5><?php echo $recordSet->fields["lead"] ?></textarea>
  </td>
</tr>
<tr>
      <td valign="top"> Text </td>
   <td>
   <textarea name="objtext" id="objtext" cols="90" rows="30" wrap="VIRTUAL" class=newstext><? echo $recordSet->fields["newstext"];?></textarea>
   <script type="text/javascript"> 
        initEditor();
   </script> 
 
                
   </td>
</tr>
<tr>
      <td> Bild</td>
   <td>
         <? if($recordSet->fields["image"]>""){ $imgsrc=$site_url."content/news/image/".$recordSet->fields["image"];
           }else{ $imgsrc=SKRES_URL."img/spacer.gif";
         }?>
      <img src="<?= $imgsrc?>" alt="<?php echo $recordSet->fields["image"] ?>" name="saFieldimage_img"><br>
      <input type="file" 
             name="saField[image]"  
             OnChange="change_imgsrc('saFieldimage_img',this.value)">
      <input type="hidden" name="old_saField[image]" value="<?php echo $recordSet->fields["image"] ?>">
      <?php if($recordSet->fields["image"]>"") {echo "&nbsp;&nbsp;&nbsp;".$recordSet->fields["image"] ?>&nbsp;&nbsp;&nbsp;l&ouml;schen:<input type="checkbox" name="del_saField[image]" value="1"><?}?>
   </td>
</tr>

<tr>
   <td>
      file1   </td>
   <td>
      <input type="file" 
             name="saField[file1]"  
             >
      <input type="hidden" name="old_saField[file1]" value="<?php echo $recordSet->fields["file1"] ?>">
      <?php if($recordSet->fields["file1"]>"") {echo "&nbsp;&nbsp;&nbsp;".$recordSet->fields["file1"] ?>&nbsp;&nbsp;&nbsp;l&ouml;schen:<input type="checkbox" name="del_saField[file1]" value="1"><?}?>
   </td>
</tr>

<tr>
   <td>
      file2   </td>
   <td>
      <input type="file" 
             name="saField[file2]"  
             >
      <input type="hidden" name="old_saField[file2]" value="<?php echo $recordSet->fields["file2"] ?>">
      <?php if($recordSet->fields["file2"]>"") {echo "&nbsp;&nbsp;&nbsp;".$recordSet->fields["file2"] ?>&nbsp;&nbsp;&nbsp;l&ouml;schen:<input type="checkbox" name="del_saField[file2]" value="1"><?}?>
   </td>
</tr>
<tr>
   <td>
      Ablaufdatum&nbsp;&nbsp;   </td>
   <td>
    <?       if(!$recordSet->fields["duedate"]) { 
              $today = getdate(); 
              $dtemp[0] = $today['mday']; 
              $dtemp[1] = $today['mon']+1;
              $dtemp[2] = $today['year']; 
              }else{
              $dtemp = explode("-",$recordSet->fields["duedate"]);
              }
             echo listbox_date("dday", $dtemp[0]);?> -
             <?echo listbox_month("dmonth",  $dtemp[1]);?> -
             <?echo listbox_year("dyear", 2002, 2010,  $dtemp[2]);?>
   </td>
</tr>
<tr>
   <td>&nbsp; </td>
   <td><? IF (!isset($recordSet->fields["section_id"])) $recordSet->fields["section_id"]=$section_id;?>
   <input type="hidden" 
             name="saField[section_id]"  
             value="<?php echo $recordSet->fields["section_id"] ?>"
             >
    <!-- <input type="hidden" 
             name="saField[user]"  
             value="admin"
             > -->
    </td>
</tr>
<tr><td colspan="2">
  <table><tr>
    <td><input type="submit" name="ok" value="OK">
    </td>
    <td><?IF ($recordSet->fields["id"] > 0):?><input type="button" name="delete" value="l&ouml;schen" onClick="frage_s();"><?ELSE:?>&nbsp;<?ENDIF;?></td>
  </tr></table>
</td></tr>
                  
</table>
</td></tr>
                  
</table>
<input type="hidden" name="current_page" value="<?echo $current_page;?>">
<input type="hidden" name="section_id" value="<?=$section_id?>">    
<input type="hidden" name="action" value="do">
</form>
<? include(MODULE_PATH."sk/admin/admin_foot.inc.php"); ?>