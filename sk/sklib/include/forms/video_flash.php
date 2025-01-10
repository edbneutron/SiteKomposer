

<br>
<table width="480" border="0" vspace="0" hspace="0" cellpadding="2" cellspacing="0" align="center">
  <tr> 
    <td> 
      <form action="<? echo $formtarget;?>" method="POST" target="skpage" enctype="multipart/form-data" name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="video_flash">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <!-- <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>"> -->
        <input type="Hidden" name="oldfilename" value="<? echo $edit_object->attributes['file'];?>">
        <input type="Hidden" name="income_path" value="<? echo ROOT_PATH.$current_site->attributes['dirname'].'content/income/video/';?>">
        <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="52428800">

        <table width="100%" border="0"  cellpadding="2" cellspacing="2">
         <tr>
            <td width="35%" colspan="2"><b>Objekt-Embed Datei (Video, Flash)</b></td>
          </tr>
         <tr>
            <td width="35%"><b>Titel</b></td>
            <td width="65%">
              <input type="text" name="attributes_vars[title]" size="30" value="<? echo $edit_object->attributes_vars['title'];?>">
              </td>
          </tr>
        <tr> 
            <td width="35%"><b>Objekt-Datei</b><br>
            <? echo $edit_object->attributes['file'];?>
              </td>
            <td width="65%">
              <input type="File" name="file" accept="audio/mp3" enctype="multipart/form-data">
              <br>maximale Gr&ouml;sse <?=$IntMaxUploadSize ?>; Format MPG,AVI,SWF,QT,MOV
	      <br>
              <? echo skfileselect(ROOT_PATH.$current_site->attributes['dirname'].'content/income/video/',"","income_file",".mov,.avi,.wmv,.swf,.rm,.mpg,.mp4",0,1);
              ?></td>
          </tr>
          <tr> 
            <td width="96" bgcolor="#FFFFFF">Class-ID
              </td>
            <td width="140" bgcolor="#FFFFFF">
              <select name="attributes_vars[clsid]">
              <?/*IF (isset($edit_object->attributes_vars[cls_id])):?><option value="<? echo $edit_object->attributes_vars[cls_id];?>"><? echo $edit_object->attributes_vars[cls_id];?></option><? ENDIF; */?>
                <option value="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA" <?if($edit_object->attributes_vars[clsid]=="clsid:CFCDAA03-8BE4-11cf-B84B-0020AFBBCCFA")echo"selected"?>>Realplayer</option>
                <option value="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" <?if($edit_object->attributes_vars[clsid]=="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000")echo"selected"?> <?if($edit_object->attributes_vars[clsid]=="")echo"selected"?>>Shockwave for Flash</option>
                <option value="clsid:166B1BCA-3F9C-11CF-8075-444553540000" <?if($edit_object->attributes_vars[clsid]=="clsid:166B1BCA-3F9C-11CF-8075-444553540000")echo"selected"?>>Shockwave for Director</option>
                <option value="clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95" <?if($edit_object->attributes_vars[clsid]=="clsid:22d6f312-b0f6-11d0-94ab-0080c74c7e95")echo"selected"?>>Windows Media-Player</option>
                <option value="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" <?if($edit_object->attributes_vars[clsid]=="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B")echo"selected"?>>Quicktime</option>
                <option value="" >nicht gesetzt</option>
              </select>
              </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Breite</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[width]" size="5" value="<? if($edit_object->attributes_vars[width]!=""){ echo $edit_object->attributes_vars[width];} else{ echo "200";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>H&ouml;he</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[height]" size="5" value="<? if($edit_object->attributes_vars[height]!=""){ echo $edit_object->attributes_vars[height];} else{ echo "200";}?>">
            </td>
          </tr>
          <tr>
          <td width="96" bgcolor="#FFFFFF">Ausrichtung
              </td>
            <td width="140" bgcolor="#FFFFFF">
              <select name="attributes_vars[align]"><?
                IF (isset($edit_object->attributes_vars['align'])):?><option value="<? echo $edit_object->attributes_vars['align'];?>"><? echo $edit_object->attributes_vars['align'];?></option><? eNDIF;?>
                <option value="">default</option>
                <option value="left">links</option>
                <option value="right">rechts</option>
                <option value="center">mitte</option>

              </select>
              </td>
          </tr>
          <tr>
            <td  width="96" bgcolor="#FFFFFF">Autostart </td>
            <td width="140" bgcolor="#FFFFFF">
              <?
              $checked=""; 
              if(!isset($edit_object->attributes_vars['autostart'])) $checked1="checked";
              elseif($edit_object->attributes_vars['autostart']=="true") $checked="checked";
              ?>
              <input type="radio" name="attributes_vars[autostart]" size="20" value="true" <?=$checked?>>ja
	      <input type="radio" name="attributes_vars[autostart]" size="20" value="false" <?=$checked1?>>nein
              </td>
          </tr>
	  <tr>
            <td  width="96" bgcolor="#FFFFFF">Transparent </td>
            <td width="140" bgcolor="#FFFFFF">
              <?
              $checked=""; 
              
              if($edit_object->attributes_vars['transparent']=="true") $checked="checked";
              ?>
              <input type="checkbox" name="attributes_vars[transparent]" size="20" value="true" <?=$checked?>>
              </td>
          </tr>
          <tr>
            <td  width="96" bgcolor="#FFFFFF">Endloßschleife </td>
            <td width="140" bgcolor="#FFFFFF">
              <? 
              $checked="";
              if(!isset($object_id)) $checked="checked";
              elseif($edit_object->attributes_vars[loop]=="true") $checked="checked";
              ?>
              <input type="checkbox" name="attributes_vars[loop]" size="20" value="true" <?=$checked?>>
              </td>
          </tr>
          <tr> 
            <td width="96" bgcolor="#FFFFFF">Controls
              </td>
            <td width="140" bgcolor="#FFFFFF">
              <select name="attributes_vars[controls]">
                <option value="all" <?if($edit_object->attributes_vars[controls]=="all")echo"selected"?>>Alle</option>
                <option value="imagewindow" <?if($edit_object->attributes_vars[controls]=="imagewindow")echo"selected"?>>nur Anzeige (Realplayer)</option>
                <option value="controlpanel" <?if($edit_object->attributes_vars[controls]=="controlpanel")echo"selected"?>>Steuerung (Realplayer)</option>
                <option value="false" <?if($edit_object->attributes_vars[controls]=="false")echo"selected"?>>keine Kontrollen</option>
                <option value="" <?if($edit_object->attributes_vars[clsid]=="")echo"selected"?>>nicht gesetzt</option>
              </select>
              </td>
          </tr>
          <tr> 
            <td width="35%"><b>Beschreibung</b></td>
            <td width="65%">
              <textarea name="objtext" cols="40" rows="5"><? echo htmlspecialchars($edit_object->attributes['objtext']);?></textarea>
              </td>
          </tr>
          <tr>
            <td width="96" bgcolor="#FFFFFF" align="left"> Link&nbsp;(inkl.&nbsp;http://)</td>
            <td align="left" width="140" bgcolor="#FFFFFF">
              <input type="text" name="attributes_vars[link]" size="25" value="<? echo $edit_object->attributes_vars[link];?>">
            </td>
          </tr>
          <tr> 
            <td width="96" bgcolor="#FFFFFF">Linkziel
              </td>
            <td width="140" bgcolor="#FFFFFF">
              <select name="attributes_vars[target]">
               <option value="">----</option>
              <? IF ($edit_object->attributes_vars[target]>""):?><option value="<? echo $edit_object->attributes_vars[target];?>" selected><? echo $edit_object->attributes_vars[target];?></option><? eNDIF;?>
                <option value="_top">_top (gleiches Fenster)</option>
                <option value="_blank">_blank (neues Fenster)</option>
              </select>
              </td>
          </tr>
          <tr>
            <td width="35%" bgcolor="#FFFFFF" align="center"> <div align="left"><strong>Sortierung</strong></div></td>
            <td align="center" width="65%" bgcolor="#FFFFFF"><input type="text" name="sort_nr" size="5" value="<? echo $edit_object->attributes['sort_nr'];?>">
            </td>
          </tr>
        </table>
        <br>
        <script>//change_filesrc();</script>
        <br>
        <? include($forms_tpl_path.'submit_buttons.php');?>
        </form>
    </td>
  </tr>
</table>

