<?
session_start();

$docrelpath="../../../../";
if(!array_key_exists('sess_site_id',$_SESSION)) header("Location:".$docrelpath."index.php");
$sk_include_path=substr($_SERVER["SCRIPT_FILENAME"],0,strpos($_SERVER["SCRIPT_FILENAME"],"/sk/")+4);
require_once($sk_include_path."sk.php");
$current_site = new sksite($_SESSION["sess_site_id"]);
define("CONTENT_PATH",ROOT_PATH.$current_site->attributes[dirname].'content/');
?>
