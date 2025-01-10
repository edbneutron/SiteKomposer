<?php
/*
 * Login-Form for Page-Realms
 */

require_once(INCLUDE_PATH."auth/auth_session_inc.php");

$LoginPageId=$GLOBALS['LoginPageId'];
$RequestedPageId=$GLOBALS['RequestedPageId'];
if($RequestedPageId<1) $RequestedPageId=$GLOBALS['mid'];

/* Logout */
if(isset($_REQUEST['logout'])){
	 	session_destroy();//kills the session if user not in the database
        session_unset();
	 	unset($_SESSION["sess_uid"]);
	 }
	
	 if (!isset($_SESSION["sess_uid"])){
       $dont_showbox=0;
       $title1 = "Login";
       $title2 = "Login";
       $output='<br><br>';
       
       if ($user_unknown==1) $output.='<b>Benutzer unbekannt!</b>';
       if ($passvalid=="no") $output.='<b>Falsches Passwort!</b></p>';
       $output.='<table  border="0" cellspacing="1" cellpadding="2" >
            <form name="loginform" action="index.php?mid='.$RequestedPageId.'" method="POST">
            <input type="hidden" name="do_check" value=1>
            <input type="hidden" name="RequestedPageId" value="'.$RequestedPageId.'">
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
       
	 }else{
        $current_user=new skuser($_SESSION['sess_uid']);
        $usrqry = "SELECT * FROM sk_users WHERE uid ='".$_SESSION['sess_uid']."'";
        $result = $skdb->Execute($usrqry);
        if ($result->EOF OR $result == false) 
            {
                session_destroy();//kills the session if user not in the database
                session_unset();
                $dont_showbox=0;
        		$output='<b>Benutzer unbekannt oder Passwort falsch!</b><br><br>
                    Nehmen Sie mit dem Administrator Kontakt auf!';
        } else {
        $dont_showbox=0;
        $sess_site_id=0;
        
        $output="Hallo, <strong>".$current_user->attributes[uname]."</strong>!<br><br>";
        $output.="Sie haben sich bereits ".$current_user->attributes[logins]." mal angemeldet.<br>Zuletzt am: ".date("d.M Y - H:i",$result->fields[last_login])."<br>";
          if(isset($form_username)) {
                $current_user->loglogins();
                $_SESSION["sess_site_id"]==0;
                                  
          }
         $output.='<br/><a href="index.php?mid='.$GLOBALS['mid'].'&logout=1">abmelden</a>';
         if(isset($_POST['RequestedPageId']) && $_POST['RequestedPageId']>0){
	        ob_end_clean();
	        //echo(var_export($_POST,1));
			header('Location:index.php?mid='.$_POST['RequestedPageId']);
          }
         } 
	 }      