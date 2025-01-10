// PHP Layers Menu 2.2.0 (C) 2001-2002 Marco Pratesi (marco at telug dot it)

loaded = 0;
movedlayers = 0;

menuxleftshift = 6;
menuxrightshift = 10;

currentY = 0;
function grabMouse(e) {	// for NS4
	currentY = e.pageY;
}
if (NS4) {
	document.captureEvents(Event.MOUSEDOWN | Event.MOUSEMOVE);
	document.onmousemove = grabMouse;
}

function shutdown() {
	for (i=1; i<=numl; i++) {
		popUpL(listl[i], false);
	}
}
if (NS4) {
	document.onmousedown = shutdown;
} else {
	document.onclick = shutdown;
}

function resizeHandler() {
	if (NS4) {
		window.location.reload();
	}
	shutdown();
	for (i=1; i<=numl; i++) {
		setleft(listl[i], 0);
		settop(listl[i], 0);
	}
//	moveLayers();
	movedlayers = 0;
}
window.onresize = resizeHandler;

function yaresizeHandler() {
	if (window.innerWidth != origWidth || window.innerHeight != origHeight) {
		if (Konqueror2 || Opera5) {
			window.location.reload();	// Opera 5 often fails this
		}
		origWidth  = window.innerWidth;
		origHeight = window.innerHeight;
		resizeHandler();
	}
	setTimeout('yaresizeHandler()', 500);
}
function loadHandler() {
	if (Konqueror2 || Opera) {
		origWidth  = window.innerWidth;
		origHeight = window.innerHeight;
		yaresizeHandler();
	}
}
window.onload = loadHandler;

function setX(menuName) {
	if (father[menuName] != "") {
		if (!Opera5 && !IE4) {
			width0 = lwidth[father[menuName]];
			width1 = lwidth[menuName];
		} else if (Opera5) {
			// Opera 5 stupidly and exaggeratedly overestimates layers widths
			// hence we consider a default value equal to $abscissa_step
			width0 = abscissa_step;
			width1 = abscissa_step;
		} else if (IE4) {
			width0 = getoffsetwidth(father[menuName]);
			width1 = getoffsetwidth(menuName);
		}
		onleft = getoffsetleft(father[menuName]) - width1 + menuxleftshift;
		onright = getoffsetleft(father[menuName]) + width0 - menuxrightshift;
		windowwidth = getwindowwidth();
		windowxoffset = getwindowxoffset();
		if (NS4 && !DOM) {
			windowxoffset = 0;
		}
		if (onleft < windowxoffset && onright + width1 > windowwidth + windowxoffset) {
			if (onright + width1 - windowwidth - windowxoffset > windowxoffset - onleft) {
				onleft = windowxoffset;
			} else {
				onright = windowwidth + windowxoffset - width1;
			}
		}
		if (back[father[menuName]]) {
			if (onleft < windowxoffset) {
				back[menuName] = 0;
			} else {
				back[menuName] = 1;
			}
		} else {
//alert(onright + " - " + width1 + " - " +  windowwidth + " - " + windowxoffset);
			if (onright + width1 > windowwidth + windowxoffset) {
				back[menuName] = 1;
			} else {
				back[menuName] = 0;
			}
		}
		if (back[menuName]) {
			setleft(menuName, onleft);
		} else {
			setleft(menuName, onright);
		}
	}
}

function moveLayerY(menuName, ordinate_margin) {
if (loaded) {
	if (!movedlayers) {
		moveLayers();
		movedlayers = 1;
	}
	if (!NS4) {
		newY = getoffsettop("ref" + menuName);
	} else {
		newY = currentY;
	}
	newY -= ordinate_margin;
	layerheight = getoffsetheight(menuName);
	windowheight = getwindowheight();
	windowyoffset = getwindowyoffset();
	if (newY + layerheight > windowheight + windowyoffset) {
		if (layerheight > windowheight) {
			newY = windowyoffset;
		} else {
			newY = windowheight + windowyoffset - layerheight;
		}
	}
	if (Math.abs(getoffsettop(menuName) - newY) > thresholdY) {
		settop(menuName, newY);
	}
}
}

function popUp(menuName) {
	shutdown();
	setX(menuName);
	do {
		popUpL(menuName,true);
		menuName = father[menuName];
	} while (menuName != "")
}

function popUpL(menuName,on) {
	if (loaded) {
		if (!movedlayers) {
			moveLayers();
			movedlayers = 1;
		}

		if (on) {
//			moveLayers();
			if (DOM) {
				document.getElementById(menuName).style.visibility = "visible";
			} else if (NS4) {
				document.layers[menuName].visibility = "show";
			} else {
				document.all[menuName].style.visibility = "visible";
			}
		} else {
			if (DOM) {
				document.getElementById(menuName).style.visibility = "hidden";
			} else if (NS4) {
				document.layers[menuName].visibility = "hide";
			} else {
				document.all[menuName].style.visibility = "hidden";
			}
		}
	}
}

function setleft(layer,x) {
	if (DOM && !Opera5) {
		document.getElementById(layer).style.left = x + "px";
	} else if (Opera5) {
		document.getElementById(layer).style.left = x;
	} else if (NS4) {
		document.layers[layer].left = x;
	} else {
		document.all[layer].style.pixelLeft = x;
	}
}

function getoffsetleft(layer) {
	var value = 0;
	if (DOM) {	// Mozilla, Konqueror >= 2.2, Opera 5 and 6, IE
			// timing problems with Konqueror 2.1 ?
		object = document.getElementById(layer);
		value = object.offsetLeft;
//alert (object.tagName + " --- " + object.offsetLeft);
		while (object.tagName != "BODY" && object.offsetParent) {
			object = object.offsetParent;
//alert (object.tagName + " --- " + object.offsetLeft);
			value += object.offsetLeft;
		}
	} else if (NS4) {
		value = document.layers[layer].pageX;
	} else {	// IE4 IS SIMPLY A BASTARD !!!
		if (document.all["IE4" + layer]) {
			layer = "IE4" + layer;
		}
		object = document.all[layer];
		value = object.offsetLeft;
		while (object.tagName != "BODY") {
			object = object.offsetParent;
			value += object.offsetLeft;
		}
	}
	return (value);
}

function settop(layer,y) {
	if (DOM && !Opera5) {
		document.getElementById(layer).style.top = y + "px";
	} else if (Opera5) {
		document.getElementById(layer).style.top = y;
	} else if (NS4) {
		document.layers[layer].top = y;
	} else {
		document.all[layer].style.pixelTop = y;
	}
}

function getoffsettop(layer) {
// IE 5.5 and 6.0 behaviour with this function is really strange:
// in some cases, they return a really too large value...
// ... after all, IE is buggy, nothing new
	var value = 0;
	if (DOM) {
		object = document.getElementById(layer);
		value = object.offsetTop;
		while (object.tagName != "BODY" && object.offsetParent) {
			object = object.offsetParent;
			value += object.offsetTop;
		}
	} else if (NS4) {
		value = document.layers[layer].pageY;
	} else {	// IE4 IS SIMPLY A BASTARD !!!
		if (document.all["IE4" + layer]) {
			layer = "IE4" + layer;
		}
		object = document.all[layer];
		value = object.offsetTop;
		while (object.tagName != "BODY") {
			object = object.offsetParent;
			value += object.offsetTop;
		}
	}
	return (value);
}

function setwidth(layer,w) {
	if (DOM) {
		document.getElementById(layer).style.width = w;
	} else if (NS4) {
//		document.layers[layer].width = w;
	} else {
		document.all[layer].style.pixelWidth = w;
	}
}

function getoffsetwidth(layer) {
	var value = 0;
	if (DOM && !Opera) {
		value = document.getElementById(layer).offsetWidth;
		if (isNaN(value)) {
		// e.g. undefined on Konqueror 2.1
			value = abscissa_step;
		}
	} else if (NS4) {
		value = document.layers[layer].document.width;
	} else if (Opera) {
		value = document.getElementById(layer).style.pixelWidth;
	} else {	// IE4 IS SIMPLY A BASTARD !!!
		if (document.all["IE4" + layer]) {
			layer = "IE4" + layer;
		}
		value = document.all[layer].offsetWidth;
	}
	return (value);
}

function setheight(layer,h) {	// unused, not tested
	if (DOM) {
		document.getElementById(layer).style.height = h;
	} else if (NS4) {
//		document.layers[layer].height = h;
	} else {
		document.all[layer].style.pixelHeight = h;
	}
}

function getoffsetheight(layer) {
	var value = 0;
	if (DOM && !Opera) {
		value = document.getElementById(layer).offsetHeight;
		if (isNaN(value)) {
		// e.g. undefined on Konqueror 2.1
			value = 25;
		}
	} else if (NS4) {
		value = document.layers[layer].document.height;
	} else if (Opera) {
		value = document.getElementById(layer).style.pixelHeight;
	} else {	// IE4 IS SIMPLY A BASTARD !!!
		if (document.all["IE4" + layer]) {
			layer = "IE4" + layer;
		}
		value = document.all[layer].offsetHeight;
	}
	return (value);
}

function getwindowwidth() {
	var value = 0;
	if ((DOM && !IE) || Konqueror || Opera) {
		value = top.innerWidth;
	} else if (NS4) {
		value = document.width;
	} else {	// IE
		if (document.documentElement && document.documentElement.clientWidth) {
			value = document.documentElement.clientWidth;
		} else if (document.body) {
			value = document.body.clientWidth;
		}
	}
	if (isNaN(value)) {
		value = top.innerWidth;
	}
	return (value);
}

function getwindowxoffset() {
	var value;
	if ((DOM && !IE) || NS4 || Konqueror || Opera) {
		value = window.pageXOffset;
	} else {	// IE
		if (document.documentElement && document.documentElement.scrollLeft) {
			value = document.documentElement.scrollLeft;
		} else if (document.body) {
			value = document.body.scrollLeft;
		}
	}
	return (value);
}

function getwindowheight() {
	var value = 0;
	if ((DOM && !IE) || NS4 || Konqueror || Opera) {
		value = top.innerHeight;
	} else {	// IE
		if (document.documentElement && document.documentElement.clientHeight) {
			value = document.documentElement.clientHeight;
		} else if (document.body) {
			value = document.body.clientHeight;
		}
	}
	if (isNaN(value)) {
		value = top.innerHeight;
	}
	return (value);
}

function getwindowyoffset() {
	var value;
	if ((DOM && !IE) || NS4 || Konqueror || Opera) {
		value = window.pageYOffset;
	} else {	// IE
		if (document.documentElement && document.documentElement.scrollTop) {
			value = document.documentElement.scrollTop;
		} else if (document.body) {
			value = document.body.scrollTop;
		}
	}
	return (value);
}

function fixieflm(menuName) {
	if (DOM) {
		setwidth(menuName, "100%");
	} else {	// IE4 IS SIMPLY A BASTARD !!!
		document.write("</div>");
		document.write("<div id=\"IE4" + menuName + "\" style=\"position: relative; width: 100%; visibility: visible;\">");
	}
}

