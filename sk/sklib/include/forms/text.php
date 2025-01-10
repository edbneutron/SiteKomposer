<?
  require_once(WYSIWYG_EDITOR);
?>

<br>
<table width="550" border="0" vspace="0" hspace="0" cellpadding="0" cellspacing="0" align="center">
  <tr> 
    <td> 
      <form action="<? echo $formtarget;?>" method="POST" target="skpage"  name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="text">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>">
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
          <tr>
            <td width="35%"><!--<b>Text</b>-->&nbsp;</td>
            <td width="65%">
               <textarea name="objtext" id="objtext" cols="100" rows="25" wrap="VIRTUAL" class=sktext><? echo htmlspecialchars($edit_object->attributes['objtext']);?></textarea>
               <script type="text/javascript"> 
               initEditor();
               </script> 
             </td>
          </tr>
        </table>
        <br>
        <table width="100%" border="0" cellpadding="2" cellspacing="2" vspace="0" hspace="0">
          <tr>

            <td  bgcolor="#FFFFFF" align="center">Ausrichtung
              <select name="attributes_vars[align]"><?
                 IF (isset($edit_object->attributes_vars['align'])):?><option value="<? echo $edit_object->attributes_vars['align'];?>"><? echo $edit_object->attributes_vars['align'];?></option><? eNDIF;?>
                <option value="">default</option>
                <option value="left">links</option>
                <option value="center">mitte</option>
                <option value="right">rechts</option>
                <option value="justify">blocksatz</option>

              </select>
              </td>
            <td bgcolor="#FFFFFF"><font face="Verdana, Arial, Helvetica, sans-serif" size="-2">&nbsp;  </td>
            <td bgcolor="#FFFFFF" align="center">Rand
              <select name="attributes_vars[margin]"><?
                IF (isset($edit_object->attributes_vars['margin'])):?><option value="<? echo $edit_object->attributes_vars['margin'];?>"><? echo $edit_object->attributes_vars['margin'];?></option><? eNDIF;?>
                <option value="">0</option>
                <option value="3px">3px</option>
                <option value="5px">5px</option>
                <option value="8px">8px</option>
                <option value="10px">10px</option>
                <option value="15px">15px</option>
                <option value="20px">20px</option>
                <option value="25px">25px</option>
              </select>
              </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" align="center">
              Sortierung&nbsp;<input type="text" name="sort_nr" size="5" value="<? echo $edit_object->attributes['sort_nr'];?>"></td>
              <td bgcolor="#FFFFFF">&nbsp;</td>
            <td align="left" width="51%" bgcolor="#FFFFFF">
            </td>
          </tr>
          </table>
          <br>
        <? include($forms_tpl_path.'submit_buttons.php');?>

      </form>

    </td>
  </tr>
</table>
<script type="text/javascript" language="javascript">
<!--
   obj=MM_findObj('formsl');
   obj.style.width='660px';
   -->
</script>