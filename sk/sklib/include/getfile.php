<?
/***************************************
** Title........: SK File Redirector with counter
** Filename.....: getfile.php
** Author.......: Edgar Bueltemeyer
** Notes........:
** Last changed.: 25/04/2005
***************************************/

require_once(CLASS_PATH."class.skobject.inc.php");
$file_obj=new skobject();
$file_obj->get($object_id);
$file_obj->attributes2vars();

$dlcnt=$file_obj->attributes_vars['dlcnt'];
if($dlcnt=='') $dlcnt=1; else $dlcnt++;
$file_obj->attributes_vars['dlcnt']=$dlcnt;

$file_obj->vars2attributes();
$file_obj->update(0);

//DEBUG_window();
Header("Location: ".SITE_URL."content/".$file_obj->attributes['type']."/".$file_obj->attributes['file']);




?>