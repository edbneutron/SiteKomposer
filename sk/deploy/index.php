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

$sk_include_path=substr($_SERVER[SCRIPT_FILENAME],0,strpos($_SERVER[SCRIPT_FILENAME],"/sk/")+4);
//if(!isset($_SESSION[sess_hash])) header("Location:../../sk/admin/index.php");
require_once($sk_include_path."sk.php");
require_once(SKRES_PATH."tpl/page/dirindex.php");
 
?>

