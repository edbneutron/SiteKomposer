function openPictureWindow(imageName,imageWidth,imageHeight,alt,posLeft,posTop,ecard_form,print) {
    windowHeight=Number(imageHeight);
    windowWidth=Number(imageWidth);
    windowHeight+=49;
    windowWidth+=27;
    newWindow = window.open("","ImageWindow","width="+windowWidth+",height="+windowHeight+",left="+posLeft+",top="+posTop);
    newWindow.document.open();
    newWindow.document.write('<html><title>'+alt+'</title><style type="text/css">a.image:LINK,a.image:VISITED {    font-family: Arial, Helvetica, sans-serif; font-size: 10px;    color: #333333;    text-decoration:none;}');
    newWindow.document.write('a.image:HOVER {font-family: Arial, Helvetica, sans-serif; font-size: 10px;color: #333333;text-decoration:underline;}</style>');
    newWindow.document.write('<body bgcolor="#FFFFFF" leftmargin="8" topmargin="8" marginwidth="8" marginheight="8" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px;  ><div align="center">'); //onBlur="self.close()"
    newWindow.document.write('<a href="" class="image" onclick="self.close()"><img src="'+imageName+'" width="'+imageWidth+'" height="'+imageHeight+'" border="0" alt="'+alt+'" ></a><br>'+alt);
    newWindow.document.write('<table width="100%" border="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px;" align="center"><tr>');
    if(ecard_form>"") newWindow.document.write('<td align="left"><a href="JAVASCRIPT:void(0);" class="image" onclick="window.open(\''+ecard_form+'\',\'ecard\',\'width=650,height=600,left=100,top=100\')">send&nbsp;as&nbsp;e-card</a></td>');
    if(print==1) newWindow.document.write('<td align="center"><a href="JAVASCRIPT:void(0);" class="image" onclick="self.print();">drucken</a></td>');
    newWindow.document.write('<td align="right"><a href="JAVASCRIPT:void(0);" class="image" onclick="self.close()">schliessen</a></td>');
    newWindow.document.write('</tr></table>');
    newWindow.document.write('</div></body></html>');
    newWindow.document.close();
    newWindow.focus();
}

function changePic(imageName,imageWidth,imageHeight,alt,posLeft,posTop) {
   var imgsrc;
   imgsrc=imageName;
   imageName=new Image; imageName.src=imgsrc;
   viewerobj=document.getElementById('skimgviewer');

   viewerobj.alt=alt;
   viewerobj.src=imgsrc;
   timer=setTimeout("changeSrc('skimgviewer','"+imgsrc+"')",100);
   viewerobj.width=imageWidth;
   viewerobj.height=imageHeight;
}

function picautoload(){
   if(window.myskpic)
     if(myskpic.src>"") {
        changePic(myskpic.src,myskpic.width,myskpic.height,myskpic.alt);
        //alert("changed! src="+myskpic.src+" "+myskpic.width+"x"+myskpic.height);
        }
}

function openDebugWindow(debug,name) {
    newWindow = window.open("","debugWindow","width=500,height=600,left=0,top=50,status=yes,scrollbars=yes");
    if(newWindow){
    newWindow.document.open();
    newWindow.document.write('<html><title>Debug-Window</title><body bgcolor="#EEEEEE" leftmargin="0" topmargin="0" marginwidth="0" style="font-family: Arial, Helvetica, sans-serif; font-size: 10px;">');
    newWindow.document.write(debug); 
    newWindow.document.write('</body></html>');
    newWindow.document.close();
    self.focus(); }
}

function openPopup(name,title,content,features,bodystyle) {
    newPopWindow = window.open("",title,features);
    newPopWindow.document.open();
    newPopWindow.document.write('<html><title>'+title+'</title><body '+bodystyle+'>');
    newPopWindow.document.write(content); 
    newPopWindow.document.write('</body></html>');
    newPopWindow.document.close();
    newPopWindow.focus();
}

function custom_print() {
    if (document.all) {
        if (navigator.appVersion.indexOf("5.0") == -1) {
            var OLECMDID_PRINT = 6;
            var OLECMDEXECOPT_DONTPROMPTUSER = 2;
            var OLECMDEXECOPT_PROMPTUSER = 1;
            var WebBrowser = "<OBJECT ID=\"WebBrowser1\" WIDTH=0 HEIGHT=0 CLASSID=\"CLSID:8856F961-340A-11D0-A96B-00C04FD705A2\"></OBJECT>";
            document.body.insertAdjacentHTML("beforeEnd", WebBrowser);
            WebBrowser1.ExecWB(6, 2);
            WebBrowser1.outerHTML = "";
        } else {
            self.print();
        }
    } else {
        self.print();
    }
}

function changeDisplay(objID,value) {
   var i,a=changeDisplay.arguments;
   for(i=0;i<(a.length-1);i+=2) document.getElementById(a[i]).style.display=a[i+1];
}
function changeSrc(objID,value) {
   var i,a=changeSrc.arguments;
   for(i=0;i<(a.length-1);i+=2) {
       document.getElementById(a[i]).src=a[i+1] + "?"+Math.random();    
   }
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function MM_showHideLayers() { //v6.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}



function correctPNG() // correctly handle PNG transparency in Win IE 5.5 or higher.
   {
   for(var i=0; i<document.images.length; i++)
      {
    var img = document.images[i]
    var imgName = img.src.toUpperCase()
    if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
       {
     var imgID = (img.id) ? "id='" + img.id + "' " : ""
     var imgClass = (img.className) ? "class='" + img.className + "' " : ""
     var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' "
     var imgStyle = "display:inline-block;" + img.style.cssText 
     if (img.align == "left") imgStyle = "float:left;" + imgStyle
     if (img.align == "right") imgStyle = "float:right;" + imgStyle
     if (img.parentElement.href) imgStyle = "cursor:hand;" + imgStyle    
     var strNewHTML = "<span " + imgID + imgClass + imgTitle
     + " style=\"" + "width:" + img.width + "px; height:" + img.height + "px;" + imgStyle + ";"
       + "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
     + "(src=\'" + img.src + "\', sizingMethod='scale');\"></span>" 
     img.outerHTML = strNewHTML
     i = i-1
       }
      }
   }
<!--[if gte IE 5.5000]>
// if(window.attachEvent) window.attachEvent("onload", correctPNG);


<!--[endif]-->

