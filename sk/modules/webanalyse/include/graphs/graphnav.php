<?php
require("../stats_main.inc");
require("../stats_vars.inc");
$IPath=CONTENT_PATH.'log/';
include ("../../include/jpgraph/jpgraph.php");
include ("../../include/jpgraph/jpgraph_pie.php");
include ("../../include/jpgraph/jpgraph_pie3d.php");

function ReturnBrowserSer($Year, $Month)
{
    $File = $GLOBALS[IPath]."__tbrowser.ser";
    if (file_exists($File))
    {
        $fd = fopen ($File, "r");
        $contents = fread ($fd, filesize($File));
        fclose ($fd);
        
        $tdata = unserialize($contents);
        $data = $tdata;
        return $data;
    }   
}

$tres = ReturnBrowserSer($_GET["Year"], $_GET["Month"]);
foreach($tres as $key => $value)
{
    if (preg_match ("/opera/i", $key)) 
        $td["Opera"] += $value;
    elseif (preg_match ("/Konqueror/i", $key)) 
        $td["Konqueror"] += $value;
    elseif (preg_match ("/Mac/i", $key)) 
        $td["IE Mac"] += $value;
    elseif (preg_match ("/MSIE/i", $key)) 
        $td["MSIE"] += $value;
    elseif (preg_match ("/Mozilla/i", $key)) 
        $td["Mozilla"] += $value;
}

foreach($td as $key => $value)
{
    $tnb[] = $value;
    $name[] = $key; 
}

// Some data
$data = array(5,27,45,75,90, 12, 14);

// Create the Pie Graph.
$graph = new PieGraph(300,200,"auto");
$graph->SetShadow();

// Create pie plot
$p1 = new PiePlot3d($tnb);
$p1->SetTheme("sand");
$p1->SetCenter(0.4);
$p1->SetAngle(30);
$p1->SetLegends($name);

$graph->Add($p1);
$graph->img->SetAntiAliasing(); 
$graph->Stroke();

?>


