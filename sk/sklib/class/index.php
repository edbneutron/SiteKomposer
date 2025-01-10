<?
/*------------------------------------------------------------------------------*/
/* SITE-Komposer  PHP-Content Management System                                    */
/*------------------------------------------------------------------------------*/

/***************************************
** Title........: Site-Komposer-DirIndex
** Filename.....: index.php
** Author.......: Edgar Bueltemeyer
** displays info in sub-directory under sk/ where no listing should occur
***************************************/
 session_start();

$search_include_path=substr($_SERVER[SCRIPT_FILENAME],0,strpos($_SERVER[SCRIPT_FILENAME],"/sklib/"));
//if(!isset($_SESSION[sess_hash])) header("Location:../../sk/admin/index.php");
require_once($search_include_path."/sk/sk.php");
require_once(SK_PATH."res/tpl/page/dirindex.php");
 
?>

