<?php
/*###################################################################################
# S T A T S _ U N I Q U E               Version 0.1                                 #
# Copyright 2003 Sarah King.            sarah@pcpropertymanager.com                 #
# Last Updated 10 April 2003			http://www.pcpropertymanager.com            #
# 						                                                            #
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
# To use the existing information to get a count of unique visitors for the current #
# day.                                                                              #
#                                                                                   #
#####################################################################################
#                                                                                   #
# M E T H O D S                                                                     #
# StatsUnique($show)  Constructor	                                                #
* Main Functions to process the referrer strings.                                   #
# setVisitorCnt():  get the number of unique visitors                               #
# addToVisitors($visitor): checks the ip, if new then adds to array                 #
#                   if false then ok to check if a search engine                    #
# showVisitorCnt(): outputs the count with formatting                               #
#                                                                                   #
###################################################################################*/
Class StatsUnique
{
 	var $rawData = array();
	var $aVisitors = array();			// Array of unique visitors
	var $cnt = 0;
	var $process;						// object Process from Web_Analyse module
	
	//Constructor
	function StatsUnique($rawData, $show = false)
	{
		$this->rawData = $rawData;
		$this->setVisitorCnt();
		if ($show) $this->showVisitorCnt();
	}//StatsUnique
	
	function setVisitorCnt()
	{
		for ($i = 0; $i < count($this->rawData); $i++)
		{
		  $ip = $this->rawData[$i]["Ip"];
		  $this->addToVisitors($ip);
		}//foreach $ref
		$this->cnt = count($this->aVisitors);
	}//setVisitorCnt
	
    function showVisitorCnt()
	{
	  echo "<font class='verysmallgrey'>Unique Visitors :</font><div align='right'><font class='smallgrey'>";
	  echo $this->cnt;
	  echo "&nbsp;&nbsp;</font></div>";
	}
	
	function addToVisitors($visitor)
	{
	  if (!in_array( $visitor, $this->aVisitors ))
	  	$this->aVisitors[] = $visitor;
	}//addToVisitors
}//Class StatsUnique
?>