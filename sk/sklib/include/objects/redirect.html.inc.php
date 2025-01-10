<?//*********** Redirect-Object **************

IF ($editable==1 || $GLOBALS['Output_All']==1) {
	$output.='Umleitung zu URL:'.$this->attributes_vars['url'];
	
}else{
ob_end_clean();
header('Location:'.$this->attributes_vars['url']);
exit;
}
?>