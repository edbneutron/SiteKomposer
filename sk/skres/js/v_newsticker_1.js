/* USE WORDWRAP AND MAXIMIZE THE WINDOW TO SEE THIS FILE
========================================
 V-NewsTicker v1.2
 License : Freeware (Enjoy it!)
 (c)2002 VASIL DINKOV- PLOVDIV, BULGARIA
========================================
 For IE4+, NS4+ & Opera5+
========================================
 Get the NewsTicker script at:
 http://smartmenus.tripod.com/other.html
 and don't wait to get the Great SmartMenus script at:
 http://smartmenus.tripod.com
 LEAVE THESE NOTES PLEASE - delete the comments if you want */

// BUG in Opera:
// If you want to be able to control the body margins
// put the script right after the BODY tag, not in the HEAD!!!

// === 1 === FONT, COLORS, EXTRAS...
v_font='verdana,arial,sans-serif';
v_fontSize='10px';
v_fontSizeNS4='11px';
v_fontWeight='normal';
v_fontColor='#4A49A8';
v_textDecoration='none';
v_fontColorHover='#ff0000';//		| won't work
v_textDecorationHover='underline';//	| in Netscape4
v_bgColor='#DEDFF6';//set [='transparent'] for transparent
v_top=100;//	|
v_left=20;//	| defining
v_width=200;//	| the box
v_height=52;//	|
v_paddingTop=2;
v_paddingLeft=2;
v_timeout=2500;//1000 = 1 second
v_slideSpeed=30;
v_slideDirection=0;//0=down-up;1=up-down
v_pauseOnMouseOver=true;

// === 2 === THE CONTENT - ['href','text','target']
v_content=[
['http://smartmenus.tripod.com/other.html','<img src=strelka.gif align=top width=20 height=11 border=0>Welcome to the V-NewsTicker example page! Presenting the best FREE vertical news scroller ever written.','_blank'],
['http://smartmenus.tripod.com/other.html','<img src=strelka.gif align=top width=20 height=11 border=0>Featuring: support for the most popular browsers, easy setup, small size, pausing, sliding up or down...','_blank'],
['http://smartmenus.tripod.com/','<img src=strelka.gif align=top width=20 height=11 border=0>Don\'t wait and also get the most advanced navigation system for your site- the SmartMenus DHTML menu.','_blank']
];

// THE SERIOUS SCRIPT - PLEASE DO NOT TOUCH
v_nS4=document.layers?1:0;v_iE=document.all&&!window.innerWidth&&navigator.userAgent.indexOf("MSIE")!=-1?1:0;v_oP=navigator.userAgent.indexOf("Opera")!=-1?1:0;v_kN=navigator.userAgent.indexOf("Konqueror")!=-1?1:0;v_count=v_content.length;v_s1=1;v_cur=1;v_d=v_slideDirection?-1:1;v_TIM=0;v_fontSize2=v_nS4&&navigator.platform.toLowerCase().indexOf("win")!=-1?v_fontSizeNS4:v_fontSize;v_canPause=0;function v_getOS(a){return v_iE?document.all[a].style:v_nS4?document.layers['v_container'].document.layers[a]:document.getElementById(a).style};function v_start(){var o=v_getOS('v_'+v_cur);if(!o)return;if(v_iE||v_oP){if(o.pixelTop==v_paddingTop){v_canPause=1;if(v_count>1)v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return};o.pixelTop-=v_d;if(v_oP&&o.visibility.toLowerCase()!='visible')o.visibility='visible';setTimeout("v_start()",v_slideSpeed);return};if(parseInt(o.top)==v_paddingTop){v_canPause=1;if(v_count>1)v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return};o.top=parseInt(o.top)-v_d;setTimeout("v_start()",v_slideSpeed)};function v_slide(){var o=v_getOS('v_'+v_cur);var o2=v_getOS('v_'+(v_cur<v_count?v_cur+1:1));if(!o||!o2)return;if(v_iE||v_oP){if(o.pixelTop==v_paddingTop*2-v_height*v_d){if(v_oP)o.visibility='hidden';o.pixelTop=v_height*v_d;v_cur=v_cur<v_count?v_cur+1:1;v_canPause=1;v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return};o.pixelTop-=v_d;if(v_oP&&o2.visibility.toLowerCase()!='visible')o2.visibility='visible';o2.pixelTop-=v_d;setTimeout("v_slide()",v_slideSpeed);return};if(parseInt(o.top)==v_paddingTop*2-v_height*v_d){o.top=v_height*v_d;v_cur=v_cur<v_count?v_cur+1:1;v_canPause=1;v_TIM=setTimeout("v_canPause=0;v_slide()",v_timeout);return};o.top=parseInt(o.top)-v_d;o2.top=parseInt(o2.top)-v_d;setTimeout("v_slide()",v_slideSpeed)};document.write('<style>.vnewsticker{font-family:'+v_font+';font-size:'+v_fontSize2+';color:'+v_fontColor+';text-decoration:'+v_textDecoration+';font-weight:'+v_fontWeight+'}.vnewsticker:hover{font-family:'+v_font+';font-size:'+v_fontSize2+';color:'+v_fontColorHover+';text-decoration:'+v_textDecorationHover+'}</style>');v_temp='<div '+(v_nS4?"name":"id")+'=v_container style="position:absolute;top:'+v_top+'px;left:'+v_left+'px;width:'+v_width+'px;height:'+v_height+'px;background-color:'+v_bgColor+';layer-background-color:'+v_bgColor+';clip:rect(0,'+v_width+','+v_height+',0);overflow:hidden">';for(v_i=0;v_i<v_count;v_i++)v_temp+='<div '+(v_nS4?"name":"id")+'=v_'+(v_i+1)+' style="position:absolute;top:'+(v_height*v_d)+'px;left:'+v_paddingLeft+'px;width:'+(v_width-v_paddingLeft*2)+'px;height:'+(v_height-v_paddingTop*2)+'px;clip:rect(0,'+(v_width-v_paddingLeft*2)+','+(v_height-v_paddingTop*2)+',0);overflow:hidden'+(v_oP||v_kN?";visibility:hidden":"")+'"><a href="'+v_content[v_i][0]+'" target="'+v_content[v_i][2]+'" class=vnewsticker'+(v_pauseOnMouseOver?" onmouseover=\'if(v_canPause&&v_count>1)clearTimeout(v_TIM)\' onmouseout=\'if(v_canPause&&v_count>1)v_TIM=setTimeout(\"v_canPause=0;v_slide()\","+v_timeout+")\'":"")+'>'+v_content[v_i][1]+'</a></div>';v_temp+='</div>';document.write(v_temp);if(!v_kN)setTimeout("v_start()",1000);if(v_nS4)onresize=function(){location.reload()}