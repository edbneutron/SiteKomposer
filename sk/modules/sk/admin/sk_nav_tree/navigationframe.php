<?
session_start();
$docrelpath="../../../../../";
$sk_include_path=substr($_SERVER["SCRIPT_FILENAME"],0,strpos($_SERVER["SCRIPT_FILENAME"],"/sk/")+4);
require_once($sk_include_path."sk.php");
//include Files Part 2
 require_once(INCLUDE_PATH."skincludes2.php");
if(!isset($_SESSION["sess_hash"]) && $no_relocate==0) header("Location:".SK_URL."modules/sk/admin/index.php");
$alvis="2";
$current_site_id=$_SESSION["sess_site_id"];
$current_site=$_SESSION["sess_site_id"];
$Menu = new skmenu(1);
$Menu->getfromdb();
$tree=$Menu->treemenu("default_g","",1,0,0,0,1,0,0,1);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Navigation Frame</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="<?echo SKRES_URL.'skinz/'.$theme;?>/css/admin.css" type="text/css">

<script language="JavaScript" src="<? echo SKRES_URL;?>js/edit_scripts.js"></script>
<script language="JavaScript" src="<? echo SKRES_URL;?>js/standard.js"></script>
</head>

<body marginwidth="0" marginheight="0" leftmargin="0" topmargin="0" bgcolor="#FFFFFF" class="admin">
<? echo $tree; ?>
<br><p> &nbsp;</p>
<?
//DEBUG_out(1,"debug1","Globals:".print_array($GLOBALS));
DEBUG_window();?>
</body>
</html>