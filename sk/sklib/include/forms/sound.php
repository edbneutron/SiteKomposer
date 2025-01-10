

<br>
<table width="480" border="0" vspace="0" hspace="0" cellpadding="2" cellspacing="0" align="center">
  <tr> 
    <td> 
      <form action="<? echo $formtarget;?>" method="POST" target="skpage" enctype="multipart/form-data" name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="sound">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <!-- <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>"> -->
	 <input type="Hidden" name="income_path" value="<? echo ROOT_PATH.$current_site->attributes['dirname'].'content/income/sound/';?>">
        <input type="Hidden" name="oldfilename" value="<? echo $edit_object->attributes['file'];?>">
        <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="2097152000">

        <table width="100%" border="0"  cellpadding="2" cellspacing="2">
         <tr>
            <td width="35%"><b>Titel</b></td>
            <td width="65%">
              <input type="text" name="attributes_vars[title]" size="30" value="<? echo $edit_object->attributes_vars['title'];?>">
              </td>
          </tr>
        <tr> 
            <td width="35%"><b>Sound-Datei</b><br>
            <? echo $edit_object->attributes['file'];?>
              </td>
            <td width="65%">
              <input type="File" name="file" accept="audio/mp3" enctype="multipart/form-data">
              <br>maximale Gr&ouml;sse <?=$IntMaxUploadSize ?>; Format MP3
	      <br>
              <? echo skfileselect(ROOT_PATH.$current_site->attributes['dirname'].'content/income/sound/',"","income_file",".mp4,.mp3",0,1);?>
              </td>
          </tr>
          <tr> 
            <td width="35%"><b>Beschreibung</b></td>
            <td width="65%">
              <textarea name="objtext" cols="40" rows="5"><? echo htmlspecialchars($edit_object->attributes['objtext']);?></textarea>
              </td>
          </tr>
          <tr>
            <td width="35%" bgcolor="#FFFFFF" align="center"> <div align="left"><strong>Sortierung</strong></div></td>
            <td align="center" width="65%" bgcolor="#FFFFFF"><input type="text" name="sort_nr" size="5" value="<? echo $edit_object->attributes['sort_nr'];?>">
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

