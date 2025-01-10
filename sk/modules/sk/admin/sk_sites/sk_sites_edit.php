<?
$table="sk_sites";
$title1 = "Site-Verwaltung";
$title2 = "Site-Verwaltung";
$titleimg = "icon.gif";
require_once("../admin_top.inc.php");

if (isset($saField[site_id])){

$group = new skgroup();

	if (isset($action)){
		
		
			
		switch($action) {
		
		case "add_group":
			$group->add_permission($groups,'sites',$saField[site_id],'A');
			$Messages.="Group(s) added.";
			break;
		case "remove_group":
			$group->remove_permission($sgroups,'sites',$saField[site_id],'A');
			$Messages.="Group(s) removed.";
			break;
	}
	}
	$sql_select = "select *,DATE_FORMAT(last_mod, '%d-%m-%Y') as last_mod from $table  where site_id = '$saField[site_id]'";
	if ($GLOBALS["debug"]>0) $Messages .= $sql_select;
	$recordSet = $skdb->Execute($sql_select);
	if ($recordSet == false) $Messages .= $table." DB-select failed! ".$skdb->Errormsg()."<br>";
	$saField=$recordSet->fields;
	
	
	
	
}

?>
<!-- END top of the form.------------------------- -->

<!-- START html table code ----------------------------------------- -->


  <table bgcolor="#EEEEEE" border="0" cellpadding="3" cellspacing="3">
<form name="editform" method="post" action="sk_sites_list.php" enctype="multipart/form-data">  
    <tr>
      <td valign="top" align="left"  width="550" bgcolor="#FFFFFF">
        <table border="0" cellpadding="2" cellspacing="0" width="100%">

  <input type="hidden" 
             name="saField[site_id]"  
             value="<?echo $saField[site_id]?>">
<tr>
      <td valign="top" align="left" colspan="2" width="550" bgcolor="#EEEEEE">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr height="25">
            <td width="13" valign="top" align="left" bgcolor="#eeeeee" height="25">&nbsp;</td>
            <td width="400" valign="middle" align="left" bgcolor="#eeeeee" height="25"><font size="2"><a href="sk_sites_list.php?alvis=<?=$alvis?>">zur&uuml;ck 
              zur Liste</a></font></td>
            <td valign="middle" align="right" bgcolor="#eeeeee" width="161" height="25"><font size="2">
           &nbsp;</font></td>
          </tr></table>
	</td>
</tr>
<tr>
   <td>
      Name   </td>
   <td>
      <input type="text" class="text" size="35" 
             maxlength="150" 
             name="saField[name]"  
             value="<?php echo $saField[name] ?>"
             >
   </td>
</tr>
<tr>
   <td>
      Beschreibung</td>
   <td>
      <textarea name="saField[description]"  
                cols=40 
                rows=5 
                wrap="virtual"
                ><?php echo $saField[description] ?></textarea>
   </td>
</tr>
<tr>
   <td>
      Meta-Info</td>
   <td>
      <textarea name="saField[meta]"  
                cols=60 
                rows=5 
                wrap="virtual" class="textareasmall"
                ><?php echo $saField[meta] ?></textarea>
   </td>
</tr>
<tr>
          <td> Directory (ohne / am Anfang)</td>
   <td>
      <input type="text" class="text" size="35" 
             maxlength="100" 
             name="saField[dirname]"  
             value="<?php echo $saField[dirname] ?>"
             >
   </td>
</tr>
<tr>
   <td>
      Site-URL   </td>
   <td>
      <input type="text" class="text" size="35" 
             maxlength="127" 
             name="saField[site_url]"  
             value="<?php echo $saField[site_url] ?>"
             >
   </td>
</tr>
<tr>
   <td>
      zuletzt ge&auml;ndert   </td>
   <td>
      <?php echo $saField[last_mod] ?>
   </td>
</tr>
<tr>
   <td>
      User   </td>
   <td>
     <select name="saField[user_id]">
	 <option value="0">-----</option>
	 <? $skuser=new skuser;
	    echo $skuser->getusers(0,2,$saField[user_id]);
	 ?>
	 </select>		
   </td>
</tr>

<!-- <tr>
   <td>
      port   </td>
   <td>
      <input type="text" class="text" size="20" 
             maxlength="6" 
             name="saField[port]"  
             value="<?// echo $saField[port] ?>"
             >
   </td>
</tr>-->
<tr><td colspan="2">
  <table><tr>
    <td><input type="submit" name="ok" value="OK"></td>
    <td><?IF ($saField[site_id] <> 0):?><input type="button" name="delete" value="l&ouml;schen" onClick="frage_s();"><?ELSE:?>&nbsp;<?ENDIF;?></td>
  </tr></table>
</td></tr>

<input type="hidden" name="alvis" value="">
<input type="hidden" name="current_page" value="">
<input type="hidden" name="action" value="do">
</form>
<? if (isset($saField[site_id])){ ?>
<form name="sitegroups" action="<?=$PHP_SELF?>" method="post" enctype="application/x-www-form-urlencoded">
		    <tr bgcolor="#DBBBB9"> 
            <td valign="top">Admin<br>Gruppen<br>
							 Berechtigung<br></td>
            <td> 
			<table>
			<tr>
			<td>alle Gruppen</td>
			<td>&nbsp;</td>
			<td>Admin-Zugriff f&uuml;r:</td>
			</tr>
			
			<tr><td>
			<select name="groups[]" multiple size="10" style="">
                <? echo $group->getgroups(0,2);?>
              </select> 
			  </td>

			  <td><input name="add" type="button" value="-&gt;" OnClick="document.sitegroups.action.value='add_group';document.sitegroups.submit();"><br>
			  	  <input name="remove" type="button" value="&lt;-" OnClick="document.sitegroups.action.value='remove_group';document.sitegroups.submit();">
				  <input type="hidden" name="saField[site_id]" value="<?echo $saField[site_id]?>">
				  <input type="hidden" name="alvis" value="<? echo $alvis?>">
		  		  <input type="hidden" name="current_page" value="">
				  <input name="action" type="hidden" value="do"><br>
			  </td>
			  <td>
			  <select name="sgroups[]" multiple size="10" style="">
                <? echo $group->getgroups(0,2,0,"sites",$saField[site_id],"A");?>
              </select> 
			  </td></tr>
			 </table>
			 </td>
          </tr></form>
<? } ?>
</td></tr>
</table>
</table>

<? include(MODULE_PATH."sk/admin/admin_foot.inc.php");?>