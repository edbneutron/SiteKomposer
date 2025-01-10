<? 
$docrelpath="../../../../";




$title1 = "Seite verwalten";
$title2 = "Seite verwalten";
$titleimg = "news.gif";
require_once("../admin_top.inc.php");

$DEBUG_OUTPUT.=print_array($HTTP_POST_VARS);
$current_site_id=$current_site->attributes[site_id];
$group = new skgroup();
$Menu = new skmenu(1);
$Menu->getfromdb();
  
if (isset($mid)){


    if (isset($action)){
        

    switch($action) {
        
        case "add_agroup":
            $group->add_permission($groups,'nav_tree',$mid,$type);
            $Messages.="Group(s) added.";
            break;
        case "remove_agroup":
            $group->remove_permission($ngroups,'nav_tree',$mid,$type);
            $Messages.="Group(s) removed.";
            break;
    }
    } // isset($action))
    
$sql_select = "select * from sk_nav_tree WHERE id = $mid ";
if ($GLOBALS["debug"]>0) $Messages .= $sql_select;
$recordSet = $skdb->Execute($sql_select);
IF($recordSet === false)$Messages .= "Menu-entry DB-failed! ".$skdb->Errormsg()."<br>";

} //isset $mid

if (!isset($sess_site_id)) $sess_site_id=$recordSet->fields[site_id];



if($framed==1): ?>
<html>
<head>
<title>Navigation Frame</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="<?echo SKRES_URL.'skinz/'.$theme;?>/css/admin.css" rel="stylesheet" type="text/css">
<link href="<?echo SKRES_URL.'skinz/'.$theme;?>/css/common.css" rel="stylesheet" type="text/css">

</head>
<script language="JavaScript" src="<? echo SKRES_URL;?>js/edit_scripts.js"></script>
<script language="JavaScript" src="<? echo SKRES_URL;?>js/standard.js"></script>
<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0">
<? endif; //framed 
 if($mid<>0 OR $action=="add"):

if($action=="add"){

$recordSet->fields[title]="titel";
$recordSet->fields[linkname]="titel";
$recordSet->fields[sort_nr]=1;
$recordSet->fields[p]=$p;


}
?>
<form name="editform" method="post" target="_parent" action="sk_nav_tree_list.php?mid=<? echo $p;?>&alvis=<?=$alvis?>&tmv_value=<?=$tmv_value?>" enctype="multipart/form-data">
<table bgcolor="#FFFFFF"><tr>
      <td valign="top" align="left" colspan="2" width="550" bgcolor="#EEEEEE">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr height="25">
            <td width="13" valign="top" align="left" bgcolor="#eeeeee" height="25">&nbsp;</td>
            <td width="400" valign="middle" align="left" bgcolor="#eeeeee" height="25">
            <? if($action=="add"){
                echo"neue Seite anlegen<br>";
                if(isset($p_name)) echo "unter: ".$p_name;
                }else{?>
            <strong><? echo $recordSet->fields[title]; } ?></strong>
            <? if($framed==0):?><font size="2"><a href="sk_nav_tree_list.php?current_site=<? echo $sess_site_id;?>&alvis=<?=$alvis?>&tmv_value=<?=$tmv_value?>">zur&uuml;ck zur Liste</a></font><? endif;?></td>
            <td valign="middle" align="right" bgcolor="#eeeeee" width="161" height="25"><font size="2">
           &nbsp;</font></td>
          </tr></table>
    </td>
    
</tr>

      <input type="hidden" 
             name="saField123[p]"  
             value="<? echo $recordSet->fields[p] ?>">
             
         <input type="hidden" 
             name="p123"  
             value="<? echo $p ?>">
         
      <input type="hidden" 
             name="saField[id]"  
             value="<? echo $recordSet->fields[id] ?>">
<tr>
   <td>
      Seiten-ID</td>
   <td>
      <? echo $recordSet->fields[id];?>
   </td>
</tr>
<tr>
   <td>
      Ebene - unter:</td>
   <td>
      <select name="saField[p]">
      <option value="self" <?if($recordSet->fields[p]==$recordSet->fields[id]) echo "selected"; ?>>Hauptebene
      <option value="">----------
        <? 
        if(is_array($Menu->tree)) {
reset($Menu->tree);
while (list($index, $submenu) = each($Menu->tree) ) {    
    ?>
    <option value=<?echo '"'.$submenu[3].'"'; if($submenu[3]==$recordSet->fields[p] && $submenu[3]!=$recordSet->fields[id]) echo ' selected'; ?>><? for($i=1;$i<$submenu[0];$i++) echo "&nbsp;-"; echo $submenu[1]?></option>
    <?}
    }?>
    </select>
   </td>
</tr>
<tr>
   <td>
      titel</td>
   <td>
      <input type="text" class="text" size="20" 
             maxlength="255" 
             name="saField[title]"  
             value="<? echo $recordSet->fields[title] ?>">
   </td>
</tr>

<tr>
   <td>
      sortier-nummer  </td>
   <td>
      <input type="text" class="text" size="10" 
             maxlength="10" 
             name="saField[sort_nr]"  
             value="<? echo $recordSet->fields[sort_nr] ?>">
   </td>
</tr>

      <input type="hidden" 
             name="saField[group_id]"  
             value="0">
      <input type="hidden" 
             name="saField[site_id]"  
             value="<? // echo $recordSet->fields[site_id] ?><? echo $sess_site_id; ?>">

<tr>
   <td>
      template   </td>
   <td>
       <?   echo skfileselect(ROOT_PATH.$current_site->attributes["dirname"].'res/tpl/page/',$recordSet->fields[template],"saField[template]","",0,0,"index.html","index.html");?>
   </td>
</tr>

      <input type="hidden" 
             size="20" 
             maxlength="50" 
             name="saField[filename]"  
             value="index.php"
             >

<tr>
   <td>
      link-name   </td>
   <td>
      <input type="text" class="text" size="20" 
             maxlength="255" 
             name="saField[linkname]"  
             value="<? echo $recordSet->fields[linkname] ?>"
             >
   </td>
</tr>
<tr>
   <td>
      sichtbar   </td>
   <td>
      <input type="checkbox" 
             name="saField[mview]"  
             value="1" <? if($recordSet->fields[mview]=='1') echo"checked"; ?>
             >
             
   </td>
</tr>
<tr>
   <td>
      Kein Link im Menü  </td>
   <td>
      <input type="checkbox" 
             name="saField[nolink]"  
             value="1" <? if($recordSet->fields[nolink]=='1') echo"checked"; ?>
             >
             
   </td>
</tr>
<? if(isset($recordSet->fields[id])){ ?>
<tr>
   <td>
      editieren  </td>
   <td>
     <a href="<? echo $current_site->attributes["site_url"];?>index.php?mid=<? echo $recordSet->fields[id];?>&edit=1" target="_top">diese Seite editieren</a>
             
   </td>
</tr>
<? }?>
<tr><td colspan="2">
  <table><tr>
    <td><input type="submit" name="ok" value="OK"></td>
    <td><? IF ($recordSet->fields[id] <> 0):?><input type="button" name="delete" value="löschen" onClick="frage_s();"><? ELSE:?>&nbsp;<? ENDIF;?></td>
  </tr></table>
</td></tr>

<input type="hidden" name="action" value="do">
</form>
</table>
<? 
if($action!="add"){

 ?>
<a href="#" onclick="ToggleDisplay(permbutton, 'layerMore','<?echo SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/";?>iconminus.gif','<?echo SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/";?>iconplus.gif')" class='small'><img src="<?echo SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/";?>iconplus.gif" id="permbutton" border="0" align='left'><b>Berechtigungen</b></b></a>
<div id="layerMore" style="display: none;">

<table>
<form name="natrgroups" action="sk_nav_tree_edit.php" method="post" enctype="application/x-www-form-urlencoded">
            <tr bgcolor="#BBDBB9"> 
            <td valign="top"><div class="smallfont">Gruppen<br>
                             Berechtigung<br>Lesen</div></td>
            <td> 
            <table>
            <tr>
            <td><div class="smallfont">alle Gruppen</div></td>
            <td>&nbsp;</td>
            <td><div class="smallfont">Zugriff für:</div></td>
            </tr>
            
            <tr><td>
            <select name="groups[]" multiple size="5" class="small">
                <? echo $group->getgroups(0,2);?>
              </select> 
              </td>

              <td><input name="add" type="button" value="-&gt;" OnClick="document.natrgroups.action.value='add_agroup';document.natrgroups.submit();"><br>
                    <input name="remove" type="button" value="&lt;-" OnClick="document.natrgroups.action.value='remove_agroup';document.natrgroups.submit();">
                  <input type="hidden" name="mid" value="<? echo $mid?>">
                  <input type="hidden" name="alvis" value="<? echo $alvis?>">
                    <input type="hidden" name="framed" value="<? echo $framed?>">
                    <input type="hidden" name="tmv_value" value="<? echo $tmv_value?>">
                  <input name="type" type="hidden" value="R">
                  <input name="action" type="hidden" value="do"><br>
              </td>
              <td>
              <select name="ngroups[]" multiple size="5" class="small">
                <? echo $group->getgroups(0,2,0,"nav_tree",$mid,"R");?>
              </select> 
              </td></tr>
             </table>
             </td>
          </tr></form>
          
<form name="natgroups" action="sk_nav_tree_edit.php" method="post" enctype="application/x-www-form-urlencoded">
            <tr bgcolor="#DBBBB9"> 
            <td valign="top"><div class="smallfont">Gruppen<br>
                             Berechtigung<br>Editieren</div></td>
            <td> 
            <table>
            <tr>
            <td><div class="smallfont">alle Gruppen</div></td>
            <td>&nbsp;</td>
            <td><div class="smallfont">Zugriff für:</div></td>
            </tr>
            
            <tr><td>
            <select name="groups[]" multiple size="5" class="small">
                <? echo $group->getgroups(0,2);?>
              </select> 
              </td>

              <td><input name="add" type="button" value="-&gt;" OnClick="document.natgroups.action.value='add_agroup';document.natgroups.submit();"><br>
                    <input name="remove" type="button" value="&lt;-" OnClick="document.natgroups.action.value='remove_agroup';document.natgroups.submit();">
                  <input type="hidden" name="mid" value="<? echo $mid?>">
                  <input type="hidden" name="alvis" value="<? echo $alvis?>">
                    <input type="hidden" name="framed" value="<? echo $framed?>">
                    <input type="hidden" name="tmv_value" value="<? echo $tmv_value?>">
                  <input name="type" type="hidden" value="A">
                  <input name="action" type="hidden" value="do"><br>
              </td>
              <td>
              <select name="ngroups[]" multiple size="5" class="small">
                <? echo $group->getgroups(0,2,0,"nav_tree",$mid,"A");?>
              </select> 
              </td></tr>
             </table>
             </td>
          </tr></form>

</table>
</div>
<?} //action !="add"

 endif; //isset($mid) OR $action="add" 

//include(MODULE_PATH."sk/admin/admin_foot.inc.php"); 
?>

