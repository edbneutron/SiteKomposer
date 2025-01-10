
<?php
/***************************************
** Title........: SK XML Output for Objects
** Filename.....: skxmlobj.php
** Author.......: Edgar Bueltemeyer
** Notes........:
** Last changed.: 15/04/2005
***************************************/
require_once(CLASS_PATH."class.skcontent_area.inc.php");
require_once(CLASS_PATH."class.skobject.inc.php");
$object=new skobject();
$object->get($object_id);

//Set output-format to XML
//required files are included automatically

$outputformat="xml";
echo $object->display();




?>