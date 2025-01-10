<?
session_start();
$docrelpath="../../../";
$sk_include_path=substr($_SERVER["SCRIPT_FILENAME"],0,strpos($_SERVER["SCRIPT_FILENAME"],"/sk/")+4);
require_once($sk_include_path."sk.php");

 
?>
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="<?echo SKRES_URL.'skinz/'.$theme;?>/css/admin.css" rel="stylesheet" type="text/css">
<? echo"<script src=\"".$docrelpath."res/js/standard.js\" language=\"JavaScript\"> </script>";?>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="400" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td><a href="../../sk/admin/index.php" target="_top">zur&uuml;ck zur Administration</a></td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>
