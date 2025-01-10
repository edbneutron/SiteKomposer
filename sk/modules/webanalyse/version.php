<?
require("include/stats_display.inc");
$dsp = new Stats_Display();
?>
<html>
<title>Webanalyse <?= $_SERVER["HTTP_HOST"] ?> Online Check</title>
<link rel="stylesheet" href="./resources/style.css">
<head>
</head>
<body leftmargin='0' topmargin='0'>
<?
if (!empty($_GET["a"]))
	echo "<script>window.close()</script>";
$dsp->Version();
?>
</body>
</html>