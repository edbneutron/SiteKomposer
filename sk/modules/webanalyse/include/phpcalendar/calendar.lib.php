<?PHP
  // ***************************************************
  // (c) 2002 by Sebastian Lothary. All rights reserved.
  // ***************************************************
  // Function-library to show a calendar.
  // ------------------------------------
  // This library is freeware, don't delete
  // copyright-informations!!!
  // ***************************************************

  // call calendar with parameters:
  // $site = the site you have include this function,
  //         example: $PHP_SELF
  // $lan  = is the language of calendar
  //         "eng" = english
  //         "ger" = german
  //         for other languages please modifi function

  // please dont't modifi this 3 variables ($d, $m, $y)
  // $d    = chosen day
  // $m    = chosen month
  // $y    = chosen year

  // includingTag for the calendar.css
  //         location: after </header> bevor <body>
  // <link rel=stylesheet type="text/css" href="calendar.css">

  // Style-Sheet Example for the caledar.css
  // <style>
  //   <!-- 
  //     body                  {margin:0px;}
  //
  //     a                     {font-size:10pt;font-family:Helvetica,Arial;text-decoration:none;color:#003399;}
  //     a:hover               {font-family:Helvetica,Arial;text-decoration:none;color:#FF0000;}
  //
  //     font                  {font-size:10pt;font-family:Helvetica,Arial;}
  //     
  //     font.Calendar         {font-size:8pt;font-family:Helvetica,Arial;font-weight:none;text-decoration:none;color:#000000;}
  //     font.Calendar2        {font-size:10pt;font-family:Helvetica,Arial;font-weight:bold;text-decoration:none;color:#FFFFFF;}
  //     a.Calendar            {font-size:8pt;font-family:Helvetica,Arial;text-decoration:none;color:#336699;}
  //     a.Calendar:hover      {font-size:8pt;font-family:Helvetica,Arial;text-decoration:none;color:#FF0000;}
  //     a.Calendar2           {font-size:8pt;font-family:Helvetica,Arial;text-decoration:none;color:#FFFFFF;}
  //     a.Calendar2:hover     {font-size:8pt;font-family:Helvetica,Arial;text-decoration:none;color:#FFFF00;}
  //     a.Calendar3           {font-size:10pt;font-family:Helvetica,Arial;text-decoration:none;color:#FFFFFF;}
  //     a.Calendar3:hover     {font-size:10pt;font-family:Helvetica,Arial;text-decoration:none;color:#FFFF00;}
  //   -->
  // </style>

  function wrCalendar($site, $d,$m,$y, $lan="us") {
    // example for calling function
    // wrCalendar($PHP_SELF, "eng", $d,$m,$y);

    $showDay = $d;
    $showMonth = $m;
    $showYear = $y;
  
    if (trim($showDay)=="") {
      $showDay = date("d");
    }

    if (trim($showMonth)=="") {
      $showMonth = date("m");
    } else {
      if (trim($showMonth)==13) {
        $showMonth = 1;
        $showYear++;
      }
    
      if (trim($showMonth)==0) {
        $showMonth = 12;
        $showYear--;
      }
    }
  
    if (trim($showYear)=="") {
      $showYear = date("Y");
    }
    
    settype ($showDay, "integer");
    settype ($showMonth, "integer");
    settype ($showYear, "integer");
  
    // 01
    $nowDay = date("d");

    //12
    $nowMonth = date("m");

    //2001
    $nowYear  = date("Y");
  
    settype ($nowDay, "integer");
    settype ($nowMonth, "integer");
    settype ($nowYear, "integer");
  
    $totalDays = trim(date ("d", mktime(0,0,0,$showMonth+1,1-1,$showYear)));
  
    $sMonth = trim(date ("F", mktime(0,0,0,$showMonth,1,$showYear)));
    $sYear = trim(date ("Y", mktime(0,0,0,$showMonth,1,$showYear)));

    $i = 1;

    switch (trim(date ("D", mktime(0,0,0,$showMonth,1,$showYear)))) {
      case "Mon":
        $i = 1;
        break;
      case "Tue":
        $i = 2;
        break;
      case "Wed":
        $i = 3;
        break;
      case "Thu":
        $i = 4;
        break;
      case "Fri":
        $i = 5;
        break;
      case "Sat":
        $i = 6;
        break;
      case "Sun":
        $i = 7;
        break;
    }
  
    switch (trim($lan)) {
      case "us":
        $Mon = "M";
        $Tue = "T";
        $Wed = "W";
        $Thu = "T";
        $Fri = "F";
        $Sat = "S";
        $Sun = "S";
        break;
      case "de":
        $Mon = "M";
        $Tue = "D";
        $Wed = "M";
        $Thu = "D";
        $Fri = "F";
        $Sat = "S";
        $Sun = "S";
        switch (trim($sMonth)) {
          case "January":
            $sMonth = "Januar";
            break;
          case "February":
            $sMonth = "Februar";
            break;
          case "March":
            $sMonth = "M&auml;rz";
            break;
          case "April":
            $sMonth = "April";
            break;
          case "May":
            $sMonth = "Mai";
            break;
          case "June":
            $sMonth = "Juni";
            break;
          case "July":
            $sMonth = "Juli";
            break;
          case "August":
            $sMonth = "August";
            break;
          case "September":
            $sMonth = "September";
            break;
          case "October":
            $sMonth = "Oktober";
            break;
          case "November":
            $sMonth = "November";
            break;
          case "December":
            $sMonth = "Dezember";
            break;
          
        }
        break;
      default:
        $Mon = "M";
        $Tue = "T";
        $Wed = "W";
        $Thu = "T";
        $Fri = "F";
        $Sat = "S";
        $Sun = "S";
    }
  
    echo "  <!-- begin calendar (c) 2001 Sebastian Lothary (www.fireball88.de) -->\n";
    echo "  <table width=110 cellspacing=0 cellpadding=0 border=0 bgcolor='8686A7'>\n";
    echo "   <tr>\n";
    echo "    <td align=left bgcolor=#8686A7><font class=calendar2>$sMonth $sYear</font></td>\n";
    echo "   </tr>\n";
  	echo "	</table>";
  	
    echo "  <table width=110 cellspacing=1 cellpadding=0 border=0 bgcolor='6C5446'>\n";
    echo "   <tr>\n";
    echo "    <td width=25 align=center bgcolor=#cccccc><font class=calendar>$Mon</font></td>\n";
    echo "    <td width=25 align=center bgcolor=#cccccc><font class=calendar>$Tue</font></td>\n";
    echo "    <td width=25 align=center bgcolor=#cccccc><font class=calendar>$Wed</font></td>\n";
    echo "    <td width=25 align=center bgcolor=#cccccc><font class=calendar>$Thu</font></td>\n";
    echo "    <td width=25 align=center bgcolor=#cccccc><font class=calendar>$Fri</font></td>\n";
    echo "    <td width=25 align=center bgcolor=#cccccc><font class=calendar>$Sat</font></td>\n";
    echo "    <td width=25 align=center bgcolor=#cccccc><font class=calendar>$Sun</font></td>\n";
    echo "   </tr>\n";

    $i--;

    echo "   <tr>\n";
    echo "    <td bgcolor=#A6A6CC colspan=$i>\n";

    $i++;
    $Day = 1;
    while ($Day <= $totalDays) {
      if ($i==1) {
        echo "   <tr>\n";
      }
	if ($Day < 10) $nDay = "0" . $Day;
	else $nDay = $Day;
		
	if ($showMonth < 10) $nshowMonth = "0" . $showMonth;
    else $nshowMonth = $showMonth; 
      switch (trim(trim($Day).".".trim($showMonth).".".trim($showYear))) {
        case trim(trim($showDay).".".trim($showMonth).".".trim($showYear)):
          echo "    <td align=center bgcolor=#336699><a href=$site?Day=$nDay&Month=$nshowMonth&Year=$showYear class=calendar2>$Day</a></td>\n";
          break;
        case trim(trim($nowDay).".".trim($nowMonth).".".trim($nowYear)):
          echo "    <td align=center bgcolor=#FF0000><a href=$site?Day=$nDay&Month=$nshowMonth&Year=$showYear class=calendar2>$Day</a></td>\n";
          break;
        default:
          echo "    <td align=center bgcolor=#A6A6CC><a href=$site?Day=$nDay&Month=$nshowMonth&Year=$showYear class=calendar>$Day</a></td>\n";
      }
    
      if ($i==7) {
        $i = 0;
        echo "   </tr>\n";
      }

      $Day++;
      $i++;
    }
  
     switch ($i) {
      case 1:
        break;
      case 2:
        echo "    <td bgcolor=#A6A6CC colspan=6>&nbsp;</td>\n";
        break;
      case 3:
        echo "    <td bgcolor=#A6A6CC colspan=5>&nbsp;</td>\n";
        break;
      case 4:
        echo "    <td bgcolor=#A6A6CC colspan=4>&nbsp;</td>\n";
        break;
      case 5:
        echo "    <td bgcolor=#A6A6CC colspan=3>&nbsp;</td>\n";
        break;
      case 6:
        echo "    <td bgcolor=#A6A6CC colspan=2>&nbsp;</td>\n";
        break;
      case 7:
        echo "    <td bgcolor=#A6A6CC>&nbsp;</td>\n";
        break;
    }
    echo "   </tr>\n";

    $LastMonth = $showMonth-1;
    if ($LastMonth < 10)  $LastMonth = "0" .$LastMonth;
    else	$LastMonth = $LastMonth;
    
    $NextMonth = $showMonth+1;
    if ($NextMonth < 10)  $NextMonth = "0" .$NextMonth;
    else	$NextMonth = $NextMonth;
    	

    echo "   <tr>\n";
    echo "    <td align=left colspan=4 bgcolor=#A6A6CC><a href=$site?Day=01&Month=$LastMonth&Year=$showYear class=calendar3>&lt;&lt;</a></td>\n";
    echo "    <td align=right colspan=3 bgcolor=#A6A6CC><a href=$site?Day=01&Month=$NextMonth&Year=$showYear class=calendar3>&gt;&gt;</a></td>\n";
    echo "   </tr>\n";

    echo"  </table>\n";
    echo "  <!-- end calendar (c) 2001 Sebastian Lothary (www.fireball88.de) -->\n";
  }
?>