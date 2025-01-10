<?php
/***************************************
** Title........: Form Processor
** Filename.....: formproc.php
** Author.......: Richard Heyes
** Version......: 2.03
** Notes........:
** Last changed.: 28/01/2001
** Last change..:
***************************************/

/***************************************
** Security.
***************************************/
        $security      = 0;
        $servername[]  = 'myserver.com';
        $servername[]  = 'localhost';
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
        $email     = 'formproc@yourwebsite.com';
        $recipient = 'edgar_b@gmx.net';
        $subject   = 'Feedback from your Form Processor';
        $addhostip = 1;




########################################
# Nothing to change below here.        #
########################################



/***************************************
** Title.........: HTML Mime Mail class (Cut down version)
** Version.......: 1.26
** Author........: Richard Heyes <richard.heyes@heyes-computing.net>
** Filename......: html_mime_mail.class
** Last changed..: 25/06/2000
** Notes.........: Based upon mime_mail.class
**                 by Tobias Ratschiller <tobias@dnet.it>
**                 and Sascha Schumann <sascha@schumann.cx>.
***************************************/
        class html_mime_mail{

                var $headers;
                var $body;
                var $multipart;
                var $mime;
                var $parts = array();

                function html_mime_mail($headers = ''){
                        $this->headers = $headers;
                }

                function add_attachment($file, $name = '', $c_type = 'application/octet-stream'){
                        $this->parts[] = array( 'body'   => $file,
                                                'name'   => $name,
                                                'c_type' => $c_type );
                }

                function add_body_text($body){
                        $this->body = $body;
                }

                function build_body(){
                        $body  = 'Content-Type: text/html'."\n";
                        $body .= 'Content-Transfer-Encoding: base64'."\n\n";
                        $body .= chunk_split(base64_encode($this->body))."\n";
                        return $body;
                }

                function build_part($i){
                        $message_part = '';
                        $message_part.= 'Content-Type: '.$this->parts[$i]['c_type'];
                        if($this->parts[$i]['name'] != '')
                                $message_part .= '; name="'.$this->parts[$i]['name']."\"\n";
                        else
                                $message_part .= "\n";

                        $message_part.= 'Content-Transfer-Encoding: base64'."\n";
                        $message_part.= 'Content-Disposition: attachment; filename="'.$this->parts[$i]['name']."\"\n\n";
                        $message_part.= chunk_split(base64_encode($this->parts[$i]['body']))."\n";
                        return $message_part;
                }

                function build_message(){
                        $boundary = '=_'.md5(uniqid(time()));

                        $this->headers.= "MIME-Version: 1.0\n";
                        $this->headers.= "Content-Type: multipart/mixed;".chr(10).chr(9)."boundary=\"".$boundary."\"\n";
                        $this->multipart = '';
                        $this->multipart.= "This is a MIME encoded message.\nCreated by html_mime_mail.class.\nSee http://www.heyes-computing.net/ for a copy.\n\n";

                        $this->multipart .= '--'.$boundary."\n".$this->build_body();

                        for($i=(count($this->parts)-1); $i>=0; $i--){
                                $this->multipart.= '--'.$boundary."\n".$this->build_part($i);
                        }

                        $this->mime = $this->multipart."--".$boundary."--\n";
                }

                function send($to_name, $to_addr, $from_name, $from_addr, $subject = '', $headers = ''){

                        if($to_name != '') $to = '"'.$to_name.'" <'.$to_addr.'>';
                        else $to = $to_addr;

                        if($from_name != '') $from = '"'.$from_name.'" <'.$from_addr.'>';
                        else $from = $from_addr;

                        mail($to, $subject, $this->mime, 'From: '.$from."\n".$this->headers);
                }

        } // End of class.



/***************************************
** Title........: Template class
** Filename.....: class.template.inc
** Author.......: Richard Heyes
** Version......: 1.12
** Notes........:
** Last changed.: 25/06/2000
** Last change..: Altered load_file() so
**                it reads the file using
**                fread() instead of file().
**                Marginally faster.
***************************************/
        class template{

                var $var_names = array();
                var $files = array();
                var $start = '{';
                var $end = '}';

                function load_file($file_id, $filename){
                        $this->files[$file_id] = fread($fp = fopen($filename, 'r'), filesize($filename));
                        fclose($fp);
                }

                function set_identifiers($start, $end){
                        $this->start = $start;
                        $this->end = $end;
                }

                function register($file_id, $var_name){
                        if(is_long(strpos($var_name, ',')) == TRUE){
                                $var_name = explode(',', $var_name);
                                for(reset($var_name); $current = current($var_name); next($var_name)) $this->var_names[$file_id][] = $current;
                        }else{
                                $this->var_names[$file_id][] = $var_name;
                        }
                }

                function parse($file_id){
                        if(isset($this->var_names[$file_id]) AND count($this->var_names[$file_id]) > 0){
                                for($i=0; $i<count($this->var_names[$file_id]); $i++){
                                        $temp_var = $this->var_names[$file_id][$i];
                                        $$temp_var=$_POST[$temp_var];
                                        $this->files[$file_id] = str_replace($this->start.$temp_var.$this->end, $$temp_var, $this->files[$file_id]);
                                }
                        }
                }

                function parse_loop($file_id, $array_name){
                        global $$array_name;
                        $loop_code = '';
                        $file = explode(chr(10), $this->files[$file_id]);

                        $start_pos = strpos(strtolower($this->files[$file_id]), '<loop name="'.$array_name.'">') + strlen('<loop name="'.$array_name.'">');
                        $end_pos = strpos(strtolower($this->files[$file_id]), '</loop name="'.$array_name.'">');

                        $loop_code = substr($this->files[$file_id], $start_pos, $end_pos-$start_pos);
                        $this->files[$file_id] = str_replace(substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), '<loop name="'.$array_name.'">'),strlen('<loop name="'.$array_name.'">')), '', $this->files[$file_id]);
                        $this->files[$file_id] = str_replace(substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), '</loop name="'.$array_name.'">'),strlen('</loop name="'.$array_name.'">')), '', $this->files[$file_id]);

                        if($loop_code != ''){
                                $new_code = '';
                                for($i=0; $i<count($$array_name); $i++){
                                        $temp_code = $loop_code;
                                        while(list($key,) = each(${$array_name}[$i])){
                                                $temp_code = str_replace($this->start.$key.$this->end,${$array_name}[$i][$key], $temp_code);
                                        }
                                        $new_code .= $temp_code;
                                }
                                $this->files[$file_id] = str_replace($loop_code, $new_code, $this->files[$file_id]);
                        }
                }

                function parse_sql($file_id, $result_name){
                        global $$result_name;
                        $loop_code = '';

                        $start_pos = strpos(strtolower($this->files[$file_id]), '<loop name="'.$result_name.'">') + strlen('<loop name="'.$result_name.'">');
                        $end_pos = strpos(strtolower($this->files[$file_id]), '</loop name="'.$result_name.'">');

                        $loop_code = substr($this->files[$file_id], $start_pos, $end_pos-$start_pos);
                        $this->files[$file_id] = str_replace(substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), '<loop name="'.$result_name.'">'),strlen('<loop name="'.$result_name.'">')), '', $this->files[$file_id]);
                        $this->files[$file_id] = str_replace(substr($this->files[$file_id], strpos(strtolower($this->files[$file_id]), '</loop name="'.$result_name.'">'),strlen('</loop name="'.$result_name.'">')), '', $this->files[$file_id]);

                        if($loop_code != ''){
                                $new_code = '';
                                $field_names = array();
                                for($i=0; $i<mysql_num_fields($$result_name); $i++) $field_names[] = mysql_field_name($$result_name,$i);
                                while($row_data = mysql_fetch_array($$result_name, MYSQL_ASSOC)){
                                        $temp_code = $loop_code;
                                        for($i=0; $i<count($field_names); $i++){
                                                $temp_code = str_replace($this->start.$field_names[$i].$this->end, $row_data[$field_names[$i]], $temp_code);
                                        }
                                        $new_code.= $temp_code;
                                }
                                $this->files[$file_id] = str_replace($loop_code, $new_code, $this->files[$file_id]);
                        }
                }

                function print_file($file_id){
                        if(is_long(strpos($file_id, ',')) == TRUE){
                                $file_id = explode(',', $file_id);
                                for(reset($file_id); $current = current($file_id); next($file_id)) echo $this->files[$current];
                        }else{
                                echo $this->files[$file_id];
                        }
                }

                function return_file($file_id){
                        $ret = '';
                        if(is_long(strpos($file_id, ',')) == TRUE){
                                $file_id = explode(',', $file_id);
                                for(reset($file_id); $current = current($file_id); next($file_id)) $ret .= $this->files[$current];
                        }else{
                                $ret .= $this->files[$file_id];
                        }
                        return $ret;
                }

        } // End of class


########################################
# End of classes, now some functions   #
########################################
   function print_array($array) {
    $output="";
    if(gettype($array)=="array") {
    $output.="<ul>";
    while (list($index, $subarray) = each($array) ) {
      $output.="<li>$index=";
      $output.=print_array($subarray);
      $output.="</li>";
    }
    $output.="</ul>";
    } else $output.=$array;
    return $output;
  }

        /***************************************
        ** Required input check.
        ***************************************/
                function check_required($key, $value){
                        if(substr($key,-9) == '_required' AND $value == ''){
                                global $required_error;
                                header('Location: '.$required_error);
                                exit;
                        }
                }

        /***************************************
        ** PHP3 file upload function.
        ***************************************/
                function file_upload_php3($key, $value){
                        if(substr($key,-5) != '_file' AND substr($key,-14) != '_file_required') return FALSE;
                        if(substr($key,-9) == '_required' AND ($value == 'none' OR $value == '')){
                                global $required_error;
                                header('Location: '.$required_error);
                                exit;
                        }
                        if($value != 'none' AND $value != ''){
                                global $num_attachments, $mail;
                                if(get_magic_quotes_gpc() == 1 AND (is_long(strpos(strtolower($value), 'windows')) OR is_long(strpos(strtolower($value), 'winnt')))) $value = stripslashes($value);
                                $ctype = $key.'_type';
                                $filename = $key.'_name';
                                global $$filename, $$ctype;
                                $attachment = fread($fp = fopen($value, 'r'), filesize($value)); fclose($fp);
                                $mail->add_attachment($attachment, $$filename, $$ctype);
                                $num_attachments++;
                        }
                        return TRUE;
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
                                                header('Location: '.$required_error);
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
                        global $tpl, $use_templates, $$key, $tpl_confirm;
                        if($key != 'configfile' AND $key != 'thankyou_page' AND $key != 'recipient' AND $key != 'subject' AND $key != 'addhostip' AND $key != 'MAX_FILE_SIZE' AND !eregi('_file$|_file_name$|_file_size$|_file_type$', $key)){
                                if(is_array($value)){

                                        $message[] = $key;
                                        $message_values[] = (get_magic_quotes_gpc() == 1) ? stripslashes(implode(', ', $value)) : implode(', ', $value);
                                        if(isset($use_templates) AND $use_templates == 1){
                                                $$key = implode(', ', $value);
                                                $tpl->register('main', $key);
                                                $tpl->register('email', $key);
                                                if($GLOBALS[confirm]==1) $tpl_confirm->register('email', $key);
                                        }
                                }else{

                                        $message[] = $key;
                                        $message_values[] = (get_magic_quotes_gpc() == 1) ? stripslashes($value) : $value;
                                        if(isset($use_templates) AND $use_templates == 1){
                                                if(get_magic_quotes_gpc() == 1) $$key = stripslashes($$key);
                                                $tpl->register('main', $key);
                                                $tpl->register('email', $key);
                                                if($GLOBALS[confirm]==1) $tpl_confirm->register('email', $key);
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
            header('Location: '.$bad_referer);
            exit;
        }

                if(isset($bad_addresses) AND $bad_addresses != '' AND file_exists($bad_addresses)){
                        $file_array = file($bad_addresses);
                        for($i=0; $i<count($file_array); $i++) if(getenv('REMOTE_ADDR') == trim($file_array[$i]) OR getenv('REMOTE_HOST') == trim($file_array[$i])){ header('Location: '.$bad_address); exit; }
                }
        }

/***************************************
** Debug feature. If $debug is set to 1,
** script dies and prints whatever is in
** $HTTP_POST_VARS and phpinfo.
***************************************/

        if(isset($postvars['debug']) AND $postvars['debug'] == 1){
                while(list($key, $value) = each($postvars)){
                        echo $key.' = '.$value."<BR>\n";
                }
                echo "<BR><BR>\n\n";
                echo print_array($GLOBALS);//phpinfo();
                reset($postvars);//exit;
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
        if(isset($postvars['subject']) AND $postvars['subject'] != '') $subject = $postvars['subject']; elseif(!isset($subject) OR $subject == '') $subject = 'Feedback from website.('.$HTTP_REFERER.')';
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
                header('Location: '.$invalid_email);
                exit;
        }

/***************************************
** Setup the objects.
***************************************/
        $mail = new html_mime_mail();

        if($use_templates == 1){
                $tpl = new template;

                if($tpl_thankyou != '') $tpl->load_file('main', $tpl_thankyou);
                if($tpl_email != '') $tpl->load_file('email', $tpl_email);
                if(is_file('confirm_'.$tpl_email)){
                   $confirm=1;
                   $tpl_confirm = new template;
                   if($tpl_email != '') $tpl_confirm->load_file('email', 'confirm_'.$tpl_email);
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
                if((int)phpversion() < 4) if(file_upload_php3($key,$value) == TRUE) continue;
                main($key, $value, $message, $message_values);
        }

        if((int)phpversion() >= 4 AND isset($HTTP_POST_FILES) AND is_array($HTTP_POST_FILES)) file_upload_php4($HTTP_POST_FILES);

/***************************************
** Post processing stuff.
***************************************/
        padding($message, '.');
        for($i=0; $i<count($message); $i++) $message[$i] .= '..:'.$message_values[$i];

        $time = date('H:i', time());
        $date = date('l m F Y', time());

/***************************************
** Constructs the email. If there are
** attachments it uses the mime mail
** class, if not then normal mail().
***************************************/
        if(isset($use_templates) AND $use_templates == 1 AND $tpl_email != ''){
                $tpl->register('email','REMOTE_ADDR,REMOTE_HOST,HTTP_REFERER,HTTP_USER_AGENT,recipient,num_attachments,subject,time,date');
                $tpl->parse('email');
                $body = $tpl->return_file('email');
                while(preg_match('/({.+})/U',$body, $matches) == TRUE){
                $body = str_replace($matches[1], '', $body);
                }
                if($confirm==1){
                    reset($matches);
                    $tpl_confirm->register('email','REMOTE_ADDR,REMOTE_HOST,HTTP_REFERER,HTTP_USER_AGENT,recipient,num_attachments,subject,time,date');
                    $tpl_confirm->parse('email');
                    $body_confirm = $tpl_confirm->return_file('email');
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
                $headers = 'Content-Type: '.$email."\r\n";
                $headers = 'From: '.$email."\r\n";
                if(isset($postvars['debug']) AND $postvars['debug'] == 1) {
                   echo $recipient.$subject.$body.$headers;
                   exit;
                }
                mail($recipient, $subject, $body, $headers);
                if($confirm==1) mail($email, $subject, $body_confirm, $headers);
                //mail("edgar_b@gmx.net", $subject, $body, $headers);
        }


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
        }elseif(isset($thankyou_page) AND $thankyou_page != ''){
                header('Location: '.$thankyou_page);
                exit;
        }
?>