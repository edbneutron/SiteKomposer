<? 
// *****************************************
// Site-Komposer Admin Index-Page
//
//
// *****************************************


//$sk_include_path=substr($SCRIPT_FILENAME,0,strpos($SCRIPT_FILENAME,"/sk/")+4);
//echo $SCRIPT_FILENAME;
//require_once($sk_include_path."sk.php");
session_start();
require_once("../../../sk.php");

   header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   header ("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
   header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
   header ("Pragma: no-cache");
$no_relocate=1;
require_once(INCLUDE_PATH."auth/auth_session_inc.php");
//include Files Part 2
 require_once(INCLUDE_PATH."skincludes2.php");

if ($do=="logout") {
 $title1 = "Logged out";
 $title2 = "Logged out";
 $no_relocate=1;
 unset($sess_uid);
 unset($_SESSION["sess_hash"]);
 unset($_SESSION["site_id"]);
 unset($_SESSION["uid"]);
 unset($_SESSION["username"]);
 session_unset();
// session_destroy();

$output='<div style="text-align:center;"><br><br><b>Sie sind jetzt abgemeldet!</b><br><br><p>
<div align="center"><font face="Arial, Helvetica, sans-serif" size="2"><a href="index.php">neu anmelden</a><br><br>
<a href="../../../../">zur&uuml;ck zur Homepage</a></font></div></div>';

} else {
 if (!isset($_SESSION["sess_hash"])):
       $dont_showbox=0;
       $title1 = "Login";
       $title2 = "Login";
       $output='<br><br>';
       if ($user_unknown==1) $output.='<b>Benutzer unbekannt!</b>';
       if ($passvalid=="no") $output.='<b>Falsches Passwort!</b></p>';
       $output.='<table  border="0" cellspacing="1" cellpadding="2" align="center">
            <form name="loginform" action="index.php" method="POST">
            <input type="hidden" name="do_check" value=1>
              <tr>
                <td colspan="2" align="center"><b>Anmeldung</b></td>
              </tr>
              <tr>
                <td width="85">Benutzername:</td>
                <td >
                  <input type="text" class="text" name="form_username" size="20">
                </td>
              </tr>
              <tr>
                <td width="85">Passwort:</td>
                <td >
                  <input type="password" class="password" name="form_password" size="20">
                </td>
              </tr>
              <tr>
                <td width="85">&nbsp;</td>
                <td >
                  <input type="submit" name="Submit" value="anmelden">
                </td>
              </tr>
              </form>
            </table>';
else:
        $current_user=new skuser($sess_uid);
        $usrqry = "SELECT * FROM sk_users WHERE uid =$sess_uid";
        $result = $skdb->Execute($usrqry);
        if ($result->EOF OR $result == false) 
            {
                session_destroy();//kills the session if user not in the database
                session_unset();
                $dont_showbox=0;
        $title1 = "Fehler!";
        $title2 = "Fehler!";
        $output='<b>Benutzer unbekannt oder Passwort falsch!</b><br><br>
                    Nehmen Sie mit dem Administrator Kontakt auf!';
        } else {
        $dont_showbox=0;
        $sess_site_id=0;
        $title1 = "Hauptmenue";
        $title2 = "Hauptmenue";
        $Messages.='<b>Willkommen!</b><br>Anmeldung erfolgreich<br>&nbsp;';
        $output="Hallo, <strong>".$current_user->attributes[uname]."</strong>!<br><br>";
        $output.="Sie haben sich bereits ".$current_user->attributes[logins]." mal angemeldet.<br>Zuletzt am: ".date("d.M Y - H:i",$result->fields[last_login])."<br>";
          if(isset($form_username)) {
                $current_user->loglogins();
                $_SESSION["sess_site_id"]==0;
                                  
          }
         } 
endif;
        
        
    } //logout

$alvis="2";
require_once("admin_top.inc.php");
echo $output;
include("admin_foot.inc.php");

