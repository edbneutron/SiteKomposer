<?//*********** Sound-File-Output for HTML **************


$output.='<table width="250" class="soundbox" border="0" cellspacing="0" cellpadding="0"><tr>';
$output.='<td width="150" class="soundboxtd"><div class="sktext">'.$this->attributes_vars['title'].'</div></td>';
$output.='<td align="right" class="soundboxheader"><a href="'.SITE_URL.'content/sound/'.$this->attributes['file'].'" class="">download</a></td></tr>';

$output.="<tr><td class=\"soundboxtd\"><span class=\"sktext\" style=\"text-align:".$this->attributes_vars['align']."; margin:".$this->attributes_vars['margin'].";\">";
if($this->attributes['objtext']>'')$output.=$this->attributes['objtext']; else $output.=$this->attributes['file'];
$output.='</span></td><td align="right">';

$output.='&nbsp;</td></tr></table>';

?>