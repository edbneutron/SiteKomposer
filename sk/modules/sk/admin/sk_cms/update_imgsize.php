<?
require_once("../admin_top.inc.php");

$current_site_id=$_SESSION["sess_site_id"];
$current_site = new sksite($current_site_id);
define(SITE_URL,$current_site->attributes["site_url"]);
define(SITE_PATH,ROOT_PATH.$current_site->attributes["dirname"]);


if($_POST['action']=="doit") {
echo "Updating Image-Objects.<br>";
// get menu entries of site
$Menu = new skmenu();
$Menu->getfromdb();


$cnt=count($Menu->tree);
$ca=new skcontent_area(1,1); //create new content_area object
//echo print_array($Menu->tree);
$cntr=0;

foreach($Menu->tree as $treekey => $treevalue){

    //get content_area & objects
    $ca->skcontent_area(1,$treevalue['3']);
    $ca->build_objectlist();
    //$listobj= new skobject();

    if(is_array($ca->object_list)) foreach($ca->object_list as $objkey => $obj){
            reset($obj);
            $listobj=new skobject($obj);
            //$listobj->get();
            $listobj->init($obj);
            //$listobj->attributes[attributes]=$obj[attributes];
            $listobj->attributes2vars();

            reset($listobj->attributes_vars);
            /*$listobj->attributes_vars['thumbnail']="1";
            $listobj->attributes_vars['viewer']="1";
            $listobj->attributes_vars['align']="middle";
            if(strpos($listobj->attributes[file],"_1.jpg")) {
               echo "autoload";
               $listobj->attributes_vars['autoload']="1";
               }  */

            if($listobj->attributes[type]=="image") {
            echo "update_imgsize: ".$listobj->attributes[file]." ".$listobj->attributes_vars[width]."x".$listobj->attributes_vars[height]." / ".$listobj->attributes_vars[twidth]."x".$listobj->attributes_vars[theight]."->";
             $listobj->update_imgsize();
            echo "updated! : ".$listobj->attributes_vars[width]."x".$listobj->attributes_vars[height]." / ".$listobj->attributes_vars[twidth]."x".$listobj->attributes_vars[theight]."<br>";
            }

            //reset($listobj->$attributes_vars);
            unset($listobj);
    }
    unset($ca->object_list);

    }

}//if action

?>
Update Image-Size of all image-objects:<br>


<form name="form1" method="post" action="">

 <input name="action" type="hidden" id="action" value="doit">
 <br>
 <label>Make it so
 <input type="submit" name="Submit" value="OK">
 </label>
</form>
<?
include(MODULE_PATH."sk/admin/admin_foot.inc.php");

if ($GLOBALS["debug"]>0) $GLOBALS[DEBUG_OUTPUT].="<div class=\"bench\">time: ".timer($time_start)." <b>FINISHED!</b> page created</div>"; // benchmarking
DEBUG_window();
?>
</body>
</html>