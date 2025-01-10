<?

  $sql_select = "select * from  sk_newssections WHERE site_id=".$current_site_id." order by name";
  $result = $skdb->Execute($sql_select);
?>

<br>
<table width="480" border="0" vspace="0" hspace="0" cellpadding="2" cellspacing="0" align="center">
  <tr>
    <td>
      <form action="<? echo $formtarget;?>" method="POST" target="skpage" enctype="multipart/form-data" name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="news">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>">
        <input type="Hidden" name="old_file_name" value="<? echo $edit_object->attributes['file'];?>">

        <table width="100%" border="0"  cellpadding="2" cellspacing="2">
         <tr>
            <td width="35%" colspan="2" align="center"><b>News-Objekt</b><br>&nbsp;</td>

          </tr>
          <tr>
            <td width="35%"><b>News-Template</b></td>
            <td width="65%">
              <? echo skfileselect($news_tpl_path,$edit_object->attributes_vars['template'],"attributes_vars[template]");?>
              </td>
          </tr>
          <tr>
            <td width="35%"><b>News-Section</b>
              </td>
            <td width="65%">
            <select name="attributes_vars[section_id]" size="1">
        <? while (!$result->EOF AND !$result === false) {
            $selected="";
            if ($edit_object->attributes_vars[section_id]==$result->fields['id'] ) $selected="selected";?>
           <OPTION value="<? echo $result->fields['id'].'" '.$selected;?>><? echo $result->fields["name"]?>
           
        <? $result->MoveNext(); } ?>
            <OPTION value="%">alle</OPTION>
            <select>
              </td>
          </tr>
          <tr>
            <td width="35%"><b>Detail-Link</b></td>
            <td width="65%">
              <input type="text" name="attributes_vars[detail_link]" size="30" value="<? echo $edit_object->attributes_vars[detail_link];?>">
              </td>
          </tr>
       <tr>
            <td width="35%" bgcolor="#FFFFFF" align="center"> <div align="left"><strong>Sortierung</strong></div></td>
            <td align="left" width="65%" bgcolor="#FFFFFF"><input type="text" name="sort_nr" size="5" value="<? echo $edit_object->attributes['sort_nr'];?>">
            </td>
          </tr>
        </table>
        <br>

        <br>
        <? include($forms_tpl_path.'submit_buttons.php');?>
        </form>
    </td>
  </tr>
</table>


