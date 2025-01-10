<?PHP
  // ***************************************************
  // (c) 2001 - 2002 by Sebastian Lothary. All rights reserved.
  // ***************************************************
  // this is a sample how you can use the calendar.lib
  // ***************************************************

  include("calendar.lib.php");

  echo "<html>\n";
  echo " <head>\n";
  echo "  <title>Calendar</title>\n";
  echo " </head>\n\n";

  // Cascading-Stylesheet for the visualisation
  // ***************************************************
  echo " <link rel=stylesheet type=\"text/css\" href=\"calendar.css\">\n\n";
  // ***************************************************

  echo " <body bgcolor=\"#FFFFFF\">\n";

  // call the Calendar-Function, see library for
  // defined parameters.
  // ***************************************************
  wrCalendar($PHP_SELF, $d,$m,$y, $lan);
  // ***************************************************

  echo " </body>\n";
  echo "</html>\n";
?>