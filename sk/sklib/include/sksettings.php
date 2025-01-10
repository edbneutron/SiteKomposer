<?php
/*------------------------------------------------------------------------------*/
/* SITE-Komposer  PHP-Content Management System                                 */
/*------------------------------------------------------------------------------*/

/***************************************
** Title........: Site-Komposer-Settings-Include
** Filename.....: sk_settings.php
** Author.......: Edgar Bueltemeyer
***************************************/

/*------------------------------------------------------------------------------*/
/* Settings for Site-Komposer                                                   */
/*------------------------------------------------------------------------------*/
    /*------------------------------------------------------------------------------*/
    /* Physical Path�s -------------------------------------------------------------*/
    /*------------------------------------------------------------------------------*/

    if(!defined("LIB_PATH")) define("LIB_PATH",SK_PATH);
    if(!defined("SKRES_PATH")) define("SKRES_PATH",ROOT_PATH."skres/");
    define("MODULE_PATH",SK_PATH."modules/");
    define("INCLUDE_PATH",LIB_PATH."include/");
    define("CLASS_PATH",LIB_PATH."class/");
    define("SKIN_PATH",SKRES_PATH.'skinz/'.$theme.'/');
    if(!defined("WYSIWYG_EDITOR")) define("WYSIWYG_EDITOR",SKRES_PATH.'js/htmlarea/includer.php');
    
    if(!isset($docrelpath)) $docrelpath="";
    include(INCLUDE_PATH."skutil.inc.php"); // Utility-Functions
    $time_start = getmicrotime(); // benchmarking

    define("ADODB_DIR",CLASS_PATH."adodb/");       // Path to ADODB- DB Abstraction-Layer
    
    $news_tpl_path = MODULE_PATH."sk_news/blocks/"; //SK_News-Template Path
    $forms_tpl_path = INCLUDE_PATH."forms/"; //SK_Forms-Template Path
    //$img_res_path = SK_PATH."res/img/"; //SK_Image Resources Path
    
    /* initialise Translation-Array */
    $SK_translations= array();
    
    //****** URL definitions ******//
    /* WWW (SK-Url)                                     */
    if(!defined("SK_URL")) define("SK_URL","http://".SERVER_ADDRESS."sk/");
    /* WWW (SKRES-Url)   Url to ressources like images css js               */
    if(!defined("SKRES_URL")) define("SKRES_URL","http://".SERVER_ADDRESS."skres/");
    define("SKIN_URL",SKRES_URL.'skinz/'.$theme.'/');
    /* relative path�s to root */
    $img_res_relpath = $docrelpath."sk/res/img/";
      define("CONTENT_REL_PATH",$docrelpath."content/");
    $imagepath1 = $docrelpath."content/image/"; // Path for viewing Images
    $newsimgrelpath = $docrelpath."content/news/images/"; // Path for viewing Images
   /*------------------------------------------------------------------------------*/
   /* Start of Debug-Output--------------------------------------------------------*/
   /*------------------------------------------------------------------------------*/
    $debug=0;
    $t_timer1=0;
    $t_timer2=0;
    $GLOBALS['DEBUG_OUTPUT']="";
    if($debug_level>0){
    include(INCLUDE_PATH."debug_ips.php");
    if(isset($_GET['debug_level']) && $_GET['debug_level']>0 && in_array($_SERVER["REMOTE_ADDR"],$debug_ips)) $debug=$_GET["debug_level"];
    elseif (in_array($_SERVER["REMOTE_ADDR"],$debug_ips)) $debug=$debug_level;
    else $debug=4;
  }

    DEBUG_out(1,"debugt","Site-Komposer Debug-Output  Level:".$debug."<br>");
    DEBUG_out(1,"debug1","SK_URL:".SK_URL);
    DEBUG_out(1,"debug1","file running:".$_SERVER['SCRIPT_FILENAME']);
    DEBUG_out(1,"debug1","User Agent:".$_SERVER['HTTP_USER_AGENT']." remote_addr:".$_SERVER['REMOTE_ADDR']);
    if(isset($_SESSION)) DEBUG_out(1,"debug2","Session-Info:".var_export($_SESSION,1));

    //DEBUG_out(4,"debug4","GLOBALS:".print_array($globalvars,1));
    //DEBUG_out(4,"debug4","GLOBALS2:".print_array($GLOBALS1,1));
    /* some elements */
    $edit_button="<img src=\"".SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/edit_icon.gif\"  border=\"0\" align=\"baseline\">";
    $edit_button_small="<img src=\"".SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/edit_icon_small.gif\"  border=\"0\" align=\"baseline\">";
    $del_button="<img src=\"".SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/del_icon.gif\"  border=\"0\" align=\"baseline\" OnClick=\"frage_s();return false\">";
    $add_button="<img src=\"".SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/add_icon.gif\" border=\"0\" align=\"baselineabsmiddle\">";
    $add_button_small="<img src=\"".SKRES_URL."skinz/".$GLOBALS['theme']."/buttons/add_icon_small.gif\" border=\"0\" align=\"baselineabsmiddle\">";
    $url_par="";
    
    // Include first required files
    require_once(INCLUDE_PATH."skincludes1.php");

//include(INCLUDE_PATH."auth/auth_session.php");
/* Error-messages */
$not_logged_in="Nicht eingeloggt";

/*------------------------------------------------------------------------------*/
/* Some Vars.... */
/*------------------------------------------------------------------------------*/
    /* DB-Host                                                                  */
    $dbhost="localhost";
    /* DB-Type                                                                    */
    $db_type="mysqli";
    /* DB-Name                                                                    */
    define("SK_DB_NAME",$dbname);
    /* DB-User for Read-only access                                                */
    define("SK_DB_USER",$db_user_name);
    define("SK_DB_PW",$db_user_pw);
    /* DB-User for admin access                                                    */
    define("SK_DB_ADMIN_NAME",$db_admin_name);
    define("SK_DB_ADMIN_PW",$db_admin_pw);
 /* Connect to databse */
    ADOLoadCode($db_type); // Database-type = mysql
    global $skdb;
    /* Initial Connection to database-object $skdb. Should be used for all outputs!  */
    $skdb = NewADOConnection();
    $skdb->Connect($dbhost, $db_user_name, $db_user_pw, $dbname);
    $skdb->debug = false;
	$skdb->EXECUTE("SET NAMES 'UTF8'");
	//$skdb->EXECUTE("SET CHARACTER SET 'latin1'");
	DEBUG_out(1,"bench","Database Connection:".var_export($skdb,1));
/*------------------------------------------------------------------------------*/
/* Special Settings   */
/*------------------------------------------------------------------------------*/

$outputformat="html"; //Output-Format of page;
/* XITAMI Webserver Workaround on Windows*/
//$PHP_SELF=$SCRIPT_NAME;

/* Image-Library default=netpbm  values: 0=default,1=gd*/
$imglib=0;

/* wrap_exec: wrap exec through http_get   default=0; values: 0=no,1=yes*/
$wrap_exec=1;
/* Exec-wrapper default=shell_exec.phpx.php */
$exec_wrapper='shell_exec.phpx.php';

/* Image-Thumbnailsize */
$thumbw=250;//width
$thumbh=250;//height


# handle configs that have REGISTER_GLOBALS turned off 
# (we use $PHP_SELF as the test since it should always be there)
# --------------------------------------------------------------
    if (!isset($PHP_SELF)) {
        include (INCLUDE_PATH."register_globals.php");
    }

DEBUG_out(1,"bench","time:".timer($time_start)." Database connected");

// Report all errors except E_NOTICE
// This is the default value set in php.ini
error_reporting(E_ALL ^ E_NOTICE);

?>