<?//*********** Overview of Subtree for HTML **************


//get variables
foreach ($this->attributes_vars as $key=>$value)  {
        $$key=$value;
        $GLOBALS[$key]=$value;
        }

$gallery_title=$this->attributes_vars['title'];
$gallery_columns=$this->attributes_vars['columns'];
$gallery_descr=$this->attributes['objtext'];
$GLOBALS['gallery_columns']=$gallery_columns;
$GLOBALS['gallery_title']=$gallery_title;
$GLOBALS['gallery_descr']=$gallery_descr;
$tpl = new rhtemplate;

$nrpics=1;
$picpath=SITE_URL."content/image/"."thumbnails/";
if ($this->attributes_vars[picpath]>"") $picpath=$this->attributes_vars[picpath];
if ($this->attributes_vars[nrpics]>1) $nrpics=$this->attributes_vars[nrpics];
$scale=100;
if($this->attributes_vars[scale]) $scale=$this->attributes_vars[scale];

DEBUG_out(3,"bench3","overview: nrpics:".$this->attributes_vars[nrpics]." scale: ".$this->attributes_vars[scale]);

// Decide which type of gallery to show

IF ($editable==1) {
   $tpl->load_file('mgallery', SITE_PATH.'res/tpl/gallery/'.$this->attributes_vars['template']);
   }else{
switch($this->attributes_vars['type']){
    case "FlashNav": // does not exist yet.....
         $tpl->load_file('mgallery', SKRES_PATH.'tpl/objects/gallery.'.$this->attributes_vars['type'].'.html');
         $nohtml=1;
    break;
    default:
        $tpl->load_file('mgallery', SITE_PATH.'res/tpl/gallery/'.$this->attributes_vars['template']);
    break;
}
}


// get menu entries to show
$Menu = new skmenu();
$Menu->getfromdb();
$subtree=$Menu->getSubTree($this->attributes_vars[selected_page]);
unset($subtree[0]); //delete this parent entry
//$output.=print_array($subtree);
$coll_obj_list=array();
$cnt=count($subtree);
$ca=new skcontent_area(1,1); //create new content_area object

$cntr=0;
//for($cntr=0;$cntr<$cnt;$cntr++){
foreach($subtree as $treekey => $treevalue){

    // set some variables like title,...
    // only if depth greater than mindepth
    if($treevalue['0']>=$this->attributes_vars[mindepth] && $treevalue['0']<=$this->attributes_vars[maxdepth]) {

    $coll_obj_list[$cntr]['id']=$treevalue['3'];
    $coll_obj_list[$cntr]['title']=$treevalue['1'];
    $coll_obj_list[$cntr]['link']=$treevalue['2'];
    //get content_area & objects
    $ca->skcontent_area(1,$coll_obj_list[$cntr]['id']);
    $ca->build_objectlist();
    $listobj= new skobject();
    $image_set=0;$text_set=0;
    //$output.="#".$cntr;

    if(is_array($ca->object_list))foreach($ca->object_list as $objkey => $obj){
            $listobj->init($obj);
            $listobj->attributes['attributes']=$obj[attributes];
            $listobj->attributes2vars();
            if($obj[type]=="image" && $image_set < $nrpics) {
            if($image_set>=1)$cntr++;
                                   $coll_obj_list[$cntr]['image']=$obj['file'];
                                   $coll_obj_list[$cntr]['twidth']=($listobj->attributes_vars['twidth']/100)*$scale;
                                   $coll_obj_list[$cntr]['theight']=($listobj->attributes_vars['theight']/100)*$scale;
                                   $image_set++;

            if($obj[type]=="text" && $text_set < $nrpics) { $coll_obj_list[$cntr]['text']=$obj['objtext']; $text_set=1;}

            }
            if($image_set==$nrpics) break;
    }
    unset($ca->object_list);

    } //if
    if($treevalue['0']==($this->attributes_vars[maxdepth]+1) && $image_set < $nrpics){
     //$output.="#".$cntr." ".$treevalue['3']." ".$treevalue['0']." ".($this->attributes_vars[maxdepth]+1)."<br>";
    //get content_area & objects from navigation-items under maxdepth
    $ca->skcontent_area(1,$treevalue['3']);
    $ca->build_objectlist();
    $listobj= new skobject();
    //$output.=print_array($ca->object_list);
    if(is_array($ca->object_list))
    foreach($ca->object_list as $objkey => $obj){
            $listobj->init($obj);
            $listobj->attributes['attributes']=$obj[attributes];
            $listobj->attributes2vars();
            if($obj[type]=="image" && $image_set == 0) {
                                   
                                   $coll_obj_list[$cntr-1]['image']=$obj['file'];
                                   $coll_obj_list[$cntr-1]['twidth']=($listobj->attributes_vars['twidth']/100)*$scale;
                                   $coll_obj_list[$cntr-1]['theight']=($listobj->attributes_vars['theight']/100)*$scale;
                                   $image_set=1;
                                   }
            if($obj[type]=="text" && $text_set == 0) { $coll_obj_list[$cntr-1]['text']=$obj['objtext']; $text_set=1;}
            if($image_set==1) break;

    }
    unset($ca->object_list);

    } // if elseif
    if($treevalue['0']>=$this->attributes_vars[mindepth] && $treevalue['0']<=$this->attributes_vars[maxdepth]) $cntr++;
}

DEBUG_out(3,"debug3","Overview: coll_obj_list:".print_array($coll_obj_list));

// Page-Navigation
$start=0;
$perpage=$this->attributes_vars[perpage];
$oricntr=$cntr;
$nextpage=0;

if($perpage != "alle" || $perpage == "") {

    $pages=div($cntr,$perpage);        
    $nextpage=1;
    $page=$GLOBALS['ovpage'];
    if($page=="")$page=1;
    $start=($perpage * $page)-$perpage; 
    $cntr=$perpage * ($page);
    if($cntr>$oricntr) $cntr=$oricntr;
    if($cntr>=$oricntr) $nextpage=0;
    if($page>1 || $nextpage==1) $navigation=1;
    //$output.="page ".$page." start ".$start." perpage ".$perpage." cntr ".$cntr;
    }
    
IF ($cntr>0 && $nohtml!=1){

    $thiscol=1;
    $mgallery_items=array();
    $ci=0; $rowcnt=1;
    for($i=$start; $i<$cntr; $i++){
    
        if($coll_obj_list[$i]['image']>"") {

          $mgallery_items[$ci]['objectt']=$coll_obj_list[$i]['title'];
          $mgallery_items[$ci]['image']="<img class=\"skimage\" src=\"".$picpath.$coll_obj_list[$i]['image']."\" width=\"".$coll_obj_list[$i]['twidth']."\" height=\"".$coll_obj_list[$i]['theight']."\" align=\"center\" border=\"0\" alt=\"".htmlspecialchars($coll_obj_list[$i]['title'])."\" />";
          $mgallery_items[$ci]['linkurl']=$coll_obj_list[$i]['link'];

          $mgallery_items[$ci]['object']="<a href=\"".$coll_obj_list[$i]['link']."\" id=\"overview\">";
          $mgallery_items[$ci]['object'].="<img class=\"skimage\" src=\"".$picpath.$coll_obj_list[$i]['image']."\" width=\"".$coll_obj_list[$i]['twidth']."\" height=\"".$coll_obj_list[$i]['theight']."\" align=\"center\" border=\"0\" alt=\"".htmlspecialchars($coll_obj_list[$i]['title'])."\" />";
          $mgallery_items[$ci]['object'].="<br />".$coll_obj_list[$i]['title']."</a>";
        }else{
          $mgallery_items[$ci]['objectt']=$coll_obj_list[$i]['title'];;
          $mgallery_items[$ci]['image']="";
          $mgallery_items[$ci]['linkurl']=$coll_obj_list[$i]['link'];
          $mgallery_items[$ci]['object']="<a href=\"".$coll_obj_list[$i]['link']."\" id=\"overview\">".$coll_obj_list[$i]['title']."</a>";

         }
        $thiscol+=1;
        if($thiscol>$columns){

        $mgallery_items[$ci]['row'].='
        </tr>
        ';
        /*if($rowcnt==5){
           $mgallery_items[$ci]['row'].='<tr><td height="10" colspan="'.$columns.'" class="mgalleryspace">&nbsp;</td>
           </tr>';
           $mgallery_items[$ci]['row'].='<tr><td height="10" colspan="'.$columns.'" class="mgalleryspace">&nbsp;</td>
           </tr>';
           $rowcnt=0;
           }*/

          if($this->attributes_vars['multitable']==1) {
             $mgallery_items[$ci]['row'].='</table><table><tr>';
          }else{
             $mgallery_items[$ci]['row'].='<tr>'; 
          }
        $thiscol=1;
        $rowcnt++;
        }else{$mgallery_items[$ci]['row']="";}
        $ci++;
    }
}
    
//$output.=print_array($mgallery_items);


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
$tpl->register('mgallery', 'object_id');
$tpl->register('mgallery', 'skres_url');
$tpl->register('mgallery', 'site_url');
$tpl->parse_loop('mgallery', 'mgallery_items');
$output.=$tpl->pget('mgallery');

if($navigation==1){
//Page next,previous buttons
$output.="<div id=\"ovnav\">";
if($this->attributes_vars[prevb]=="") $this->attributes_vars[prevb]="previous";
if($this->attributes_vars[nextb]=="") $this->attributes_vars[nextb]="next";
if($editable==1) $edit="&edit=1";
if($page>1) $output.="<div id=\"prevb\" style=\"float:left;clear:left;\"><a href=\"".SITE_URL."index.php?mid=".$GLOBALS['mid']."&ovpage=".($page-1).$edit."\" id=\"overview\">".$this->attributes_vars[prevb]."</a></div>";
if($nextpage==1) $output.="<div id=\"nextb\" style=\"float:right;clear:both;\"><a href=\"".SITE_URL."index.php?mid=".$GLOBALS['mid']."&ovpage=".($page+1).$edit."\" id=\"overview\">".$this->attributes_vars[nextb]."</a></div>";
$output.="</div>";
}
//$output.=$Menu->treemenu($this->attributes_vars[theme],"",1,$GLOBALS['mid'],$this->attributes_vars[mindepth],$this->attributes_vars[onlybranch],0,0,$GLOBALS['mid'],$this->attributes_vars[view_all]);
?>