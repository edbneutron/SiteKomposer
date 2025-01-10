<?

$tpl = new rhtemplate;
$tpl->load_file('form_bottom', SKRES_PATH.'skinz/'.$theme.'/tpl/form_bottom.html');
$title=$window_title;
$themeurl=SKRES_URL.'skinz/'.$theme.'/';
$skurl=SK_URL;
$skresurl=SKRES_URL;
$sitename=$current_site->attributes["name"];
$site_url=$current_site->attributes['site_url'];

if(isset($edit_object->attributes['object_id']))
    $show_object_id="object-id: ".$edit_object->attributes['object_id'];
//if($type!="text" && $type!="text_image")
   $resize='
   <script language="javascript">
   <!--
   // get dimensions of form-div-tag and resize window
   //obj=MM_findObj(\'formsl\');
   obj=document.getElementById(\'formsl\');
   width=obj.offsetWidth;
   height=obj.offsetHeight;
   self.resizeTo(width+30,height+80);
   //alert(\'johoho \' + (width+30) + \' \' +  (height+80));
   window.parent.Mediabox.resize(width+15,height+10);
   
   //if(window.Mediabox) 
   -->
   </script>';
$tpl->register('form_bottom', 'themeurl');
$tpl->register('form_bottom', 'title');
$tpl->register('form_bottom', 'resize');
$tpl->register('form_bottom', 'skurl');
$tpl->register('form_bottom', 'skresurl');
$tpl->register('form_bottom', 'customer_logo');
$tpl->register('form_bottom', 'server_address');
$tpl->register('form_bottom', 'site_url');
$tpl->register('form_bottom', 'show_object_id');
$tpl->register('form_bottom', 'currentsitename');
$tpl->register('form_bottom', 'dont_showbox');

echo $tpl->pget('form_bottom');


?>