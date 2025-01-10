<?php
/*------------------------------------------------------------------------------*/
/* SITE-Komposer  PHP-Content Management System                                  */
/*------------------------------------------------------------------------------*/

/***************************************
** Title........: Site-Komposer-Include-File1 required before Cache
** Filename.....: skincludes1.php
** Author.......: Edgar Bueltemeyer
***************************************/

if($GLOBALS['debug']>0)$t_timer1=timer($time_start);
DEBUG_out(1,"bench","time:".$t_timer1." start file inclusion part1 - Start");


require_once(CLASS_PATH."class.skgroup.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.skgroup.inc.php:".($t_timer2-$t_timer1)."ms");

$t_timer1=$t_timer2;
require_once(CLASS_PATH."class.skuser.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.skuser.inc.php:".($t_timer2-$t_timer1)."ms");

$t_timer1=$t_timer2;
require_once(CLASS_PATH."class.sknavtree.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.sknavtree.inc.php:".($t_timer2-$t_timer1)."ms");

$t_timer1=$t_timer2;
include(CLASS_PATH."class.sksite.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.sksite.inc.php:".($t_timer2-$t_timer1)."ms");

$t_timer1=$t_timer2;
include(CLASS_PATH."class.sktemplate.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","class.sktemplate.inc.php:".($t_timer2-$t_timer1)."ms");

$t_timer1=$t_timer2;
include(INCLUDE_PATH."gzdoc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","gzdoc.php:".($t_timer2-$t_timer1)."ms");


$t_timer1=$t_timer2;
/* Load Database Abstraction-Layer*/
include(ADODB_DIR."adodb.inc.php");
if($GLOBALS['debug']>1)$t_timer2=timer($time_start);
DEBUG_out(2,"bench2","adodb.inc.php:".($t_timer2-$t_timer1)."ms");

 
DEBUG_out(1,"bench","time:".timer($time_start)." Files Part 1 included");

?>