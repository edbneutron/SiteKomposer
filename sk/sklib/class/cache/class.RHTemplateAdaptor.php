<?php

/*
 * Class TemplateAdaptor (Richard Heyes' Template version)
 * by Jesus M. Castagnetto (jmcastagnetto@zkey.com)
 * (c) 2000. Version 1.1
 *
 * $Id: class.RHTemplateAdaptor.php,v 1.3 2000/07/16 19:33:46 jesus Exp $
 *
 * Description:
 * This class extends Richard Heyes' Template class
 * implementing methods and attributes needed for the CachedTemplate class.
 *
 * The adaptor class needs to implement the getTemplatesList() method that
 * returns an array with the names of the templates loaded, the init() 
 * method used to initialize the constructor of the parent template class,
 * and the getParsedDoc() method which returns the parsed document.
 *
 * Changes:
 * 2000/06/10 - Initial release.
 * 2000/07/17 - Documentation, new release.
 */


class TemplateAdaptor extends rhtemplate {

	var $TEMPLATE = array();

	/*
	 * This method is used to initialize the parent class
	 */

	function init() {
		// empty, class Template does not have a constructor
	}


	/*
	 * method to return the list of template names
	 */
	
	function getTemplatesList() {
		return $this->TEMPLATES;
	}


	/*
	 * method to return the parsed document
	 */
	
	function getParsedDoc($sect) {
		$out = "";
		if(is_long(strpos($sect, ','))){
			$sect = explode(',', $sect);
			for(reset($sect); $current = current($sect); next($sect))
				$out .= $this->files[$current];
		}else{
			$out .= $this->files[$sect];
		}
		return $out;
	}
	
	/*
	 * overriden method to simplify creation of the list of templates files
	 */

	function load_file($file_id, $filename){
		$this->TEMPLATES[] = $filename;
		$this->files[$file_id] = implode('', file($filename));
	}


} // end of class definition
?>