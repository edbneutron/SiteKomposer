<?
require("../stats_main.inc");
require("../stats_vars.inc");
$IPath=CONTENT_PATH.'log/';
require("../../include/jpgraph/jpgraph.php"); 
require("../../include/jpgraph/jpgraph_line.php"); 
require("../../include/jpgraph/jpgraph_bar.php");

function ReturnMonthSer()
{
    $File = $GLOBALS[IPath]."__tmonth.ser";
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

$tres = ReturnMonthSer();
$res = array();

for ($i = 1; $i < 13; $i++)
{
    if ($i < 10)
        $i = "0" . $i;
                
    if ($tres[$_GET["Year"].$i] == "")
        $res[] = 0;
    else
        $res[] = $tres[$_GET["Year"].$i];
}

$graph = new Graph(300,200); 
$graph->SetBox(array(12,12,14),array(0,0,0),true,4,3);
$graph->SetScale("textlin");
$graph->SetShadow();
$graph->SetFrame(true); // No border around the graph
$a = $gDateLocale->GetShortMonth();
$graph->xaxis->SetTickLabels($a);

// Création du système de points
$lineplot2=new LinePlot($res);
$lineplot2->SetFillColor("green");

// On rajoute les points au graphique
$graph->Add($lineplot2);

$graph->img->SetAntiAliasing(); 
// Affichage
$graph->Stroke();

?>