<?
/***************************************
** Title........: SK-Items class
** Filename.....: class.skitem.inc.php
** Author.......: Edgar Bueltemeyer
** Version......: 0.1
** Notes........:
** Last changed.: 23/02/2006
** Last change..: 
************************************** */


class skitem {
        var $debug = 0;
        var $skdbadm;        



// init-function
function skitem() {


    }
//-------------------------------------------
//----- function delbutton
//----- returns delete_button with specific form
//----- @param string $form name of form
//----- @param string $format Output-Format
//-------------------------------------------

function delbutton($form="",$varname="",$varvalue=""){
	
	return "<img src=\"".SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/del_icon.gif\"  border=\"0\" align=\"baseline\" OnClick=\"frage_f('".$form."','".$varname."','".$varvalue."');return false\">";
	
} // end function delbutton
    

} //class skitem
