

<br>
<table width="480" border="0" vspace="0" hspace="0" cellpadding="2" cellspacing="0" align="center">
  <tr>
    <td>
      <form action="<?echo $formtarget;?>" method="POST" target="skpage" enctype="multipart/form-data" name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="image">
        <input type="Hidden" name="parent_node" value="<?echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<?echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>">
        <input type="Hidden" name="old_file_name" value="<? echo $edit_object->attributes['file'];?>">
        <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000">

        <table width="100%" border="0"  cellpadding="2" cellspacing="2">
          <tr>
            <td width="35%"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1"><b>Bildtitel</b></font></td>
            <td width="65%"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">
              <input type="text" name="objtext" size="40" value="<?echo htmlspecialchars($edit_object->attributes['objtext']);?>">
              </font></td>
          </tr>
          <tr>
            <td width="35%"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1"><b>Bild-Datei</b>
              </font></td>
            <td width="65%"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">
              <input type="File" name="file" accept="image/jpeg, image/gif" enctype="multipart/form-data"  onFocus="change_filesrc()">
              </font></td>
          </tr>
        </table>
        <br>
        <table width="100%" border="0" cellpadding="2" cellspacing="2" vspace="0" hspace="0">
          <tr>
            <td rowspan="2" width="150" >
              <!--Bild
              <a onMouseOver="change_filesrc()">--><?
              IF (isset($object_id)):?> <img src="<?echo $imagepath1;?><?echo $edit_object->attributes['file'];?>"  border=0 alt="" name="picture"><?
              ELSE: ?><img src="<? echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/foto.jpg"  border=0 alt="" name="picture"><?
              ENDIF;?>
              </a> <br>
              <font face="Verdana, Arial, Helvetica, sans-serif" size="-2"> </font>
            </td>
            <td width="96" bgcolor="#FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">Ausrichtung
              </font></td>
            <td width="140" bgcolor="#FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">
              <select name="attributes_vars[align]"><?
                IF (isset($edit_object->attributes_vars['align'])):?><option value="<?echo $edit_object->attributes_vars['align'];?>"><?echo $edit_object->attributes_vars['align'];?></option><?ENDIF;?>
                <option value="">default</option>
                <option value="left">links</option>
                <option value="right">rechts</option>
                <option value="middle">mitte</option>

              </select>
              </font></td>
          </tr>
          <tr>
            <td width="96" bgcolor="#FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">Rahmenbreite
              </font></td>
            <td width="140" bgcolor="#FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">
              <select name="attributes_vars[border]"><?
                 IF (isset($edit_object->attributes_vars[border])):?><option value="<?echo $edit_object->attributes_vars[border];?>"><?echo $edit_object->attributes_vars[border];?></option><?ENDIF;?>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
              </font></td>
          </tr>
          <!--<tr>
            <td width="96" bgcolor="#FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">Alt-text</font></td>
            <td width="140" bgcolor="#FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif" size="-1">
              <input type="text" name="pos" size="20">
              </font></td>
          </tr>-->
        </table>
        <script>change_filesrc();</script>
        <? include($forms_tpl_path.'submit_buttons.php');?>
        </form>
    </td>
  </tr>
</table>


