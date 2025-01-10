<?
require("./include/stats_main.inc");
require("./include/stats_unique.inc");

Class Stats_Display extends process
{
    function Stats_Display($Graph = true)
    {
        $this->Process('./',CONTENT_PATH);
        $this->IPath=CONTENT_PATH.'log/';
    }

    function DisplayAll($Year, $Month, $Day)
    {
        $UrlTopY = $this->WsOverUrl("Top", $Year);
        $UrlTopM = $this->WsOverUrl("Top", $Year.$Month);
        $UrlTopD = $this->WsOverUrl("Top", $Year.$Month.$Day);
        ?>
            <table border='0' width='100%' cellspacing='0' cellpadding='0'>
                <tr>
                    <td valign='top'>
                        <table border='0' cellspacing='0' cellpadding='0' width='100%'>
                            <tr>
                                <td width='70%' valign='top' align='center'>
                                <font class='normalblack'><b><?= DSP_ALL_DETAILS ?></b></font><br>
                                        <?= $this->DisplayDetailDay($Year, $Month, $Day)?>
                                    <table border='0' cellspacing='0' cellpadding='0' width='100%'>
                                        <tr>
                                            <td colspan='5' align='center' valign='top'><font class='normalblack'><b><?= DSP_ALL_TOPT ?><br> <?= DSP_ALL_BY ?> <a href='<?=$UrlTopY?>'><?= DSP_ALL_YEAR ?></a> - <a href='<?=$UrlTopM?>'><?= DSP_ALL_MONTH ?></a> - <a href='<?=$UrlTopD?>'><?= DSP_ALL_DAY ?></a></b></font></td>
                                        </tr>
                                        <tr>
                                            <td valign='top'><?= $this->DisplayTopHost($Year,$Month, $Day) ?></td>
                                            <td valign='top'>&nbsp;</td>
                                            <td valign='top'><?= $this->DisplayTopReferer($Year, $Month, $Day) ?><br></td>
                                            <td valign='top'>&nbsp;</td>
                                            <td valign='top'><?= $this->DisplayTopPage($Year, $Month, $Day) ?></td>
                                        </tr>
                                    </table><br>    
                                    <table border='0' cellspacing='0' cellpadding='0' width='100%'>
                                        <tr>    
                                            <td valign='top'><?= $this->DisplayTopCountry($Year, $Month, $Day) ?><br></td>
                                            <td valign='top' width='100%'>&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table> 
<!-- Start of Additional Code #2 -->
                                        <tr><td>
                                            <table border='0'><tr><td valign='top'>
            <?php
            require("include/stats_words.inc");
            $wrds = new StatsWords();
            $wrds->displayWords();
            echo "</td><td valign='top'>";
            //$wrds->displayExceptions(true,15,1);
            ?>
                                            </td></tr></table></td></tr>
<!-- End of Additional Code #2 -->                                  </table>
                                </td>
                                <?if ($this->dspgConf == "show")
                                {
                                    ?>
                                <td>&nbsp;</td>
                                <td valign='top'>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='000000'>
                                    <tr bgcolor='ffffff'>
                                        <td align='center' width='100%'><font class='normalblack'><b><?= DSP_ALL_VISITBYDAY ?> <b><?= $this->ReturnMonth($Month) ?></b></font></td>
                                    </tr>
                                    <tr bgcolor='ffffff'>
                                        <td align='center'><? echo "<img src='./include/graphs/graphday.php?Year=$Year&Month=$Month&Day=$Day' border=0 align=center width=300 height=200>"; ?></td>
                                    </tr>
                                </table>
                                <table border='0' cellspacing='1' cellpadding='0' bgcolor='000000' width='100%'>
                                    <tr><td bgcolor='green' class='smallwhite'>&nbsp;&nbsp;</td><td class='smallwhite' width='30%'>:&nbsp;&nbsp;<?= DSP_ALL_GRAPHALL ?></td>
                                        <td bgcolor='red' class='smallwhite'>&nbsp;&nbsp;</td><td class='smallwhite' width='30%'>:&nbsp;&nbsp;<?= DSP_ALL_GRAPHWITHREF ?></td>
                                        <td bgcolor='blue' class='smallwhite'>&nbsp;&nbsp;</td><td class='smallwhite' width='30%'>:&nbsp;&nbsp;<?= DSP_ALL_GRAPHWITHNOREF ?></td></tr>
                                </table>
                                <a href="#" onclick="ToggleDisplay(Daybutton, 'layerMore')" class='small2'><img src="./resources/images/iconPlus.gif" id="Daybutton" border="0" align='left'><b><?= DSP_ALL_MOREGFX ?></b></b></a>
                                <div id="layerMore" style="display: none;">
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='000000'>
                                    <tr bgcolor='ffffff'>
                                        <td align='center' width='100%'><font class='normalblack'><b><?= DSP_ALL_VISITPERHOUR ?></b></font></td>
                                    </tr>
                                    <tr bgcolor='ffffff'>
                                        <td align='center'><? echo "<img src='./include/graphs/graphour.php?Year=$Year&Month=$Month&Day=$Day' border=0 align=center width=300 height=200>"; ?></td>
                                    </tr>
                                </table>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='000000'>
                                    <tr bgcolor='ffffff'>
                                        <td align='center' width='100%'><font class='normalblack'><?= DSP_ALL_BESTBRO ?></b></font></td>
                                    </tr>
                                    <tr bgcolor='ffffff'>
                                        <td align='center'><? echo "<img src='./include/graphs/graphnav.php?Year=$Year&Month=$Month&Day=$Day' border=0 align=center width=300 height=200>"; ?></td>
                                    </tr>
                                </table>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='000000'>
                                    <tr bgcolor='ffffff'>
                                        <td align='center' width='100%' colspan='2'><font class='normalblack'><?= DSP_ALL_CNXON ?> <b><?= $Year ?></b></font></td>
                                    </tr>
                                    <tr bgcolor='ffffff'>
                                        <td colspan='2' align='center'><? echo "<img src='./include/graphs/graphyear.php?Year=$Year' border=0 align=center width=300 height=200>"; ?></td>
                                    </tr>
                                </table>
                                </div>
                                </td>
                                <?}?>
                            </tr>
                        </table>
                    </td>
                </tr>       
            </table>
        <?
    }
        
    function DisplayDetailVisit($Year, $Month, $Day)
    {
        ?>
            <table border='0' width='100%' cellspacing='0' cellpadding='0'>
                <tr>
                    <td valign='top'>
                        <table border='0' cellspacing='0' cellpadding='0' width='100%'>
                            <tr>
                                <td width='70%' valign='top' align='center'>
                                <font class='normalblack'><b><?= DSP_ALL_DETAILS ?></b></font><br>
                                        <?= $this->DisplayVisit($Year, $Month, $Day)?>
                                </td>
                                <?if ($this->dspgConf == "show")
                                {
                                    ?>
                                <td>&nbsp;</td>
                                <td valign='top'>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='000000'>
                                    <tr bgcolor='ffffff'>
                                        <td align='center' width='100%'><font class='normalblack'><b><?= DSP_ALL_VISITBYDAY ?> <b><?= $this->ReturnMonth($Month) ?></b></font></td>
                                    </tr>
                                    <tr bgcolor='ffffff'>
                                        <td align='center'><? echo "<img src='./include/graphs/graphday.php?Year=$Year&Month=$Month&Day=$Day' border=0 align=center width=300 height=200>"; ?></td>
                                    </tr>
                                </table>
                                <table border='0' cellspacing='1' cellpadding='0' bgcolor='000000' width='100%'>
                                    <tr><td bgcolor='green' class='smallwhite'>&nbsp;&nbsp;</td><td class='smallwhite' width='30%'>:&nbsp;&nbsp;<?= DSP_ALL_GRAPHALL ?></td>
                                        <td bgcolor='red' class='smallwhite'>&nbsp;&nbsp;</td><td class='smallwhite' width='30%'>:&nbsp;&nbsp;<?= DSP_ALL_GRAPHWITHREF ?></td>
                                        <td bgcolor='blue' class='smallwhite'>&nbsp;&nbsp;</td><td class='smallwhite' width='30%'>:&nbsp;&nbsp;<?= DSP_ALL_GRAPHWITHNOREF ?></td></tr>
                                </table>
                                <a href="#" onclick="ToggleDisplay(Daybutton, layerMore)" class='small2'><img src="./resources/images/iconMoins.gif" id="Daybutton" border="0" align='left'><b><?= DSP_ALL_MOREGFX ?></b></b></a>
                                <div id='layerMore'>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='000000'>
                                    <tr bgcolor='ffffff'>
                                        <td align='center' width='100%'><font class='normalblack'><b><?= DSP_ALL_VISITPERHOUR ?></b></font></td>
                                    </tr>
                                    <tr bgcolor='ffffff'>
                                        <td align='center'><? echo "<img src='./include/graphs/graphour.php?Year=$Year&Month=$Month&Day=$Day' border=0 align=center width=300 height=200>"; ?></td>
                                    </tr>
                                </table>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='000000'>
                                    <tr bgcolor='ffffff'>
                                        <td align='center' width='100%'><font class='normalblack'><?= DSP_ALL_BESTBRO ?></b></font></td>
                                    </tr>
                                    <tr bgcolor='ffffff'>
                                        <td align='center'><? echo "<img src='./include/graphs/graphnav.php?Year=$Year&Month=$Month&Day=$Day' border=0 align=center width=300 height=200>"; ?></td>
                                    </tr>
                                </table>
                                <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='000000'>
                                    <tr bgcolor='ffffff'>
                                        <td align='center' width='100%' colspan='2'><font class='normalblack'><?= DSP_ALL_CNXON ?> <b><?= $Year ?></b></font></td>
                                    </tr>
                                    <tr bgcolor='ffffff'>
                                        <td colspan='2' align='center'><? echo "<img src='./include/graphs/graphyear.php?Year=$Year' border=0 align=center width=300 height=200>"; ?></td>
                                    </tr>
                                </table>
                                </div>
                                </td>
                                <?}?>
                            </tr>
                        </table>
                    </td>
                </tr>       
            </table>
        <?
    }

    function DisplaySelect()
    {
        ?>
            <table border='0' cellpadding='0' cellspacing='1' width='100%' bgcolor='B0B1CC'>
                <tr bgcolor='9899BC'>
                    <td colspan='3'><font class='smallgrey' align='center'><b><?= DSP_SEL_MODE ?></b></font></td>
                </tr>
                <tr bgcolor='9899BC'>
                    <td colspan='3' class='smallgrey' align='left'>
                    <?
                    echo "<ul>";
                    echo "<li><a href='" . $this->WsOverUrl("detail", "all") . "' class='small2'>".DSP_SEL_MODEALL."</a>";
                    echo "<li><a href='" . $this->WsOverUrl("detail", "visit") . "' class='small2'>".DSP_SEL_MODEDETAIL."</a>";
                    ?>
                    </td>
                </tr>
            </table>
            <br>
        <?
    }
    
    function DisplayHeader()
    {
        if ($this->checkupdate == true)
        {
            $check = $this->CheckVersion();
            $prv = explode("=", $check);
            $prv = explode("|", $prv[1]);
            
            $version = $prv[0];
            $url = $prv[1];
        }
        /*if ($this->Version != $version)
            $dsp = "<a href='$url' target='_blank'><img src='./resources/images/up2date.gif' border='0' alt='Update available'></a>";*/
        ?>
            <table border='0' cellspacing='0' cellpadding='0' width='100%'>
                <tr bgcolor='8686A7'>
                    <td width='350'><a href="<?= $_SERVER['PHP_SELF'] ?>"><img src='./resources/images/wa_log.gif' border='0'></a>
                    <?= $dsp ?>
                    </td>
                    <td align='right' bgcolor='8686A7'>
                    <font class='bigwhite'><?= $_SERVER["HTTP_HOST"] ?></font>
                    <table border='0' cellspacing='0' cellpadding='0'>
                        <tr bgcolor='8686A7'>
                            <td><IMG SRC="./resources/images/ico/bullet.gif" BORDER=0 ALT="Settings">&nbsp;<a href="javascript:MM_openBrWindow('./setting.php','Setting', 'toolbar=no,scrollbars=no,resizable=no,width=430,height=260')" class='small2'><?= DSP_HEADER_SETT ?></a>&nbsp;&nbsp;</td>
                            <td><IMG SRC="./resources/images/ico/bullet.gif" BORDER=0 ALT="Settings">&nbsp;<a href="javascript:MM_openBrWindow('./about.php','About', 'toolbar=no,scrollbars=no,resizable=no,width=370,height=220')" class='small2'><?= DSP_HEADER_ABOUT ?></a>&nbsp;</td>
                        </tr>
                    </table>    
                    </td>
                </tr>
            </table>
        <?
    }
    
    function DisplayFooter()
    {
        ?>
            <table border='0' cellspacing='0' cellpadding='0' width='100%'>
                <tr>
                    <td class='normalblack' align='center'>-=| CopyLeft <a href='http://www.nanobody.net' target='_blank' class='small2'><b>Nanobody 2003 </b></a>|=-</td>
                </tr>
            </table>
        <?
    }
    
    function DisplayYearTab()
    {
        $this->tYear = $this->UnSerializeLog($this->SerFileYear);
        if (!empty($this->tYear))
        {
        ?>
            <table border='0' cellpadding='0' cellspacing='1' width='100%' bgcolor='B0B1CC'>
                <tr bgcolor='9899BC'>
                    <td width='107' class='smallgrey' bgcolor='9899BC' align='center'><b><?= DSP_YEART_AV ?></b>&nbsp;</td>
                    <?
                    foreach ($this->tYear as $key => $value)
                    {
                       if ($key != '1970')
                            echo "<td width='35'>&nbsp;<a href='" . $_SERVER['PHP_SELF'] . "?Year=$key' class='small2'>" . $key . "</a>&nbsp;</td>";
                    }
                    ?>
                    <td>&nbsp;</td>
                </tr>
                <tr bgcolor='9899BC'>
                    <td width='107' class='smallwhite' align='center' bgcolor='9899BC'></td>
                    <?
                    foreach ($this->tYear as $key => $value)
                    {
                       if ($key != '1970')
                           echo "<td bgcolor='9899BC' class='verysmallgrey' align='center'>&nbsp;" . $value . "&nbsp;</td>";
                    }
                    ?>
                    <td>&nbsp;</td>
                </tr>       
            </table>
        <?
        }
    }
    
    function DisplayMonthTab($Year, $Month)
    {
        $this->tMonth = $this->UnSerializeLog($this->SerFileMonth);
        if (!empty($this->tMonth))
        {
        ?>
            <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='9899BC'>
                <tr>
                    <td width='107' class='smallgrey' bgcolor='9899BC' align='center'><b><?= DSP_MONTHT_AV ?></b>&nbsp;</td>
                    <?
                    for ($i = 1; $i < 13; $i++)
                    {
                        if ($i < 10)
                            $i = "0" . $i;
                        
                        if (empty($this->tMonth[$Year.$i]))
                            $count = "<a href='" . $_SERVER['PHP_SELF'] . "?Year=$Year&Month=$i' class='small2'><font class='verysmallgrey'>" . $this->ReturnMonth($i) . "</a></font>&nbsp";
                        else
                            $count = "<a href='" . $_SERVER['PHP_SELF'] . "?Year=$Year&Month=$i' class='small2'>" . $this->ReturnMonth($i) . "</a>&nbsp;<font class='verysmallgrey'>[" . $this->tMonth[$Year.$i] . "]</font>&nbsp";
                                
                        if ($Month == $i)
                           echo "<td bgcolor='777894' width='35'>&nbsp;" . $count . "</td>";
                        else
                           echo "<td bgcolor='9899BC' width='35'>&nbsp;" . $count . "</td>";
                    }
                    ?>
                    <td bgcolor='9899BC'>&nbsp;</td>
                </tr>
            </table>
        <?
        }
    }


    
    function DisplayTotalDetails($Year,$Month,$Day)
    {
        $this->tDay = $this->UnSerializeLog($this->SerFileDay);

        $this->tHitsDay = $this->GetHit($Year.$Month.$Day);
        $this->tHitsMonth = $this->GetHit($Year.$Month);
        $this->tHitsYear = $this->GetHit($Year);

        $this->tTime = $this->UnSerializeLog($this->SerFileTotalTime);

        if (empty($this->tHitsYear))
            return false;

        if($this->tTime[$Year.$Month] > 0 && $this->tHits[$Year.$Month] > 0)
            $moyenne = $this->tTime[$Year.$Month] / $this->tHits[$Year.$Month];
        else
            $Moyenne = 0;
        ?>
            <br>
            <table border='0' cellpadding='0' cellspacing='1' width='100%' bgcolor='B0B1CC'>
                <tr bgcolor='9899BC'>
                    <td>
                        <font class='smallgrey' align='center'><b><?= DSP_DAYT_DAY ?></b></font>
                    </td>
                </tr>
                <tr bgcolor='9899BC'>
                    <td class='verysmallgrey'>
                        <?= DSP_DAYT_VISIT ?> : <font class='smallgrey'><?= $this->tDay[$Year.$Month.$Day] ?></font><br>
                        <?= DSP_DAYT_HIT ?> : <font class='smallgrey'><?= $this->tHitsDay ?></font><br><br>
                    </td>
                </tr>       
<!-- Start of Additional Code #1 -->
                <tr bgcolor='9899BC'>
                    <td>
                        <?
                         $unique = new StatsUnique($this->ReturnDetailToday(), true);
                        ?>
                    </td>
                </tr>       
<!-- End of Additional Code #1 -->
                <tr bgcolor='9899BC'>
                    <td>
                        <font class='smallgrey' align='center'><b><?= DSP_DAYT_THIS . " " . DSP_DAYT_MONTH ?></b></font>
                    </td>
                </tr>
                <tr bgcolor='9899BC'>
                    <td class='verysmallgrey'>
                        <?= DSP_DAYT_VISIT ?> : <font class='smallgrey'><?= $this->tMonth[$Year.$Month] ?></font><br>
                        <?= DSP_DAYT_HIT ?> : <font class='smallgrey'><?= $this->tHitsMonth ?></font><br><br>
                    </td>
                </tr>
                <tr bgcolor='9899BC'>
                    <td>
                        <font class='smallgrey'><b><?= DSP_DAYT_THIS2 . " " . DSP_DAYT_YEAR ?></b></font>
                    </td>
                </tr>
                <tr bgcolor='9899BC'>
                    <td class='verysmallgrey'>
                        <?= DSP_DAYT_VISIT ?> : <font class='smallgrey'><?= $this->tYear[$Year] ?></font><br>
                        <?= DSP_DAYT_HIT ?> : <font class='smallgrey'><?= $this->tHitsYear ?></font><br><br>
                    </td>
                </tr>
            </table>
        <?
    }
    
    function DisplayDetailDay($Year, $Month, $Day)
    {
        $Detail = $this->RetunrDetailDay($Year, $Month, $Day);
        if (empty($Detail))
        {
            echo "<font class='smallblack'>".DSP_DETAILD_NOENTRY."</font>";
            return false;
        }

        ?>
        <DIV style="DISPLAY: block; OVERFLOW: auto; WIDTH: 575; POSITION: relative; HEIGHT: 216px">
        <table border='0' cellpadding='0' cellspacing='1' width='555' bgcolor='4C4E72'>
            <tr bgcolor='8686A7'>
                <td align='center' width='45'><font class='smallgrey'><?= DSP_DETAILD_TIME ?></font></td>
                <td align='center' width='350'><font class='smallgrey'><?= DSP_DETAILD_HOST ?></font></td>
                <td align='center' width='15'><font class='smallgrey'><?= DSP_DETAILD_CLIENT ?></font></td>
                <td align='center' width='15'><font class='smallgrey'><?= DSP_DETAILD_REF ?></font></td>
                <!--<td align='center' width='5%'><font class='smallgrey'><?= DSP_DETAILD_DETAILS ?></font></td>-->
            </tr>
        <?
        $flag = 1;
        for ($i = 0; $i < count($Detail); $i++)
        {
            //$dtl = $this->RetunrDetailDayPerVisit($Detail[$i]["Uid"], $Year, $Month, $Day);
            $detail = count($dtl["all"]);
            
            $Url = $this->WsOverUrl();
            $Url = $this->WsOverUrl("detail", "visit", $Url);
            $UrlD = $this->WsOverUrl("id", $Detail[$i]["Uid"], $Url);
            
            if (is_numeric($Detail[$i]["Host"]))
                $Cnty = "ukn";
            else
            {
                preg_match("/[^\.\/]+\.[^\.\/]+$/",$Detail[$i]["Host"],$matches);

                $host = $matches[0];
                $Tnat = explode(".", $host);
                $Cnty = $Tnat[1];
            }

            if ($flag == 1)
            {
                $flag = 0;
                $bgcolor='9899BC';
            }
            else
            {
                $flag = 1;
                $bgcolor='B3B4AA';
            }
            
            if ($detail > 1)
                $ImgDetail = "<a href='".$UrlD."#".$Detail[$i]["Uid"]."'><img src='./resources/images/system2.gif' border='0'></a>";
            else
                $ImgDetail = "";
            
            if (!empty($Detail[$i]["Referer"]))
                $ImgRef = "<a href='" . $Detail[$i]["Referer"] . "' target='_blank'><img src='./resources/images/icon_becar.gif' border='0' alt='" . $Detail[$i]["Referer"] . "'></a>";
            else
                $ImgRef = "";
            
                
            echo "<tr bgcolor='$bgcolor'>";
            echo "<td bgcolor='B2B3CA'><font class='smallblack'>" . date("H:i:s", $Detail[$i]["Time"]) . "</td>";
            echo "<td bgcolor='9899BC' width='350'><font class='smallblack'><img src='./resources/images/flag/" . $this->ReturnFlag($Cnty) . "' alt='$Tnat[1]' align='left'><a href='".$UrlD."#".$Detail[$i]["Uid"]."' class='small2'>" . $Detail[$i]["Host"] . "</a></td>";
            echo "<td bgcolor='ffffff' align='center'>" . $this->ReturnImgBrower($Detail[$i]["Client"]) . "</td>";
            echo "<td bgcolor='ffffff' align='center'>" . $ImgRef . "</td>";
            //echo "<td bgcolor='ffffff' align='center'>" . $ImgDetail . "</td>";
            echo "</tr>";
        }
        echo "</table></div>";

    }


    function DisplayVisit($Year, $Month, $Day)
    {
        $Detail = $this->RetunrDetailDay($Year, $Month, $Day);

        if (empty($Detail))
        {
            echo "<font class='smallblack'>".DSP_DETAILD_NOENTRY."</font>";
            return false;
        }
        ?>
        <table border='0' cellpadding='0' cellspacing='0' width='100%' bgcolor='4C4E72'>
        <?
        echo "<tr bgcolor='ffffff'>
                <td colspan='5'>";
        for ($i = 0; $i < count($Detail); $i++)
        {
            if ($_GET["id"] == $Detail[$i]["Uid"])
            {
                $bgcolor1='AEBC98';
                $bgcolor2='C1CAB2';
            }
            else
            {
                $bgcolor1 = '9899BC';
                $bgcolor2 = 'B2B3CA';
            }               
            $dtl = $this->RetunrDetailDayPerVisit($Detail[$i]["Uid"], $Year, $Month, $Day);

            if (is_numeric($Detail[$i]["Host"]))
                $Cnty = "ukn";
            else
            {
                preg_match("/[^\.\/]+\.[^\.\/]+$/",$Detail[$i]["Host"],$matches);
                $host = $matches[0];
                $Tnat = explode(".", $host);
                $Cnty = $Tnat[1];
            }

            if (!empty($Detail[$i]["Referer"]))
                $ImgRef = "<a href='" . $Detail[$i]["Referer"] . "' target='_blank'><img src='./resources/images/icon_becar.gif' border='0' alt='" . $Detail[$i]["Referer"] . "'></a>";
            else
                $ImgRef = "";
            
            $id = $Detail[$i]["Uid"];
            echo "<table cellspacing='1' cellpadding='0' bgcolor='000000' width='99%'>";
            echo "<tr bgcolor='9899BC'>";
            echo "<td bgcolor='".$bgcolor2."' width='20%' class='normalblack' valign='top'>
                    <a name='".$Detail[$i]["Uid"]."'><img src='./resources/images/flag/" . $this->ReturnFlag($Cnty) . "' alt='$Tnat[1]' align='left'></a>&nbsp;
                    ". $ImgRef ."<br><br><b>".DSP_VISIT_TIME." :</b> " . date("H:i:s", $Detail[$i]["Time"]);
            echo "<br><b>".DSP_VISIT_PAGEVIEW." : </b>" . $dtl["cumul"]["NbPage"];
            echo "<br><b>".DSP_VISIT_NBHIT." : </b>" . $dtl["cumul"]["Hits"] . "</td>";
            echo "<td bgcolor='".$bgcolor1."' class='smallblack'><ul>";
            if (!empty($dtl))
            {
                echo "<br><br><b>".DSP_VISIT_TIMEALL. " (" .DSP_GEN_BETA.") : </b><br>";
                $cumul = 0;
                for ($j = 0; $j < count($dtl["all"]); $j++)
                {
                    $start = $dtl["all"][$j]["TimeStart"];
                    $end = $dtl["all"][$j]["TimeEnd"];

                    if (empty($end))
                        $time = 0;
                    else                
                        $time = ($end - $start);

                    $cumul += $time;
                    echo "<font class='smallwhite'>" . $this->GetTime($time) . " -&nbsp;&nbsp;</font><a href='".$PHP_SELF.$dtl["all"][$j]["Uri"]."' target='_blank' class='small2'>" . $dtl["all"][$j]["Title"] . "-" . $dtl["all"][$j]["Uri"] . "</a><br>";
                }
                echo "<br><center><font class='normalblack'><b>".DSP_VISIT_TIMEALL2." (".DSP_GEN_BETA.") :</b> </font><font class='smallwhite'>". $this->GetTime($cumul) ."</font></center>";
            }
            echo "</ul></td>";
            echo "</tr>";
            echo "</table>";
        }
        echo "</td></tr>";
        echo "</table>";
    }
        
    function DisplayTopHost($Year,$Month, $Day)
    {
        if ($this->topConf == "Day")
            $tri = $Year.$Month.$Day;
        elseif ($this->topConf == "Month")
            $tri = $Year.$Month;
        elseif ($this->topConf == "Year")
            $tri = $Year;

        if (!empty($_GET["Top"]))
            $this->tHost = $this->Get($_GET["Top"], $this->SerFileHost, $this->Limittop);
        else
            $this->tHost = $this->Get($tri, $this->SerFileHost, $this->Limittop);
            
        if (empty($this->tHost))
            return false;

        ?>
            <table border='0' cellpadding='0' cellspacing='1' width='100%' bgcolor='4C4E72'>
                <tr bgcolor='8686A7'>
                    <td width='90'><font class='smallgrey'>&nbsp;&nbsp;<?= DSP_HOST_HOST ?></font></td>
                    <td width='40'><font class='smallgrey'>&nbsp;&nbsp;%</font></td>
                </tr>
                    <?
                    array_multisort ($this->tHost, SORT_DESC, array_keys ($this->tHost));
                    $flag = 0;
                    foreach ($this->tHost as $key => $value)
                    {
                        $Moyenne = ($value * 100) / $this->tHost["Count"];
                        
                        if ($key != "Count" && $flag < $this->Limittop)
                        {                       
                            echo "<tr bgcolor='9899BC'>";
                            echo "<td align='center'><font class='smallgrey'>" . $key . "</font></td>";
                            echo "<td align='center' bgcolor='" . $this->ReturnColorCell($Moyenne) . "'><font class='smallblack'>" . round($Moyenne,2) . "%</font></td>";
                            echo "</tr>";
                        }elseif($flag > $this->Limittop) break; // PERFORMANCE!!!
                        $flag += 1;
                    }   
                    ?>
            </table>
        <?
    }       

    function DisplayTopReferer($Year, $Month, $Day)
    {
        if ($this->topConf == "Day")
            $tri = $Year.$Month.$Day;
        elseif ($this->topConf == "Month")
            $tri = $Year.$Month;
        elseif ($this->topConf == "Year")
            $tri = $Year;

        if (!empty($_GET["Top"]))
            $this->tReferer = $this->Get($_GET["Top"], $this->SerFileReferer,$this->Limittop);
        else
            $this->tReferer = $this->Get($tri, $this->SerFileReferer,$this->Limittop);

        if (empty($this->tReferer))
            return false;

        ?>
            <table border='0' cellpadding='0' cellspacing='1' width='100%' bgcolor='4C4E72'>
                <tr bgcolor='8686A7'>
                    <td width='90'><font class='smallgrey'>&nbsp;&nbsp;<?= DSP_HOST_REF?></font></td>
                    <td width='40'><font class='smallgrey'>&nbsp;&nbsp;%</font></td>
                </tr>
                    <?
                    $flag = 0;
                    array_multisort ($this->tReferer, SORT_DESC, array_keys ($this->tReferer));
                    foreach ($this->tReferer as $key => $value)
                    {
                        $Moyenne = ($value * 100) / $this->tReferer["Count"];
                        if ($key != "Count" && $flag < $this->Limittop)
                        {                       
                            echo "<tr bgcolor='9899BC'>";
                            echo "<td align='center'><a href='$key' target='_blank' class='small2'>" . $key . "</font></td>";
                            echo "<td align='center' bgcolor='" . $this->ReturnColorCell($Moyenne) . "'><font class='smallblack'>" . round($Moyenne,2) . "%</font></td>";
                            echo "</tr>";
                        }elseif($flag > $this->Limittop) break; // PERFORMANCE!!!
                        
                        $flag += 1;
                    }   
                    ?>
            </table>
        <?
    }       

    function DisplayTopPage($Year, $Month, $Day)
    {
        if ($this->topConf == "Day")
            $tri = $Year.$Month.$Day;
        elseif ($this->topConf == "Month")
            $tri = $Year.$Month;
        elseif ($this->topConf == "Year")
            $tri = $Year;

        if (!empty($_GET["Top"]))
            $this->tPages = $this->Get($_GET["Top"], $this->SerFilePages,$this->Limittop);
        else
            $this->tPages = $this->Get($tri, $this->SerFilePages,$this->Limittop);

        if (empty($this->tPages))
            return false;
        ?>
            <table border='0' cellpadding='0' cellspacing='1' width='100%' bgcolor='4C4E72'>
                <tr bgcolor='8686A7'>
                    <td width='90'><font class='smallgrey'>&nbsp;&nbsp;<?= DSP_PAGE_PAGE ?></font></td>
                    <td width='40'><font class='smallgrey'>&nbsp;&nbsp;%</font></td>
                </tr>
                    <?
                    array_multisort ($this->tPages, SORT_DESC, array_keys ($this->tPages));
                    $flag = 0;
                    foreach ($this->tPages as $key => $value)
                    {
                        $Moyenne = ($value * 100) / $this->tPages["Count"];
                        if ($key != "Count" && $flag < $this->Limittop)
                        {                       
                            echo "<tr bgcolor='9899BC'>";
                            echo "<td align='center'><a href='http://".$_SERVER["HTTP_HOST"]. $key . "' class='small2' target='_blank'>".$key."</a></td>";
                            echo "<td align='center' bgcolor='" . $this->ReturnColorCell($Moyenne) . "'><font class='smallblack'>" . round($Moyenne,2) . "%</font></td>";
                            echo "</tr>";
                        }elseif($flag > $this->Limittop) break; // PERFORMANCE!!!
                    $flag += 1;
                    }   
                    ?>
            </table>
        <?
    }   

    function DisplayTopCountry($Year, $Month, $Day)
    {
        if ($this->topConf == "Day")
            $tri = $Year.$Month.$Day;
        elseif ($this->topConf == "Month")
            $tri = $Year.$Month;
        elseif ($this->topConf == "Year")
            $tri = $Year;

        if (!empty($_GET["Top"]))
            $this->tCountry = $this->Get($_GET["Top"], $this->SerFileCountry);
        else
            $this->tCountry = $this->Get($tri, $this->SerFileCountry);

        if (empty($this->tCountry))
            return false;
        ?>
            <table border='0' cellpadding='0' cellspacing='1' width='100%' bgcolor='4C4E72'>
                <tr bgcolor='ffffff'>
                    <?
                    array_multisort ($this->tCountry, SORT_DESC, array_keys ($this->tCountry));
                    $flag = 0;
                    foreach ($this->tCountry as $key => $value)
                    {
                        $Moyenne = ($value * 100) / $this->tCountry["Count"];
                        if ($key != "Count" && $flag < $this->Limittop && $flag < 15)
                        {                       
                            echo "<td bgcolor='" . $this->ReturnColorCell($Moyenne) . "'><font class='smallblack'>" . round($Moyenne) . "%</font></td>";
                            echo "<td><img src='./resources/images/flag/" . $this->ReturnFlag($key) . "' alt='$key'></td>";
                            echo "<td class='smallblack'>&nbsp;</td>";
                        }elseif($flag > $this->Limittop) break; // PERFORMANCE!!!
                        $flag += 1;
                    }   
                    ?>
                </tr>
            </table>
        <?
    }
    
    function DisplayFormConf()
    {
        ?>
        <table border='0' cellspacing='1' cellpadding='0' bgcolor='000000'>
            <tr>
                <td width='70%'>
                <form action='<?=$PHP_SELF?>' method='post'>
                <table border='0' cellspacing='1' cellpadding='0' width='100%' bgcolor='9899BC'>
                    <tr class='smallgrey' bgcolor='4C4E72'>
                        <td><?= DSP_SETT_TITLE ?></td>
                    </tr>
                    <tr bgcolor='9899BC' class='smallgrey'>
                        <td bgcolor='9899BC'><?= DSP_SETT_INTLNG ?> :<br>
                        <?
                            if ($this->lngConf == "us")
                                echo "<input type='radio' name='lng' value='us' checked><img src='./resources/images/flag/uk.gif'>";
                            else
                                echo "<input type='radio' name='lng' value='us'><img src='./resources/images/flag/uk.gif'>";
        
                            if ($this->lngConf == "fr")
                                echo "<input type='radio' name='lng' value='fr' checked><img src='./resources/images/flag/fr.gif'>";
                            else
                                echo "<input type='radio' name='lng' value='fr'><img src='./resources/images/flag/fr.gif'>";
        
                            if ($this->lngConf == "de")
                                echo "<input type='radio' name='lng' value='de' checked><img src='./resources/images/flag/de.gif'>";
                            else
                                echo "<input type='radio' name='lng' value='de'><img src='./resources/images/flag/de.gif'>";
        
                            if ($this->lngConf == "es")
                                echo "<input type='radio' name='lng' value='es' checked><img src='./resources/images/flag/es.gif'>";
                            else
                                echo "<input type='radio' name='lng' value='es'><img src='./resources/images/flag/es.gif'>";

                            if ($this->lngConf == "bg")
                                echo "<input type='radio' name='lng' value='bg' checked><img src='./resources/images/flag/bg.gif'>";
                            else
                                echo "<input type='radio' name='lng' value='bg'><img src='./resources/images/flag/bg.gif'>";

                            if ($this->lngConf == "ru")
                                echo "<input type='radio' name='lng' value='ru' checked><img src='./resources/images/flag/ru.gif'>";
                            else
                                echo "<input type='radio' name='lng' value='ru'><img src='./resources/images/flag/ru.gif'>";

                        ?>
                        </td>
                    </tr>
                    <tr bgcolor='9899BC' class='smallgrey'>
                        <td bgcolor='9899BC'><?= DSP_SETT_DSPGFX ?>:<br>
                        <?
                            if ($this->dspgConf == "show")
                            {
                                echo "<input type='radio' name='dspg' value='show' checked>" . DSP_GEN_YES;
                                echo "<input type='radio' name='dspg' value='hide'>".DSP_GEN_NO;
                            }
                            else
                            {
                                echo "<input type='radio' name='dspg' value='show'>" . DSP_GEN_YES;
                                echo "<input type='radio' name='dspg' value='hide' checked>" . DSP_GEN_NO;
                            }
                        ?>
                            <br><br>
                        </td>
                    </tr>
                    <tr bgcolor='9899BC' class='smallgrey'>
                        <td bgcolor='9899BC'><?= DSP_SETT_TTENSORT ?> :<br>
                        <?
                            if ($this->topConf == "Day")
                            {
                                echo "<input type='radio' name='tten' value='Day' checked>".DSP_ALL_DAY;
                                echo "<input type='radio' name='tten' value='Month'>".DSP_ALL_MONTH;
                                echo "<input type='radio' name='tten' value='Year'>".DSP_ALL_YEAR;
                            }
                            elseif ($this->topConf == "Month")
                            {
                                echo "<input type='radio' name='tten' value='Day'>".DSP_ALL_DAY;
                                echo "<input type='radio' name='tten' value='Month' checked>".DSP_ALL_MONTH;
                                echo "<input type='radio' name='tten' value='Year'>".DSP_ALL_YEAR;
                            }
                            else
                            {
                                echo "<input type='radio' name='tten' value='Day'>".DSP_ALL_DAY;
                                echo "<input type='radio' name='tten' value='Month'>".DSP_ALL_MONTH;
                                echo "<input type='radio' name='tten' value='Year' checked>".DSP_ALL_YEAR;
                            }
                        ?>
                        <br><br>
                        </td>
                    </tr>
                    <tr bgcolor='9899BC' class='smallgrey'>
                        <td bgcolor='9899BC'><?= DSP_SETT_NBTOP ?> :<br>
                        <?
                            if ($this->Limittop == "10")
                                echo "<input type='radio' name='limittop' value='10' checked>10";
                            else
                                echo "<input type='radio' name='limittop' value='10'>10";
        
                            if ($this->Limittop == "20")
                                echo "<input type='radio' name='limittop' value='20' checked>20";
                            else
                                echo "<input type='radio' name='limittop' value='20'>20";
        
                            if ($this->Limittop == "50")
                                echo "<input type='radio' name='limittop' value='50' checked>50";
                            else
                                echo "<input type='radio' name='limittop' value='50'>50";
                        ?>
                            <br><br>
                        </td>
                    </tr>
                    <tr bgcolor='9899BC' class='smallgrey'>
                        <td bgcolor='9899BC'><?= DSP_SETT_BLOCKIPACT ?> :<br>
                        <?
                            if ($this->BlockIp == "yes")
                            {
                                echo "<input type='radio' name='blockip' value='yes' checked>" . DSP_GEN_YES;
                                echo "<input type='radio' name='blockip' value='no'>".DSP_GEN_NO;
                            }
                            else
                            {
                                echo "<input type='radio' name='blockip' value='yes'>" . DSP_GEN_YES;
                                echo "<input type='radio' name='blockip' value='no' checked>" . DSP_GEN_NO;
                            }
                        ?>
                        </td>
                    </tr>           
                    <tr class='smallgrey' bgcolor='4C4E72'>
                        <td align='right'><input type='submit' name='btn_valid' value='<?= DSP_GEN_BTNVALID ?>'><input type='submit' name='btn_apply' value='<?= DSP_GEN_BTNAPPLY ?>'></td>
                    </tr>
                </form>
                </table>
            </td>
            <td bgcolor='9899BC' valign='top'>
                <form action='<?=$PHP_SELF?>' method='post'>
                <table border='0' cellspacing='1' cellpadding='0' width='100%' bgcolor='9899BC'>
                    <tr class='smallgrey' bgcolor='4C4E72'>
                        <td colspan='2'><?= DSP_SETT_BLOCKIPTITLE ?></td>
                    </tr>
                        <td width='5%'>&nbsp;</td>
                        <td bgcolor='9899BC' class='smallgrey'>
                        <?= DSP_SETT_BLOCKIP ?>
                                <input type='text' name='ip' value='<?= $_SERVER["REMOTE_ADDR"] ?>'><input type='submit' name='btn_add' value='<?=DSP_BTN_ADD?>'><br>
                                <table border='0' cellspacing='0' cellpadding='0'>
                                    <tr>
                                        <td class='smallgrey'><?=DSP_SETT_IP?></td>
                                <?
                                    $IpList = $this->IpIgnore;
                                    
                                    if (!empty($IpList))
                                        foreach($IpList as $k => $v)
                                            echo "<tr><td><a href='$PHP_SELF?action=del&ip=$v' class='small2'>" . $v . "</a></td></tr>";
                                ?>
                                </table>
                        </td>
                    </tr>
                </table>                
                </form>
            </td>
        </tr>
        </table>    
        <?
    }   

    function Version()
    {
        $check = $this->CheckVersion();
        $prv = explode("=", $check);
        $prv = explode("|", $prv[1]);
        
        $version = $prv[0];
        $url = $prv[1];
    
        if ($check == false)
            $Message = DSP_VERS_NOCNX . "<a href='http://www.nanobody.net' target='blank' class='small2'>http://www.nanobody.net</a>";
        else
        {
            if($version == $this->Version)
                $Message = DSP_VERS_NONEW . "<a href='http://www.nanobody.net' target='blank' class='small2'>http://www.nanobody.net</a>";
            else
                $Message = DSP_VERS_NEW . "<a href='$url' target='_blank' class='small2'>Webanalyse" .$version."</a>";              
        }
        ?>
        <table border='0' cellspacing='1' cellpadding='0' width='100%' bgcolor='9899BC'>
            <tr class='smallgrey' bgcolor='4C4E72'>
                <td colspan='2'><?= DSP_VERS_TITLE ?></td>
            </tr>
            <tr bgcolor='9899BC' class='smallgrey'>
                <td width='15%'>&nbsp;</td>
                <td bgcolor='9899BC'><?= DSP_VERS_YOUR ?> :</td>
            </tr>
            <tr bgcolor='9899BC' class='smallgrey'>
                <td width='15%'>&nbsp;</td>
                <td bgcolor='9899BC' class='verysmallgrey'>Version <?= $this->Version ?></td>
            </tr>
            <tr bgcolor='9899BC' class='smallgrey'>
                <td width='15%'>&nbsp;</td>
                <td bgcolor='9899BC'><?= DSP_VERS_LAST ?> :</td>
            </tr>
            <tr bgcolor='9899BC' class='smallgrey'>
                <td width='15%'>&nbsp;</td>
                <td bgcolor='9899BC' class='verysmallgrey'>Version <?= $version ?>
            </tr>
            <tr bgcolor='9899BC' class='smallgrey'>
                <td width='15%'>&nbsp;</td>
                <td bgcolor='9899BC' class='verysmallgrey'>&nbsp;</td>
            </tr>
            <tr bgcolor='9899BC' class='smallgrey'>
                <td width='15%'>&nbsp;</td>
                <td bgcolor='9899BC' class='smallgrey'><?= $Message ?></td>
            </tr>
            <tr class='smallgrey' bgcolor='4C4E72'>
                <td colspan='2' align='right'><a href='<?=$PHP_SELF?>?a=close' class='small'><?=DSP_VERS_CLOSE?></a></td>
            </tr>
        </table>
        <?
    }

    function Display_About()
    {
        ?>
        <table border='0' cellspacing='1' cellpadding='0' width='100%' bgcolor='9899BC'>
            <tr class='smallgrey' bgcolor='4C4E72'>
                <td colspan='2'>Release Notes</td>
            </tr>
            <tr bgcolor='9899BC'>
                <td bgcolor='9899BC' class='verysmallgrey' colspan='2' align='center'>
                    Webanalyse is Freeware. You can distribute this tool, but not for a commercial use, Webanalyse is CopyLetf.
                    <br>more informations <a href='http://webanalyse.sourceforge.net' target='_blank' class='small2'> here</a>
                    <br><br>
                </td>
            </tr>
            <tr class='smallgrey'>
                <td colspan='2'><u>Author & Main coding</u></td>
            </tr>
            <tr bgcolor='9899BC'>
                <td width='15%'>&nbsp;</td>
                <td bgcolor='9899BC' class='verysmallgrey'>
                    <img src='./resources/images/flag/fr.gif' width='13' height='10'>&nbsp;<a href='mailto:ranx@nanobody.net' class='small2'><img src='./resources/images/ico/info.gif' alt='contact' border='0'></a>&nbsp;David Guillien &nbsp;&nbsp;&nbsp;<a href='http://www.nanobody.net' target='_blank' class='small2'>[ www.nanobody.net ]</a>
                </td>
            </tr>
            <tr class='smallgrey'>
                <td colspan='2'><u>AddOn coding</u></td>
            </tr>
            <tr bgcolor='9899BC'>
                <td width='15%'>&nbsp;</td>
                <td bgcolor='9899BC' class='verysmallgrey'>
                    <img src='./resources/images/flag/nz.gif' width='13' height='10'>&nbsp;<a href='mailto:sarah@pcpropertymanager.com' class='small2'><img src='./resources/images/ico/info.gif' alt='contact' border='0'></a>&nbsp;Sarah King&nbsp;&nbsp;&nbsp;<a href='http://www.pcpropertymanager.com/' target='_blank' class='small2'>[ www.pcpropertymanager.com ]</a>
                </td>
            </tr>
            <tr class='smallgrey'>
                <td colspan='2'><u>Translation</u></td>
            </tr>
            <tr bgcolor='9899BC'>
                <td width='15%' class='smallblack'></td>
                <td bgcolor='9899BC' class='verysmallgrey'>
                    <img src='./resources/images/flag/de.gif' width='13' height='10'>&nbsp;<a href='mailto:beate@paland.tv' class='small2'><img src='./resources/images/ico/info.gif' alt='contact' border='0'></a>&nbsp;Beate (Deutch version)&nbsp;&nbsp;&nbsp;<a href='http://www.paland.tv/' target='_blank' class='small2'>[ www.paland.tv ]</a>
                </td>
            </tr>
            <tr bgcolor='9899BC'>
                <td width='15%' class='smallblack'></td>
                <td bgcolor='9899BC' class='verysmallgrey'>
                    <img src='./resources/images/flag/es.gif' width='13' height='10'>&nbsp;<a href='mailto:kaliopus@hotmail.com' class='small2'><img src='./resources/images/ico/info.gif' alt='contact' border='0'></a>&nbsp;Frederic Bottex (Spanish version)
                    <br>
                </td>
            </tr>
            <tr bgcolor='9899BC'>
                <td width='15%' class='smallblack'></td>
                <td bgcolor='9899BC' class='verysmallgrey'>
                    <img src='./resources/images/flag/bg.gif' width='13' height='10'>&nbsp;<a href='mailto:rusin@tsonev.com' class='small2'><img src='./resources/images/ico/info.gif' alt='contact' border='0'></a>&nbsp;Rusin R. Tsonev (Bulgarian&Russian version)&nbsp;&nbsp;&nbsp;<a href='http://www.tsonev.com/' target='_blank' class='small2'>[ www.tsonev.com ]</a>
                    <br>
                </td>
            </tr>
            <tr class='smallgrey' bgcolor='4C4E72'>
                <td colspan='2' align='right'><a href='<?=$PHP_SELF?>?a=close' class='small'><?=DSP_VERS_CLOSE?></a></td>
            </tr>
        </table>
        <?
    }

//Additional function -> quicker call to get today's details
//Removes the additional lines from the display function
    function ReturnDetailToday()
    {
      $Year = date('Y');
      $Month = date('m');
      $Day = date('d');
      return $this->RetunrDetailDay($Year, $Month, $Day);
    }//ReturnDetailToday
//End of Class
}
?>