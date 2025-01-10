<?php
/*------------------------------------------------------------------------------*/
/* SITE-Komposer  PHP-Content Management System                                  */
/*------------------------------------------------------------------------------*/

/***************************************
** Title........: Site-Komposer-Include-File2 required if no Cache
** Filename.....: skincludes2.php
** Author.......: Edgar Bueltemeyer
** Last modified: 23.Feb.2005
***************************************/
if($GLOBALS['debug']>0)$t_timer1=timer($time_start);
DEBUG_out(1,"bench","time:".$t_timer1." start file inclusion part2 - Start");

require_once(INCLUDE_PATH."paging.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","paging.php:".($t_timer2-$t_timer1)."ms");

$t_timer1=$t_timer2;
require_once(CLASS_PATH."class.sktranslate.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.sktranslate.inc.php:".($t_timer2-$t_timer1)."ms");

$t_timer1=$t_timer2;
require_once(CLASS_PATH."class.skmenu.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.skmenu.inc.php:".($t_timer2-$t_timer1)."ms");

/*
$t_timer1=$t_timer2;
require_once(CLASS_PATH."layersmenu/layersmenu.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench3","layersmenu/layersmenu.inc.php:".($t_timer2-$t_timer1)."ms");
$t_timer1=$t_timer2;
require_once(CLASS_PATH."layersmenu/template.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench3","layersmenu/template.inc.php:".($t_timer2-$t_timer1)."ms");
$t_timer1=$t_timer2;
require_once(CLASS_PATH."treemenu/treemenu.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench3","treemenu/treemenu.inc.php:".($t_timer2-$t_timer1)."ms");
*/

$t_timer1=$t_timer2;
require_once(CLASS_PATH."class.skcontent_area.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.skcontent_area.inc.php:".($t_timer2-$t_timer1)."ms");
$t_timer1=$t_timer2;
require_once(CLASS_PATH."class.skobject.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.skobject.inc.php:".($t_timer2-$t_timer1)."ms");
$t_timer1=$t_timer2;

require_once(CLASS_PATH."class.skvarstore.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.skvarstore.inc.php:".($t_timer2-$t_timer1)."ms");

$t_timer1=$t_timer2;
require_once(CLASS_PATH."class.skitem.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.skitem.inc.php:".($t_timer2-$t_timer1)."ms");

$t_timer1=$t_timer2;
require_once(CLASS_PATH."class.skadminmenu.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.skadminmenu.inc.php:".($t_timer2-$t_timer1)."ms");

DEBUG_out(1,"bench","time:".timer($time_start)." Files Part 2 included");
?>