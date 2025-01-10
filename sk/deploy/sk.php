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
    define("SERVER_ADDRESS","w005df6d.dd2706.kasserver.com/christian-ecker.com/");
    /* WWW (SK-Url)                                     */
    define("SK_URL","http://".SERVER_ADDRESS."sk/");
    /* WWW (SKRES-Url)   Url to ressources like images css js               */
    define("SKRES_URL","http://w005df6d.dd2706.kasserver.com/skres/");
    /* Web-Server Physical Root Path                                            */
    define("WWW_ROOT_PATH","/www/htdocs/w005df6d/");
    /* This Web-Site´s Physical Root Path with slash at the end                */
    define("ROOT_PATH",WWW_ROOT_PATH."christian-ecker.com/");
    /* Website of your company                                                 */
    define("AGENCY_ADDRESS","http://www.null.at/");
    /* Path of sk-libraries                                                    */
    define("SK_PATH",ROOT_PATH."sk/");
    /* Path of sk-ressources                                                    */
    define(SKRES_PATH,WWW_ROOT_PATH."skres/");
    /* special Path of sk-libraries (for inclusion in other dirs above)         */
    define(LIB_PATH,WWW_ROOT_PATH."sklib/");
    /* DB-Settings -------------------------------------------------------------*/
    /* DB-Host                                                                    */
    $db_host = "localhost";
    /* DB-Type                                                                    */
    $db_type = "mysql";
    /* DB-Name                                                                    */
    $dbname = "d0032d4b";
    /* DB-User for Read-only access                                                */
    $db_user_name = "d0032d4b";
    $db_user_pw = "ceckdb";
    /* DB-User for admin access                                                    */
    $db_admin_name = "d0032d4b";
    $db_admin_pw = "ceckdb";
    

    /* Image-Library default=netpbm  values: 0=default,1=gd*/
    $imglib=0;

    /* wrap_exec: wrap exec through http_get   default=0; values: 0=no,1=yes*/
    $wrap_exec=0;
    /* Exec-wrapper default=shell_exec.phpx.php */
    $exec_wrapper='shell_exec.phpx.php';

    /* Debug-Function SubWindow    ---------------------------------------------*/
    /* 1 = benchmark, some infos  */
    /* 2 = more infos, objects*/
    /* 3 = more blabla*/
    /* 4 = all blabla*/
    $debug_level=0;
    $debug_ips[]="192.168.1.33";
    $debug_ips[]="192.168.1.37";
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

require_once(LIB_PATH."include/sksettings.php");
?>