<?php
/*###################################################################################
# S T A T S _ W O R D S                 Version 0.1                                 #
# Copyright 2003 Sarah King.            sarah@pcpropertymanager.com                 #
# Last Updated 10 April 2003            http://www.pcpropertymanager.com            #
#                                                                                   #
# An Addon to WebAnalyse from http://webanalyse.sourceforge.net/                    #
#####################################################################################
#                                                                                   #
# Copyright 2003 Sarah King. All Rights Reserved.                                   #
#                                                                                   #
# This program may be modified as long as the copyright information remains intact. #
#                                                                                   #
# Any use of this program is entirely at the risk of the user. No liability will be #
# accepted by the author.                                                           #
#                                                                                   #
# This code must not be sold or distributed, even in modified form, without the     #
# written permission of the author. Use on commercial websites is permitted.        #
#                                                                                   #
#####################################################################################
#                                                                                   #
# The purpose of this code is to extend the functionality of Web-Analyse v1.0       #
# to extract the words used within Search Engine Strings. This allows the content   #
# to better understand the language which is effective. Where words are targetted   #
# but aren't being used to successfully find a site then the site may benefit from  #
# search engine optimisation techniques.   
#                                                                                   #
# The results are not stored in a log file.                                         #
#                                                                                   #
#####################################################################################
#                                                                                   #
# M E T H O D S                                                                     #
# StatsWords($maxDays)  Constructor                                                 #
* Used by the maintenance page to build up the serialization files used to analyze  #
* the referrers                                                                     #
# addHostIgnore($host)                                                              #
# addToEngines($engine, $letter)                                                    #
# addToException($url)                                                              #
# addToNoise($word)                                                                 #
* Used by the maintenance page to remove items from the serialization files         #
# delEngine($engine)                                                                #
# delHostIgnore($host)                                                              #
# delNoise($word)                                                                   #
* Used to display the arrays. Hyperlink is boolean and indicates whether a link to  #
* the maintenance page is needed - this is managed by showLastRow().                #
# displayEngines($hyperlink)                                                        #
# displayExceptions($hyperlink, $maxRows)                                           #
# displayHostIgnore($hyperlink)                                                     #
# displayNoise($hyperlink)                                                          #
# showLastRow($show, $cols, $text, $anchor)                                         #
* Called by the constructor to get the info from the serialization files            #
* If the logs don't exist then default settings are used                            #
# getAEngines()                                                                     #
# getANoise()                                                                       #
# getHostIgnore()                                                                   #
* Used by the add and delete functions to save the changes made. These call a       #
* serialization function within this class which completely overwrites the old file.#
# setAEngines()                                                                     #
# setANoise()                                                                       #
# setHostIgnore()                                                                   #
* Main Functions to process the referrer strings.                                   #
# addToWord($element):  add to the word array                                       #
# checkIgnoreRef($url): if true then marked for ignoring - go to next $ref,         #
#                       if false then ok to check if a search engine                #
# displayWords($hyperlink, $maxRows): the main function to display the words        #
# getAWords():          the main function to process the referrers.                 #
# getQueryFromEngine($fullUrl): return string of words used in the query            #
# getQueryString($url, $host, $tag): return string of words used in the query       #
#                       for a specific engine                                       #
* Additional functions to manage the arrays and files.                              #
# SerializeLog($file, $tdata)                                                       #
# deleteKey($array, $keyname) returns array()                                       #
#                                                                                   #
###################################################################################*/
Class StatsWords
{
    var $wordCount = 0;                 // tally of words used in calculating the %
    var $aWords = array();              // Array of Words - added to as they are found
    var $aEngines = array();            // Array of Engines - reworked from $aEngineNames
    var $aEngineNames = array();        // Array of Engines - populated from file
    var $aIgnore = array();             // Array of Hosts to Ignore
    var $aException = array();          // Array of Referrers which aren't "ignored" or engines
    var $aNoise = array();              // Array of noise words - populated from file
    var $SerHostIgnore = "./log/__w_ignore.ser";
    var $SerEngineNames = "./log/__w_enginenames.ser";
    var $SerNoise = "./log/__w_noise.ser";
    var $stats;                         // object Stats from Web_Analyse module
    var $process;                       // object Process from Web_Analyse module
    var $maxDays;                       // days to limit the results by.
    
    //Constructor
    function StatsWords($maxDays = 30)
    {
        $this->maxDays = $maxDays;
        $this->process = new Process('./',CONTENT_PATH);
        $this->stats = new Stats_Display();
        $SerHostIgnore = $this->process->IPath."__w_ignore.ser";
        $SerEngineNames = $this->process->IPath."__w_enginenames.ser";
        $SerNoise = $this->process->IPath."__w_noise.ser";
        $this->aIgnore = array();
        $this->getHostIgnore();
        $this->getAEngines();
        $this->getANoise();
    }//StatsWords
    
    function getHostIgnore()
    {
      $this->aIgnore = $this->process->UnSerializeLog($this->SerHostIgnore);
      if (count($this->aIgnore) == 0)
      { //first time in
        $self = $_SERVER["HTTP_HOST"];  
        if (substr($self, 0, 4) == "www.") $self = substr($self, 4);

        $this->aIgnore = array( $self => 0,
                                "dmoz.org" => 0,
                                "directory.google" => 0,
                                "phpbuilder.com"  => 0,
                                "phpclasses.org"=> 0
                                );
      }  
    }//getHostIgnore
    
    function setHostIgnore()
    {
        ksort($this->aIgnore);
        reset($this->aIgnore);
        $this->SerializeLog($this->SerHostIgnore, $this->aIgnore);
    }//setHostIgnore
    
    function addHostIgnore($host)
    {
      foreach($this->aIgnore as $k=>$v)
      {
        if ($k == $host) return;
      }
      $this->aIgnore[$host] = 0;
      $this->setHostIgnore();

    }//addHostIgnore
    
    function delHostIgnore($host)
    {
      if (strlen($host) < 2) return;
      $this->aIgnore = $this->deleteKey($this->aIgnore, $host);
      $this->setHostIgnore();
    }//delHostIgnore
    
    function displayHostIgnore($hyperlink=true)
    {//we want to see every noise word
            ?>
            <table border='0' cellpadding='0' cellspacing='1' bgcolor='4C4E72'>
                <tr bgcolor='8686A7'>
                    <td width='200px'><font class='smallgrey'>&nbsp;Ignore these Referrers&nbsp;</font></td>
                </tr>
            <?
            $flag = 0;
            $target = ($hyperlink)? "target='_blank'" : "";
            asort($this->aIgnore);
            reset($this->aIgnore);
                
            foreach ($this->aIgnore as $key => $val)
            { //$val is not used.
                echo "<tr bgcolor='9899BC'><td align='center'><font class='verysmallgrey'><a href='words.php?action=delHostIgnore&host=$key' class='small2' $target title='Stop ignoring referrers with $key in their url.'>$key</a></font></td></tr>";                 
            }   
            $this->showLastRow($hyperlink, 1, "Manage Referrers", "hosts");
    }//displayHostIgnore

    function getAWords()
    {
      $tDay = $this->process->UnSerializeLog($this->process->SerFileDay);
      $loops = 0;
      foreach($tDay as $k => $v) 
      {
        if ($loops > $this->maxDays) return;
        $loops++;
        $Year = substr($k,0,4);
        $Month = substr($k,4,2);
        $Day = substr($k,6,2);
        $Detail = $this->process->RetunrDetailDay($Year, $Month, $Day);

        if (!empty($Detail))
            arsort( $Detail);
        
        for ($i = 0; $i < count($Detail); $i++)
        {
          $ref = $Detail[$i]["Referer"];
          $words = "";
          if (!$this->checkIgnoreRef($ref))
          { 
            $words = $this->getQueryFromEngine($ref);
            
          if (empty($words)) $this->addToException($ref);
          else
          {
            $words = urldecode($words);
            $bits = explode(" ", $words);
            foreach($bits as $word)
            { 
                $this->addToWord($word);
            }
          }
          }//process list of words 
        }//foreach $ref
      }//foreach day
    }//getAWords
    
    function getQueryFromEngine($fullUrl)
    {
        $words = "";
        foreach($this->aEngines as $k=>$v)
        { 
          $words = $this->getQueryString($fullUrl, $k, $v);
          if (!empty($words)) return $words;
        }   
        return $words;    
    }//getQueryFromEngine
    
    function getQueryString($url, $host, $tag)
    {
    //only works for google and msn
      $match = "/\b$host\b/i";
      $query = "";
      $tagLen = strlen($tag);
      if (preg_match( $match, $url))
      {
        $tail = substr($url, strpos($url, "?")+1);
        $qarray = explode("&", $tail);

        foreach($qarray as $qbit)
        { 
         
            if (substr($qbit, 0, $tagLen) == $tag) $query = $qbit;
        }
        
        if (preg_match("/\b=\b/i", $query))
            $query = substr($query, strpos( $query, "=", 1)+1);
      }
      return $query;
    }//getQueryString1
    
    function checkIgnoreRef($url)
    {
      $url = trim($url);
      if (empty($url)) return true;
      error_reporting(E_ERROR);
      if(!is_array($this->aIgnore))$this->aIgnore = array();
      reset($this->aIgnore);
      foreach($this->aIgnore as $k=>$v)
      {
          $k = addslashes($k);
          $match = "/\b$k\b/i";
          if (preg_match( $match, $url)) return true;
      }
      return false;
    }//checkIgnoreRef
    
    function addToWord($element) 
    {
      $found = false;
      $element = trim($element);
      if (strlen($element)< 2) return;
      if (substr($element,0,1) == "\"") $element = substr($element, 1);
      $rev = strrev($element);
      if (substr($rev,0,1) == "\"")
      {
        $rev = substr($rev, 1);
        $element = strrev($rev);
      }
      //check again
      if (strlen($element)< 2) return;
      $element = strtolower($element);
      foreach($this->aNoise as $key => $val)
      {
        if ($key == $element) return; //nothing more to do
      }

      //if (in_array($element, $this->aNoise)) return;
      
      foreach($this->aWords as $key => $val)  
      {
    
        if ($key == $element && !$found) 
        {
          $this->aWords[$element] = ++$val;
          $found = true;
        }
      }
      if (!$found) $this->aWords[$element] = 1;
      $this->wordCount++;
    }//addToWord
    
    function displayWords($hyperlink=true, $maxRows=10)
    {
        $this->getAWords();
        ?>
            <table border='0' cellpadding='0' cellspacing='1' bgcolor='4C4E72'>
                <tr bgcolor='8686A7'>
                    <td width='20px'><font class='smallgrey'>&nbsp;cnx&nbsp;</font></td>
                    <td width='90px'><font class='smallgrey'>&nbsp;word&nbsp;</font></td>
                    <td width='40px'><font class='smallgrey'>&nbsp;%&nbsp;</font></td>
                </tr>
        <?
        $flag = 0;
        $target = ($hyperlink)? "target='_blank'" : "";
        arsort($this->aWords);
        reset($this->aWords);
                
        foreach ($this->aWords as $key => $value)
        {
            if ($flag < $maxRows)
            {
                if ($value > 0)
                    $Moyenne = ($value * 100) / $this->wordCount;
                else
                    $Moyenne = (1 * 100) / $this->wordCount;
                            
                echo "<tr bgcolor='9899BC'>";
                echo "<td align='center'><font class='verysmallgrey'>$value</font></td>";                   
                echo "<td align='center'><font class='verysmallgrey'><a href='words.php?action=addNoise&word=$key' class='small2' $target title='Add $key to the list of noise words.'>$key</a></font></td>";
                echo "<td align='center' bgcolor='" . $this->process->ReturnColorCell($Moyenne) . "'><font class='smallblack'>" . round($Moyenne,2) . "%</font></td>";
                echo "</tr>";
            }else break; // PERFORMANCE!!!
            $flag++;
        }
        $this->showLastRow($hyperlink, 3, "Manage Noise Words", "noise");
    }//displayWord

    function showLastRow($show, $cols, $text, $anchor)
    {
        if ($show)
        {   
          ?>
            <tr bgcolor='9899BC'><td colspan='<? echo $cols; ?>'>
            <font class='smallgrey'>&nbsp;<a href='words.php#<? echo $anchor; ?>' target="_blank" class="small2"><? echo $text; ?></a>
            </font></td></tr>
          <?php
        }
        echo "</table>";
    }//showLastRow
    
    function addToException($url)
    {
        $this->aException[] = $url;
    }//addToException
    
    function displayExceptions($hyperlink=true, $maxRows = 10,$short=0)
    {
        ?>
            <table width='250' border='0' cellpadding='0' cellspacing='1' bgcolor='4C4E72'>
                <tr bgcolor='8686A7'>
                    <td width='200px' wrap><font class='smallgrey'>&nbsp;exception&nbsp;</font></td>
                </tr>
                <?
                $flag = 0;
                rsort($this->aException);
                reset($this->aException);
                
                foreach ($this->aException as $key)
                {//just show the top 10, and expect the user to work through these
                if ($short==1) $key=substr($key,0,50)."...";
                    if ($flag < $maxRows)
                    {
                        echo "<tr bgcolor='9899BC'>";
                        echo "<td width='250' align='center' wrap><font class='smallgrey'>&nbsp;$key&nbsp;</font></td>";                    
                        echo "</tr>";
                    }
                    else break; // PERFORMANCE!!!
                    $flag++;
                }   
                $this->showLastRow($hyperlink, 1, "Manage Exceptions", "exceptions");
    }//displayExceptions
    
    function addToEngines($engine, $letter)
    {
      foreach($this->aEngines as $k=>$v)
      {
        if ($k == $engine) return;
      }
      $this->aEngines[$engine] = $letter;
      $this->setAEngines();
    }//addToEngines
    
    function delEngine($engine)
    {
      if (strlen($engine) < 2) return;
      $this->aEngines = $this->deleteKey($this->aEngines, $engine);
      $this->setAEngines();
    }//delEngine

    function deleteKey($array, $keyname)
    {
        $newArray = array();
        foreach($array as $key => $val)
        {
          if($key <> $keyname) $newArray[$key] = $val;
        }
        return $newArray;
    }//deleteKey

    
    function getAEngines()
    {
      $this->aEngineNames = $this->process->UnSerializeLog($this->SerEngineNames);
      if (count($this->aEngineNames) == 0)
      { //first time in
        $this->aEngineNames = array("google!q" => 0,
                                "google.com!as_q" => 0,
                                "search.msn.com!q" => 0,
                                "yahoo!p"  => 0,
                                "ask.com!q"=> 0,
                                "search.xtramsn!q" => 0,
                                "searchnz.co.nz!q" => 0,
                                "looksmart.com!key" => 0,
                                "searchalot.com!q" => 0,
                                "altavista.com!q" => 0,
                                "aolsearch!query" => 0
                                );
      }  
      while(list($key, $val) = each($this->aEngineNames))
      {
        $pos = strpos($key, "!");
        $engine = substr($key,0, $pos);
        $letter = substr($key, $pos + 1);
        $this->aEngines[$engine] = $letter;
      }
    }//getAEngines
    
    function setAEngines()
    {
      ksort($this->aEngines);
      unset($this->aEngineNames);
      $this->aEngineNames = array();
      foreach($this->aEngines as $key=>$val)
      {
        $this->aEngineNames["$key!$val"] = 0;
      }
      $this->SerializeLog($this->SerEngineNames, $this->aEngineNames);
    }//setAEngines
    
    function SerializeLog($file, $tdata)
    {
    //this doesn't append but writes over the old version every time
        $data = serialize($tdata);
        $fd = fopen($file, "w+");
        fputs ($fd, $data);         
        fclose($fd);
    }//SerializeLog
    
    function displayEngines($hyperlink=true)
    {//we want to see every engine
        ?>
        <table border='0' cellpadding='0' cellspacing='1' bgcolor='4C4E72'>
            <tr bgcolor='8686A7'>
                <td width='90px'><font class='smallgrey'>&nbsp;engine&nbsp;</font></td>
                <td width='50px'><font class='smallgrey'>&nbsp;flag&nbsp;</font></td>
            </tr>
        <?
            $target = ($hyperlink)? "target='_blank'" : "";
            reset($this->aEngines);
                
            foreach ($this->aEngines as $key => $value)
            {
                echo "<tr bgcolor='9899BC'>";
                echo "<td align='center'><font class='verysmallgrey'><a href='words.php?action=delSE&engine=$key' class='small2' $target title='Remove $key - $value from the list of Search Engines.'>$key</a></font></td>";                   
                echo "<td align='center'><font class='smallgrey'>$value</font></td></tr>";
            }   
            $this->showLastRow($hyperlink, 2, "Manage Search Engines", "engines");
    }//displayEngines
    
    function getANoise()
    {
      $this->aNoise = $this->stats->UnSerializeLog($this->SerNoise);
      if (count($this->aNoise) == 0)
      { //first time in
        $this->aNoise = array( "in" => 0, "for" => 0, "at" => 0);
      }  
    }//getANoise
    
    function setANoise()
    {
        ksort($this->aNoise);
        reset($this->aNoise);
        $this->SerializeLog($this->SerNoise, $this->aNoise);
    }//setANoise
    
    function addToNoise($word)
    {
      if (strlen($word) < 2) return;

      reset($this->aNoise);
      foreach($this->aNoise as $key => $val)
      {
        if ($key == $word) return;
      }
      //if it was found we wouldn't get this far
      $this->aNoise[$word] = 0;
      $this->setANoise();
    }//addToNoise
    
    function delNoise($word)
    {
      if (strlen($word) < 2) return;
      unset($this->aNoise[$word]);
      $this->setANoise();
    }//delNoise

    function displayNoise($hyperlink=true)
    {//we want to see every noise word
            ?>
            <table border='0' cellpadding='0' cellspacing='1' bgcolor='4C4E72'>
            <tr bgcolor='8686A7'>
                <td width='90px'><font class='smallgrey'>&nbsp;noise&nbsp;</font></td>
            </tr>
            <?
            $target = ($hyperlink)? "target='_blank'" : "";
            reset($this->aNoise);
                
            foreach ($this->aNoise as $key => $val)
            {//val is a workaround
                echo "<tr bgcolor='9899BC'><td align='center'><font class='verysmallgrey'><a href='words.php?action=delNoise&word=$key' class='small2' $target title='Remove $key from the list of noise words.'>$key</a></font></td></tr>";                    
            }   
            $this->showLastRow($hyperlink, 1, "Manage Noise Words", "noise");
    }//displayNoise
    
}//Class StatsWords

?>