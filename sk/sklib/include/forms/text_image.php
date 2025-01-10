<?

  require_once(WYSIWYG_EDITOR);
?>

<br>
<table width="480" border="0" vspace="0" hspace="0" cellpadding="2" cellspacing="0" align="center">
  <tr> 
    <td> 
      <form action="<? echo $formtarget;?>" method="POST" target="skpage"  name="editform" onSubmit="_close()" enctype="multipart/form-data">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="text_image">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <!-- <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>"> -->
        <input type="Hidden" name="oldfilename" value="<? echo $edit_object->attributes['file'];?>">
        <input type="Hidden" name="attributes_vars[width]" value="<? echo $edit_object->attributes_vars[width];?>">
        <input type="Hidden" name="attributes_vars[height]" value="<? echo $edit_object->attributes_vars[height];?>">
        <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="307200">
        
        <table width="100%" border="0" cellpadding="2" cellspacing="2">
          <tr>
            <td width="35%"><b>Text</b>&nbsp;</td>
            <td width="65%">
               <textarea name="objtext" id="objtext" cols="90" rows="20" wrap="VIRTUAL" class=sktext><? echo htmlspecialchars($edit_object->attributes['objtext']);?></textarea>
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
                 IF (isset($edit_object->attributes_vars[textalign])):?><option value="<? echo $edit_object->attributes_vars[textalign];?>"><? echo $edit_object->attributes_vars[textalign];?></option><? eNDIF;?>
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
        </table>
        <table width="100%" border="0"  cellpadding="2" cellspacing="2">
          <tr> 
            <td width="35%"><b>Bildtitel</b></td>
            <td width="65%">
              <input type="text" name="attributes_vars[imgtitle]" size="40" value="<? echo htmlspecialchars($edit_object->attributes_vars[imgtitle]);?>">
              </td>
          </tr>
          <tr> 
            <td width="35%"><b>Bild-Datei</b><br>
            <? echo $edit_object->attributes['file'];?>
              </td>
            <td width="65%">
              <input type="File" name="file" accept="image/jpeg, image/gif, image/png, image/pjpeg, image/jfif" enctype="multipart/form-data"  onChange="change_filesrc('no')">
              <br>maximale Gr&ouml;sse <?=$IntMaxUploadSize ?>;Format .jpg,.png, .gif
              </td>
          </tr>
        </table>
        <br>
        <table width="100%" border="0" cellpadding="2" cellspacing="2" vspace="0" hspace="0">
          <tr> 
            <td rowspan="9" width="150" align="center">
            <font face="Verdana, Arial, Helvetica, sans-serif" size="-2">Vorschau<br>
              <!--Bild
              --><?
              IF (isset($object_id)):?> <img src="<? echo SITE_URL."content/image/".$edit_object->attributes['file'];?>"  border=0 alt="" name="picture" width="150" height="150"  onClick="change_filesrc('no');"><?
              ELSE: ?><img src="<? echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>img/formimg/foto.jpg"  border=0 alt="" name="picture" width="150" height="150" onClick="change_filesrc('no');"><?
              ENDIF;?>

              <br>
              <img src="<?echo SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';?>buttons/reload_icon.gif" border="0" align="middle" alt="reload" name="reload" width="16" height="16" onClick="change_filesrc('no');" />&nbsp;<span class="small">reload</span>
            </td>
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
            <td width="96" bgcolor="#FFFFFF">Rahmenbreite
              </td>
            <td width="140" bgcolor="#FFFFFF">
              <select name="attributes_vars[border]"><?
                 IF (isset($edit_object->attributes_vars[border])):?><option value="<? echo $edit_object->attributes_vars[border];?>"><? echo $edit_object->attributes_vars[border];?></option><? eNDIF;?>
                <option value="0">0</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
              </select>
              </td>
          </tr>
           <tr> 
            <td width="96" bgcolor="#FFFFFF">Aussenabstand
              </td>
            <td width="140" bgcolor="#FFFFFF">
              <select name="attributes_vars[imgspace]"><?
                 IF (isset($edit_object->attributes_vars[imgspace])):?><option value="<? echo $edit_object->attributes_vars[imgspace];?>"><? echo $edit_object->attributes_vars[imgspace];?></option><? eNDIF;?>
                <option value="0">0</option>
        <option value="2">2</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
              </select>
              </td>
          </tr>
          <tr> 
            <td width="96" bgcolor="#FFFFFF">Positionierung (bei Ausrichtung links/rechts)
              </td>
            <td width="140" bgcolor="#FFFFFF">
            umfliessend <input type="radio" name="attributes_vars[noflow]" value="0" <?if($edit_object->attributes_vars[noflow]==0):?>checked <?endif;?>/><br>
            mit Umbruch <input type="radio" name="attributes_vars[noflow]" value="1" <?if($edit_object->attributes_vars[noflow]==1):?>checked <?endif;?> />
              </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" align="left"> Sortierung</td>
            <td align="left" width="51%" bgcolor="#FFFFFF">
              <input type="text" name="sort_nr" size="5" value="<? echo $edit_object->attributes['sort_nr'];?>">
            </td>
          </tr>
          <tr >
            <td  width="96" bgcolor="#FFFFFF">Bildgr&ouml;sse &auml;ndern </td>
            <td width="140" bgcolor="#FFFFFF">

              <input type="checkbox" name="attributes_vars[resize]" id="resizecheck" size="20" value="1" >
              <select name="imgsize" id="imgsize" onchange="document.editform.resizecheck.checked=true;">
              
                <?  $contentpath=ROOT_PATH.$current_site->attributes['dirname']."content/";
                    $selected="selected";
                    $imgfile = $contentpath."image/".$edit_object->attributes['file'];

                    if($edit_object->attributes['file']>'' ){
                    $imgsize = GetImagesize($imgfile);
                    ($imgsize[0]>$imgsize[1])? $imgsel = $imgsize[0] :    $imgsel = $imgsize[1];
                    echo '<option value="'.$imgsize[0].'" selected>'.$imgsize[0].'x'.$imgsize[1].'</option>';
                    $selected="";
                    }
                ?>
                <option value="200">200x200</option>
                <option value="250">250x250</option>
                <option value="300">300x300</option>
                <option value="350">350x350</option>
                <option value="400">400x400</option>
                <option value="450">450x450</option>
                <option value="500">500x500</option>
                <option value="550">550x550</option>
                <option value="600">600x600</option>
                <option value="650">650x650</option>
                <option value="700">700x700</option>
                <option value="750">750x750</option>
                <option value="800">800x800</option>
                <option value="850">850x850</option>
              </select>
              </td>
          </tr>
          <tr>
            <td width="96" bgcolor="#FFFFFF">Thumbnail mit Gr&ouml;sse </td>
            <td width="140" bgcolor="#FFFFFF">
              <? 
              if(!isset($object_id)) $checked="checked";
              elseif($edit_object->attributes_vars[thumbnail]==1) $checked="checked";
              ?>
              <input type="checkbox" name="attributes_vars[thumbnail]" size="20" value="1" <? echo $checked;?> >
              <select name="thumbsize" id="thumbsize">
              
                <?  $contentpath=ROOT_PATH.$current_site->attributes['dirname']."content/";
                    $selected="selected";
                    $thumbfile = $contentpath."image/thumbnails/".$edit_object->attributes['file'];
                    echo $thumbfile;
                    //echo SITE_PATH;
                    if($edit_object->attributes['file']>'' && file_exists($thumbfile)){
                    $timgsize = GetImagesize($thumbfile);
                    ($timgsize[0]>$timgsize[1])? $timgsel = $timgsize[0] :    $timgsel = $timgsize[1];
                    echo '<option value="'.$timgsel.'" selected>'.$timgsel.'x'.$timgsel.'</option>';
                    $selected="";
                    }
                ?>
                <option value="25">25x25</option>
                <option value="50">50x50</option>
                <option value="75">75x75</option>
                <option value="100">100x100</option>
                <option value="150">150x150</option>
                <option value="200" <? echo $selected?>>200x200</option>
                <option value="250">250x250</option>
                <option value="300">300x300</option>
                <option value="350">350x350</option>
		        <option value="400">400x400</option>
		        <option value="450">450x450</option>
		        <option value="500">500x500</option>
              </select>
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
            <td width="96" bgcolor="#FFFFFF">Anzeige
              </td>
            <td width="140" bgcolor="#FFFFFF">
            popup <input type="radio" name="attributes_vars[viewer]" value="0" <?if($edit_object->attributes_vars[viewer]==0):?>checked <?endif;?>/> 
            lightbox <input type="radio" name="attributes_vars[viewer]" value="3" <?if($edit_object->attributes_vars[viewer]==3):?>checked <?endif;?> />
            viewer <input type="radio" name="attributes_vars[viewer]" value="1" <?if($edit_object->attributes_vars[viewer]==1):?>checked <?endif;?> />
            <!-- download <input type="radio" name="attributes_vars[viewer]" value="2" <?if($edit_object->attributes_vars[viewer]==2):?>checked <?endif;?> />-->
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
   obj=document.getElementById('formsl');
   obj.style.width='670px';
   -->
</script>