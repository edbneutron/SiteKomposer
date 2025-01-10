<?php //*********** Objekt-Link-Output for HTML **************

$object = new skobject(0);
$object->attributes['object_id']=$this->attributes_vars['object_id'];
$object->get();

if($this->attributes_vars['show_ca']==1){
  
  $content_area = new skcontent_area();
  $content_area->content_id=$this->attributes_vars['content_id'];
  $content_area->is_link=1;
  $content_area->get_byid();
  $content_area->build_objectlist();
  $output.=$content_area->display_objects();

}
else{

$output.=$object->display();

}
IF ($editable==1){
$navtree=new sknavtree($object->attributes['parent_node']);

$link1='<a title="gehe zu Originalobjekt" href="'.$docrelpath.$navtree->attributes['path'].$_SERVER["SCRIPT_NAME"]."?".'mid='.$navtree->attributes['id'].'&edit=1">';
$link2='&nbsp;&lt;zu Orig.</a>';
$output.="<br>".$link1.$object->icon(1).$link2;
}

?>