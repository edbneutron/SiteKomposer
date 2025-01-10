<? session_start();

header ("Ex-pires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");  




$table="sk_users";
$title1 = "Benutzer-Administration";
$title2 = "Benutzer-Administration";
$titleimg = "icon.gif";
include("../admin_top.inc.php");

if (isset($saField[uid])):
	$sql_select = "select * from $table where uid = $saField[uid]";
	if ($GLOBALS["debug"]>0) $Messages .= $sql_select;
	$recordSet = $skdb->Execute($sql_select);
	if ($recordSet == false) $Messages .= "DB-select failed! ".$skdb->Errormsg()."<br>";
	$saField=$recordSet->fields;
endif;

?>
<!-- END top of the form.------------------------- -->

<!-- START html table code ----------------------------------------- -->

<form name="editform" method="post" action="sk_users_list.php?alvis=<?=$alvis?>" enctype="multipart/form-data">
  <table bgcolor="#EEEEEE" border="0" cellpadding="3" cellspacing="3">
    <tr>
      <td valign="top" align="left"  width="550" bgcolor="#FFFFFF">
        <table border="0" cellpadding="3" cellspacing="0" width="100%">

  <input type="hidden" 
             name="saField[uid]"  
             value="<?echo $saField[uid]?>">
<tr>
      <td valign="top" align="left" colspan="2" width="550" bgcolor="#EEEEEE">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
          <tr height="25">
            <td width="13" valign="top" align="left" bgcolor="#eeeeee" height="25">&nbsp;</td>
            <td width="400" valign="middle" align="left" bgcolor="#eeeeee" height="25"><font size="2"><a href="sk_users_list.php?alvis=<?=$alvis?>">zur&uuml;ck 
              zur Liste</a></font></td>
            <td valign="middle" align="right" bgcolor="#eeeeee" width="161" height="25"><font size="2">
           &nbsp;</font></td>
          </tr></table>
	</td>
</tr>
<tr>
   <td>&nbsp;
        </td>
   <td>
      <input type="hidden" 
             name="saField[uid]"  
             value="<?php echo $saField[uid] ?>"
             >
   </td>
</tr>
<tr>
   <td>
      Benutzername   </td>
   <td>
      <input type="text" class="text" size="30" 
             maxlength="60" 
             name="saField[name]"  
             value="<?php echo $saField[name] ?>"
             >
   </td>
</tr>
<tr>
   <td>
      Login   </td>
   <td>
      <input type="text" class="text" size="20" 
             maxlength="25" 
             name="saField[uname]"  
             value="<?php echo $saField[uname] ?>"
             >
   </td>
</tr>
<tr>
   <td>
      email   </td>
   <td>
      <input type="text" class="text" size="30" 
             maxlength="60" 
             name="saField[email]"  
             value="<?php echo $saField[email] ?>"
             >
   </td>
</tr>
<tr>
   <td>
      url   </td>
   <td>
      <input type="text" class="text" size="30" 
             maxlength="100" 
             name="saField[url]"  
             value="<?php echo $saField[url] ?>"
             >
   </td>
</tr>
<!-- <tr>
   <td>
      user_viewemail   </td>
   <td>
      <input type="checkbox" 
             name="saField[user_viewemail]"  
             value="<?php echo $saField[user_viewemail] ?>"
             >
   </td>
</tr> -->
<tr>
   <td>
      passwort<br>
	  2 mal eingeben
   </td>
   <td>
      <input type="password" 
             name="saField[pass]"  
             value="<?php echo $saField[pass] ?>"
             ><br>
	  <input type="password" 
             name="pass_check"  
             value="<?php echo $saField[pass] ?>"
             >
   </td>
</tr>
<tr>
   <td>
      informationen   </td>
   <td>
      <textarea name="saField[bio]"  
                cols=40 
                rows=5 
                wrap="virtual"
                ><?php echo $saField[bio] ?></textarea>
   </td>
</tr>
<tr><td colspan="2">
  <table><tr>
    <td><input type="submit" name="ok" value="OK"></td>
    <td><?IF ($saField[uid] <> 0):?><input type="button" name="delete" value="l&ouml;schen" onClick="frage_s();"><?ELSE:?>&nbsp;<?ENDIF;?></td>
  </tr></table>
</td></tr>

</table>
</td></tr>
</table>

<input type="hidden" name="current_page" value="">
<input type="hidden" name="action" value="do">
<input type="hidden" name="alvis" value="<? echo $alvis?>">
</form>
<? include("../admin_foot.inc.php"); ?>