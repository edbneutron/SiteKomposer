<? session_start();
require_once("../admin_top.inc.php");
if(!isset($_SESSION["sess_hash"])) header("Location:../index.php");


$table="sk_groups";
$title1 = "Gruppen administrieren";
$title2 = "Gruppen administrieren";
$titleimg = "icon.gif";



if (isset($saField[groupid])):
    if (isset($action)){
        
        
            
        switch($action) {
        
        case "add_user":
            foreach($users as $group_user){
            $insertSQL = "INSERT INTO sk_groups_users_link (groupid, uid) VALUES ($saField[groupid], $group_user)";
            $result = $skdbadm->Execute($insertSQL);
            if ($GLOBALS["debug"]>0) $Messages .= $insertSQL;
            }
            $Messages.="User(s) added.";
            break;
        case "remove_user":
            foreach($gusers as $group_user){
            $deleteSQL = "DELETE FROM sk_groups_users_link WHERE groupid=$saField[groupid] AND uid=$group_user";
            $result = $skdbadm->Execute($deleteSQL);
            if ($GLOBALS["debug"]>0) $Messages .= $deleteSQL;
            }
            $Messages.="User(s) removed.";
            break;
        }
    }
    
    $sql_select = "select * from $table  where groupid = '$saField[groupid]'";
    if ($GLOBALS["debug"]>0) $Messages .= $sql_select;
    $recordSet = $skdb->Execute($sql_select);
    if ($recordSet == false) $Messages .= "groups DB-select failed! ".$skdb->Errormsg()."<br>";
    $saField=$recordSet->fields;
    // Modules select
    $sql_select = "select * from sk_modules ORDER by type,sort";
    if ($GLOBALS["debug"]>0) $Messages .= $sql_select;
    $moduleSet = $skdb->Execute($sql_select);
    if ($moduleSet == false) $Messages .= "sk_modules DB-select failed! ".$skdb->Errormsg()."<br>";
    // Users select
    $sql_select = "select * from sk_users ORDER by uname";
    if ($GLOBALS["debug"]>0) $Messages .= $sql_select;
    $usersSet = $skdb->Execute($sql_select);
    if ($usersSet == false) $Messages .= "sk_users DB-select failed! ".$skdb->Errormsg()."<br>";
    // Group-Users select
    $sql_select = "select * from sk_users INNER JOIN sk_groups_users_link ON sk_users.uid=sk_groups_users_link.uid WHERE sk_groups_users_link.groupid=$saField[groupid]";
    if ($GLOBALS["debug"]>0) $Messages .= $sql_select;
    $gusersSet = $skdb->Execute($sql_select);
    if ($gusersSet == false) $Messages .= "sk_groups_users DB-select failed! ".$skdb->Errormsg()."<br>";
    // Group-Modules select
    $sql_select = "select * from sk_groups_modules_link WHERE sk_groups_modules_link.groupid=$saField[groupid]";
    if ($GLOBALS["debug"]>0) $Messages .= $sql_select;
    $gmoduleSet = $skdb->Execute($sql_select);
    if ($gmoduleSet == false) $Messages .= "sk_groups_users DB-select failed! ".$skdb->Errormsg()."<br>";
    
    
    
endif;

?>
<!-- END top of the form.------------------------- -->

<!-- START html table code ----------------------------------------- -->

<form name="editform" method="post" action="sk_groups_list.php" enctype="multipart/form-data">
  <table width="600" border="0" cellpadding="3" cellspacing="3" bgcolor="#EEEEEE">
    <tr>
      <td valign="top" align="left"  width="550" bgcolor="#FFFFFF">
        <table border="0" cellpadding="2" cellspacing="0" width="100%">
          <input type="hidden" 
             name="saField[groupid]"  
             value="<?echo $saField[groupid]?>">
          <tr> 
            <td valign="top" align="left" colspan="2" bgcolor="#EEEEEE"> <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr height="25"> 
                  <td width="13" valign="top" align="left" bgcolor="#eeeeee" height="25">&nbsp;</td>
                  <td width="400" valign="middle" align="left" bgcolor="#eeeeee" height="25"><font size="2"><a href="sk_groups_list.php?alvis=<?=$alvis?>">zur&uuml;ck 
                    zur Liste</a></font></td>
                  <td valign="middle" align="right" bgcolor="#eeeeee" width="161" height="25"><font size="2"> 
                    &nbsp;</font></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td width="114"> Name </td>
            <td width="466"> <input type="text" class="text" size="20" 
             maxlength="50" 
             name="saField[name]"  
             value="<?php echo $saField[name] ?>"
             > </td>
          </tr>
          <tr> 
            <td> Beschreibung</td>
            <td> <textarea name="saField[description]"  
                cols=40 
                rows=5 
                wrap="virtual"
                ><?php echo $saField[description] ?></textarea> </td>
          </tr>
          <tr> 
            <td> type </td>
            <td> 
            <select size="1" name="saField[type]">
            <option value="Anonymous"<? if ($saField[type]=="Anonymous") echo " selected";?>>Anonymous</option>
            <option value="User"<? if ($saField[type]=="User") echo " selected";?>>User</option>
            <option value="Admin"<? if ($saField[type]=="Admin") echo " selected";?>>Admin</option>
            
            </select>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td bgcolor="#DBBBB9">Adminrechte</td>
            <td bgcolor="#DBBBB9"> <DIV class="small">
              <table cellpadding="1"><tr><? 
            $i=0;
            $moduleAdmin = array();
            while (!$gmoduleSet->EOF AND !$gmoduleSet == false) {
                    $moduleAdmin[$i]=$gmoduleSet->fields[mid];
                    $i++;
                    $gmoduleSet->MoveNext(); 
                    }
            $i=0;
            while (!$moduleSet->EOF AND !$moduleSet == false) {
                    
                    echo "<td class=\"thingrid\">";
                    ?>
              <input name="moduleAdmin[]" type="checkbox" value="<? echo $moduleSet->fields[id];?>" <? if (in_array($moduleSet->fields[id],$moduleAdmin)):?>checked<? endif;?>> 
              <? echo $moduleSet->fields[title];?>
              </td><?         $moduleSet->MoveNext(); 
                    $i++;
                    if ($i==4){ echo "</tr><tr>"; $i=0;}
                    } 

                    ?></tr></table></DIV>
            </td>
          </tr>
          <tr> 
            <td valign="top">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>

          <tr> 
            <td colspan="2"> <table>
                <tr> 
                  <td><input type="submit" name="ok" value="OK"></td>
                  <td> 
                    <?IF ($saField[groupid] <> 0):?>
                    <input type="button" name="delete" value="löschen" onClick="frage_s();"> 
                    <?ELSE:?>
                    &nbsp; 
                    <?ENDIF;?>
                  </td>
                </tr>
              </table></td>
          </tr>
          <input type="hidden" name="alvis" value="<? echo $alvis?>">
          <input type="hidden" name="current_page" value="">
          <input type="hidden" name="action" value="do">
          </form>
          <form name="groupusers" action="<?=$PHP_SELF?>" method="post" enctype="application/x-www-form-urlencoded">
            <tr> 
            <td valign="top">Benutzer dieser Gruppe<br></td>
            <td> 
            <table>
            <tr>
            <td>Benutzer</td>
            <td>&nbsp;</td>
            <td>in dieser Gruppe</td>
            </tr>
            
            <tr><td>
            <select name="users[]" multiple size="10" style="">
                <? 
            while (!$usersSet->EOF AND !$usersSet == false) {
                    
                    echo $usersSet->fields[title];
                    ?>
                <OPTION title="<? echo $usersSet->fields[uname];?>" value="<? echo $usersSet->fields[uid];?>"> 
                <? echo $usersSet->fields[uname];?></OPTION>
                <?     $usersSet->MoveNext(); 
                    } 

                    ?>
              </select> 
              </td>

              <td><input name="add" type="button" value="-&gt;" OnClick="document.groupusers.action.value='add_user';document.groupusers.submit();"><br>
                    <input name="remove" type="button" value="&lt;-" OnClick="document.groupusers.action.value='remove_user';document.groupusers.submit();">
                  <input type="hidden" name="saField[groupid]" value="<?echo $saField[groupid]?>">
                  <input type="hidden" name="alvis" value="<? echo $alvis?>">
                    <input type="hidden" name="current_page" value="">
                  <input name="action" type="hidden" value="do"><br>
              </td>
              <td>
              <select name="gusers[]" multiple size="10" style="">
                <? 
            while (!$gusersSet->EOF AND !$gusersSet == false) {
                    
                    echo $gusersSet->fields[title];
                    ?>
                    <OPTION title="<? echo $gusersSet->fields[uname];?>" value="<? echo $gusersSet->fields[uid];?>" > 
                    <? echo $gusersSet->fields[uname];?></OPTION>
                    <? $gusersSet->MoveNext(); 
                    } 

                    ?>
              </select> 
              </td></tr>
             </table>
             </td>
          </tr></form>
        </table>
</td></tr>

</table>
<? include(MODULE_PATH."sk/admin/admin_foot.inc.php");?>