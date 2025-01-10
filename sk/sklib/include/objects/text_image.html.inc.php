<?//*********** Text-Output for HTML **************
$output.="<div class=\"sktext_image\">";

IF ($this->attributes['file']){
IF ($this->attributes_vars['align']=='center') $output.='<div align="center">';
IF ($this->attributes_vars[imgtitle]>"") $output.="<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\"  align=\"".$this->attributes_vars['align']."\"><tr><td>";
IF ($this->attributes_vars[link]>"") $output.="<a href=\"".$this->attributes_vars[link]."\" target=\"".$this->attributes_vars[target]."\">";
IF ($this->attributes_vars[ec]==1) $ecf=$form_relpath."ecard.php?image_id=".$this->attributes['object_id'];
IF ($this->attributes_vars[pb]==1) $print=1;
IF ($this->attributes_vars[thumbnail]==1){
$output.="<a href=\"#\" onClick=\"openPictureWindow('".SITE_URL."content/image/".$this->attributes['file']."','".$this->attributes_vars[width]."','".$this->attributes_vars[height]."','".$this->attributes[imgtitle]."','50','50','".$ecf."','".$print."')\">";
$output.="<IMAGE class=\"skimage\" SRC=\"".SITE_URL."content/image/"."thumbnails/".$this->attributes['file']."\" width=\"".$this->attributes_vars[twidth]."\" height=\"".$this->attributes_vars[theight]."\" align=\"".$this->attributes_vars['align']."\" vspace=\"".$this->attributes_vars[imgspace]."\" hspace=\"".$this->attributes_vars[imgspace]."\" border=\"".$this->attributes_vars[border]."\" ALT=\"".htmlspecialchars($this->attributes[imgtitle])."\">";
$output.="</a>";
//$output.="onMouseDown=\"openPictureWindow('".SITE_URL."content/image/".$this->attributes['file']."','".$this->attributes_vars[width]."','".$this->attributes_vars[height]."','".$this->attributes['objtext']."','50','50','".$ecf."','".$print."')\"";
}ELSE{ 
$output.="<IMAGE class=\"skimage\" vspace=\"".$this->attributes_vars[imgspace]."\" hspace=\"".$this->attributes_vars[imgspace]."\" SRC=\"".SITE_URL."content/image/".$this->attributes['file']."\" width=\"".$this->attributes_vars[width]."\" height=\"".$this->attributes_vars[height]."\" align=\"".$this->attributes_vars['align']."\" border=\"".$this->attributes_vars[border]."\"  ALT=\"".htmlspecialchars($this->attributes[imgtitle])."\">";
}
$output.="";
IF ($this->attributes_vars[link]>"") $output.="</a>";
IF ($this->attributes_vars['align']=='center') $output.='</div>';
IF ($this->attributes_vars[imgtitle]>"") $output.="</td></tr>\n<tr><td align=\"center\">\n<font size=\"-1\">".htmlspecialchars($this->attributes_vars[imgtitle])."</font></td></tr></table>";
}
$output.="<div class=\"sktext\" style=\"text-align:".$this->attributes_vars[textalign]."; alignment:".$this->attributes_vars[textalign]."; margin:".$this->attributes_vars['margin'].";\">";
IF ($GLOBALS['debug']>0) $GLOBALS['DEBUG_OUTPUT'].=$GLOBALS['current_site']->attributes['dirname'];
$site_url=$GLOBALS['current_site']->attributes['site_url'].$GLOBALS['current_site']->attributes['dirname'];
$output.=str_replace("%server_address%",SITE_URL,$this->attributes['objtext']);
$output.="</div><div style=\"clear:both;\"></div></div>";

?>