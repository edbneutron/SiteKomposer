<?
  global $current_page;
  global $PHP_SELF;
  global $QUERY_STRING;
  global $REQUEST_URI;
  global $edit;
  if ($edit==1) $editurl="&edit=1"; else $editurl="";
  $detail_link=$this->attributes_vars[detail_link];
  $section_id=$this->attributes_vars[section_id];
  
  
$output.='<script language="JavaScript1.2" type="text/javaScript">
/*
========================================
 V-NewsTicker v1.2
 License : Freeware (Enjoy it!)
 (c)2002 VASIL DINKOV- PLOVDIV, BULGARIA
========================================*/
// === 1 === FONT, COLORS, EXTRAS...
v_font=\'verdana,arial,sans-serif\';
v_fontSize=\'10px\';
v_fontSizeNS4=\'11px\';
v_fontWeight=\'normal\';
v_fontColor=\'#4A49A8\';
v_textDecoration=\'none\';
v_fontColorHover=\'#ff0000\';
v_textDecorationHover=\'underline\';
v_bgColor=\'#ffffff\';//set [=transparent] for transparent
v_top=0;//	|
v_left=0;//	| defining
v_width=450;//	| the box
v_height=15;//	|
v_paddingTop=2;
v_paddingLeft=2;
v_timeout=2500;//1000 = 1 second
v_slideSpeed=30;
v_slideDirection=0;//0=down-up;1=up-down
v_pauseOnMouseOver=true;
';

	/* ****************** SQL-Selects to fill data********************* */
	$curr_page = $current_page;
	$num_of_rows_per_page=2;
	if (empty($curr_page)) $curr_page = 1;
	$section_crit="";
	if ($section_id!="%") $section_crit="AND section_id=$section_id";
	$sql_select = "SELECT sk_news.id, title, lead,uid, ndate AS sortdate, DATE_FORMAT(ndate, '%d-%m-%Y') as ndate, name, site_id ".
 				  "FROM sk_news INNER JOIN sk_newssections ON sk_news.section_id = sk_newssections.id ".
	              "WHERE site_id=".$GLOBALS[current_site_id]."  ".$section_crit." order by sortdate desc 
				  ";
	$result1 = $skdb->Execute($sql_select);
	if (!$result1->EOF):
	$result = $skdb->PageExecute($sql_select, $num_of_rows_per_page, $curr_page);
		if ($result == false) $GLOBALS[DEBUG_OUTPUT].= $sql_select."<br>News-DB-select failed! ".$skdb->Errormsg()."<br>";
	 ENDIF;
	$mess_nr=0;
	$output.="// === 2 === THE CONTENT - [\'href\',\'text\',\'target\']
	v_content=[";
	while (!$result->EOF AND !$result == false) {
	$author= new skuser($result->fields[uid]);
	if($mess_nr>0)$output.=",";
	$output.="['".$detail_link."&news_id=".$result->fields["id"].$editurl."','<img src=".SITE_URL."res/img/newsitem.gif align=top width=12 height=11 border=0><i>".$result->fields["ndate"]."</i>&nbsp;<b>".$result->fields["title"]."</b>','']";
	//$output.='messages['.$mess_nr.']="<div class=\'newstext\'>--- '.$result->fields["name"].' ---</div>";';
	//$mess_nr++;
	unset($author);
	$mess_nr++;
 $result->MoveNext();
  }
$output.='];
</script>';

$output.='<script language=JavaScript1.2 src="'.SKRES_URL.'js/v_newsticker_3.js" type="text/javaScript"></script>';
/*$output.='
// THE SERIOUS SCRIPT - PLEASE DO NOT TOUCH
v_nS4=document.layers?1:0;v_iE=document.all&&!window.innerWidth&&navigator.userAgent.indexOf("MSIE")!=-1?1:0;v_oP=navigator.userAgent.indexOf("Opera")!=-1?1:0;v_kN=navigator.userAgent.indexOf("Konqueror")!=-1?1:0;v_count=v_content.length;v_s1=1;v_cur=1;v_d=v_slideDirection?-1:1;v_TIM=0;v_fontSize2=v_nS4&&navigator.platform.toLowerCase().indexOf("win")!=-1?v_fontSizeNS4:v_fontSize;v_canPause=0;function v_getOS(a){return v_iE?document.all[a].style:v_nS4?document.layers[\'v_container\'].document.layers[a]:document.getElementById(a).style};function v_start(){var o=v_getOS(\'v_\'+v_cur);if(!o)return;if(v_iE||v_oP){if(o.pixelTop==v_paddingTop){v_canPause=1;if(v_count>1)v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return};o.pixelTop-=v_d;if(v_oP&&o.visibility.toLowerCase()!=\'visible\')o.visibility=\'visible\';setTimeout("v_start()",v_slideSpeed);return};if(parseInt(o.top)==v_paddingTop){v_canPause=1;if(v_count>1)v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return};o.top=parseInt(o.top)-v_d;setTimeout("v_start()",v_slideSpeed)};function v_slide(){var o=v_getOS(\'v_\'+v_cur);var o2=v_getOS(\'v_\'+(v_cur<v_count?v_cur+1:1));if(!o||!o2)return;if(v_iE||v_oP){if(o.pixelTop==v_paddingTop*2-v_height*v_d){if(v_oP)o.visibility=\'hidden\';o.pixelTop=v_height*v_d;v_cur=v_cur<v_count?v_cur+1:1;v_canPause=1;v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return};o.pixelTop-=v_d;if(v_oP&&o2.visibility.toLowerCase()!=\'visible\')o2.visibility=\'visible\';o2.pixelTop-=v_d;setTimeout("v_slide()",v_slideSpeed);return};if(parseInt(o.top)==v_paddingTop*2-v_height*v_d){o.top=v_height*v_d;v_cur=v_cur<v_count?v_cur+1:1;v_canPause=1;v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return};o.top=parseInt(o.top)-v_d;o2.top=parseInt(o2.top)-v_d;setTimeout("v_slide()",v_slideSpeed)};document.write(\'<style>.vnewsticker{font-family:\'+v_font+\';font-size:\'+v_fontSize2+\';color:\'+v_fontColor+\';text-decoration:\'+v_textDecoration+\';font-weight:\'+v_fontWeight+\'}.vnewsticker:hover{font-family:\'+v_font+\';font-size:\'+v_fontSize2+\';color:\'+v_fontColorHover+\';text-decoration:\'+v_textDecorationHover+\'}</style>\');v_temp=\'<div \'+(v_nS4?"name":"id")+\'=v_container style="position:absolute;top:\'+v_top+\'px;left:\'+v_left+\'px;width:\'+v_width+\'px;height:\'+v_height+\'px;background-color:\'+v_bgColor+\';layer-background-color:\'+v_bgColor+\';clip:rect(0,\'+v_width+\',\'+v_height+\',0);overflow:hidden">\';for(v_i=0;v_i<v_count;v_i++)v_temp+=\'<div \'+(v_nS4?"name":"id")+\'=v_\'+(v_i+1)+\' style="position:absolute;top:\'+(v_height*v_d)+\'px;left:\'+v_paddingLeft+\'px;width:\'+(v_width-v_paddingLeft*2)+\'px;height:\'+(v_height-v_paddingTop*2)+\'px;clip:rect(0,\'+(v_width-v_paddingLeft*2)+\',\'+(v_height-v_paddingTop*2)+\',0);overflow:hidden\'+(v_oP||v_kN?";visibility:hidden":"")+\'"><a href="\'+v_content[v_i][0]+\'" target="\'+v_content[v_i][2]+\'" class=vnewsticker\'+(v_pauseOnMouseOver?" onmouseover=\'if(v_canPause&&v_count>1)clearTimeout(v_TIM)\' onmouseout=\'if(v_canPause&&v_count>1)v_TIM=setTimeout(\"v_canPause=0;v_slide()\","+v_timeout+")\'":"")+\'>\'+v_content[v_i][1]+\'</a></div>\';v_temp+=\'</div>\';document.write(v_temp);if(!v_kN)setTimeout("v_start()",1000);if(v_nS4)onresize=function(){location.reload()}

</script>
'; */



