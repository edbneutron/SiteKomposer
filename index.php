<?php
/************************************************
* Site-Komposer Index-Page
* 
* by Edgar Bueltemeyer (c) 2005.
* 
* Description:
* This is the starting-point
* for the Site-Komposer-System
*
***********************************************/
// page compression

ob_start();
ob_implicit_flush(0);

if (!isset($_REQUEST["mid"])) {$mid=1;
}else{$mid=$_REQUEST["mid"];}
if (isset($_POST["parent_node"])) $mid=$_POST["parent_node"];
if (isset($_REQUEST["tmv_value"])) $tmv_value=$_REQUEST["tmv_value"];
if (isset($_REQUEST["news_id"])) $news_id=$_REQUEST["news_id"];
$documentpath="";
$docrelpath="";//relative path to site-root

require_once($docrelpath."sk/sk.php"); //load Site Komposer Main-Include
require_once(LIB_PATH."include/skpage.php"); //load Site Komposer Page-Include

//**********************************************
// Not a form-window -- Display PAGE
//**********************************************
if (!$form_window==1): 


//**********************************************    
//              Set Variables 
//**********************************************
        

/* Build the Menus:

treemenu($theme="default",
         $title="",
         $format=0,
         $page_id=0,
         $mindepth=0,
         $onlybranch=0,
         $parent_links=1,
         $exclude_branch_id=0,
         $current_page_id=0,
         $view_all=0)


*/


$Menu = new skmenu();
$Menu->getfromdb();
$BranchParent=$Menu->getBranchParent($GLOBALS[mid]);
//function css_menu($mindepth=0,$maxdepth=10,$onlybranch=0,$parent_links=1,$exclude_branch_id=0,$current_page_id=0,$view_all=0,$sel_branch=0,$parent_id=0){
$css_menu=$Menu->css_menu(0,3,0,1,0,$mid,0,1);
//$css_menu1=$Menu->css_menu(2,2,1,1,0,$mid,0,1,1000);
//$css_menu2=$Menu->css_menu(3,3,1,1,0,$mid,0,1,1003);
//$css_menu3=$Menu->css_menu(3,3,1,1,0,$mid,0,1,1004);
//$bread_crumb=$Menu->bread_crumb($mid,2);

$sk_header=$edit_scripts.$styles.$scripts;  

          $tpl->register('complete', 'document_title');
          $tpl->register('complete', 'styles');
          //$tpl->register('complete', 'main_menu');
          $tpl->register('complete', 'css_menu');
          $tpl->register('complete', 'css_menu1');
          $tpl->register('complete', 'css_menu2');
          $tpl->register('complete', 'css_menu3');
          $tpl->register('complete', 'menu_header');
          $tpl->register('complete', 'bread_crumb');
          $tpl->register('complete', 'edit_on');
          $tpl->register('complete', 'scripts');
          $tpl->register('complete', 'edit_scripts');
          $tpl->register('complete', 'date');
          
          
// END register Variables
require_once(LIB_PATH."include/skpageout.php"); //load Site Komposer Page-Include
          

endif;// Display Page