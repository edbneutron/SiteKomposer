<?
require("../stats_main.inc");
require("../stats_vars.inc");
$IPath=CONTENT_PATH.'log/';
require("../../include/jpgraph/jpgraph.php"); 
require("../../include/jpgraph/jpgraph_line.php"); 
require("../../include/jpgraph/jpgraph_bar.php");

function ReturnHourSer($Year, $Month)
{
    $File = $GLOBALS[IPath]."__ttotalhour.ser";
    if (file_exists($File))
    {
        $fd = fopen ($File, "r");
        $contents = fread ($fd, filesize($File));
        fclose ($fd);
        
        $tdata = unserialize($contents);
        $data = $tdata;
    }
    return $data;
}

$tres = ReturnHourSer($_GET["Year"], $_GET["Month"]);
$res = array();//bug fixed!!
for ($i = 0; $i < 23; $i++)
{
    if ($tres[$_GET["Year"].$_GET["Month"].$i] == null)
        $res[] = 0;
    else        
        $res[] = $tres[$_GET["Year"].$_GET["Month"].$i];
}

$graph = new Graph(300,200); 
$graph->SetBox(array(5,12,14),array(0,0,0),true,4,3);
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->SetFrame(true); // No border around the graph
$graph->xaxis->SetTickLabels($a);

$bplot = new BarPlot($res);
$bplot->SetFillColor('orange');
$bplot->SetWidth(1.0);
$graph->Add($bplot);

// Create a red line plot
//$p1 = new LinePlot($res);
//$p1->SetColor("red");
//$p1->SetLegend("frequentation");

// The order the plots are added determines who's ontop
//$graph->Add($p1);

$graph->Stroke();
?>