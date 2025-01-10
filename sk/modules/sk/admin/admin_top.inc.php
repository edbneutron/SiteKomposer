<? 
if(!isset($_GET['do'])) $_GET['do']="";
if (!isset($_SESSION["sess_uid"]) && $_GET['do']!="logout" ) {

session_start();
header ("Ex-pires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");  
}
$sk_include_path=substr($_SERVER["SCRIPT_FILENAME"],0,strpos($_SERVER["SCRIPT_FILENAME"],"/sk/")+4);
require_once($sk_include_path."sk.php");
//include Files Part 2
 require_once(INCLUDE_PATH."skincludes2.php");
if(!isset($framed)) $framed=0;

 
if(!isset($_SESSION["sess_hash"]) && $no_relocate==0) header("Location:".SK_URL."modules/sk/admin/index.php");
//if ($GLOBALS["debug"]>0) $GLOBALS[DEBUG_OUTPUT].="time:".date("D M j G:i:s T Y")."<hr size=0>file running:".$PHP_SELF."<hr size=0>".print_array($_SESSION);
//if ($GLOBALS["debug"]>0) $GLOBALS[DEBUG_OUTPUT].="Globals:".print_array($GLOBALS);
if (isset($sess_uid)){
if(!isset($_SESSION["sess_uid"]) ) header("Location:".SK_URL."modules/sk/admin/index.php");


$current_user=new skuser;
$now=time();
$login_time_min=div($now - $_SESSION["sess_time"],60);
$login_time_sec=($now - $_SESSION["sess_time"]) % 60;

// change current site
if (isset($change_site) && $change_site==1) {
    $sess_site_id=$sel_site_id;
    $_SESSION["sess_site_id"]=$sess_site_id;
    session_register("sess_site_id");
    }

// save current visible menu-layer in session
if(isset($_REQUEST["alvis"]) && $_REQUEST["alvis"] != 0){
   $_SESSION["sess_alvis"]=$_REQUEST["alvis"];
   session_register("sess_alvis");
   }
// set default site


if($_SESSION["sess_site_id"]<1) {
    $site_list=$current_user->getsites(0,3,"A");
    if(is_array($site_list)) {
      foreach($site_list as $lkey=>$lvalue) {
        if(strpos($_SERVER["HTTP_REFERER"],$lvalue[name]))  $sess_site_id=$lvalue[site_id];
      }
      if($sess_site_id==0) $sess_site_id=$site_list[0][site_id];
    }else{
     $output.="User is not Admin!<br>";
      $sess_site_id=$site_list;
      if(strstr($site_list,",")) $sess_site_id=substr($site_list,0,strstr($site_list,",")+1);
    }
    session_register("sess_site_id");
    $_SESSION["sess_site_id"]=$sess_site_id;

    }


$current_site = new sksite($_SESSION["sess_site_id"]);
define("SITE_URL","http://".SERVER_ADDRESS.$current_site->attributes["dirname"]); 
$skdbadm = NewADOConnection();
$skdbadm->Connect($dbhost, $db_admin_name, $db_admin_pw, $dbname);
$skdbadm->debug = false;
$skdbadm->EXECUTE("SET NAMES 'UTF8'");
}
if($framed==0){

    
$tpl = new rhtemplate;
$tpl->load_file('admin_top', SKRES_PATH.'skinz/'.$theme.'/tpl/admin_top.html'); 

$themeurl=SKRES_URL.'skinz/'.$theme.'/';
$skurl=SK_URL;
$skresurl=SKRES_URL;
$sitename=$current_site->attributes["name"];
$site_url=$current_site->attributes["site_url"];
$customer_logo="";

if($_SESSION["sess_site_id"]<>0 && isset($current_user)){
$site_changeform='<select name="sel_site_id">'.$current_user->getsites(0,2,"A",$_SESSION["sess_site_id"]).'</select><input class="small" name="getsite" type="submit" value="&lt;-">&nbsp;';
$site_changeform1='<span><form action="{site_form_action}" method="post"><div><select name="sel_site_id">'.$current_user->getsites(0,2,"A",$_SESSION["sess_site_id"]).'</select><input class="small" name="getsite" type="submit" value="&lt;-">&nbsp;</div><input type="hidden" name="change_site" value="1"></form></span>';
$site_changeform2='<span><a href="#" class="non">Web-Site:</a></span>';
$site_form_action=$_SERVER["SCRIPT_NAME"]."?".$QUERY_STRING;
$currentsitename=$current_site->attributes["name"];
$customer_logo='<a href="'.$site_url.'"><img src="'.$site_url.'res/img/customer_logo.jpg" border="0"></a>';
}

if (isset($_SESSION["sess_hash"])) {
           $adminmenu = new adminmenu();
           $adminmenuhtml=$adminmenu->toolbar(3,3,$_SESSION["sess_alvis"],0,"","relative",0); }

$tpl->register('admin_top', 'themeurl');
$tpl->register('admin_top', 'title1');
$tpl->register('admin_top', 'skurl');
$tpl->register('admin_top', 'skresurl');
$tpl->register('admin_top', 'customer_logo'); 
$tpl->register('admin_top', 'server_address');
$tpl->register('admin_top', 'site_url');
$tpl->register('admin_top', 'site_changeform');
$tpl->register('admin_top', 'site_changeform1');
$tpl->register('admin_top', 'site_changeform2');
$tpl->register('admin_top', 'site_form_action');
$tpl->register('admin_top', 'currentsitename');
$tpl->register('admin_top', 'adminmenuhtml');
$tpl->register('admin_top', 'dont_showbox');

echo $tpl->pget('admin_top');


} // framed?>