<?
// edit_top.html.inc.php

if($this->attributes['type']=="image1"){

$edit_top="<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" align=\"".$this->attributes_vars['align']."\" class=\"\" ><tr><td>";
$edit_bottom="<div id=\"editbutton\">
  <span class=\"smallw\">sort#: ".$this->attributes['sort_nr']."
  von ".$author->attributes["uname"]."
  <a title=\"Objekt editieren\" href=\"JAVASCRIPT:void(0);\""."onClick=\"Mediabox.open('$formtarget".
   "?object_id=".$this->attributes['object_id']."&form_window=1&window_title=".$this->attributes['type']."', 'Objekt editieren', '640 340');return false;\" class=\"small\">
   ".$edit_button_small."</a></span></div></td>
  </tr>
</table>";


}
elseif($this->attributes['type']=="image"){

 switch($this->attributes_vars['align']){
 case "middle":
 case "absmiddle":
 case "top":
 case "bottom":
 case "baseline":
 case "left":
       $style="float:left;";
 break;
 case "right":
       $style="float:right;";
 break;
 default:$style="";
 break;
}
$edit_top="<div id=\"sk_image_edit\" style=\"".$style."\">";
$edit_top.="<div id=\"editbutton\">
  <span class=\"smallw\">sort#: ".$this->attributes['sort_nr']."
  von ".$author->attributes["uname"]."
  <a title=\"Objekt editieren\" href=\"JAVASCRIPT:void(0);\""."onClick=\"Mediabox.open('$formtarget".
   "?object_id=".$this->attributes['object_id']."&form_window=1&window_title=".$this->attributes['type']."', 'Objekt editieren', '640 340');return false;\" class=\"small\">
   ".$edit_button_small."</a></span></div></div>";
$edit_bottom=""; 
}
else
{
$edit_top="<div class=\"editback\" align=\"".$this->attributes_vars['align']."\"  style=\"display:block; border: 1px dotted #000000; white-space: normal;align:".$this->attributes_vars['align'].";\">";

$edit_bottom="<div class=\"editfoot\" style=\"display:inherit;\"align=\"right\"><div class=\"small\">".$this->icon(1,1)."&nbsp;sort#: ".$this->attributes['sort_nr']."
  von <a href=\"#userview?uid=".$author->attributes["uid"]."\">".$author->attributes["uname"].
    "</a> - ge&auml;ndert am ".date("d.m.y",$this->attributes[last_mod])." von ".$editor->attributes["uname"]."
    <a title=\"Objekt editieren\" href=\"JAVASCRIPT:void(0);\""."onClick=\"Mediabox.open('$formtarget".
   "?object_id=".$this->attributes['object_id']."&form_window=1&window_title=".$this->attributes['type']."', 'Objekt editieren', '640 340');return false;\" class=\"small\">
   ".$edit_button_small."</a></div></div></div>";
}

if($this->attributes['type']=="image2"){
   $edit_top.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"\" align=\"".$this->attributes_vars['align']."\" class=\"dotbox\" style=\"align:".$this->attributes_vars['align'].";\"><tr><td>";
   $edit_bottom.="</td></tr></table>";
}

//$edit_bottom.="</div>";
?>