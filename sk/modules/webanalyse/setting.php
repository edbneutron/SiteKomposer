<?
require("include/stats_display.inc");
session_start();

$docrelpath="../../../";
$sk_include_path=substr($_SERVER["SCRIPT_FILENAME"],0,strpos($_SERVER["SCRIPT_FILENAME"],"/sk/")+4);
require_once($sk_include_path."sk.php");
$current_site = new sksite($_SESSION["sess_site_id"]);
define(CONTENT_PATH,ROOT_PATH.$current_site->attributes[dirname].'content/');
$dsp = new Stats_Display();
?>
<html>
<title>Webanalyse <?= $_SERVER["HTTP_HOST"] ?> Setting</title>
<link rel="stylesheet" href="./resources/style.css">
<head>
<script language="javascript" type="text/javascript" src="./resources/nav.js"></script>
</head>
<body leftmargin='0' topmargin='0'>
<?
if (!empty($_POST["btn_valid"]))
{
    $dsp->UpdateConf($_POST);
    echo "<script>onloadfct(true)</script>";
}   
elseif(!empty($_POST["btn_apply"]))
{
    $dsp->UpdateConf($_POST);
    echo "<script>onloadfct()</script>";
    $dsp->InitStats();
}
elseif (!empty($_POST["btn_add"]))
    $dsp->AddIpFilter($_POST["ip"]);
elseif (!empty($_GET["action"]))
    $dsp->DelIpFilter($_GET["ip"]);
    
$dsp->DisplayFormConf();
?>
</body>
</html>