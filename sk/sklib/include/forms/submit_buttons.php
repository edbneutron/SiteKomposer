<table width="250" border="0" bgcolor="#FFFFFF" cellpadding="2" cellspacing="2" align="right">
          <tr>
            <td width="60" align="center" bgcolor="#FFFFFF">
              <input type="image" border="0" name="insert" src="<? echo SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/";?>ok_icon25.gif" width="25" height="25"  onClick="_close()"  align="absmiddle" >
              <br><font face="Verdana, Arial, Helvetica, sans-serif" color="#00CC33"><b>OK
              </b></td>
            <td align="center" width="60" bgcolor="#FFFFFF">
            <a href="#" onClick="parent.window.Mediabox.close();"><img src="<? echo SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/";?>cancel_icon25.gif" width="25" height="25" border="0" align="absmiddle" ></a>
            <br><font face="Verdana, Arial, Helvetica, sans-serif" color="#666666"><b>Abbrechen</b></td>
            <?IF ($object_id > 0):?>
          <td width="100" align="center" bgcolor="#FFFFFF">
          <font face="Verdana, Arial, Helvetica, sans-serif">
              <a href="#" onClick="frage();return false"><img src="<?echo SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/";?>del_icon25.gif" width="25" height="25" border="0" align="absmiddle" ></a>
              <br><b><font color="#CC0000">l&ouml;schen?</font></b></font>
          </td> <?ENDIF;?>
          </tr>
</table>