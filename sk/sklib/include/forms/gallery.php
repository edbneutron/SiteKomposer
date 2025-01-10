<?

  if(!$edit_object->attributes_vars['ca_nr']>=10000) {
  $sql_select="SELECT  MAX(content_area) AS maxcanr FROM  `sk_content` WHERE mid=".$parent_node;
  $canr_result = $skdb->Execute($sql_select);
  $maxcanr = $canr_result->fields["maxcanr"];

  if($maxcanr >= 10000) $ca_nr=$maxcanr+1;
  else $ca_nr=10000;
  DEBUG_out(3,"debug3","max_ca_nr: ".$maxcanr."<br>");
  DEBUG_out(3,"debug3","new ca_nr: ".$ca_nr."<br>");
  } else {
     $ca_nr=$edit_object->attributes_vars['ca_nr'];
  }
?>

<br>
<table width="480" border="0" vspace="0" hspace="0" cellpadding="2" cellspacing="0" align="center">
  <tr>
    <td>
      <form action="<? echo $formtarget; ?>" method="POST" target="skpage" enctype="multipart/form-data" name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="gallery">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node; ?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id']; ?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>">
        <input type="Hidden" name="old_file_name" value="<? echo $edit_object->attributes['file'];?>">
        <input type="Hidden" name="attributes_vars[ca_nr]" value="<? echo $ca_nr;?>">
        <table width="100%" border="0"  cellpadding="2" cellspacing="2">
         <tr>
            <td width="35%" colspan="2" align="center"><b>Gallery-Objekt</b><br>&nbsp;</td>

          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Titel</b></td>
            <td align="left" width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[title]" size="35" value="<? echo $edit_object->attributes_vars['title'];?>">
            </td>
          </tr>
          <tr> 
            <td width="35%"><b>Beschreibung</b></td>
            <td width="65%">
              <textarea name="objtext" cols="40" rows="5"><? echo htmlspecialchars($edit_object->attributes['objtext']);?></textarea>
              </td>
          </tr>
          <tr>
            <td width="35%"><b>Art</b></td>
            <td width="65%">
              <select name="attributes_vars[type]"><?
                IF (isset($edit_object->attributes_vars['type'])):?><option value="<? echo $edit_object->attributes_vars['type'];?>"><? echo $edit_object->attributes_vars['type'];?></option><? eNDIF;?>
                <option value="">default</option>
                <option value="SmoothGallery">SmoothGallery (JS/AJAX)</option>
                <option value="SimpleViewer">SimpleViewer (Flash)</option>
                <option value="FastGallery">FastGallery (Flash)</option>
		<option value="jpgrotator">JPG-Rotator (Flash)</option>
                <option value="mp3player">Mp3Player (Flash/MP3)</option>
                <option value="mp3playersmall">Mp3Player klein (Flash/MP3)</option>
                <option value="streamingplayer">StreamingPlayer (Flash/MP3)</option>
		<option value="musicplayer">MusicPlayer (Flash/MP3)</option>
              </select>
              </td>
          </tr>
          <? // Settings for Flash Files?>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Autostart (mp3-player)</b></td>
               <? 
              if($edit_object->attributes_vars['autostart']==1) $checked="checked";
              ?>
            <td  width="51%" bgcolor="#FFFFFF"><input type="checkbox" name="attributes_vars[autostart]" size="20" value="1" <? echo $checked;?> >
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Breite (Flash_applet)</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[width]" size="10" value="<? if($edit_object->attributes_vars[width]!=""){ echo $edit_object->attributes_vars[width];} else{ echo "500";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Höhe (Flash_applet)</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[height]" size="10" value="<? if($edit_object->attributes_vars[height]!=""){ echo $edit_object->attributes_vars[height];} else{ echo "400";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Navigation (SimpleViewer)</b></td>
               <td width="65%">
             <select name="attributes_vars[navPosition]"><?
                IF (isset($edit_object->attributes_vars[navPosition])):?><option value="<? echo $edit_object->attributes_vars[navPosition];?>"><? echo $edit_object->attributes_vars[navPosition];?></option><? eNDIF;?>
                <option value="bottom" <?if($edit_object->attributes_vars[navPosition]=="bottom") echo"selected";?>>unten</option>
                <option value="top" <?if($edit_object->attributes_vars[navPosition]=="top") echo"selected";?>>oben</option>
                <option value="left" <?if($edit_object->attributes_vars[navPosition]=="left") echo"selected";?>>links</option>
                <option value="right" <?if($edit_object->attributes_vars[navPosition]=="right") echo"selected";?>>rechts</option>
              </select>
              </td>
            <td  width="51%" bgcolor="#FFFFFF">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Hintergrundfarbe</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[bgcolor]" size="10" value="<? if($edit_object->attributes_vars['bgcolor']!=""){ echo $edit_object->attributes_vars['bgcolor'];} else{ echo "#181818";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Rahmenfarbe</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[framecolor]" size="10" value="<? if($edit_object->attributes_vars['framecolor']!=""){ echo $edit_object->attributes_vars['framecolor'];} else{ echo "#FFFFFF";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Textfarbe</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[textcolor]" size="10" value="<? if($edit_object->attributes_vars['textcolor']!=""){ echo $edit_object->attributes_vars['textcolor'];} else{ echo "#FFFFFF";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Anzahl der Spalten(SimpleViewer)</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[vcolumns]" size="5" value="<? if($edit_object->attributes_vars[vcolumns]!=""){ echo $edit_object->attributes_vars[vcolumns];} else{ echo "5";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Anzahl der Zeilen (SimpleViewer)</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[rows]" size="5" value="<? if($edit_object->attributes_vars[rows]!=""){ echo $edit_object->attributes_vars[rows];} else{ echo "2";}?>">
            </td>
          </tr>
          <? // end settings ?>
          <tr>
            <td width="35%"><b>Template</b></td>
            <td width="65%">
              <? echo skfileselect(SITE_PATH.'res/tpl/gallery/',$edit_object->attributes_vars['template'],"attributes_vars[template]",0,0,'default.html'); ?>
              </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF">
              <b>Ausrichtung</b></td>

            <td  width="51%" bgcolor="#FFFFFF">
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
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Anzahl der Spalten</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[columns]" size="5" value="<? if($edit_object->attributes_vars['columns']!=""){ echo $edit_object->attributes_vars['columns'];} else{ echo "2";}?>">
            </td>
          </tr>

          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>jede Zeile in eigene Tabelle</b></td>
            <td  width="51%" bgcolor="#FFFFFF">
            <? 
	    if($edit_object->attributes_vars['multitable']==1) {$checked="checked";}else{$checked="";}
              ?>
              <input type="checkbox" name="attributes_vars[multitable]" size="20" value="1" <? echo $checked;?> >

            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              Sortierung</td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="sort_nr" size="5" value="<? echo $edit_object->attributes['sort_nr'];?>">
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


