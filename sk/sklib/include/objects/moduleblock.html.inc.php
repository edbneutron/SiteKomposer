<?//*********** PHP-File-Include **************


if(is_file(SK_PATH.'modules/'.$this->attributes_vars['dirname'].'/blocks/'.$this->attributes_vars['block'])) {
	
	/* include initialisation file (if there) */
	if(is_file(SK_PATH.'modules/'.$this->attributes_vars['dirname'].'/init.php')){
		include(SK_PATH.'modules/'.$this->attributes_vars['dirname'].'/init.php');
	}
	
	/* load module translation */
	SKTranslate::loadModuleTranslation($this->attributes_vars['dirname']); 
	
	/*include block file */
	include(SK_PATH.'modules/'.$this->attributes_vars['dirname'].'/blocks/'.$this->attributes_vars['block']);
}
else {
    	$output.="<div class=\"small\">Block-File nicht gefunden!<br>".SK_PATH.'modules/'.$this->attributes_vars['dirname'].'/blocks/'.$this->attributes_vars['block']."</div>"; 
}

?>