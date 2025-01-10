<?
//*********** Image-Output for HTML **************

IF ($this->attributes_vars['align']>"") $align="align=\"".$this->attributes_vars['align']."\"";

IF ($this->attributes_vars['align']=="center")$output.="<div align=\"center\">";

IF ($this->attributes['objtext']>""  || $this->attributes_vars[noflow]==1 || $this->attributes_vars[viewer]==2) $show_table=1;

IF ($show_table==1):$output.="<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" $align><tr><td $align>";ENDIF;
IF ($this->attributes_vars[link]>"") $output.="<a href=\"".$this->attributes_vars[link]."\" target=\"".$this->attributes_vars[target]."\">";

IF ($this->attributes_vars[ec]==1) $ecf=SITE_URL."index.php?form_window=1&form_template=ecard.php&image_id=".$this->attributes['object_id'];
IF ($this->attributes_vars[pb]==1) $print=1;
IF ($this->attributes_vars[thumbnail]==1){
     if($this->attributes_vars[viewer]==1)//viewer
         $output.="<a href=\"javascript:void(0);\" onclick=\"changePic('".SITE_URL."content/image/".$this->attributes['file']."','".$this->attributes_vars[width]."','".$this->attributes_vars[height]."','".$this->attributes['objtext']."','50','50','".$ecf."','".$print."')\">";
     elseif($this->attributes_vars[viewer]==0)//popup
         $output.="<a href=\"javascript:void(0);\" onclick=\"openPictureWindow('".SITE_URL."content/image/".$this->attributes['file']."','".$this->attributes_vars[width]."','".$this->attributes_vars[height]."','".$this->attributes['objtext']."','50','50','".$ecf."','".$print."')\">";
     elseif($this->attributes_vars[viewer]==3)//lightbox
         $output.="<a href=\"".SITE_URL."content/image/".$this->attributes['file']."\" rel=\"lightbox[images".$GLOBALS['lighboxnr']."]\" title=\"".htmlspecialchars($this->attributes['objtext'])."\">";
	 
  if($editable==1){
      $output.="<img class=\"skimage\" id=\"skimage".$this->attributes['object_id']."\" src=\"".SITE_URL."content/image/"."thumbnails/".$this->attributes['file']."\" width=\"".$this->attributes_vars[twidth]."\" height=\"".$this->attributes_vars[theight]."\" vspace=\"".$this->attributes_vars[space]."\" hspace=\"".$this->attributes_vars[space]."\" border=\"".$this->attributes_vars[border]."\" alt=\"".htmlspecialchars($this->attributes['objtext'])."\" />";
  }else{
      $output.="<img class=\"skimage\" id=\"skimage".$this->attributes['object_id']."\" src=\"".SITE_URL."content/image/"."thumbnails/".$this->attributes['file']."\" width=\"".$this->attributes_vars[twidth]."\" height=\"".$this->attributes_vars[theight]."\" align=\"".$this->attributes_vars['align']."\" vspace=\"".$this->attributes_vars[space]."\" hspace=\"".$this->attributes_vars[space]."\" border=\"".$this->attributes_vars[border]."\" alt=\"".htmlspecialchars($this->attributes['objtext'])."\" />";
      }
  if($this->attributes_vars[viewer]==1 || $this->attributes_vars[viewer]==0 || $this->attributes_vars[viewer]==3)$output.="</a>";
  //$output.="onMouseDown=\"openPictureWindow('".SITE_URL."content/image/".$this->attributes['file']."','".$this->attributes_vars[width]."','".$this->attributes_vars[height]."','".$this->attributes['objtext']."','50','50','".$ecf."','".$print."')\"";

  if($this->attributes_vars[autoload]==1)
  $output.="<script language=\"JavaScript\" type=\"text/JavaScript\"><!--
      var myskpic=new Image;
      myskpic.src='".SITE_URL."content/image/".$this->attributes['file']."';
      myskpic.width='".$this->attributes_vars[width]."';myskpic.height='".$this->attributes_vars[height]."';myskpic.alt='".$this->attributes['objtext']."';
  //--></script>";

}ELSE{
    $output.="<img class=\"skimage\" id=\"".$this->attributes_vars['id']."\" vspace=\"".$this->attributes_vars[space]."\" hspace=\"".$this->attributes_vars[space]."\" src=\"".SITE_URL."content/image/".$this->attributes['file']."\" width=\"".$this->attributes_vars[width]."\" height=\"".$this->attributes_vars[height]."\" align=\"".$this->attributes_vars['align']."\" border=\"".$this->attributes_vars[border]."\"  alt=\"".htmlspecialchars($this->attributes['objtext'])."\" usemap=\"".htmlspecialchars($this->attributes_vars['usemap'])."\" />";
}//thumbnail
$output.="";
IF ($this->attributes_vars[link]>"") $output.="</a>";


IF ($show_table==1)
    $output.="</td></tr>\n<tr><td align=\"center\" class=\"imgdescr\">\n".$this->attributes['objtext']."</td></tr>";
// Download-Link
if($this->attributes_vars[viewer]==2) {
   $output.='<tr><td align="center" class="sktext"><a href="?action=getfile&object_id='.$this->attributes['object_id'].'" >download</a>';
   $output.='</td></tr>';
}
IF ($show_table==1)
    $output.="</table>\n";
 
    
IF ($this->attributes_vars['align']=="center")$output.="</div><!--align center-->";

?>