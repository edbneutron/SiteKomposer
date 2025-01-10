
window.onload = init;

var jsReady = false;
var flashMovie = "";

function init(){
	jsReady = true;
}

function isReady(){
	return(jsReady)
}

function getFlashMovie(movieName) {
	if (navigator.appName.indexOf("Microsoft") != -1) {
		return window[movieName];
	} else {
		return document[movieName];
	}
}
function sendToNonverBlaster(value) {
	getFlashMovie(flashMovie).sendToActionScript(value);
}

function registerForJavaScriptCommunication(_flashMovie){
	flashMovie = _flashMovie;
}