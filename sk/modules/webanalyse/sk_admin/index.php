<? session_start();

$docrelpath="../../";
$sk_include_path=substr($_SERVER["SCRIPT_FILENAME"],0,strpos($_SERVER["SCRIPT_FILENAME"],"/sk/")+4);
if(!array_key_exists('sess_site_id',$_SESSION)) header("Location:".$docrelpath."sk/admin/index.php");
require_once($sk_include_path."sk.php");
//include Files Part 2
 require_once(INCLUDE_PATH."skincludes2.php");
 $current_user=new skuser;
 //---------------------------------------------------------
    // display Admin-Mode Menu.
    //---------------------------------------------------------
    $adminmenu = new adminmenu();
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">

<head>
<title>Webanalyse</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<script language="JavaScript">
function dhtmlLoadScript(url)
{
   var e = parent.phpmain.document.createElement("script");
   e.src = url;
   e.type="text/javascript";
   parent.phpmain.document.getElementsByTagName("head")[0].appendChild(e);
}
function dhtmlLoadCss(url)
{
   var link = parent.phpmain.document.createElement("link");
   link.rel = "stylesheet";
   link.type = "text/css";
   link.href = url;
   parent.phpmain.document.getElementsByTagName("head")[0].appendChild(link);
}

function displayMenubar() {

 var dochead = document.getElementsByTagName("head")[0];
 var docbody = document.getElementsByTagName("body")[0];

menubar='<? $echo="<link href=\"".SKRES_URL.'skinz/'.$theme."/css/admin.css\" rel=\"stylesheet\" type=\"text/css\">";?>' +
'<? echo ereg_replace("'","\'",ereg_replace("(\r\n|\n|\r)", "",$adminmenu->toolbar(3,78,12,0,$messages,"absolute",0))); 
 ?>';

//timer=setTimeout('a=1',3000);

dhtmlLoadCss("<?echo SKRES_URL.'skinz/'.$theme."/css/admin.css";?>");
//dhtmlLoadCss("<?=$docrelpath?>res/css/common.css");

timer=setTimeout('parent.phpmain.document.body.innerHTML =parent.phpmain.document.body.innerHTML + parent.menubar ;',100);
//parent.phpmain.document.body.innerHTML =parent.phpmain.document.body.innerHTML + parent.menubar;
//parent.phpmain.document.body.appendChild( parent.menubar );

}
</script>
</head>


<frameset border="0" rows="1,*" frameborder="0" >
<frame src="sk_nav.php" name="sknav" frameborder="1" scrolling="no">
    <frame src="../index.php" name="phpmain" 
    OnLoad="dhtmlLoadScript('<?echo SKRES_URL;?>js/standard.js');displayMenubar();" >
    <noframes>
        <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!--<iframe name="content" width="100%" height="8000" src="../index.php"> 
</iframe>-->
<? // echo $adminmenu->toolbar(3,85,12,0,$messages);
?>


</body>
    </noframes>
</frameset>
</html>