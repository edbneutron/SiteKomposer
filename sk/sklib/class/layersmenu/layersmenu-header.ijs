<!-- {package_name} {version} {copyright} {author} -->

<script language="JavaScript" type="text/javascript">
<!--
{browser_detection}
// -->
</script>

<script language="JavaScript" type="text/javascript" src="{libwww}layersmenu.js"></script>

<script language="JavaScript" type="text/javascript">
<!--

var thresholdY = {thresholdY};
var abscissa_step = {abscissa_step};

listl = new Array();
{listl}
var numl = {numl};

father = new Array();
for (i=1; i<={nodes_count}; i++) {
	father["L" + i] = "";
}
{father}

lwidth = new Array();
var lwidthdetected = 0;

function moveLayers() {
	if (!lwidthdetected) {
		for (i=1; i<=numl; i++) {
			lwidth[listl[i]] = getoffsetwidth(listl[i]);
		}
		lwidthdetected = 1;
	}
{moveLayers}
}

back = new Array();
for (i=1; i<={nodes_count}; i++) {
	back["L" + i] = 0;
}

// -->
</script>

<!-- {package_name} {version} {copyright} {author} -->
