function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);


function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function MM_dragLayer(objName,x,hL,hT,hW,hH,toFront,dropBack,cU,cD,cL,cR,targL,targT,tol,dropJS,et,dragJS) { //v4.01
  //Copyright 1998 Macromedia, Inc. All rights reserved.
  var i,j,aLayer,retVal,curDrag=null,curLeft,curTop,IE=document.all,NS4=document.layers;
  var NS6=(!IE&&document.getElementById), NS=(NS4||NS6); if (!IE && !NS) return false;
  retVal = true; if(IE && event) event.returnValue = true;
  if (MM_dragLayer.arguments.length > 1) {
    curDrag = MM_findObj(objName); if (!curDrag) return false;
    if (!document.allLayers) { document.allLayers = new Array();
      with (document) if (NS4) { for (i=0; i<layers.length; i++) allLayers[i]=layers[i];
        for (i=0; i<allLayers.length; i++) if (allLayers[i].document && allLayers[i].document.layers)
          with (allLayers[i].document) for (j=0; j<layers.length; j++) allLayers[allLayers.length]=layers[j];
      } else {
        if (NS6) { var spns = getElementsByTagName("span"); var all = getElementsByTagName("div"); 
          for (i=0;i<spns.length;i++) if (spns[i].style&&spns[i].style.position) allLayers[allLayers.length]=spns[i];}
        for (i=0;i<all.length;i++) if (all[i].style&&all[i].style.position) allLayers[allLayers.length]=all[i]; 
    } }
    curDrag.MM_dragOk=true; curDrag.MM_targL=targL; curDrag.MM_targT=targT;
    curDrag.MM_tol=Math.pow(tol,2); curDrag.MM_hLeft=hL; curDrag.MM_hTop=hT;
    curDrag.MM_hWidth=hW; curDrag.MM_hHeight=hH; curDrag.MM_toFront=toFront;
    curDrag.MM_dropBack=dropBack; curDrag.MM_dropJS=dropJS;
    curDrag.MM_everyTime=et; curDrag.MM_dragJS=dragJS;
    curDrag.MM_oldZ = (NS4)?curDrag.zIndex:curDrag.style.zIndex;
    curLeft= (NS4)?curDrag.left:(NS6)?parseInt(curDrag.style.left):curDrag.style.pixelLeft; 
    if (String(curLeft)=="NaN") curLeft=0; curDrag.MM_startL = curLeft;
    curTop = (NS4)?curDrag.top:(NS6)?parseInt(curDrag.style.top):curDrag.style.pixelTop; 
    if (String(curTop)=="NaN") curTop=0; curDrag.MM_startT = curTop;
    curDrag.MM_bL=(cL<0)?null:curLeft-cL; curDrag.MM_bT=(cU<0)?null:curTop-cU;
    curDrag.MM_bR=(cR<0)?null:curLeft+cR; curDrag.MM_bB=(cD<0)?null:curTop+cD;
    curDrag.MM_LEFTRIGHT=0; curDrag.MM_UPDOWN=0; curDrag.MM_SNAPPED=false; //use in your JS!
    document.onmousedown = MM_dragLayer; document.onmouseup = MM_dragLayer;
    if (NS) document.captureEvents(Event.MOUSEDOWN|Event.MOUSEUP);
  } else {
    var theEvent = ((NS)?objName.type:event.type);
    if (theEvent == 'mousedown') {
      var mouseX = (NS)?objName.pageX : event.clientX + document.body.scrollLeft;
      var mouseY = (NS)?objName.pageY : event.clientY + document.body.scrollTop;
      var maxDragZ=null; document.MM_maxZ = 0;
      for (i=0; i<document.allLayers.length; i++) { aLayer = document.allLayers[i];
        var aLayerZ = (NS4)?aLayer.zIndex:parseInt(aLayer.style.zIndex);
        if (aLayerZ > document.MM_maxZ) document.MM_maxZ = aLayerZ;
        var isVisible = (((NS4)?aLayer.visibility:aLayer.style.visibility).indexOf('hid') == -1);
        if (aLayer.MM_dragOk != null && isVisible) with (aLayer) {
          var parentL=0; var parentT=0;
          if (NS6) { parentLayer = aLayer.parentNode;
            while (parentLayer != null && parentLayer.style.position) {             
              parentL += parseInt(parentLayer.offsetLeft); parentT += parseInt(parentLayer.offsetTop);
              parentLayer = parentLayer.parentNode;
          } } else if (IE) { parentLayer = aLayer.parentElement;       
            while (parentLayer != null && parentLayer.style.position) {
              parentL += parentLayer.offsetLeft; parentT += parentLayer.offsetTop;
              parentLayer = parentLayer.parentElement; } }
          var tmpX=mouseX-(((NS4)?pageX:((NS6)?parseInt(style.left):style.pixelLeft)+parentL)+MM_hLeft);
          var tmpY=mouseY-(((NS4)?pageY:((NS6)?parseInt(style.top):style.pixelTop) +parentT)+MM_hTop);
          if (String(tmpX)=="NaN") tmpX=0; if (String(tmpY)=="NaN") tmpY=0;
          var tmpW = MM_hWidth;  if (tmpW <= 0) tmpW += ((NS4)?clip.width :offsetWidth);
          var tmpH = MM_hHeight; if (tmpH <= 0) tmpH += ((NS4)?clip.height:offsetHeight);
          if ((0 <= tmpX && tmpX < tmpW && 0 <= tmpY && tmpY < tmpH) && (maxDragZ == null
              || maxDragZ <= aLayerZ)) { curDrag = aLayer; maxDragZ = aLayerZ; } } }
      if (curDrag) {
        document.onmousemove = MM_dragLayer; if (NS4) document.captureEvents(Event.MOUSEMOVE);
        curLeft = (NS4)?curDrag.left:(NS6)?parseInt(curDrag.style.left):curDrag.style.pixelLeft;
        curTop = (NS4)?curDrag.top:(NS6)?parseInt(curDrag.style.top):curDrag.style.pixelTop;
        if (String(curLeft)=="NaN") curLeft=0; if (String(curTop)=="NaN") curTop=0;
        MM_oldX = mouseX - curLeft; MM_oldY = mouseY - curTop;
        document.MM_curDrag = curDrag;  curDrag.MM_SNAPPED=false;
        if(curDrag.MM_toFront) {
          eval('curDrag.'+((NS4)?'':'style.')+'zIndex=document.MM_maxZ+1');
          if (!curDrag.MM_dropBack) document.MM_maxZ++; }
        retVal = false; if(!NS4&&!NS6) event.returnValue = false;
    } } else if (theEvent == 'mousemove') {
      if (document.MM_curDrag) with (document.MM_curDrag) {
        var mouseX = (NS)?objName.pageX : event.clientX + document.body.scrollLeft;
        var mouseY = (NS)?objName.pageY : event.clientY + document.body.scrollTop;
        newLeft = mouseX-MM_oldX; newTop  = mouseY-MM_oldY;
        if (MM_bL!=null) newLeft = Math.max(newLeft,MM_bL);
        if (MM_bR!=null) newLeft = Math.min(newLeft,MM_bR);
        if (MM_bT!=null) newTop  = Math.max(newTop ,MM_bT);
        if (MM_bB!=null) newTop  = Math.min(newTop ,MM_bB);
        MM_LEFTRIGHT = newLeft-MM_startL; MM_UPDOWN = newTop-MM_startT;
        if (NS4) {left = newLeft; top = newTop;}
        else if (NS6){style.left = newLeft; style.top = newTop;}
        else {style.pixelLeft = newLeft; style.pixelTop = newTop;}
        if (MM_dragJS) eval(MM_dragJS);
        retVal = false; if(!NS) event.returnValue = false;
    } } else if (theEvent == 'mouseup') {
      document.onmousemove = null;
      if (NS) document.releaseEvents(Event.MOUSEMOVE);
      if (NS) document.captureEvents(Event.MOUSEDOWN); //for mac NS
      if (document.MM_curDrag) with (document.MM_curDrag) {
        if (typeof MM_targL =='number' && typeof MM_targT == 'number' &&
            (Math.pow(MM_targL-((NS4)?left:(NS6)?parseInt(style.left):style.pixelLeft),2)+
             Math.pow(MM_targT-((NS4)?top:(NS6)?parseInt(style.top):style.pixelTop),2))<=MM_tol) {
          if (NS4) {left = MM_targL; top = MM_targT;}
          else if (NS6) {style.left = MM_targL; style.top = MM_targT;}
          else {style.pixelLeft = MM_targL; style.pixelTop = MM_targT;}
          MM_SNAPPED = true; MM_LEFTRIGHT = MM_startL-MM_targL; MM_UPDOWN = MM_startT-MM_targT; }
        if (MM_everyTime || MM_SNAPPED) eval(MM_dropJS);
        if(MM_dropBack) {if (NS4) zIndex = MM_oldZ; else style.zIndex = MM_oldZ;}
        retVal = false; if(!NS) event.returnValue = false; }
      document.MM_curDrag = null;
    }
    if (NS) document.routeEvent(objName);
  } return retVal;
}


function frage(){
if (confirm("Sind Sie sicher?") == true)
  {
    document.editform.action.value ="delete"
    document.editform.submit();
    _close();
    return true;
  } else {
  return false;}
}

function frage_s(varname,varvalue){
if (confirm("Sind Sie sicher?") == true)
  {
    document.editform.action.value ="delete";
    if(varname>'') {
     obj=eval("document.editform."+varname);
     obj.value=varvalue;
    }
    document.editform.submit();
    return true;
  } else {
  return false;}
}

function frage_f(form,varname,varvalue){
if (confirm("Sind Sie sicher?") == true)
  {
    formobj=eval("document."+form);         
    if(formobj.action) formobj.action.value=varvalue;
    if(varname>'') {
     obj=eval("document."+form+"."+varname);
     obj.value=varvalue;
    }
    formobj.submit();
    return true;
  } else {
  return false;}
}

function _close()
{
timer=setTimeout('window.close()',500);
}

function change_filesrc(resize)
{
  picsrc='file://' + document.editform.file.value;
  if (picsrc > 'file://')
  { document.picture.src=picsrc

  }  //confirm(document.picture.src);

  if (resize!='no') timer=setTimeout('resize()',100);
}

function resize()
{
 var xadd,yadd;
 if(window.document.picture) xadd=document.picture.width-120;
 if(window.document.picture) yadd=document.picture.height-120;
 if (xadd <0) {xadd=0}
 if (yadd <0) {yadd=0}
 if (xadd >282) {xadd=282}
 if (yadd >160) {yadd=160}
self.resizeTo(650+xadd,550+yadd);
}

function change_imgsrc(image,file) {
  picsrc='file://' + eval('document.editform.'+file+'.value');
  if (picsrc > 'file://')
  { 
  obj = eval('document.images.'+image+'.src');
  //confirm(picsrc+'\n'+obj+'\n'+image);
  eval('document.images.'+image).src = picsrc
  }

} // end-function change_imgsrc
function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}

}

function ToggleDisplay(oButton, oItems,img1,img2) {
    oItems=MM_findObj(oItems);
     if (oItems.style.display == "none") {
          oItems.style.display = "block";
          oButton.src = img1;
     }     else {
          oItems.style.display = "none";
          oButton.src = img2;
     }

     return false;
}