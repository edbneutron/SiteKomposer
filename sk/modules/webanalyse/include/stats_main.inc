<?


Class Init
{
    var $CookiesTime;                                                // Temps de validité du cookie
    
    var $Ip;                                                            // Retourne l'IP
    var $Host;                                                            // Retourne le Host
    var $Referer;                                                        // Retourne le Referer
    var $Client;                                                        // Retourne la version du navigateur
    var $Date;                                                             // Date du jour
    var $DateAccess;                                                    // Timestamp de l'acces
    var $PageTitle;
    var $AccessPath = '/log/';
    var $active;

    function Init($activ = true, $Path, $PageTitle="",$LogPath="")
    {
        $this->AccessPath = $Path;
        if($LogPath>"") {$this->LogPath=$LogPath; }else{$this->LogPath=$Path;}
        $this->Active = $activ;
        $this->PageTitle = $PageTitle; //added page-title
        
//        $this->UID = session_id();
        $this->UID = $_COOKIE["UID"];

        $this->Ip = $_SERVER["REMOTE_ADDR"];                                // Retourne l'IP
        $this->Host = gethostbyaddr($this->Ip);                                // Retourne le Host
        $this->Referer = $_SERVER["HTTP_REFERER"];                            // Retourne le Referer
        $this->Date = date("F j, Y, g:i:s");                                // Date du jour
        $this->FileDate = date("Ymd");                                    // Date du fichier de log.. . 1/jour
        $this->DateAccess = time($this->Date);         
        $this->Client = $_SERVER["HTTP_USER_AGENT"];

        $browser_search = array("MSIE 6.0", "MSIE 5.5", "MSIE 5.0", "MSIE 4.0","Opera","Konqueror","Mozilla/5", "Mozilla/4", "Mozilla", "SAGEM");
        while(list($key, $value) = each ($browser_search))
        {
            $pos = strpos ($this->Client, $value);
            if($pos !== false)
            {
                $IBROWSER = true;
                break 1;
            }
        }

        // Filtrage d'ip
        if (file_exists($this->LogPath."log/conf.ser"))
        {
            $fd = fopen ($this->LogPath."log/conf.ser", "r");
            $contents = fread ($fd, filesize($this->LogPath."log/conf.ser"));
            fclose ($fd);
            $data = unserialize($contents);
        }
    
        if ($data["BlockIp"] == "yes")
        {
            if (file_exists($this->LogPath."log/filterip.ser"))
            {
                $fd = fopen ($this->LogPath."log/filterip.ser", "r");
                $contents = fread ($fd, filesize($this->LogPath."log/filterip.ser"));
                fclose ($fd);
                $data = unserialize($contents);    
            }
            if (!empty($data[$this->Ip]))
                return false;
        }
            
        if ($this->Active == true && $IBROWSER == true)                                        
        {

            if (empty($this->UID))
            {
                $UID = uniqid("");
                setcookie("UID",$UID);
                $this->UID = $UID;
                $this->CreateLogFile();
            }
            else
                $this->AddEvents(time());

/*
            if (!isset($_SESSION["watime"]))
            {
                $_SESSION["watime"] = time();
                $this->CreateLogFile();
            }
            else                                                                                
                $this->AddEvents(time());
    */

        }
    }
// Added logging of Page-Title
// edb
    function CreateLogFile()
    {
        $file = $this->FileDate . ".log";
        if (is_dir($this->LogPath."log/"))
        {
            $fd = fopen ($this->LogPath."log/$file", "a+");
            fputs ($fd, $this->UID . "|" . $this->Ip . "|" . $this->Host . "|" . $this->Referer . "|" . $this->Client . "|" . $_SERVER["REQUEST_URI"] . "|" . $this->DateAccess . "|" . $this->PageTitle . "|" . "1\n");    
            fclose ($fd);
        }
    }
    
    function AddEvents($time)
    {
//        $time = $time - $_SESSION["watime"];
        $file = $this->FileDate . ".log";
        if (is_dir($this->LogPath."log/"))
        {
            $fd = fopen ($this->LogPath."log/$file", "a+");
            fputs ($fd, $this->UID . "|" . $this->Ip . "|" . $this->Host . "|" . $this->Referer . "|" . $this->Client . "|" . $_SERVER["REQUEST_URI"] . "|" . $time. "|" . $this->PageTitle . "\n");            
            fclose ($fd);
        }
    }
}

Class Process
{
    var $Date;                                                             // Date du jour
    var $SerFileYear;
    var $IPath;
    var $Version;
    var $checkupdate;
    // Modification by Edgar Bültemeyer
    // to see different vars as different pages
    // i.e. index.php?mid=123 /= index.php?mid=111
    // set to ? to disable (default mode)
    var $explode_char = '~';
    
    function Process($Path = '',$LogPath = '')
    {
        $this->Version = "1.3";
        $this->checkupdate = true;
        
        $this->Date = date("j F, Y");                            // Date du jour
        
        $this->Path = "./";
        $this->IPath = "./log/";

        if (!empty($Path))
        {
            $this->Path = $Path;
            $this->IPath = $Path . "/log/";
        }
        if (!empty($LogPath))
        {
            $this->IPath = $LogPath . "log/";
        }
        
        $this->SerFileYear                 = $this->IPath."__tyear.ser";
        $this->SerFileMonth             = $this->IPath."__tmonth.ser";
        $this->SerFileDay                 = $this->IPath."__tday.ser";

        $this->SerFileHost                 = $this->IPath."__thost.ser";
        $this->SerFileReferer             = $this->IPath."__treferer.ser";
        $this->SerFilePages             = $this->IPath."__tpages.ser";
        $this->SerFileCountry             = $this->IPath."__tcountry.ser";

        $this->SerFileWithReferer         = $this->IPath."__twithreferer.ser";
        $this->SerFileWithOutReferer     = $this->IPath."__twithoutreferer.ser";
        $this->SerFileBrowers             = $this->IPath."__tbrowser.ser";

        $this->SerFileTotalHits            = $this->IPath."__ttotalhhit.ser";
        $this->SerFileTotalTime            = $this->IPath."__ttotaltime.ser";
        $this->SerFileTotalHour            = $this->IPath."__ttotalhour.ser";

        $this->SerFileConf                = $this->IPath."conf.ser";
        $this->SerFileFilterIp            = $this->IPath."filterip.ser";
        
        $this->InitStats();
    }

    function UpdateConf($tPost)
    {
        $tConf = array(    "Language"             => $tPost["lng"],
                        "DisplayGraphics"    => $tPost["dspg"],
                        "Top"                => $tPost["tten"],
                        "Limittop"            => $tPost["limittop"],
                        "BlockIp"            => $tPost["blockip"]);

        $data = serialize($tConf);
        $fd = fopen($this->SerFileConf, "w+");
        fputs ($fd, $data);            
        fclose($fd);
    }

    function InitStats()
    {
        if (file_exists($this->SerFileConf))
        {
            $fd = fopen ($this->SerFileConf, "r+");
            $contents = fread ($fd, filesize($this->SerFileConf));
            fclose ($fd);
            $tConf = unserialize($contents);
        }
        else
        {
            $tConf = array(    "Language"             => "us",
                            "DisplayGraphics"    => "show",
                            "Top"                => "Month",
                            "Limittop"            => "10",
                            "BlockIp"            => "no");
                            
            $data = serialize($tConf);
            $fd = fopen($this->SerFileConf, "w+");
            fputs ($fd, $data);            
            fclose($fd);
        }
        
        $this->lngConf = $tConf["Language"];
        $this->dspgConf = $tConf["DisplayGraphics"];
        $this->topConf = $tConf["Top"];
        $this->Limittop = $tConf["Limittop"];
        $this->BlockIp = $tConf["BlockIp"];

        $this->IpIgnore = $this->UnSerializeLog($this->SerFileFilterIp);
    
        // Initialisation du fichier de langage
        if (file_exists($this->Path . "resources/language/" . $this->lngConf . ".inc"))
            require($this->Path . "resources/language/" . $this->lngConf . ".inc");
        else
            require($this->Path . "resources/language/test.inc");
        
        return $tConf;
    }
    
    function LogUsage($crypt = false)
    {
    //echo "Nach Hause!";
    return;
    /*
    // insert into logs nanobody stats.
        $rnd = rand(0, 1);
        if ($rnd == 1)
        { // log the site who use WebActivity
             if ($crypt)
                $sn = md5($_SERVER['SERVER_NAME']); // domain name is crypted Nanobody do not store any information about your web site
             else
                $sn = $_SERVER['SERVER_NAME']; // Nanobody will store your domain name but will not use it except for statistiques purpose.
             
                 $errno = '';
                 $errstr = '';
                 $fp = fsockopen ("www.nanobody.net", 80, $errno, $errstr, 5);
    
                 if ($fp)
                 {
                     $date = time();
                    fputs ($fp, "GET /trace_webanalyse.php?Web=$sn&time=$date HTTP/1.0\r\nHost: www.nanobody.net\r\n\r\n");
                    while (!feof($fp))
                       fgets ($fp,128);
                    fclose ($fp);
                 }
        }*/
    }

    function AddIpFilter($ip)
    {
        $tIp = array();
        $file = $this->SerFileFilterIp;
        if(file_exists($file))
        {
            $fd = fopen ($file, "r+");
            $contents = fread ($fd, filesize($file));
            $contents = unserialize($contents);
            fclose ($fd);

            $IpTest = explode(".", $ip);
            if (empty($contents[$ip]) && count($IpTest) == 4)
            {
                $contents[$ip] = $ip;
                $data = serialize($contents);
                $fd = fopen($file, "w+");
                fputs ($fd, $data);            
                fclose($fd);
                
                $this->IpIgnore = $contents;
            }
            else
                return false;            
        }
        else
        {
            $tIp[$ip] = $ip;
            $data = serialize($tIp);
            $fd = fopen($file, "w+");
            fputs ($fd, $data);            
            fclose($fd);

            $this->IpIgnore = $contents;
        }    
    }
    
    function DelIpFilter($ip)
    {
        foreach($this->IpIgnore as $k => $v)
            if($v != $ip)
                $tIp[$v] = $v;

        $this->IpIgnore = $tIp;
        $file = $this->SerFileFilterIp;
        if(file_exists($file))
        {
            $data = serialize($tIp);
            $fd = fopen($file, "w+");
            fputs ($fd, $data);            
            fclose($fd);
        }
    }
    
    function CheckVersion($check = true)
    {  /*
        $errno = '';
        $errstr = '';
        $fp = fsockopen ("www.nanobody.net", 80, $errno, $errstr, 5);
        
        if ($fp)
        {
            fputs ($fp, "GET /version_check/webanalyse.txt HTTP/1.0\r\nHost: www.nanobody.net\r\n\r\n");
            while (!feof($fp))
               $contents = fgets ($fp,128);
            fclose ($fp);

            return $contents;            
        }   */
    }

    function SerializeLog($file, $tdata)
    {
        if(file_exists($file))
        {
            $fd = fopen ($file, "r+");
            $contents = fread ($fd, filesize($file));
            fclose ($fd);

            $data = unserialize($contents);    
            $Nentre = array_keys($tdata);

            for ($i = 0; $i < count($Nentre); $i++)
            { 
                $v = $tdata[$Nentre[$i]];
                
                if (array_key_exists("$Nentre[$i]", $data))
                    $data["$Nentre[$i]"] += $v;
                else
                    $data["$Nentre[$i]"] = $v;
            }
            $fd = fopen($file, "w+");
            $data = serialize($data);
            fputs ($fd, $data);            
            fclose($fd);
        }
        else
        {
            $data = serialize($tdata);
            $fd = fopen($file, "w+");
            fputs ($fd, $data);            
            fclose($fd);
        }    
    }

    function SerializeLog2Entry($file, $tdata)
    {
        if(file_exists($file))
        {
            $fd = fopen ($file, "r+");
            $contents = fread ($fd, filesize($file));
            fclose ($fd);

            $data = unserialize($contents);    
            $Nentre = array_keys($tdata);
            for ($i = 0; $i < count($Nentre); $i++)
            { 
                foreach($tdata[$Nentre[$i]] as $key => $value)
                {
                    $v = $tdata[$Nentre[$i]][$key];
        
                    if (empty($data[$Nentre[$i]][$key]))
                        $data[$Nentre[$i]][$key] = $v;
                    elseif(in_array($key, $data[$Nentre[$i]]))
                        $data[$Nentre[$i]][$key] += $v;
                    else
                        $data[$Nentre[$i]][$key] += $v;
                                                            
                }
            }
            $fd = fopen($file, "w+");
            $data = serialize($data);
            fputs ($fd, $data);            
            fclose($fd);
        }
        else
        {
            $data = serialize($tdata);
            $fd = fopen($file, "w+");
            fputs ($fd, $data);            
            fclose($fd);
        }    
    }
    
    function UnSerializeLog($file)
    {
        if (file_exists($file))
        {
            $fd = fopen ($file, "r");
            $contents = fread ($fd, filesize($file));
            if ($file == $this->SerFileHost)
            fclose ($fd);
        
            $data = unserialize($contents);    
            return $data;
        }
    }    

    function MakeLogFile($date, $tdata)
    {
        $file = $this->IPath . $date . ".dlog";
        if (file_exists($file))
        {
            $fd = fopen ($file, "a+");
            for ($i = 0; $i < count($tdata); $i++)
                fputs($fd, $tdata[$i]."\n");
            fclose ($fd);
        }
        else
        {
            $fd = fopen ($file, "w+");
            for ($i = 0; $i < count($tdata); $i++)
                fputs($fd, $tdata[$i]."\n");
            fclose ($fd);
        }
    }

    function TLogByDay()
    {
        $Logfile = $this->IPath . $Year . $Month . $Day .".log";
        if ($handle = opendir($this->IPath))
        {
            while ($file = readdir($handle))
            {
                $ext = strrchr($file,'.');
                if ($file != "." && $file != ".." && $ext == ".log")
                {
                    $file2 = $this->IPath . $file;
                    $fd = fopen ($file2, "r");
                    $contents = fread ($fd, filesize($file2));
                    fclose ($fd);
                    $Log = explode("\n", $contents);

                    // Creation d'un fichier pour le detail .dlog et suppression du .log
                    $s = filectime($file2);
                    $DDate = date("Ymd", $s);
                    $this->MakeLogFile($DDate, $Log);
                    unlink ($file2);

                    for ($i = 0; $i < count($Log); $i++)
                    {    
                        if (!empty($Log[$i]))
                        {
                            $Part = explode("|", $Log[$i]);
                            $Part2 = explode("|", $Log[$i+1]);
                            
                            if (!empty($Part[8]))
                            {
                                //General count for Year, Month, Day, Host, REferer... Just one entry per log, not detail
                                if (date("Y", $Part[6]) != 1970)
                                {            
                                    $tLogCountY[date("Y", $Part[6])] += 1;
                                    $tLogCountM[date("Y", $Part[6]).date("m", $Part[6])] += 1;
                                    $tLogCountD[date("Y", $Part[6]).date("m", $Part[6]).date("d", $Part[6])] += 1;
                                }
                                // -- HOST -- \\
                                if (!empty($Part[2]))
                                {
                                    preg_match("/[^\.\/]+\.[^\.\/]+$/",$Part[2],$matches);
                                    if (is_numeric($matches[0]))
                                        $host = $Part[2];
                                    else
                                    {
                                        $host = $matches[0];
        
                                        //-- COUNTRY --\\
                                        $Tnat = explode(".", $host);
                                        $tLogCountCountry[date("Y", $Part[6]).date("m", $Part[6]).date("d", $Part[6])][$Tnat[1]] += 1;
                                    }
                                    $tLogCountHost[date("Y", $Part[6]).date("m", $Part[6]).date("d", $Part[6])][$host] += 1;
                                }

                                // -- REFERER -- \\
                                if (!empty($Part[3]))
                                {
                                    preg_match("/^(http:\/\/)?([^\/]+)/i", $Part[3], $matches);
                                    
                                    $YDom = $_SERVER["HTTP_HOST"];
                                    if(!preg_match ("/$YDom/i", $Part[3]))
                                    {
                                        $tLogCountReferer[date("Y", $Part[6]).date("m", $Part[6]).date("d", $Part[6])][$matches[0]] += 1;
                                        $tLogCountWithRef[date("Y", $Part[6]).date("m", $Part[6]).date("d", $Part[6])] += 1;
                                    }
                                }
                                else
                                    $tLogCountWithOutRef[date("Y", $Part[6]).date("m", $Part[6]).date("d", $Part[6])] += 1;

                                //-- BROWSER --\\
                                if (!empty($Part[4]))
                                    $tLogCountBrowser[$Part[4]] += 1;

                                //-- HOURS --\\
                                $tLogCountHour[date("Y", $Part[6]).date("m", $Part[6]).date("G", $Part[6])] += 1;

                            }
                            elseif (!empty($Part[6]) && $Part2[6] < 43200)
                            {
                                $start = $Part[6];
                                $end = $Part2[6];
                
                                if (empty($end))
                                    $time = 0;
                                else                
                                    $time = ($end - $start);
    
                                $tTotalTime[date("Y", $Part[6]).date("m", $Part[6]).date("d", $Part[6])] += $time;
                            }

                            //-- COUNT TOTAL HIT --\\
                            $tTotalHits[date("Y", $Part[6]).date("m", $Part[6]).date("d", $Part[6])] += 1;

                            //-- COUNT ALL PAGES --\\
                            if (!empty($Part[5]))
                            {    
                                if (preg_match ("/\?/i", $Part[5]))
                                {
                                    $Pagex = explode($this->explode_char, $Part[5]);
                                    $Page = $Pagex[0];
                                }
                                else
                                    $Page = $Part[5];

                                if ($Page == '/' || $Page == '/index.php' || $Page == 'index.php')
                                    $Page = "/index.php";

                                $tLogCountPage[date("Y", $Part[6]).date("m", $Part[6]).date("d", $Part[6])][$Page] += 1;
                            }
                            
                        }
                    }
                }
            }    
        }

        if (!empty($tLogCountY))
            $this->SerializeLog($this->SerFileYear,             $tLogCountY);
        if (!empty($tLogCountM))
            $this->SerializeLog($this->SerFileMonth,             $tLogCountM);
        if (!empty($tLogCountD))
            $this->SerializeLog($this->SerFileDay,                 $tLogCountD);
        if (!empty($tTotalTime))
            $this->SerializeLog($this->SerFileTotalTime,         $tTotalTime);
        if (!empty($tLogCountWithOutRef))
            $this->SerializeLog($this->SerFileWithOutReferer,     $tLogCountWithOutRef);
        if (!empty($tLogCountWithRef))
            $this->SerializeLog($this->SerFileWithReferer,         $tLogCountWithRef);            
        if (!empty($tLogCountBrowser))
            $this->SerializeLog($this->SerFileBrowers,             $tLogCountBrowser);
        if (!empty($tTotalHits))
            $this->SerializeLog($this->SerFileTotalHits,         $tTotalHits);
        if (!empty($tLogCountHour))
            $this->SerializeLog($this->SerFileTotalHour,         $tLogCountHour);
            
        if (!empty($tLogCountHost))
            $this->SerializeLog2Entry($this->SerFileHost,         $tLogCountHost);
        if (!empty($tLogCountReferer))
            $this->SerializeLog2Entry($this->SerFileReferer,     $tLogCountReferer);
        if (!empty($tLogCountCountry))
            $this->SerializeLog2Entry($this->SerFileCountry,     $tLogCountCountry);
        if (!empty($tLogCountPage))
            $this->SerializeLog2Entry($this->SerFilePages,         $tLogCountPage);
    }
    
    function RetunrDetailDay($Year, $Month, $Day)
    {
        $Path = $this->IPath;
        if (file_exists($this->IPath . $Year . $Month . $Day .".dlog"))
        {
            $fd = fopen ($this->IPath . $Year . $Month . $Day .".dlog", "r");
            $contents = fread ($fd, filesize($this->IPath . $Year . $Month . $Day .".dlog"));
            fclose ($fd);

            $Log = explode("\n", $contents);

            for ($i = 0; $i < count($Log); $i++)
            {    
                $Part = explode("|", $Log[$i]);
                $Part2 = explode("|", $Log[$i+1]);

                if (!empty($Part[8]))
                {
                    $tDayLog["Uid"]         = $Part[0];
                    $tDayLog["Ip"]             = $Part[1];
                    $tDayLog["Host"]         = $Part[2];
                    
                    $tDayLog["Referer"]     = $Part[3];
                        
                    $tDayLog["Client"]         = $Part[4];
                    
                    $tDayLog["Detail"]        = 1;
                    
                    $tDayLog["Uri"]         = $Part[5];
                    $tDayLog["Time"]         = $Part[6];
                    $tDayLog["Title"]         = $Part[7];
                    $tDay[] = $tDayLog;
                }
            }
        }
        return $tDay;
    }

    function RetunrDetailDayPerVisit($Uid, $Year, $Month, $Day)
    {
        $Path = $this->IPath;
        if (file_exists($this->IPath . $Year . $Month . $Day .".dlog"))
        {
            $fd = fopen ($this->IPath . $Year . $Month . $Day .".dlog", "r");
            $contents = fread ($fd, filesize($this->IPath . $Year . $Month . $Day .".dlog"));
            fclose ($fd);

            $Log = explode("\n", $contents);
            for ($i = 0; $i < count($Log); $i++)
            {    
                $Part = explode("|", $Log[$i]);
                $Part2 = explode("|", $Log[$i+1]);

                if ($Part[0] == $Uid)
                {
                    $tDayLog["TimeStart"]         = $Part[6];
                    $tDayLog["TimeEnd"]         = $Part2[6];
                    $tDayLog["Referer"]         = $Part[3];
                    $tDayLog["Uri"]             = $Part[5];
                    $tDayLog["Title"]             = $Part[7];
                    
                    if (empty($tmp[$Part[5]]))
                    {
                        $tmp[$Part[5]] = $Part[5];
                        $tDayLogSpe["NbPage"]            += 1;
                    }
                    $tDayLogSpe["Hits"]            += 1;
                    
                    $tDay["all"][] = $tDayLog;
                    $tDay["cumul"] = $tDayLogSpe;
                }
            }
        }
        return $tDay;
    }

    
    function ReturnColorCell($moyenne)
    {
        if ($moyenne <= 1)
            $color = 'FF0000';
        elseif ($moyenne <= 2)
            $color = 'FF1900';
        elseif ($moyenne <= 3)
            $color = 'FF3200';
        elseif ($moyenne <= 4)
            $color = 'FF4B00';
        elseif ($moyenne <= 5)
            $color = 'FF6400';
        elseif ($moyenne <= 6)
            $color = 'FF7D00';
        elseif ($moyenne <= 7)
            $color = 'FF9600';
        elseif ($moyenne <= 8)
            $color = 'FFAF00';
        elseif ($moyenne <= 10)
            $color = 'FFC800';
        elseif ($moyenne <= 12)
            $color = 'FFE100';
        elseif ($moyenne <= 14)
            $color = 'FFFA00';
        elseif ($moyenne <= 16)
            $color = 'E1FA00';
        elseif ($moyenne <= 18)
            $color = 'C8FA00';
        elseif ($moyenne <= 20)
            $color = 'AFFA00';
        elseif ($moyenne <= 25)
            $color = '96FA00';
        elseif ($moyenne <= 30)
            $color = '7DFA00';
        elseif ($moyenne <= 35)
            $color = '64FA00';
        elseif ($moyenne <= 40)
            $color = '4BFA00';
        elseif ($moyenne <= 50)
            $color = '32FA00';
        elseif ($moyenne <= 100)
            $color = '19FA00';
            
        return $color;        
    }
    
    function ReturnMonth($Month)
    {
        $tMonth = array("01" => DSP_GEN_SHORTMJAN,
                        "02" => DSP_GEN_SHORTMFEB,
                        "03" => DSP_GEN_SHORTMMAR,
                        "04" => DSP_GEN_SHORTMAPR,
                        "05" => DSP_GEN_SHORTMMAY,
                        "06" => DSP_GEN_SHORTMJUN,
                        "07" => DSP_GEN_SHORTMJUL,
                        "08" => DSP_GEN_SHORTMAUG,
                        "09" => DSP_GEN_SHORTMSEP,
                        "10" => DSP_GEN_SHORTMOCT,
                        "11" => DSP_GEN_SHORTMNOV,
                        "12" => DSP_GEN_SHORTMDEC);

        return $tMonth[$Month];
    }
    
    function ReturnImgBrower($type)//changed to support more browsers
    {
        if (preg_match ("/opera/i", $type)) 
            $Img = "opera.gif";
        elseif (preg_match ("/Konqueror/i", $type)) 
            $Img = "konqueror.gif";
        elseif (preg_match ("/MSIE/i", $type)) 
            $Img = "ie.gif";
        elseif (preg_match ("/Mac/i", $type))
            $Img = "mac.gif";
        elseif (preg_match ("/SAGEM/i", $type))
            $Img = "gsm.gif";
        elseif (preg_match ("/Netscape/i", $type)) 
            $Img = "ns.gif";
        elseif (preg_match ("/Mozilla/i", $type)) 
            $Img = "mozzila.gif";

        $BrowserImg = "<img src='./resources/images/browser/" . $Img . "' alt='$type'>";

        return $BrowserImg;
    }

    function ReturnFlag($country)
    {
        switch($country)
        {
            case "fr":
                return "fr.gif";
            case "tr":
                return "tr.gif";
            case "com":
                return "com.gif";
            case "net":
                return "net.gif";
            case "org":
                return "org.gif";
            case "ro":
                return "ro.gif";
            case "uk":
                return "uk.gif";
            case "nz":
                return "nz.gif";
            case "au":
                return "au.gif";
            case "es":
                return "es.gif";
            case "sg":
                return "sg.gif";
            case "ch":
                return "ch.gif";
            case "pl":
                return "pl.gif";
            case "at":
                return "at.gif"; //added Austrian Flag
            case "us":
                return "us.gif";
            case "nl":
                return "nl.gif";
            case "hr":
                return "hr.gif";
            case "edu":
                return "edu.gif";
            case "ru":
                return "ru.gif";
            case "it":
                return "it.gif";
            case "de":
                return "de.gif";
            case "dm":
                return "dm.gif";
            case "be":
                return "be.gif";
            case "ca":
                return "ca.gif";
            case "bg":
                return "bg.gif";
            default:
                return "unknown.gif";
        }
    }
        
    function GetTime($interval)
    {
        $ehours = floor(($interval % 86400) / (3600)); // get hours
         $emins = floor((($interval % 86400) % 3600) / 60); // get mins
         $esecs = floor((($interval % 86400) % 3600) % 60); // get secs
        
        if ($ehours < 10)
            $ehours = "0" . $ehours;
        if ($emins < 10)
            $emins = "0" . $emins;
        if ($esecs < 10)
            $esecs = "0" . $esecs;

        $time = $ehours."-".$emins."-".$esecs;
        return $time;
    }
    
    function CheckLog()
    {
        if (is_dir($this->IPath))
            $this->TLogByDay();
        else
        {
            ?>
            <script>
                alert('<?= DSP_GEN_ALERT ?>')
            </script>
            <?
        }

        if (file_exists($this->SerFileYear))
            return true;
        else
        {
            echo "<center><h3><font face='arial' color='C7E525'>".DSP_GEN_ALERT2."</font></h3></center>";
            return false;    
        }
    }
    
    function WsOverUrl($attrib = '', $data = '', $Url = '')
    {
        global $SCRIPT_URL;
        global $REQUEST_URI;
        
        if (empty($attrib))
            if ($Url == 'SCRIPT')
                return $SCRIPT_URL;
            else
                return $REQUEST_URI;
        
        if (empty($Url))
            $Url = $REQUEST_URI;
        if ($Url == 'SCRIPT')
            $Url = $SCRIPT_URL;
    
        $tUrl = parse_url($Url);
        $Argu = $tUrl['query'];
        $Url = $tUrl['path'];
        $tArgu = array();
        if (!empty($Argu))
            $tArgu = explode('&', $Argu);
        $tDstArgu = array();
        $found = false;
        for ($i = 0; $i < count($tArgu) ; $i++)
        {
            $tInfo = explode('=', $tArgu[$i]);
            $param = $tInfo[0];
            $value = $tInfo[1];
            
            if ($param == $attrib)
                $found = true;
            if (($param == $attrib) && ($data != ''))
                $tDstArgu[] = "{$param}={$data}";
            if ($param != $attrib)
                $tDstArgu[] = $tArgu[$i];
        }
    
        if (!$found)
            $tDstArgu[] = "{$attrib}={$data}";
        
        $Argu = implode('&', $tDstArgu);
        if (!empty($Argu))
            $Url = "{$Url}?$Argu";
    
        return $Url;
    }

    function GetHit($Period)
    {
        $this->tHit = $this->UnSerializeLog($this->SerFileTotalHits);
        foreach ($this->tHit as $key => $value)
            if (preg_match ("/$Period/i", $key))
                $Hit += $value;  

        return $Hit;
    }

    function Get($Period, $File, $Limit = 10)
    {
        $tdata = $this->UnSerializeLog($File);
        $flag = 0;

        if (!empty($tdata))
            foreach ($tdata as $key => $value)
                if (preg_match ("/$Period/i", $key))
                    foreach($tdata[$key] as $k => $v)
                    {
                            $tdat[$k] += $v;  
                            $tdat["Count"] += $v;
                    }
        return $tdat;
    }
}
?>