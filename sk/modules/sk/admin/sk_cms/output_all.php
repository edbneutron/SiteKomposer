<?
session_start();
$docrelpath="../../../../../";
$sk_include_path=substr($_SERVER["SCRIPT_FILENAME"],0,strpos($_SERVER["SCRIPT_FILENAME"],"/sk/")+4);
require_once($sk_include_path."sk.php");
//include Files Part 2
 require_once(INCLUDE_PATH."skincludes2.php");
if(!isset($_SESSION["sess_hash"]) && $no_relocate==0) header("Location:".SK_URL."modules/sk/admin/index.php");

$current_site_id=$_SESSION["sess_site_id"];
$current_site = new sksite($current_site_id);
define(SITE_URL,$current_site->attributes["site_url"]);
define(SITE_PATH,ROOT_PATH.$current_site->attributes["dirname"]);
$Menu = new skmenu(0);
$Menu->getfromdb();
$tree=$Menu->treemenu("xp_folders","",1,0,0);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Complete Output</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="<?echo SKRES_URL.'skinz/'.$theme."/css/admin.css";?>" rel="stylesheet" type="text/css">
<link href="<?echo SKRES_URL.'skinz/'.$theme."/css/common.css";?>" rel="stylesheet" type="text/css">
<script language="JavaScript" src="<? echo SKRES_URL;?>js/edit_scripts.js"></script>
<script src="<? echo SKRES_URL;?>js/standard.js" language="JavaScript"> </script>
</head>

<body>
<a href="../index.php" target="_top">zur&uuml;ck zur Administration</a><br>
<H1>Website: <? echo $current_site->attributes["name"] ?></H1><p>
<? 
reset($Menu->tree);
while (list($index, $submenu) = each($Menu->tree) ) {    
    ?>

    
<table width="550" border="1" cellpadding="0" cellspacing="0" class="thinbox">
<tr>
    <td colspan="2">
    <?
    echo "Seite: "."<font size=4>";
    for($i=1;$i<$submenu[0];$i++) echo "&nbsp;/-";
    echo $submenu[1]."</font><br>";
    
    ?> </td>
</tr>
  <tr>
    <td width="400" valign="top">
    <?
    $ca1=new skcontent_area(1,$submenu[3]);
    echo $ca1->display_objects()."<p>";
    ?>&nbsp;
    </td>
    <td width="150" valign="top">
    <?
    $ca2=new skcontent_area(2,$submenu[3]);
    echo $ca2->display_objects()."<p>";
    ?>&nbsp;
    </td>
  </tr>
</table>

<?
    }


if ($GLOBALS["debug"]>0) $GLOBALS[DEBUG_OUTPUT].="<div class=\"bench\">time: ".timer($time_start)." <b>FINISHED!</b> page created</div>"; // benchmarking
DEBUG_window();?>
</body>
</html>