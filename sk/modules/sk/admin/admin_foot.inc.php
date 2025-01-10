<? if ($framed==0){
$tpl = new rhtemplate;
$tpl->load_file('admin_foot', SKRES_PATH.'skinz/'.$theme.'/tpl/admin_foot.html');   

$themeurl=SKRES_URL.'skinz/'.$theme.'/';
$skurl=SK_URL;
$skresurl=SKRES_URL;
$sitename=$current_site->attributes["name"];
$site_url=$current_site->attributes["site_url"];
$currentsitename=$current_site->attributes["name"];
if($_SESSION["sess_username"]){
    $user_logout="Benutzer: <strong>".$current_user->attributes["name"]."</strong>, eingeloggt seit ".$login_time_min." min&nbsp;&nbsp;&nbsp;";
    $logout_button='<span><a href="'.SK_URL.'modules/sk/admin/index.php?do=logout"><span style="background-color:#dddddd;border: 1px solid;"><strong>>>Logout<<</strong></span></a></span>';

}

$tpl->register('admin_foot', 'themeurl');
$tpl->register('admin_foot', 'skurl');
$tpl->register('admin_foot', 'skresurl'); 
$tpl->register('admin_foot', 'server_address');
$tpl->register('admin_foot', 'site_url');
$tpl->register('admin_foot', 'currentsitename');
$tpl->register('admin_foot', 'adminmenuhtml');
$tpl->register('admin_foot', 'dont_showbox');
$tpl->register('admin_foot', 'Messages');
$tpl->register('admin_foot', 'user_logout');
$tpl->register('admin_foot', 'logout_button');

echo $tpl->pget('admin_foot');
 } // framed

unset($adminmenuhtml); //dont show admin_menu in debug-output

//DEBUG_out(4,"debug4","Globals:".print_array($GLOBALS["GLOBALS"]));
DEBUG_window();
// close Admin-DB-Connection
if(isset($skdbadm)) $skdbadm->close(); ?>
</body>
</html>

