<? require("include/stats_display.inc");
   require("include/stats_words.inc");
?>
<html>
<head>
<title>Web-Analyse Extenstion Page</title>
<link rel="stylesheet" href="./resources/style.css">
<style>
input { color:black; font-family: Arial, Helvetica, sans-serif; font-size:11px;}
h1 {    font-size: 14px;}
h2 {    font-size: 12px;}
body, table {color: black; font-family: Arial, Helvetica, sans-serif; font-size:11px;}
</style>
</head><body>
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
###################################################################################*/

        $wrds = new StatsWords();
        if (isset($action))
        {//we're returning to this form
            switch ($action)
            {
              case "addNoise": 
                $wrds->addToNoise($word); 
                break;
              case "delNoise":
                $wrds->delNoise($word);
                break;
              case "addSE":
                $wrds->addToEngines($engine, $letter);
                break;
              case "delSE":
                $wrds->delEngine($engine, $letter);
                break;
              case "addIgnore":
                $wrds->addHostIgnore($host);
                break;
              case "delHostIgnore":
                $wrds->delHostIgnore($host);
            }//switch       
          
        }
        ?><table border='0' width="100%"><tr><td><h1>Words used in Search Engine Queries</h1></td><td align="right" width="220">
<p>This code relies on an active installation of <a href="http://webanalyse.sourceforge.net/" target="_blank" title="Web-Analyse v1.00"><img src="resources/images/wa_log.gif" border='0' width="220" height="58"></a></p></td></tr></table>
        <table border="0" width="100%">
<tr><td width="350" valign="top"><h2>Top Words</h2><p>This list shows the words used most commonly by your visitors when they have used a search engine to find your site.</p>
                    <p>To remove words from the Top 10 click on the word or fill in the form in the Noise Words section below.</p>
                </td>   <td valign="top"><?php
        $wrds->displayWords(false);
        ?></td>
            </tr>
            <tr>
                <td valign="top" width="350">
                    <h2><a name="noise"></a>Noise Words</h2>
                    <p>Noise words are commonly used words in queries on sites such as ask.com. These may rise into the top 10 list and hide other useful words.&nbsp;By marking these words as noise they are ommitted from the statistics.</p>
                    <p>To add mark a word as &quot;Noise&quot; either click on the word in the Top Words list or enter it into the form below and click the &quot;Add Noise Word&quot; button.</p>
                    <form name="frmWords" action="words.php" method="get">
                        Word <input type="text" name="word" size="10">&nbsp;<input type="submit" name="submit" value="Add Noise Word"><input type="hidden" value="addNoise" name="action">
                    </form>
                </td>
                <td valign="top"><?php  
        //$wrds->setHostIgnore();
        $wrds->displayNoise(false);
        ?></td>
                
            </tr>
            <tr>
    <td valign="top" width="350"><a name="engines"></a>
                    <h2>Search Engines</h2>
                    <p>This list shows the Search Engines currently recognised. You can add to this list.</p>
                    <p>Search Engines are identified using a common string from their URL. The list may be added to by putting a unique part of the web address into the field below and the letter or letters which denote the start of the search string. </p>
                    <p>By Viewing the Exception list you can identify any new search engines which are directing visitors to your site.</p>
                    <p>To remove a search engine click on it's name in the search engine list.</p>
                    <form name="frmEngine" action="words.php" method="get">
                        Engine <input type="text" name="engine" size="20">
                        &nbsp;<br/>
                        Flag <input type="text" name="letter" size="5"><input type="submit" name="submit" value="Add Search Engine"><input type="hidden" value="addSE" name="action">
                    </form>
                </td>
                <td valign="top"><?php
        $wrds->displayEngines(false);       
        ?></td>
            </tr>
            <tr><td valign="top" width="350">
                    <h2>Exceptions</h2>
                    <p>This list shows the referrers not recognised as either being Search Engines or &quot;Common Referrers&quot;. Use this list to identify new Search Engines. You can keep the list short by adding common referrers to the list below.</p>
                </td>
                <td valign="top"><a name="engines"></a><?php
        $wrds->displayExceptions(false,20,0);
        ?></td>

        <tr>        <td valign="top" width="350"><a name="hosts"></a>
                    <h2>Common Referrers</h2>
                    <p>Common Referrers are sites with links to yours. These may be directories, links pages on other sites, or bulletin boards. </p>
                    <p>These sites will, initially, be shown on your exception list. It is expected that you will want to remove these from the exception list so that you can see new Search Engines more clearly. </p>
                    <p>Common Referrers are checked before Search Engines which is why &quot;directory.google&quot; won't be treated like a search engine yet &quot;google&quot; will be.</p>
                    <p>To add a &quot;Common&nbsp;Referrer&quot; you put a unique part of the web address into the field below and click &quot;Add Host to Ignore&quot;. </p>
                    <p>To remove a &quot;Common Referrer&quot; click on the referrer's name in the list.</p>
                    <p>This list is not ranked and is not intended to replace the &quot;Top Referrers&quot; list</p>
                    <form name="frmIgnore" action="words.php" method="get">
                        Referrer <input type="text" name="host" size="30">&nbsp;<input type="submit" name="submit" value="Add Referrer"><input type="hidden" value="addIgnore" name="action">
                    </form>
                </td>
<td valign="top">
        <?php $wrds->displayHostIgnore(false); ?>
        </td>
</tr></table>
    </body></html>