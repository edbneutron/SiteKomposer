<?

  // browse Object-Types
  $sql_select = "select * FROM sk_objecttypes WHERE admin_vis=1 AND type<> 'link'  AND type<> 'news' ORDER by sort_nr";
    $types_result = $skdb->Execute($sql_select);
    if ($types_result == false):
      echo "SK-objekttypes-select failed! ".$skdb->Errormsg()."<br>";
    ENDIF;
  $count=$types_result->RecordCount();
  // print_array($current_site->attributes);
  if (!isset($current_type)) $current_type='image';
  if (!isset($selected_site)) $selected_site=$current_site->attributes['site_id'];
  
    // get Menu-Structure
  $current_site_id=$selected_site;
  $Menu = new skmenu(1);
  $Menu->getfromdb();
  if (!isset($selected_page)) $selected_page=$Menu->tree[1][3];
  
  $displayperpage="8";              // record displayed per page
  $pageperstage="10";   // page displayed per stage

  $objlistqry = "SELECT sk_objects.content_id, sk_objects.id, sk_objects.type, sk_objects.file, sk_objects.attributes, sk_objects.objtext, sk_objects.sort_nr, sk_content.mid, sk_objects.user_id, sk_nav_tree.title, sk_content.content_area
                FROM (sk_objects INNER JOIN sk_content ON sk_objects.content_id = sk_content.id) INNER JOIN sk_nav_tree ON sk_content.mid = sk_nav_tree.id
                WHERE ((sk_objects.type='".$current_type."') AND (sk_nav_tree.site_id='".$selected_site."') AND (sk_nav_tree.id='".$selected_page."'))
                ORDER BY sk_nav_tree.title, sk_nav_tree.sort_nr, sk_nav_tree.p ASC, sk_content.content_area, sk_objects.sort_nr";

              $result1= $skdb->Execute($objlistqry);

              if ($result1 == false || $result1->EOF ){ 
                $GLOBALS['DEBUG_OUTPUT'].="object list empty ".$objlistqry.$skdb->Errormsg()."<br>";
                }
              else {
                $result = $skdb->PageExecute($objlistqry, $displayperpage, $nowpage);
              }

$number=$result1->RecordCount();                // record results selected from database            
$allpage=ceil($number/$displayperpage);     // how much page will it be ?
$allstage=ceil($allpage/$pageperstage);     // how many page will it be ?
if(trim($startpage)==""){$startpage=1;}
if(trim($nowstage)==""){$nowstage=1;}
if(trim($nowpage)==""){$nowpage=$startpage;}          

?> 
<style type="text/css">
<!--
table.mgallery {
    border: 0px solid #009966;
}
td.mgalleryitem {
    background-color: #F4F4F4;
    border: 1px solid #818181;
    padding: 1px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
}
td.noborder {
    background-color: #F4F4F4;
    border: 0px solid #818181;
    padding: 1px;
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
}
td.mgallerytitle {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 12px;
    font-weight: bold;
    color: #575757;
    background-color: #FFFFFF;
    text-align: center;
    padding: 2px;
}
td.mgallerydescr {
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 9px;
    padding: 2px;
    color: #666666;
}
-->
</style>

<a name="top">
<div id="linktable" style="min-height:500px;">
<table width="500" border="0" cellpadding="0" cellspacing="2" class="mgallery">
<form action="<?=$_SERVER["SCRIPT_NAME"]."?".$QUERY_STRING?>" method="post" name="siteform">
<tr> 
    <td height="1" colspan="4" class="mgallerytitle">
    Web-Site:<strong> 
        </strong>&nbsp;
        <select name="selected_site"  OnChange="document.siteform.submit();"><? echo $current_user->getsites(0,2,"A",$selected_site); ?></select>
        <input class="small" name="getsite" type="submit" value="&lt;-">&nbsp;
  <input type="hidden" name="change_site" value="1">
  </td>
</tr>
</form>
<form action="<?=$_SERVER["SCRIPT_NAME"]."?".$QUERY_STRING?>" method="post" name="pageform">
<tr> 
    <td height="1" colspan="4" class="mgallerytitle">
    Seite:<strong> 
        </strong>&nbsp;&nbsp;
        <select name="selected_page" OnChange="document.pageform.submit();">
        <? 
reset($Menu->tree);
while (list($index, $submenu) = each($Menu->tree) ) {    
    ?>
    <option value=<? if($submenu[3]==$selected_page) echo '"'.$submenu[3].'" selected'; else echo '"'.$submenu[3].'"';?>><? for($i=1;$i<$submenu[0];$i++) echo "&nbsp;-"; echo $submenu[1]?></option>
    <?}?>
    </select>
        <input class="small" name="getsite" type="submit" value="&lt;-">&nbsp;
  <input type="hidden" name="selected_site" value="<? echo $selected_site ?>">
  </td>
</tr>
</form>
<tr> 
 <td height="1" colspan="4" class="mgallerytitle">
 <table><tr>
 <? while (!$types_result->EOF AND !$result == false) { ?>
    <td width="94" align="center"<? if ($current_type==$types_result->fields[type]):?> bgcolor="#009966"<? endif;?>>
    <a class="smallblack" href="<? echo $formtarget;?>?object_id=<? echo $object_id;?>&current_type=<? echo $types_result->fields[type];?>&form_template=link.php&sort_nr=<? echo $sort_nr;?>&identifier=<? echo $identifier;?>&parent_node=<? echo $parent_node;?>&form_window=1&window_title=Link&selected_site=<? echo $selected_site;?>&selected_page=<? echo $selected_page;?>">
    <img src="<? echo SKRES_URL."skinz/".$GLOBALS['theme']."/object_icons/";?>small/<? echo $types_result->fields[icon];?>" width="15" height="15" border="0"><br>
    <? echo $types_result->fields["name"];?></a></td>
  <?$types_result->MoveNext();
    
  }
 ?></tr></table>
 </td>
</tr>
<tr>
    <td width="15" align="center" class="mgalleryitem" colspan="4">
<nobr>insgesamt: <b><?=$number;?></b> Objekte.&nbsp;&nbsp;&nbsp;
<?=myPageNumber($nowstage,$startpage,$allpage,$nowpage,$pageperstage,$allstage,array('object_id','type','form_template','sort_nr','identifier','parent_node','form_window','window_title','current_type','selected_site'));?><br>
<!--<b>Stage</b> <?=$nowstage." of ".$allstage;?><b>Page</b> <?=$nowpage." of ".$allpage;?><br>-->
</nobr></td>
    
</tr>
<tr>
    <td width="25" align="center" class="mgalleryitem">Seite</td>
    <!--<td width="15" align="center" class="mgalleryitem">Typ</td>-->
    <td width="200" align="center" class="mgalleryitem" colspan="2">Inhalt</td>
    <td width="150" align="center" class="mgalleryitem">view &nbsp; select</td>
</tr>
<form action="<? echo $formtarget;?>" method="POST" target="skpage"  name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="link">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>">
        <? if(isset($edit_object->attributes_vars['object_id'])):?> <input type="Hidden" name="attributes_vars[object_id]" value="<? echo $edit_object->attributes_vars['object_id'];?>"><? endif;?>
        
<? $result->MoveFirst();
   $rownr=0;
   $object = new skobject(0);

   while (!$result->EOF && $result != false && $number > 0) {
   $object->attributes['object_id']=$result->fields[1];
   $object->attributes=$result->fields;
   $object->attributes2vars();
   //$object->get();
   ?>
    <tr>
        <td width="25" align="center" class="mgalleryitem"><div class="smallfont"><? echo $result->fields[title];?><br>Bereich:<? echo $result->fields['content_area'];?></div></td>
   <?
switch ($current_type) {
    case "image":
        ?>
            <td width="15" align="center" class="mgalleryitem">
            <? IF ($object->attributes_vars[thumbnail]==1):
                echo "<IMAGE class=\"skimage\" SRC=\"".SITE_URL."content/image/"."thumbnails/".$object->attributes['file']."\" align=\"".$object->attributes_vars['align']."\" width=\"50\" height=\"50\" ALT=\"".htmlspecialchars($this->attributes['objtext'])."\" >";
                ELSE: 
                echo "<IMAGE class=\"skimage\" SRC=\"".SITE_URL."content/image/".$object->attributes['file']."\" ALT=\"".htmlspecialchars($object->attributes['objtext'])."\" width=\"50\" height=\"50\">";
                ENDIF;
        ?></td>
            <td width="200" align="center" class="mgalleryitem"><div class="smallfont"><? echo $object->attributes['file'];?><br><? echo $object->attributes_vars[width];?>x<? echo $object->attributes_vars[height];?> pixel</div></td>
            
        <?

        
        break;
    case "text":
        ?>
            <td width="15" align="center" class="mgalleryitem">
            <img src="<? echo SKRES_URL."skinz/".$GLOBALS['theme']."/object_icons/";?>small/text.gif" width="15" height="15" border="0">
            </td>
            <td width="200" align="center" class="mgalleryitem"><div class="smallfont"><? echo substr(safeHTML($object->attributes['objtext']),0,200);?></div></td>
            
        <?
        break;
    case "text_image":
        ?>
            <td width="15" align="center" class="mgalleryitem">
            <? IF ($object->attributes_vars[thumbnail]==1):
                echo "<IMAGE class=\"skimage\" SRC=\"".SITE_URL."content/image/"."thumbnails/".$object->attributes['file']."\" align=\"".$object->attributes_vars['align']."\" width=\"25\" height=\"25\" ALT=\"image\" >";
                ELSE: 
                echo "<IMAGE class=\"skimage\" SRC=\"".SITE_URL."content/image/".$object->attributes['file']."\" ALT=\"image\" width=\"50\" height=\"50\">";
                ENDIF;
        //echo $object->attributes['object_id']." ".$object->display();
        ?></td>
            <td width="200" align="center" class="mgalleryitem"><div class="smallfont"><? echo substr(safeHTML($object->attributes['objtext']),0,200);?></div></td>
            
        <?
        break;
    case "gallery":
        $object->attributes2vars();
        ?>
            <td width="15" align="center" class="mgalleryitem">
            <img src="<? echo SKRES_URL."skinz/".$GLOBALS['theme']."/object_icons/";?>small/gallery.gif" width="15" height="15" border="0">
            </td>
            <td width="200" align="center" class="mgalleryitem"><div class="smallfont"><? echo substr(safeHTML($object->attributes_vars['title']),0,50);?><br><? echo substr(safeHTML($object->attributes['objtext']),0,100);?></div></td>
            
        <?
        break;
    default:
    ?>
            <td width="15" align="center" class="mgalleryitem">
            <img src="<? echo SKRES_URL."skinz/".$GLOBALS['theme']."/object_icons/small/".$current_type;?>gif" width="15" height="15" border="0">
            </td>
            <td width="200" align="center" class="mgalleryitem"><div class="smallfont"><? echo $object->attributes['file'];?><br><? echo substr(safeHTML($object->attributes_vars['title']),0,50);?><br><? echo substr(safeHTML($object->attributes['objtext']),0,100);?></div></td>
            
        <?
        break;
}

?>
    
        
    <td width="150" align="center" class="mgalleryitem" height="15"><nobr>
        <a class="smallblack" href="#top" onClick="MM_openBrWindow('<? echo $formtarget;?>?object_id=<? echo $object->attributes['id'];?>&current_type=<? echo $current_type;?>&form_template=display_object.php&sort_nr=<? echo $sort_nr;?>&identifier=<? echo $identifier;?>&parent_node=<? echo $parent_node;?>&form_window=1&window_title=Display Object','ObjectWindow','width=650,height=500,left=100,top=100, scrollbars=yes');return true;">
    ansehen</a>&nbsp;&nbsp;&nbsp;
        <input type="radio" name="attributes_vars[object_id]" value="<? echo $object->attributes['id'];?>" onClick="document.editform.link_content_id.value=<? echo $object->attributes['content_id'];?>">  
        
     </nobr></td>
    
    </tr>
<?
$rownr++;
   $result->MoveNext();
   }?>
<tr>
    <td width="15" align="center" class="noborder" colspan="2">&nbsp;</td>
    <td width="200" align="center" class="noborder">ganzen Bereich anzeigen:<input type="checkbox" name="attributes_vars[show_ca]"  value="1" <? if($edit_object->attributes_vars[show_ca==1])echo"selected";?>></td>
    <td width="150" align="center" class="noborder">Bereich-Nr.:<input type="text" id="link_content_id" name="attributes_vars[content_id]" size="5" value="<? echo $edit_object->attributes_vars['content_id'];?>" style='background-color:#cccccc;'"></td>
</tr>
<tr>
    <td width="15" align="center" class="noborder" colspan="2">&nbsp;</td>
    <td width="200" align="center" class="noborder">Sortiernummer:<input type="text" name="sort_nr" size="5" value="<? echo $edit_object->attributes['sort_nr'];?>"></td>
    <td width="150" align="center" class="noborder"><input type="image" border="0" name="insert" src="<?echo SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/";?>ok_icon.gif" width="18" height="21"> OK  &nbsp;&nbsp;&nbsp;&nbsp;Abbrechen <a href="JAVASCRIPT:void(0);" onClick="parent.window.Mediabox.close();return false"><img src="<?echo SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/";?>cancel_icon.gif" width="18" height="21" border="0" ></a></td>
</tr>
<? IF ($object_id > 0):?>
<tr>
    <td width="15" align="center" class="noborder" colspan="2">&nbsp;</td>
    <td width="200" align="center" class="noborder">&nbsp;</td>
    <td width="150" align="center" class="noborder"><b><font color="#CC0000">
              l&ouml;schen?</b>&nbsp;&nbsp;
              <a href="#" onClick="frage();return false"><img src="<?echo SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/";?>del_icon.gif" width="18" height="21" border="0"></a></td>
</tr>


   <? ENDIF;?> 
</form> </table>       
</div>
<script type="text/javascript" language="javascript">
<!--
   obj=MM_findObj('formsl');
   obj.style.width='600px';
   -->
</script>


