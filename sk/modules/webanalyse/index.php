<?
require("include/stats_display.inc");
include("include/phpcalendar/calendar.lib.php");
session_start();

$docrelpath="../../../";
if(!array_key_exists('sess_site_id',$_SESSION)) header("Location:".$docrelpath."index.php");
$sk_include_path=substr($_SERVER["SCRIPT_FILENAME"],0,strpos($_SERVER["SCRIPT_FILENAME"],"/sk/")+4);
require_once($sk_include_path."sk.php");
$current_site = new sksite($_SESSION["sess_site_id"]);
define("CONTENT_PATH",ROOT_PATH.$current_site->attributes[dirname].'content/');

set_time_limit (100);
if (!empty($_GET["detail"]))
{
    $detail = $_GET["detail"];
    session_register("detail");
}
elseif (!empty($_SESSION["detail"]))
    $detail = $_SESSION["detail"];

if (!empty($_GET["Day"]))
    $Day = $_GET["Day"];
else    
    $Day = Date("d");

if (!empty($_GET["Month"]))
    $Month = $_GET["Month"];
else    
    $Month = Date("m");

if (!empty($_GET["Year"]))
    $Year = $_GET["Year"];
else    
    $Year = Date("Y");
?>
<html>
<title>Webanalyse <?= $_SERVER["HTTP_HOST"] ?></title>
<link rel="stylesheet" href="./resources/style.css">
<head>
<script language="javascript" type="text/javascript" src="./resources/nav.js"></script>
</head>
<body leftmargin='0' topmargin='0'>
<?
$stats = new Stats_Display();
$stats->DisplayHeader();
$check = $stats->CheckLog();
if (!$check)
    exit;

$stats->DisplayYearTab();
$stats->DisplayMonthTab($Year, $Month);
$stats->LogUsage();
?>
<table border='0' cellspacing='0' cellpadding='0' width='100%'>
    <tr>
        <td width='110' valign='top' bgcolor='8686A7'>
            <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='000000'>
                <tr>
                    <td bgcolor='8686A7' width='100%' align='center' valign='top'>
                        <?= $stats->DisplaySelect() ?>

                        <?
                            echo wrCalendar($PHP_SELF, $Day,$Month,$Year, $stats->lngConf);
                        ?>
                        <?= $stats->DisplayTotalDetails($Year,$Month,$Day) ?>
                    </td>
                </tr>
            </table>    
        </td>
        <td>&nbsp;</td>
        <td valign='top' width='100%' valign='top'>
            <?
            if (empty($detail))
                $stats->DisplayAll($Year, $Month, $Day);
            elseif($detail == "all")
                $stats->DisplayAll($Year, $Month, $Day);
            elseif($detail == "visit")
                $stats->DisplayDetailVisit($Year, $Month, $Day);
            elseif($detail == "pages")
                $stats->DisplayDetailVisitShort($Year, $Month, $Day);
            ?>
        </td>
    </tr>
    <tr>
        <td colspan='3'>
            <?= $stats->DisplayFooter() ?>
        </td>
    </tr>
</table>
</body>
</html>