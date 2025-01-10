<?
$Menu = new skmenu(1);
  $Menu->getfromdb();
  if (!isset($selected_page)) $selected_page=$Menu->tree[1][3];
  ?>
<br>
<table width="480" border="0" vspace="0" hspace="0" cellpadding="2" cellspacing="0" align="center">
  <tr> 
    <td> 
      <form action="<? echo $formtarget;?>" method="POST" target="skpage" enctype="multipart/form-data" name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="overview">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <!-- <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>"> -->
       

        <table width="100%" border="0"  cellpadding="2" cellspacing="2">
        <tr>
            <td width="35%"><b>Seite w&auml;hlen</b></td>
            <td width="65%">
             <select name="attributes_vars[selected_page]">
             <? 
            reset($Menu->tree);
            while (list($index, $submenu) = each($Menu->tree) ) {    
                ?>
                <option value=<? if($submenu[3]==$edit_object->attributes_vars[selected_page]) echo '"'.$submenu[3].'" selected'; else echo '"'.$submenu[3].'"';?>><? for($i=1;$i<$submenu[0];$i++) echo "&nbsp;-"; echo $submenu[1]?></option>
                <?}?>
                </select>
          </td>
          </tr>
         <tr>
            <td width="35%"><b>Template</b></td>
            <td width="65%">
              <? echo skfileselect(SITE_PATH.'res/tpl/gallery/',$edit_object->attributes_vars['template'],"attributes_vars[template]"); ?>
              </td>
          </tr>
          <tr>
            <td width="35%" bgcolor="#FFFFFF"> <div align="left"><strong>minimale Tiefe</strong></div></td>
            <td width="65%" bgcolor="#FFFFFF"> <input type="text" name="attributes_vars[mindepth]" size="5" value="<? echo $edit_object->attributes_vars[mindepth];?>">
            </td>
          </tr>
          <tr>
            <td width="35%" bgcolor="#FFFFFF"> <div align="left"><strong>maximale Tiefe</strong></div></td>
            <td width="65%" bgcolor="#FFFFFF"> <input type="text" name="attributes_vars[maxdepth]" size="5" value="<? echo $edit_object->attributes_vars[maxdepth];?>">
            </td>
          </tr>
          <tr> 
            <td width="35%" bgcolor="#FFFFFF"><strong>nur aktueller Unterbaum</strong>
              </td>
            <td width="65%" bgcolor="#FFFFFF">
            nein <input type="radio" name="attributes_vars[onlybranch]" value="0" <?if($edit_object->attributes_vars[onlybranch]==0):?>checked <?endif;?>/>
            ja   <input type="radio" name="attributes_vars[onlybranch]" value="1" <?if($edit_object->attributes_vars[onlybranch]==1):?>checked <?endif;?> />
              </td>
          </tr>
          <tr> 
            <td width="35%" bgcolor="#FFFFFF"><strong>alle Punkte anzeigen</strong>
              </td>
            <td width="65%" bgcolor="#FFFFFF">
            nein <input type="radio" name="attributes_vars[view_all]" value="0" <?if($edit_object->attributes_vars[view_all]==0):?>checked <?endif;?>/>
            ja   <input type="radio" name="attributes_vars[view_all]" value="1" <?if($edit_object->attributes_vars[view_all]==1):?>checked <?endif;?> />
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
              <b>Elemente pro Seite</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[perpage]" size="5" value="<? if($edit_object->attributes_vars[perpage]!=""){ echo $edit_object->attributes_vars[perpage];} else{ echo "alle";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Bilder pro Element</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[nrpics]" size="5" value="<? if($edit_object->attributes_vars[nrpics]!=""){ echo $edit_object->attributes_vars[nrpics];} else{ echo "1";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Spezialpfad f&uuml;r Bilder</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[picpath]" size="5" value="<? if($edit_object->attributes_vars[picpath]!=""){ echo $edit_object->attributes_vars[picpath];} else{ echo "";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Skalierung in Prozent</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[scale]" size="5" value="<? if($edit_object->attributes_vars[scale]!=""){ echo $edit_object->attributes_vars[scale];} else{ echo "";}?>">
            </td>
          </tr>
          <tr>
            <td width="49%" bgcolor="#FFFFFF" >
              <b>Navigationsbeschriftung</b></td>

            <td  width="51%" bgcolor="#FFFFFF"><input type="text" name="attributes_vars[prevb]" size="5" value="<? if($edit_object->attributes_vars[prevb]!=""){ echo $edit_object->attributes_vars[prevb];} else{ echo "zur&uuml;ck";}?>">
                                               <input type="text" name="attributes_vars[nextb]" size="5" value="<? if($edit_object->attributes_vars[nextb]!=""){ echo $edit_object->attributes_vars[nextb];} else{ echo "weiter";}?>">
            </td>
          </tr>
          <tr>
            <td width="35%" bgcolor="#FFFFFF"> <div align="left"><strong>Sortierung</strong></td>
            <td width="65%" bgcolor="#FFFFFF"> <input type="text" name="sort_nr" size="5" value="<? echo $edit_object->attributes['sort_nr'];?>">
            </td>
          </tr>
        </table>


        <br>
        <? include($forms_tpl_path.'submit_buttons.php');?>
      </form>
    </td>
  </tr>
</table>

