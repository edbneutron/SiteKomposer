<?//*********** File-Output for HTML **************
/*
$output.='<table width="250" class="filebox" border="0" cellspacing="0" cellpadding="2"><tr>';
$output.='<td width="15" class="sktext" id="fileicon" rowspan="2" valign="top"><img src="'.SKRES_URL.'skinz/'.$GLOBALS['theme'].'/object_icons/small/file.gif" width="15" height="15" border="0" valign="top"></td>';
$output.='<td width="150" class="sktext" id="filetitle" valign="top"><div class="filetitle">';
if($this->attributes_vars['title']>' ') $output.=$this->attributes_vars['title']; else $output.=$this->attributes['file'];
$output.='&nbsp;'.number_format($this->attributes_vars['filesize']/1024,1).'KB</div></td>';
$output.='<td align="right" class="sktext"><a href="index.php?action=getfile&object_id='.$this->attributes['object_id'].'" >download</a><br>';
if(!$this->attributes_vars['nocounter']) $output.=$this->attributes_vars['dlcnt'].'&nbsp;dlds.</td></tr>';

$output.="<tr id=\"filetext\"><td class=\"fileboxtd\"><span class=\"sktext\" style=\"text-align:".$this->attributes_vars['align']."; margin:".$this->attributes_vars['margin'].";\">";
$output.=''.$this->attributes['objtext'].'</span></td><td align="right">';

$output.='&nbsp;</td></tr></td></tr></table>';
*/
if($this->attributes_vars['dlcnt']<1)$this->attributes_vars['dlcnt']=0;
$output.='<div class="filebox">';
$output.='<div class="fileicon"><img src="'.SKRES_URL.'skinz/'.$GLOBALS['theme'].'/object_icons/small/file.gif" width="15" height="15" border="0" valign="top">
</div>';
$output.='<div class="filetitle">';
	if($this->attributes_vars['title']>' ') $output.=$this->attributes_vars['title']; else $output.=$this->attributes['file'];
	$output.='&nbsp;'.number_format($this->attributes_vars['filesize']/1024,1).'KB';
$output.='</div>';
$output.='<div class="filelink"><a href="index.php?action=getfile&object_id='.$this->attributes['object_id'].'" >download</a><br>';
	if(!$this->attributes_vars['nocounter'] || $editable==1) $output.='&nbsp;'.$this->attributes_vars['dlcnt'].'&nbsp;downloads.';
$output.='</div><br/>';
$output.="<div class=\"fileboxtext\"><span class=\"sktext\" style=\"text-align:".$this->attributes_vars['align']."; margin:".$this->attributes_vars['margin'].";\">";
$output.=''.$this->attributes['objtext'].'</span></div>';
$output.='</div>';
?>