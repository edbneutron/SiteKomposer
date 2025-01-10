<?php
/***************************************
** Title........: Form Processor
** Filename.....: formproc.php
** Author.......: Richard Heyes, Edgar Bueltemeyer
** Version......: 2.03
** Notes........:
** Last changed.: 04/13/2010
** Last change..:
***************************************/

/***************************************
** Security.
***************************************/
        $security      = 0;
        $servername[]  = 'myserver.com';
        $servername[]  = SITE_URL;
        $servername[]  = '';
        $servername[]  = '192.168.0.1';
        $bad_addresses = 'badip.txt';
        $check_email   = 1;

/***************************************
** Templates.
***************************************/
        $use_templates = 1;
        $tpl_thankyou  = '';
        $tpl_email     = 'email.template.txt';

/***************************************
** Non-template redirections.
***************************************/
        $required_error = 'error.required.html';
        $bad_referer    = 'error.referer.html';
        $bad_address    = 'error.address.html';
        $invalid_email  = 'error.email.html';
        $thankyou_page  = 'thankyou.html';
    
/***************************************
** Some defaults.
***************************************/
        $email     = 'info@neutron.at';
        $recipient = 'edb@neutron.at';
        $subject   = 'Feedback from your Form Processor at '.SITE_URL;
        $addhostip = 1;
		



        /***************************************
        ** Required input check.
        ** TODO: Make new output to blank page!!!
        ***************************************/
                function check_required($key, $value){
                        if(substr($key,-9) == '_required' AND $value == ''){
                                global $required_error;
                                //header('Location: res/tpl/mail/'.$required_error);
                                include(RES_PATH.'tpl/mail/'.$required_error);
                                exit;
                        }
                }

        /***************************************
        ** And this bit handles file uploads for
        ** php4.
        ***************************************/
                function file_upload_php4($HTTP_POST_FILES){
                        if(isset($HTTP_POST_FILES) AND is_array($HTTP_POST_FILES)){
                                while(list($key, $attributes) = each($HTTP_POST_FILES)){
                                        if($attributes['tmp_name'] != 'none' AND $attributes['tmp_name'] != ''){
                                                global $num_attachments, $mail;
                                                if(get_magic_quotes_gpc() == 1 AND (is_long(strpos(strtolower($attributes['tmp_name']), 'windows')) OR is_long(strpos(strtolower($attributes['tmp_name']), 'winnt')))) $attributes['tmp_name'] = stripslashes($attributes['tmp_name']);
                                                $ctype = $attributes['type'];
                                                $origname = $attributes['name'];
                                                $attachment = fread($fp = fopen($attributes['tmp_name'], 'r'), filesize($attributes['tmp_name'])); fclose($fp);
                                                $mail->add_attachment($attachment, $origname, $ctype);
                                                $num_attachments++;
                                        }elseif(substr($key, -9) == '_required'){
                                                global $required_error;
                                                //header('Location: res/tpl/mail/'.$required_error);
                                                include(RES_PATH.'tpl/mail/'.$required_error);
                                                exit;
                                        }
                                }
                        }
                }

        /***************************************
        ** Main function for sorting out
        ** normal inputs.
        ***************************************/
                function main($key, $value, &$message, &$message_values){
                        global $tpl, $use_templates, $$key;
                        if($key != 'configfile' AND $key != 'thankyou_page' AND $key != 'recipient' AND $key != 'subject' AND $key != 'addhostip' AND $key != 'MAX_FILE_SIZE' AND !eregi('_file$|_file_name$|_file_size$|_file_type$', $key)){
                                if(is_array($value)){

                                        $message[] = $key;
                                        $message_values[] = (get_magic_quotes_gpc() == 1) ? stripslashes(implode(', ', $value)) : implode(', ', $value);
                                        if(isset($use_templates) AND $use_templates == 1){
                                                $$key = implode(', ', $value);
                                                $tpl->register('main', $key);
                                                $tpl->register('email', $key);
                                                $tpl->register('cemail', $key);
                                        }
                                }else{

                                        $message[] = $key;
                                        //$value=utf8_decode($value);
                                        $message_values[] = (get_magic_quotes_gpc() == 1) ? stripslashes($value) : $value;
                                        if(isset($use_templates) AND $use_templates == 1){
                                                if(get_magic_quotes_gpc() == 1) $$key = stripslashes($$key);
                                                $tpl->register('main', $key);
                                                $tpl->register('email', $key);
                                                $tpl->register('cemail', $key);
                                        }
                                }
                        }
                }

        /***************************************
        ** String padding function.
        ***************************************/
                function padding(&$array, $character, $length = 0){
                        if(count($array) == 0) return;
                        $longest = 0;
                        for($i=0; $i<count($array); $i++) if(strlen($array[$i]) > strlen($array[$longest])) $longest = $i;
                        if($length == 0) $length = strlen($array[$longest]);
                        for($i=0; $i<count($array); $i++){
                                $padding = $length - strlen($array[$i]);
                                for($j=0; $j<$padding; $j++) $array[$i] .= $character;
                        }
                }



########################################
# End of functions, now the code       #
########################################


/***************************************
** Check for post vars. Die if not set.
***************************************/
        //if(isset($HTTP_POST_VARS) == FALSE) die('HTTP_POST_VARS not set - you may need to enable track_vars!'); else $postvars = $HTTP_POST_VARS;
        if(isset($_POST) == FALSE) die('_POST not set - you may need to enable track_vars!'); else $postvars = $_POST;
/***************************************
** Check for security and perform
** the referer check if enabled.
***************************************/
        if($security == 1){
        for($i=0; $i<count($servername); $i++)
                    if(is_long(strpos(getenv('HTTP_REFERER'), $servername[$i])))
                $valid = 1;

        if(!isset($valid)){
            //header('Location: res/tpl/mail/'.$bad_referer);
            include(RES_PATH.'tpl/mail/'.$bad_referer);
            exit;
        }

                if(isset($bad_addresses) AND $bad_addresses != '' AND file_exists($bad_addresses)){
                        $file_array = file($bad_addresses);
                        for($i=0; $i<count($file_array); $i++) if(getenv('REMOTE_ADDR') == trim($file_array[$i]) OR getenv('REMOTE_HOST') == trim($file_array[$i])){
                        //header('Location: res/tpl/mail/'.$bad_address);
                        include(RES_PATH.'tpl/mail/'.$bad_address);
                        exit;
                        }
                }
        }


        
/***************************************
** Debug feature. If $debug is set to 1,
** script dies and prints whatever is in
** $HTTP_POST_VARS and phpinfo.
***************************************/

        if(isset($postvars['debug']) AND $postvars['debug'] == 1){
                while(list($key, $value) = each($postvars)){
                        echo $key.' = '.$value.' decode: '.utf8_decode($value)."<BR>\n";
                }
                echo "<BR><BR>\n\n";
                //$output.=$this->attributes_vars['dirname'];
                $output.=__FILE__;
                echo "<BR><BR>\n\n";
                //echo print_array($GLOBALS);//phpinfo();
                reset($postvars);
                //exit;
        }
/***************************************
** Override checking, recipient, templates,
** subject etc.
***************************************/
        if(isset($postvars['thankyou_page']) AND $postvars['thankyou_page'] != '') $thankyou_page = $postvars['thankyou_page'];
        if(isset($postvars['recipient']) AND $postvars['recipient'] != ''){
                $recipient = $postvars['recipient'];
        }elseif(!isset($recipient) OR $recipient == ''){
                echo 'Form incorrectly configured - no recipient defined. Please see the readme for details.';
                exit;
        }
        if(isset($postvars['subject']) AND $postvars['subject'] != '') $subject = utf8_decode($postvars['subject']); elseif(!isset($subject) OR $subject == '') $subject = 'Feedback from website.('.$HTTP_REFERER.')';
        if((isset($postvars['use_templates']) AND ($use_templates = $postvars['use_templates']) == 1) OR (isset($use_templates) AND $use_templates == 1)){
                if(isset($postvars['tpl_thankyou']) AND $postvars['tpl_thankyou'] != '') $tpl_thankyou = $postvars['tpl_thankyou'];
                if(isset($postvars['tpl_email']) AND $postvars['tpl_email'] != '') $tpl_email = $postvars['tpl_email'];
        }

/***************************************
** Check to see if email was included in
** form. If so check validity (if required).
** If this fails, the redirect is after
** the config file is read in. To enable
** invalid email urls in the conf file.
***************************************/
        $regex = '^([._a-z0-9-]+[._a-z0-9-]*)@(([a-z0-9-]+\.)*([a-z0-9-]+)(\.[a-z]{2,3}))$';
        if(isset($postvars['email']) AND $postvars['email'] != ''){
                $email = $postvars['email'];
                if(isset($check_email) AND $check_email == 1 AND !eregi($regex, $email)){

                        $email_redirect = 1;
                }

        }elseif(isset($postvars['email_required']) AND $postvars['email_required'] != ''){
                $email = $postvars['email_required'];
                if(isset($check_email) AND $check_email == 1 AND !eregi($regex, $email)){

                        $email_redirect = 1;
                }
        }

/***************************************
** Parse the configuration file if one
** was specified.
***************************************/
        if(isset($postvars['configfile']) AND $postvars['configfile'] != '' AND file_exists($postvars['configfile'])){
                $file_array = file($postvars['configfile']);

                for($i=0; $i<count($file_array); $i++){
                        $var_name  = trim(substr(trim($file_array[$i]),0,strpos($file_array[$i], '=')));
                        $var_value = trim(substr(trim($file_array[$i]),strpos($file_array[$i], '=')+1));
                        $$var_name = $var_value;
                }
        }

/***************************************
** The email redirect from above
***************************************/
        if(isset($email_redirect) AND $email_redirect == 1){
                //header('Location: res/tpl/mail/'.$invalid_email);
                include(RES_PATH.'tpl/mail/'.$invalid_email);
                exit;
        }

/***************************************
** Setup the objects.
***************************************/
        $mail = new html_mime_mail();

        if($use_templates == 1){
                $tpl = new rhtemplate;

                if($tpl_thankyou != '') $tpl->load_file('main', RES_PATH.'tpl/mail/'.$tpl_thankyou);
                if($tpl_email != '') $tpl->load_file('email', RES_PATH.'tpl/mail/'.$tpl_email);
                if(is_file(RES_PATH.'tpl/mail/'.'confirm_'.$tpl_email)){
                   $confirm=1;
                   //$tpl = new rhtemplate;
                   if($tpl_email != '') $tpl->load_file('cemail', RES_PATH.'tpl/mail/'.'confirm_'.$tpl_email);
                }
        }





/***************************************
** Begin the main loop. First set the
** arrays that hold the values to email.
***************************************/
        $message         = array();
        $message_values  = array();
        $num_attachments = 0;

        while(list($key,$value) = each($postvars)){
                check_required($key, $value);
                main($key, $value, $message, $message_values);
        }

        if(isset($HTTP_POST_FILES) AND is_array($HTTP_POST_FILES)) file_upload_php4($HTTP_POST_FILES);

/***************************************
** Post processing stuff.
***************************************/
        padding($message, '.');
        for($i=0; $i<count($message); $i++) $message[$i] .= '..:'.$message_values[$i];

        $time = date('H:i', time());
        $date = date('l m F Y', time());
        
/***************************************
** If Form comes from Module
** call module init-file
** check for error
***************************************/       
        if(isset($postvars['ModuleName'])){
               
	        /* include initialisation file (if there) */
			if(is_file(SK_PATH.'modules/'.$postvars['ModuleName'].'/init.php')){
				include(SK_PATH.'modules/'.$postvars['ModuleName'].'/init.php');
			}
        }
        

/***************************************
** Constructs the email. If there are
** attachments it uses the mime mail
** class, if not then normal mail().
***************************************/
        
        if(!isset($ErrNo) || $ErrNo==0){
        	/* If theres an error, don dend email.*/
        
        if(isset($use_templates) AND $use_templates == 1 AND $tpl_email != ''){
                $tpl->register('email','REMOTE_ADDR,REMOTE_HOST,HTTP_REFERER,HTTP_USER_AGENT,recipient,num_attachments,subject,time,date');
                $tpl->parse('email');
                $body = $tpl->return_file('email');
                while(preg_match('/({.+})/U',$body, $matches) == TRUE){
                $body = str_replace($matches[1], '', $body);
                }
                if($confirm==1){
                    reset($matches);
                    $tpl->register('cemail','REMOTE_ADDR,REMOTE_HOST,HTTP_REFERER,HTTP_USER_AGENT,recipient,num_attachments,subject,time,date');
                    $tpl->parse('cemail');
                    $body_confirm = $tpl->return_file('cemail');
                    while(preg_match('/({.+})/U',$body_confirm, $matches) == TRUE){
                            $body_confirm = str_replace($matches[1], '', $body_confirm);
                    }
                }
        }else{
                $body = 'At '.$time.' on '.$date.', the following information was submitted to your form at '.getenv('HTTP_REFERER')." :\r\n\r\n";
                $body .= implode("\r\n", $message)."\r\n\r\n";
                $body .= (isset($postvars['addhostip']) AND $postvars['addhostip'] == 1) ? 'Remote IP: '.getenv('REMOTE_ADDR')."\r\nRemote hostname: ".getenv('REMOTE_HOST')."\r\n" : '';
        }
        
        if($num_attachments > 0){
                $mail->add_body_text($body);
                $mail->build_message();
                $mail->send('', $recipient, '', $email, $subject);
        }else{
        		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
                //$headers = 'Content-Type: '.$email."\r\n";
                $body=nl2br($body);
                $body_confirm=nl2br($body_confirm);
                $headers .= 'From: '.$email."\r\n";
                if(isset($postvars['debug']) AND $postvars['debug'] == 1) {
                   echo $recipient.$subject.$body.$headers;
                   echo "<br>confirm:<br>".$body_confirm;
                   exit;
                }
                mail($recipient, $subject, $body, $headers);
                
                if($confirm==1) mail($email, $subject, $body_confirm, $headers);
                
        }
        }//Error-Check

/***************************************
** Email sent, now either finish the template
** and output it, redirect to the thank you
** page or simply call the thank_you()
** function.
***************************************/

        if(isset($use_templates) AND $use_templates == 1 AND $tpl_thankyou != ''){
                $tpl->register('main','REMOTE_ADDR,REMOTE_HOST,HTTP_REFERER,HTTP_USER_AGENT,recipient,num_attachments,subject,time,date');
                $tpl->parse('main');
                $output = $tpl->return_file('main');
                while(preg_match('/({.+})/U',$output, $matches) == TRUE){
                        $output = str_replace($matches[1], '', $output);
                }
                echo $output;
        }elseif(isset($thankyou_page) AND $thankyou_page != '' AND $postvars['debug'] == 0){
                header('Location: '.$thankyou_page);
                exit;
        }
?>