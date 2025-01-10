<?php
/*------------------------------------------------------------------------------*/
/* SITE-Komposer  PHP-Content Management System                                    */
/*------------------------------------------------------------------------------*/

/***************************************
** Title........: Site-Komposer-Main-Include
** Filename.....: sk.php
** Author.......: Edgar Bueltemeyer
***************************************/

/*------------------------------------------------------------------------------*/
/* Settings for Site-Komposer                                                     */
/*------------------------------------------------------------------------------*/

    /* Settings for Site--------------------------------------------------------*/
    /* please change for your SITE                                                 */
    /*                                                                             */
    /* Web-Server Address (without http://)                                        */
    define("SERVER_ADDRESS","artconserver.at/");
    /* WWW (SK-Url)                                     */
    define("SK_URL","https://".SERVER_ADDRESS."sk/");
    /* WWW (SKRES-Url)   Url to ressources like images css js               */
    define("SKRES_URL",SK_URL."skres/");
    /* Web-Server Physical Root Path                                            */
    define("WWW_ROOT_PATH", dirname(dirname(__FILE__)) . '/');
    //define("WWW_ROOT_PATH","/www/htdocs/w00b1d8a/artconserver.net/");
    /* This Web-Site�s Physical Root Path with slash at the end                */
    define("ROOT_PATH",WWW_ROOT_PATH."");
    /* Website of your company                                                 */
    define("AGENCY_ADDRESS","http://www.null.at/");
    /* Path of sk-libraries                                                    */
    define("SK_PATH",ROOT_PATH."sk/");
    /* Path of sk-ressources                                                    */
    define("SKRES_PATH",SK_PATH."skres/");
    /* special Path of sk-libraries (for inclusion in other dirs above)         */
    define("LIB_PATH",SK_PATH."sklib/");
    //echo "LIB_PATH: ".LIB_PATH;
    /* DB-Settings -------------------------------------------------------------*/
    /* DB-Host                                                                    */
    $db_host = "localhost";
    /* DB-Type                                                                    */
    $db_type = "mysql";
    /* DB-Name                                                                    */
    $dbname = "d0425fd2";
    /* DB-User for Read-only access                                                */
    $db_user_name = "d0425fd2";
    $db_user_pw = "LB6tpPVCu8Auz69xZKeC";
    /* DB-User for admin access                                                    */
    $db_admin_name = "d0425fd2";
    $db_admin_pw = "LB6tpPVCu8Auz69xZKeC";
    

    /* Image-Library default=netpbm  values: 0=default,1=gd*/
    $imlib=1;

    /* wrap_exec: wrap exec through http_get   default=0; values: 0=no,1=yes*/
    $wrap_exec=0;
    /* Exec-wrapper default=shell_exec.phpx.php */
    $exec_wrapper='shell_exec.phpx.php';

    /* Debug-Function SubWindow    ---------------------------------------------*/
    /* 1 = benchmark, some infos  */
    /* 2 = more infos, objects*/
    /* 3 = more blabla*/
    /* 4 = all blabla*/
    $debug_level=4;
    $debug_ips[]="192.168.1.33";
    $debug_ips[]="178.115.61.49";
    $debug_ips[]="192.168.1.103";
    $debug_ips[]="81.223.242.34";
    $debug_inline=0;
    /* Page Cache-Function         ---------------------------------------------*/
    $cacheable=0;
    // SET THEME
    $theme="default";
    
    // CHOOSE TinyMCE or HtmlArea as Editor
    //
    //HtmlArea
    //define("WYSIWYG_EDITOR",SKRES_PATH.'js/htmlarea/includer.php');
    //TinyMCE
    define("WYSIWYG_EDITOR",SKRES_PATH.'js/tinymce/includer.php');
    //FCKeditor
    //define("WYSIWYG_EDITOR",SKRES_PATH.'js/FCKeditor/includer.php');
    
    /* MOD_REWRITE: Output links as index-1234.html ->index.php?mid=1234  default=0; values: 0=no,1=yes*/
    define("MOD_REWRITE",1);
/*------------------------------------------------------------------------------*/
/* End of Settings                                                              */    
/*------------------------------------------------------------------------------*/
//echo "LIB_PATH: ".LIB_PATH."include/sksettings.php";
require_once(LIB_PATH."include/sksettings.php");

?>