<?//*********** Video_Flash-File-Output for HTML **************
IF ($this->attributes_vars['align']>"") $align="align=\"".$this->attributes_vars['align']."\"";
if($this->attributes_vars[clsid]=="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" || $this->attributes_vars[clsid]=="clsid:166B1BCA-3F9C-11CF-8075-444553540000") {
   $filepath=SITE_URL."content/flash/";}
else {$filepath=SITE_URL."content/video/";}

$output.="<table border=\"0\" cellspacing=\"0\" cellpadding=\"2\" $align><tr><td $align>";

$output.='
<div '.$align.'>';
IF ($this->attributes_vars['link']>"") $output.="<a href=\"".$this->attributes_vars[link]."\" target=\"".$this->attributes_vars[target]."\">";
IF ($this->attributes_vars['transparent']=="true") {$transparent='<param name="wmode" value="transparent">';$transparent1='WMODE="transparent"';}
$output.='
    <object classid="'.$this->attributes_vars[clsid].'" width="'.$this->attributes_vars[width].'" height="'.$this->attributes_vars[height].'" id="object'.$this->attributes['object_id'].'" controller="'.$this->attributes_vars[controls].'" controls="'.$this->attributes_vars[controls].'">
    <param name="filename" value="'.$filepath.$this->attributes['file'].'">
    <param name="Autostart" value="'.$this->attributes_vars['autostart'].'">
    <param name="loop" value="'.$this->attributes_vars[loop].'">
    <param name="controls" value="'.$this->attributes_vars[controls].'">
    <param name="showcontrols" value="'.$this->attributes_vars[controls].'">
    <param name="controller" value="'.$this->attributes_vars[controls].'">
    <param name="SRC" value="'.$filepath.$this->attributes['file'].'">'.
    $transparent.'<embed src="'.$filepath.$this->attributes['file'].'" '.$transparent1.' width="'.$this->attributes_vars[width].'" height="'.$this->attributes_vars[height].'" controller="'.$this->attributes_vars[controls].'" controls="'.$this->attributes_vars[controls].'" loop="'.$this->attributes_vars[loop].'" autostart="'.$this->attributes_vars['autostart'].'" filename="'.$filepath.$this->attributes['file'].'"></embed>
  </object>';
IF ($this->attributes_vars[link]>"") $output.="</a>";


$output.="</div></td></tr>\n<tr><td align=\"center\">\n<font size=\"-1\">";
IF ($this->attributes_vars[link]>"") $output.="<a href=\"".$this->attributes_vars[link]."\" target=\"".$this->attributes_vars[target]."\">";
$output.=htmlspecialchars($this->attributes['objtext']);
IF ($this->attributes_vars[link]>"") $output.="</a>";
$output.="</font></td></tr></table>\n";



?>