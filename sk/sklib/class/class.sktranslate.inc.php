<?php
/***************************************
** Title........: SK-translation class
** Filename.....: class.sktranslation.inc.php
** Author.......: Edgar Bueltemeyer
** Version......: 0.1
** Notes........: 
** Last changed.: 24/03/2009
** Last change..: 
***************************************/


class SKTranslate {
	
	var $arrTranslations = array();
	
	/**
	 * Load translations array for Module
	 */
	function loadModuleTranslation($ModuleName,$lang="") 
	{ 
		
		$language = $GLOBALS['current_site']->attributes['lang'];
		DEBUG_out(2,"debug3","language: ".print_array($GLOBALS['current_site']));
		if(strlen($language)<2) $language="de";
		// manual language override		
		if(strlen($lang)>1) $language=$lang;
		$strFilename=SK_PATH.'modules/'.$ModuleName."/lang/" . $language .".php";
		DEBUG_out(2,"debug3","Trying to load module translation-file: ".$strFilename);
		if(is_file($strFilename)){
			DEBUG_out(2,"debug3","Loading Module Translation");
			include_once($strFilename);
			$GLOBALS['SK_translations'] = array_merge($GLOBALS['SK_translations'], $module_translation);
			//$this->arrTranslations= array_merge($this->arrTranslations, $module_translations);
		}
		
	}
	
	/*
	 * translate text
	 */
	function text($index)
	{
		DEBUG_out(2,"debug2","Translating Text");
		if(isset($GLOBALS['SK_translations'][$index]))
		{
			return $GLOBALS['SK_translations'][$index];
		}else{
			return $index;
		}
		throw new Exception("Translation string '$index' not available.");
	}

}

?>