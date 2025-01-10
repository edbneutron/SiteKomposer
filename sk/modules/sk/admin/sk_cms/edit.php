<?
session_start();
$docrelpath="../../../../../";
$sk_include_path=substr($_SERVER["SCRIPT_FILENAME"],0,strpos($_SERVER["SCRIPT_FILENAME"],"/sk/")+4);
require_once($sk_include_path."sk.php");
//include Files Part 2
 require_once(INCLUDE_PATH."skincludes2.php");
if(!isset($_SESSION["sess_hash"]) && $no_relocate==0) header("Location:".SK_URL."modules/sk/admin/index.php");

$current_site = new sksite($_SESSION["sess_site_id"]);

$GLOBALS[edit]=1;
//echo "Location:".$current_site->attributes["site_url"]."index.php?edit=1";
header("Location:".$current_site->attributes["site_url"]."index.php?edit=1");

?>
