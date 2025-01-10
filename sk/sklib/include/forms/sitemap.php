<?php 


if (!isset($selected_site)) $selected_site=$current_site->attributes['site_id'];
  
    // get Menu-Structure
  $current_site_id=$selected_site;
  $Menu = new skmenu(1);
  $Menu->getfromdb();
    

?>

<br>
<table width="480" border="0" vspace="0" hspace="0" cellpadding="2" cellspacing="0" align="center">
  <tr> 
    <td> 
      <form action="<? echo $formtarget;?>" method="POST" target="skpage" enctype="multipart/form-data" name="editform" onSubmit="_close()">
        <input type="Hidden" name="action" value="do">
        <input type="Hidden" name="edit" value="1">
        <input type="Hidden" name="type" value="sitemap">
        <input type="Hidden" name="parent_node" value="<? echo $parent_node;?>">
        <input type="Hidden" name="object_id" value="<? echo $edit_object->attributes['object_id'];?>">
        <input type="Hidden" name="content_id" value="<? echo $edit_object->attributes['content_id'];?>">
        <!-- <input type="Hidden" name="sort_nr" value="<? echo $edit_object->attributes['sort_nr'];?>"> -->
       

        <table width="100%" border="0"  cellpadding="2" cellspacing="2">
         <tr>
            <td width="35%"><b>Theme</b></td>
            <td width="65%">
              <select name="attributes_vars[theme]" size="1"><?
              IF (isset($edit_object->attributes_vars[theme])):?><option value="<? echo $edit_object->attributes_vars[theme];?>"><? echo $edit_object->attributes_vars[theme];?></option><? eNDIF;?>
                <option value="default">default</option>
                <option value="small_gray">small_gray</option>
                <option value="xp_folders">xp_folders</option>
                <option value="noline">noline</option>
              </select>
              </td>
          </tr>
          <tr>
            <td width="35%" bgcolor="#FFFFFF"> <div align="left"><strong>minimale Tiefe</strong></div></td>
            <td width="65%" bgcolor="#FFFFFF"> <input type="text" name="attributes_vars[mindepth]" size="5" value="<? echo $edit_object->attributes_vars[mindepth];?>">
            </td>
          </tr>
          <tr> 
		    <td height="1" colspan="4" class="mgallerytitle">
		    Seite:<strong> 
		        </strong>&nbsp;&nbsp;
		        <select name="attributes_vars[selected_page]" >
		        <option value="">Standard</option>
		        <? 
		        $selected_page=$edit_object->attributes_vars['selected_page'];
				reset($Menu->tree);
				while (list($index, $submenu) = each($Menu->tree) ) {    
		    	?>
		    		<option value=<? if($submenu[3]==$selected_page) echo '"'.$submenu[3].'" selected'; else echo '"'.$submenu[3].'"';?>><? for($i=1;$i<$submenu[0];$i++) echo "&nbsp;-"; echo $submenu[1]?></option>
		    	<?}?>
		    </select>
		        <input class="small" name="getsite" type="submit" value="&lt;-">&nbsp;
		  <input type="hidden" name="selected_site" value="<? echo $selected_site ?>">
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

