

<br>
<table width="480" border="0" vspace="0" hspace="0" cellpadding="2" cellspacing="0" align="center">
  <tr> 
    <td> 
      <form action="<? echo $formtarget;?>" method="POST" target="skpage" enctype="multipart/form-data" name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="http">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <!-- <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>"> -->
        <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="10072000">

        <table width="100%" border="0"  cellpadding="2" cellspacing="2">
         <tr>
            <td width="35%"><b>Titel</b></td>
            <td width="65%">
              <input type="text" name="attributes_vars[title]" size="30" value="<? echo $edit_object->attributes_vars['title'];?>">
              </td>
          </tr>
        <tr> 
            <td width="35%"><b>Http_Server_Adresse (www.meinserver.de)</b><br>
            
              </td>
            <td width="65%">
              <input type="text" name="attributes_vars[httpaddress]" size="40" value ="<? echo $edit_object->attributes_vars[httpaddress];?>">

              </td>
          </tr>
       <tr> 
            <td width="35%"><b>Http_Pfad (/index.html)</b><br>
            
              </td>
            <td width="65%">
              <input type="text" name="attributes_vars[httppath]" size="40" value ="<? echo $edit_object->attributes_vars[httppath];?>">

              </td>
          </tr>
      <tr> 
            <td width="35%"><b>Ausgabefilter<br>Start-Such-String</b><br>
            
              </td>
            <td width="65%">
              <input type="text" name="attributes_vars[startstring]" size="40" value ="<? echo $edit_object->attributes_vars[startstring];?>">

              </td>
          </tr>
      <tr> 
            <td width="35%"><b>End-Such-String</b><br>
            
              </td>
            <td width="65%">
              <input type="text" name="attributes_vars[endstring]" size="40" value ="<? echo $edit_object->attributes_vars[endstring];?>">

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
        <? include($forms_tpl_path.'submit_buttons.php');?>
        </form>
    </td>
  </tr>
</table>

