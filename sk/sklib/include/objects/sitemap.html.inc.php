<?//*********** Site-Map for HTML **************

$Menu = new skmenu();
if($this->attributes_vars[view_all]==1) {
	$Menu->admin=1;
}
$Menu->getfromdb();

//$Menu=$GLOBALS['Menu'];
if(!$this->attributes_vars[maxdepth])$this->attributes_vars[maxdepth]=10;

//$this->attributes_vars[mindepth]=0;
//$output.=$Menu->css_menu($this->attributes_vars[theme],"",1,$GLOBALS['mid'],,,0,0,$GLOBALS['mid'],);

$output.="<div id=\"sitemap\">";
if(strlen($this->attributes_vars[selected_page])>1){
	$MenuId=$this->attributes_vars[selected_page];
}else{
	$MenuId=$GLOBALS['mid'];	
}

//$output.="Sitemap<br> css_menu(".$this->attributes_vars[mindepth].",".$this->attributes_vars[maxdepth].",".$this->attributes_vars[onlybranch].",1,0,".$GLOBALS['mid'].",".$this->attributes_vars[view_all].",0,".$GLOBALS['mid'].")";
$output.=$Menu->css_menu($this->attributes_vars[mindepth],
						 $this->attributes_vars[maxdepth],
						 $this->attributes_vars[onlybranch],
						 1,
						 0,
						 $MenuId,
						 $this->attributes_vars[view_all],
						 0,
						 $MenuId);
$output0.=$Menu->css_menu($this->attributes_vars[mindepth],
						 $this->attributes_vars[maxdepth],
						 $this->attributes_vars[onlybranch],
						 1,
						 0,
						 $GLOBALS['mid'],
						 $this->attributes_vars[view_all],
						 0,
						 $GLOBALS['mid']);
$output.="</div><!--sitemap-->";
?>