##################################################
#    W E B A N A L Y S E - N A N O B O D Y       #
#                 version 1.3                    #
##################################################
/////////////////////////////////////////////////////////
;/////////////////////////////////////////%+%%#%%%%%///////
;////////////////////////////////////////+%$XXXX$%%%++++++//
////////////////////////////////////////%%$@@@@@HX$$%%%+++//
///////////////////////////////;///;///;#HHHX$XH@@HXXHX%+++//
////////////////////////////////////++++XHX////%HMMMMM@X%+%///
////////////////////////////////////++%$@X%;;;;+HMM@@##@$+%//
////////////////////////////////+++++%%$H%/;;;+HM#X%$@#@$+%///
/////////////////////////////++%%%%$$XHH@$//;+@##H$+/$@@X%%///
////////////////////////////++%%%$$X@M###H%++X###@@$+$@MH$+///
////////////////////////%+%##@@###########@#M#####@@####@$%+//
//////////////;;;//////+%X@##############MM@@@@@@#MM@M##MH%+/;
//////////////;;;/////%$H@###############M###MMM@@MMMM##MM%//;;
//////////////////////%HM################MM#MMMMMM@HH@HH@M#///;;
//////////////////////HM#####################M@MMMMHXX$$$$/==////;
////////////////////+%#########MMMMMM#######MHXHHH@HXXHMMH#%%;:://///;
///////////////+///+%X########MM@@HHH@@MMM##M@HH@@@@@@###M#HH@@@@HHHHHHHHHHHHHHHHHHHHHHHHHHHHH@@@@@@@@HHHHHHHHHHHHHHHHHHHHHH@HH$//;
///////////////+///%X@######M@@@X$$%%$$X@@@###########M@X%//////+/////////////////////////////+++///////////////////////////+%H@X+;;
//////////////////+$@####MMMM$$%/;;:;/+%XXH@M@@@@@@@@MH+;::::::::=====================::::::::;;::::::::=:=:========::::::::::;$H@;;;
///%/////////////%H#####M##@X%%#%/##%%M%%%%#######XX@@H#+=%;//;%:%####M::%###H#%::+@###%+%%#%;///;+##/:%#+=%@#####%%######%///;%+@#/;
////////////////+$@####M@HX$+;$#;/H@:/HH%+%;::=:;MMX/:+X$/%;;/;:/H@%+@H/:%@$%H@@;;XH+%@H/;$@$;%/;;$MM/=%M%:$M$;=%:=/H@$;://;;;;;:/@/;
////////////////%HM#MM#HHX%+/:%#:/HX=;HH/H@%+++%/##@+/%HX/%;:;::%#@;=XM$:%H%:$@H;/@H::H@%;%@$;//;;%MM/-%M%;XMH+/;=-+H@%//%/;;;//:;@/;
/////////++++//+$@MM@@HXX$+/;:$#:+HX:;HH/@@H$H@@%MMMHXH@$=%+/+;=+@H%/X@$:$MX:+MH/+HX/%HM$;$MX;;;;;/@#+:#@+=;$HHH@%=/HMHXH#$+/;//;;@;;
/////++++++++++%X@M@@HHX%%+;;:$#:/HH:;HH/@@+=:;/:@#H/=+@X/#M@MX/%M#@@MM$:%MX:+M@//HMH@@M$;X#X::;/::$HHXH$/-=:;==#X;/HM%::/+;;;//:;@;;
/////++++++++%%XH@M@@H#X///;;:$#;+H@:/@@%MM/,--==@#X:,/M@+%XXH%:%#M#$@MX:$MX:+##/+@@%%@M%;X#X:%%$;=;/@##;::++/-,#@+/@M/--::=;;//:;X/;
////%+%%%%%%%#XH@@@@H$#X:;/;;:$#%$#M+$MM+##$:+%+/@MM$/%MH/:::;:=$#M%:H#X=X#H%+MM/%#@;:@M$;X#H+#M@+=::$M#=:/X#H+/MX:/M#$/+%+;///;:/H/:
;;///+%$$XXXH@MM@@HX%+#@+=;/;;%@@HH@HH@X/XMHHH@@$+@HH@@X+;///////HX%;$H$:%H$+/XX//XX/:XH+:%HHH@H$/::;%H%;;;%@HHHH/:/$H@HHHX/://=:%#/;
;/////+%%XHH@#MM@H$%+/+HH/=:/:;/////////:;////++;%//////;:;;;;;;;//%;//;:;/;:://:://::;+;=;+////;:::;:/%:::;/////:=:;//;///;;;=:%@M/;
;//%/%%/%%####@###%%//%%#@#@%++/////////%//////%%%/////%%+%%%%%%%++%%%+%%+/%%+/%+%/%%%%%+++%//%/%+++%%++%%++/////+++%///%%+++%##X#/;
;///%+++///;;/++///////;;+X@MMM##MMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMMM@@@MM@@MM@@MMMMM@@@MM@@@@MM@M@@@@@M@M@@@M@@MMMM@MMMMMMM@@@HX%//
##################################################
# This software is distributted as FREE software #
# If you apreciate it, you can click on my site  #
# to say Thank you or make a free donation ;)    #
# Thanks for your support                        #
##################################################

Connect to http://www.nanobody.net/pages/productions.php
for more information.

Upload to your server all scripts of archive.

-------------
Description
-------------
Web site traffic statistics tool written in PHP 4. 
WebAnalyse doesn't use any databases, or Apache logs. 
Its reports include Web site statistics by day, week, month, and year, referer, host, IP, browser. 
The big advantage lies primarily in detail of each visit, you can follow the pages or articles which are visited on your site. 
WebAnalyse can be added very easily (2 lines of code has to add) on all the pages where you wish to follow the activity.

-------------
Installation
-------------
4 steps :

		> Upload the folder (you can rename the folder) of webanalyse on the root directory.
		> In the folder, make a new folder named "log" with CHMOD 777 (the log file are write in this) (Secure with .htaccess)
		> add the 2 lines of code on each pages, with the whole beginning of the pages in you want to analyze the traffic.
	
			Exemple :
				<?
				require("../webanalyse/include/stats_main.inc");
				$stats = new init(true, '../webanalyse/'); // 2 params. true/false for activate or not, and the path of webanalyse.
				?>
				
		> access consultation page exemple :
			http://yourwebsite/webanalyse/
			
that's all !

-------------
version 1.3
-------------
Modifications :
	Display:
		> The Maggot is always alive !!! ;)
		> Panel of configuration (choose language, choose number of result, choose ip to block).
		> Module multilinguage (French, Deutch, English, Spanish, Bulgarian).
		> 2 mode of display (General or details)! ;)
		> Integration of PHPClalendar for a better navigation.
		> Possibility to define the number of results visible in the Top listing.
		> Possibility to define a classement by Year, Month, Day in the Top listing.
		> Possibility to block a list of adress ip (Activate or not).
		> Automaticly check if a new release is available.
		> Include a module of Sarah King  [www.pcpropertymanager.com].
	 
	Treatment: 
		> Better management of the total time of navigation like on the detail of navigation.
		> Unique visitor (Module of Sarah)
		> Management of the countries.
		> Processing the data improved, the old version put too much time has to list the history. ("serialisation" is back) 
		> Idem for the graph. 

Correction of Bug :
		> Calendar, with date selected
		> Percent of result
		> Log the visitor with uniqid and cookies for better count.
				
Bugs fixed:
        > No bugs fixed.


----------------------------------------------------------------------------------
OLDER VERSION         
----------------------------------------------------------------------------------
version 1.0
-------------
Modifications :
	Display:
		> New graphic display, The Maggot is Alive ! ;)
		> Navigation improved with a small calendar.
		> Display of Best Countries.
	 
	Treatment: 
		> Management of the countries.
		> Processing the data improved, the old version put too much time has to list the history. ("serialisation" is back) 
		> Idem for the graph. 
		> Better management of the total time of navigation like on the detail of navigation.
		
Bugs fixed:
        > No bugs fixed.
         
-------------
version b0.9
-------------
Bugs fixed:
         > Slowly traitment of data.
         > Problem with host	
