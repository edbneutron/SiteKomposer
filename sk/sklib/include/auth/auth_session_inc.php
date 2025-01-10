<?php
//require_once(CLASS_PATH."class.skuser.inc.php");

 if(!isset($_SESSION["sess_username"])) {
        if($_POST["form_username"]=="") {
            //session_destroy();//Kill session if user name not posted
            //die("User authentication failed, click <a href=\"index.php\">here</a> to login again.");
        } else {
                  DEBUG_out(1,"debug1","Login received. user_name:".$_POST["form_username"]);
        		  $form_username=$_POST["form_username"];
                  $usrqry = "SELECT uid,uname,pass FROM sk_users WHERE uname='$form_username'";
                  $result = $skdb->Execute($usrqry);
                  //echo $form_username.$usrqry;
            if ($result->EOF OR $result == false)
            {
                session_destroy();//kills the session if user not in the database
                $user_unknown=1;
				$Messages.="USER NOT FOUND! <br>".$skdb->Errormsg()."<br>";

            } else {
                $user_unknown=0;
                //session_destroy();
                $md5pass=md5($_POST["form_password"]);
                /* Check Password */
                if ($md5pass == $result->fields[pass]) {
	                $passvalid="yes";
	                $Messages.="USER FOUND!<br>";
	                //success user is valid grab details from database and register them in the session
	                $secret = md5( "sdfkjsdkflhkh23hkjsdk#x@%x#%DSF" );
	                $sess_uid = $result->fields["uid"];
	                
	                $expiration_time=time()+36000;
	                $sess_username = $result->fields["uname"];
	                $session_key = microtime( ) . $sess_username;
	                $sess_hash = md5( $username .$session_key .$secret ); 
	                $sess_time=time();
					
	                /* Check if user belongs to an Admin-Group */
					
	               
	                $grouplist=skgroup::getgroups($sess_uid,1,0,"group",0,"Admin");
	                
	                if($grouplist) {
	                	/* user is in Admin Group */
	                	DEBUG_out(3,"debug3","User belongs to Admin-Group".$grouplist);
	                	$_SESSION['sess_hash']=$sess_hash;
		                 if ($admin==1){
							$sess_admin=$admin; 
							$_SESSION['sess_admin']=$sess_admin;
						}
	                }
					
					$_SESSION['sess_uid']=$sess_uid;
					$_SESSION['sess_time']=$sess_time;
					$_SESSION['sess_username']=$sess_username;
	               
	                /* edb removed
					$sess_adminmode=1;
					$_SESSION['sess_adminmode']=$sess_adminmode;*/
	                
	                setcookie("expiration_time",$expiration_time,time()+3600,"/","",0);
	
					$user_logged_in=1;
					$current_user=new skuser($sess_uid);
					$current_user->loglogins();
					DEBUG_out(3,"debug1","Auth-User-Session for: ".$sess_username." id:".$sess_uid."<br>");
	                DEBUG_out(4,"debug4","User-Session:".var_export($_SESSION,1));
                }else{
                	$passvalid="no";
                }
                
                //echo $userid.$username.$admin;
                //echo"session registered";
            }
        }
    }
    else
    {
   /*     $usrqry = "SELECT id,username,password,admin FROM sk_users WHERE username='$form_username' AND password=PASSWORD('$form_password')";
        $result = $skdb->Execute($usrqry);
        if ($result == false) die("userquery failed");
        if ($result->EOF)
        {
            session_destroy();//kill session if user not in the database

        }*/
    }
    if ($do == "logout") {
    session_unset();
    session_destroy();
    //header("Location:".SK_URL."modules/sk/admin/index.php");
//kill session if do = logout eg <a href="authenticatedpage.php?do=logout">logout</a>
   }

?>