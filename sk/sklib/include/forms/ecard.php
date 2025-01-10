<? $window_title="preview e-card";
$docrelpath="../../../../";

if(isset($image_id)){
$image = new skobject();
$image->attributes['object_id']=$image_id;
$image->get();
}
?><title>eCard</title>
<body link="#A7B45C" vlink="#A7B45C" alink="#A7B45C" leftmargin="0" topmargin="0">
<table width=477 height=477 border=0 cellpadding=0 cellspacing=0  style="BORDER-COLLAPSE: collapse">
  <tr align="center" valign="top"> 
    <td height=34 colspan=3> <b><font face=Arial size=2> 
     <br> &nbsp;<font size="3" face="Arial">Füllen Sie folgendes 
      Formular aus, um die eCard zu verschicken!&nbsp;</font></b>    </td>
  </tr> 
  <form action="<?echo $PHP_SELF."?form_window=1&form_template=ecard_preview.php";?>" method="post">
    <input type="hidden" name="image_id" value="<?echo $image_id;?>">
    <tr> 
      <td colspan=3 height=7>&nbsp; </td>
    </tr>
    <tr> 
      <td width=224 height=23> 
        <font face=Arial size=2>Name des Empfängers:</font></td>
      <td align=right width=240 height=23> 
        <font face=Arial size=1> 
          <input style="FONT-SIZE: 8pt" maxlength="25" size="36" name="namerec">
        </font></td>
      <td valign=center width=13 height=23> <img src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/markierung.gif" border=0 width="12" height="12"></td>
    </tr>
    <tr> 
      <td valign=top width=224 height=12><font face=Arial size=1> 
          <font size="2">eMail-Adresse des Empfängers</font><b>:</b></font></td>
      <td valign=top align=right width=240 height=12><font face=Arial size=1> 
          <input name="email" type="text" style="FONT-SIZE: 8pt" value="email@empfanger.at" size="36">
          </font></td>
      <td valign=top width=13 height=12> <img src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/markierung.gif" border=0 width="12" height="12"></td>
    </tr>
    <tr> 
      <td colspan=3 height=1>&nbsp; </td>
    </tr>
    <tr> 
      <td width=224 height=25> <font face=Arial size=2>Ihr Name:</font></td>
      <td align=right width=240 height=25><font face=Arial size=1> 
        <input name="namesend" type="text" style="FONT-SIZE: 8pt" value="ihrname@sender.at" size="36">
        </font></td>
      <td valign=center width=13 height=25> <img src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/markierung.gif" border=0 width="12" height="12"></td>
    </tr>
    <tr> 
      <td width=224 height=25> <font face=Arial size=2> 
          Ihre eMail-Adresse:</font></td>
      <td align=right width=240 height=25><font face=Arial size=1>&nbsp; </font><font face=Arial color=#000066 size=1>
        <input type="text" name="emailsend" size="36" style="FONT-SIZE: 8pt">
      </font></td>
      <td valign=center width=13 height=25> <img src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/markierung.gif" border=0 width="12" height="12"></td>
    </tr>
    <tr> 
      <td colspan=3 height=1>&nbsp; </td>
    </tr>
    <tr> 
      <td valign=top width=224 height=90> <font face=Arial 
            size=1><font size="2">Nachricht<strong>: </strong>(maximal 500 Zeichen!)</font><br>
          </font></td>
      <td valign=top align=right width=240 height=90> <font face=Arial size=1> 
          <textarea cols="31" rows="5" name="message" style="FONT-SIZE: 8pt">[ Ihre Nachricht ]</textarea>
          </font></td>
      <td valign=top align=right width=13 height=90> <img src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/markierung.gif" border=0 width="12" height="12"></td>
    </tr>
    <tr> 
      <td colspan=3 height=1>&nbsp; </td>
    </tr>
    <tr> 
      <td valign=top width=224 height=48> <font face=Arial, Helvetica, sans-serif size=2>Design: 
          Design der Schrift bearbeiten</font></td>
      <td align=right width=240 height=48> <font face=Arial size=1> 
          <select name="color" size="1" style="FONT-SIZE: 8pt; COLOR: #000080; FONT-FAMILY: Arial">
            <option value=1 selected>- farbe -</option>
            <option value="#000099">dunkelblau</option>
            <option value="#990033">rotbraun</option>
            <option value="#000000">schwarz</option>
            <option value="#339900">grün</option>
            <option value="#0033ff">blau</option>
            <option value="#993399">violett</option>
            <option value="#5a5a5a">grau</option>
            <option value="#ffff00">gelb</option>
            <option value="#ff0000">rot</option>
            <option value="#339999">blaugrün</option>
          </select>
          <select name="fontsize" size="1" style="FONT-SIZE: 8pt; COLOR: #000066; FONT-FAMILY: Arial">
            <option value="10px" selected>- größe -</option>
            <option value="10px">10</option>
            <option value="12px">12</option>
            <option value="14px">14</option>
            <option value="16px">16</option>
            <option value="18px">18</option>
          </select>
          </font><font face="" size=1> 
          <select name="font" size="1" style="FONT-SIZE: 8pt; COLOR: #000066; FONT-FAMILY: ">
            <option value="Arial,Helvetica,Sans Serif" selected>- schriftart -</option>
            <option value="Arial,Helvetica,Sans Serif">Arial</option>
            <option value="'Times New Roman',Times,serif">Times New Roman</option>
            <option value='Courier New',Courier,monospace>Courier</option>
          </select>
          </font></td>
      <td align=right width=13 height=48></td>
    </tr>
    <tr> 
      <td colspan=3 height=1>&nbsp; </td>
    </tr>
    <tr> 
      <td width=224 height=49> <font face=Arial 
            size=2>Layout:</font></td>
      <td align=right width=240 height=49> <font face=Arial size=1> 
          <input type="radio" name="layout" value="1">
          <img src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/querformat_bildrechts.gif" 
            border=0 width="39" height="29">&nbsp;&nbsp;&nbsp;&nbsp; 
          <input type="radio" name="layout" value="2">
          <img src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/querformat_bildlinks.gif" 
            border=0 width="39" height="29">&nbsp;&nbsp;&nbsp;&nbsp; 
          <input type="radio" name="layout" value="3" checked>
          <img height=48 src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/hochformat.gif" width=39 
            border=0></font></td>
      <td align=right width=13 height=49></td>
    </tr>
    <tr> 
      <td colspan=3 height=1> <p><br>
      </td>
    </tr>
    <tr> 
      <td width=224 height=1> <font face=Arial size=1> 
          <input type="submit" name="vorschau" value="vorschau" style="COLOR: #000066; FONT-FAMILY: Tahoma">
          </font></td>
      <td align=right width=240 height=1> <font face=Arial size=1> 
          <input type="reset" name="delete" value="löschen" style="COLOR: #000066; FONT-FAMILY: Tahoma">
          </font></td>
      <td align=right width=13 height=1></td>
    </tr>
    <tr> 
      <td valign=center colspan=3 height=21> <font face=Arial size=1>Alle 
          Felder die mit <img src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/markierung.gif" border=0 width="12" height="12"> 
          markiert sind müssen ausgefüllt werden!</font> </td>
    </tr>
  </form>
</table>

