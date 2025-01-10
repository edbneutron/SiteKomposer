<? //*********** Gallery-Output for HTML (first try)**************

$gallery_area = new skcontent_area($this->attributes_vars['ca_nr'],$this->attributes['parent_node']);
$GLOBALS['lighboxnr']=$this->attributes_vars['ca_nr'];
//get variables
foreach ($this->attributes_vars as $key=>$value)  {
        $$key=$value;
        $GLOBALS[$key]=$value;
        }
$GLOBALS['flash_bgc']="0x".substr($this->attributes_vars['bgcolor'],1);
$GLOBALS['flash_frc']="0x".substr($this->attributes_vars['framecolor'],1);
$GLOBALS['flash_txc']="0x".substr($this->attributes_vars['textcolor'],1);
$gallery_title=$this->attributes_vars['title'];
$gallery_columns=$this->attributes_vars['columns'];
$gallery_descr=$this->attributes['objtext'];
$GLOBALS['gallery_columns']=$gallery_columns;
$GLOBALS['gallery_title']=$gallery_title;
$GLOBALS['gallery_descr']=$gallery_descr;
$tpl = new rhtemplate;
// Decide which type of gallery to show

IF ($editable==1) {
   if($this->attributes_vars['type']=="" || strlen($this->attributes_vars['template'])==0 || $this->attributes_vars['type']=="SmoothGallery"){
        //$tpl->load_file('mgallery', SITE_PATH.'res/tpl/gallery/'.$this->attributes_vars['template']);
        $tpl->load_file('mgallery', SITE_PATH.'res/tpl/gallery/default.html');
   }else{
	$tpl->load_file('mgallery', SITE_PATH.'res/tpl/gallery/'.$this->attributes_vars['template']);   
        //$tpl->load_file('mgallery', SITE_PATH.'res/tpl/gallery/default.html');
   }
   
}else{
	if(strlen($this->attributes_vars['template'])==0) $this->attributes_vars['template']="default.html";
    switch($this->attributes_vars['type']){
        case "SimpleViewer":
        case "streamingplayer":
	case "musicplayer":
        case "mp3player":
        case "mp3playersmall":
        case "FastGallery":
        case "jpgrotator":
             $tpl->load_file('mgallery', SKRES_PATH.'tpl/objects/gallery.'.$this->attributes_vars['type'].'.html');
             $nohtml=1;
        break;
        case "SmoothGallery":
            $tpl->load_file('mgallery', SITE_PATH.'res/tpl/gallery/'.$this->attributes_vars['template']);
        break;
        default:
            $tpl->load_file('mgallery', SITE_PATH.'res/tpl/gallery/'.$this->attributes_vars['template']);
        break;
    }
}


DEBUG_out(3,"debug3","gallery:".print_array($this->attributes_vars));

IF (count($gallery_area->object_list)>0 && $nohtml!=1){
//$output.='<table width="392" border="0" cellpadding="0" cellspacing="8" class="mgallery"><tr>';
    $thiscol=1;
    $mgallery_items=array();
    for($i=0; $i < $gallery_area->objectcount; $i++){
        
        $sk_objects[$i] = new skobject($gallery_area->object_list[$i],$gallery_area->debug);
        //complete object display
        $mgallery_items[$i]['object']=$sk_objects[$i]->display($editable);
        //image-filename
        $mgallery_items[$i]['file']=SITE_URL."content/image/".$sk_objects[$i]->attributes['file'];
        //image-link
        $mgallery_items[$i]['link']=$sk_objects[$i]->attributes_vars['link'];
        //image-link-target
        $mgallery_items[$i]['target']=$sk_objects[$i]->attributes_vars['target'];
        //image-thumbnailfilename
        if($sk_objects[$i]->attributes_vars[thumbnail]==1)
        $mgallery_items[$i]['thumbfile']=SITE_URL."content/image/".'thumbnails/'.$sk_objects[$i]->attributes['file'];
        //image-title
        $mgallery_items[$i]['objtext']=$sk_objects[$i]->attributes['objtext'];
        
        $thiscol+=1;
        if($thiscol>$columns){

        $mgallery_items[$i]['row'].='
        </tr>
        <tr> 
        <td height="1" colspan="'.$columns.'" class="mgalleryspace"><img src="'.SKRES_URL.'img/spacer.gif" width="1" height="3"></td>
          </tr>
        ';
          if($this->attributes_vars['multitable']==1) {
             $mgallery_items[$i]['row'].='</table><table><tr>';
          }else{
             $mgallery_items[$i]['row'].='<tr>'; 
          }
        $thiscol=1;
        }else{$mgallery_items[$i]['row']="";}
    }
}
  

 IF ($editable==1) {
     $edit_buttons="<tr><td colspan=\"".$columns."\" align=\"right\"><a href=\"#\"".
     "onClick=\"MM_openBrWindow('$formtarget?identifier=".$this->attributes_vars['ca_nr']."&parent_node=".$this->attributes['parent_node'].
     "&sort_nr=".($gallery_area->last_sort_nr+10)."&form_window=1','objectprop','scrollbars=yes,toolbar=no,width=700,height=600');return false\" class=\"small\">Media-Gallery Objekt".$add_button_small."</a>".
     "</td></tr>";
 }else{$edit_buttons="";}

 // register variables
foreach ($this->attributes_vars as $key=>$value)  {
        $tpl->register('mgallery', $key);
        }

$GLOBALS['mgallery_items']=$mgallery_items;
$GLOBALS['edit_buttons']=$edit_buttons;
$GLOBALS['align']=$this->attributes_vars['align'];
$GLOBALS['autostart']=$this->attributes_vars['autostart'];
$GLOBALS['object_id']=$this->attributes['object_id'];
$GLOBALS['skres_url']=SKRES_URL;
$GLOBALS['site_url']=SITE_URL;
$tpl->register('mgallery', 'gallery_columns');
$tpl->register('mgallery', 'align');
$tpl->register('mgallery', 'autostart');
$tpl->register('mgallery', 'edit_buttons'); 
$tpl->register('mgallery', 'gallery_title');
$tpl->register('mgallery', 'gallery_descr');
$tpl->register('mgallery', 'flash_bgc');
$tpl->register('mgallery', 'flash_frc');
$tpl->register('mgallery', 'flash_txc');
$tpl->register('mgallery', 'object_id');
$tpl->register('mgallery', 'skres_url');
$tpl->register('mgallery', 'site_url');
$tpl->parse_loop('mgallery', 'mgallery_items');
$output.=$tpl->pget('mgallery');
$GLOBALS['lighboxnr']="";             
?>