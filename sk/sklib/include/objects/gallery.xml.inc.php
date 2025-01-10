<? //*********** Gallery-Output in XML-Format using different templates**************

$gallery_area = new skcontent_area($this->attributes_vars['ca_nr'],$this->attributes['parent_node']);

//get variables
foreach ($this->attributes_vars as $key=>$value)  {
        $$key=$value;
        $GLOBALS[$key]=$value;
        }
$gallery_title=$this->attributes_vars['title'];
$gallery_columns=$this->attributes_vars['columns'];
$rows=$this->attributes_vars[rows];
$gallery_descr=$this->attributes['objtext'];
$GLOBALS['gallery_columns']=$gallery_columns;
$GLOBALS[gallery_rows]=$this->attributes_vars[rows];
$GLOBALS['gallery_title']=$gallery_title;
$GLOBALS['gallery_descr']=$gallery_descr;
$GLOBALS['flash_bgc']="0x".substr($this->attributes_vars['bgcolor'],1);
$GLOBALS['flash_frc']="0x".substr($this->attributes_vars['framecolor'],1);
$GLOBALS['flash_txc']="0x".substr($this->attributes_vars['textcolor'],1);
/*
/*$bgcolor=$this->attributes_vars['bgcolor'];
$framecolor=$this->attributes_vars['framecolor'];
$textcolor=$this->attributes_vars['textcolor'];
$GLOBALS[vcolumns]=$this->attributes_vars[vcolumns];
$GLOBALS[bgcolor]=$this->attributes_vars['bgcolor'];
$GLOBALS[framecolor]=$this->attributes_vars['framecolor'];
$GLOBALS[textcolor]=$this->attributes_vars['textcolor'];  */

$tpl = new rhtemplate;
$tpl->load_file('mgallery', SKRES_PATH.'tpl/objects/gallery.'.$this->attributes_vars['type'].'.xml');
DEBUG_out(3,"debug3","gallery:".print_array($this->attributes_vars));

if($this->attributes_vars['type']=="FastGallery") $addsize=18;


IF (count($gallery_area->object_list)>0){
//$output.='<table width="392" border="0" cellpadding="0" cellspacing="8" class="mgallery"><tr>';
    $thiscol=1;
    $mgallery_items=array();
    for($i=0; $i < $gallery_area->objectcount; $i++){
        
        $sk_objects[$i] = new skobject($gallery_area->object_list[$i],$gallery_area->debug);
        $sk_objects[$i]->attributes2vars();
        $mgallery_items[$i]['file']=$sk_objects[$i]->attributes['file'];
        $mgallery_items[$i]['objtext']=$sk_objects[$i]->attributes['objtext'];
        if($mgallery_items[$i]['objtext']=="") $mgallery_items[$i]['objtext']=$sk_objects[$i]->attributes['file'];
        $mgallery_items[$i]['link']=$sk_objects[$i]->attributes_vars['link'];
        $mgallery_items[$i]['width']=$sk_objects[$i]->attributes_vars['width'] + $addsize;
        $mgallery_items[$i]['height']=$sk_objects[$i]->attributes_vars['height'] + $addsize;
        $mgallery_items[$i]['title']=$sk_objects[$i]->attributes_vars['title'];
        $mgallery_items[$i]['artist']=$sk_objects[$i]->attributes_vars['artist'];
        $mgallery_items[$i]['album']=$sk_objects[$i]->attributes_vars['album'];
        $mgallery_items[$i]['year']=$sk_objects[$i]->attributes_vars['year'];
        $mgallery_items[$i]['genre']=$sk_objects[$i]->attributes_vars['genre'];

    }
}

$GLOBALS[imagePath]='content/image/';
$GLOBALS[thumbPath]='content/image/thumbnails/';
$edit_buttons="";
$GLOBALS['mgallery_items']=$mgallery_items;
$GLOBALS['edit_buttons']=$edit_buttons;
$GLOBALS['skres_url']=SKRES_URL;
$GLOBALS['site_url']=SITE_URL;

// register variables
foreach ($this->attributes_vars as $key=>$value)  {
        $tpl->register('mgallery', $key);
        }

$tpl->register('mgallery', 'gallery_columns');
$tpl->register('mgallery', 'gallery_rows');
$tpl->register('mgallery', 'edit_buttons');
$tpl->register('mgallery', 'gallery_title');
$tpl->register('mgallery', 'gallery_descr');
$tpl->register('mgallery', 'flash_bgc');
$tpl->register('mgallery', 'flash_frc');
$tpl->register('mgallery', 'flash_txc');
$tpl->register('mgallery', 'imagePath');
$tpl->register('mgallery', 'thumbPath');
$tpl->register('mgallery', 'skres_url');
$tpl->register('mgallery', 'site_url');
$tpl->parse_loop('mgallery', 'mgallery_items');
$output.=$tpl->pget('mgallery');
             
?>