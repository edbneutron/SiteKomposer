<?

$tpl = new rhtemplate;
$tpl->load_file('form_top', SKRES_PATH.'skinz/'.$theme.'/tpl/form_top.html');
$title=$window_title;
$themeurl=SKRES_URL.'skinz/'.$GLOBALS['theme'].'/';
$skurl=SK_URL;
$skresurl=SKRES_URL;
$sitename=$current_site->attributes["name"];
$site_url=$current_site->attributes['site_url'];

$tpl->register('form_top', 'themeurl');
$tpl->register('form_top', 'title');
$tpl->register('form_top', 'type');
$tpl->register('form_top', 'skurl');
$tpl->register('form_top', 'skresurl');
$tpl->register('form_top', 'formmenu');
$tpl->register('form_top', 'customer_logo');
$tpl->register('form_top', 'server_address');
$tpl->register('form_top', 'site_url');
$tpl->register('form_top', 'currentsitename');
$tpl->register('form_top', 'dont_showbox');

echo $tpl->pget('form_top');


?>