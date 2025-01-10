<? $window_title="send e-card";
$docrelpath="../../../../";

include(CLASS_PATH.'mimemail/class.html.mime.mail.inc');
if(isset($image_id)){
$image = new skobject();
$image->attributes['object_id']=$image_id;
$image->get();
}
?>

<table width="436" height="12" border="0" cellspacing="0" cellpadding="0" align="center"  style="BORDER-COLLAPSE: collapse">
  
  <tr> 
    <td height=34> 
      <p align=center><?
$sitename = "http://".SERVER_ADDRESS;
$thankspage = "";
$footer = "<br><br>--<br>gesendet von $sitename";
$subject = "Eine Grußkarte von $namesend";
$intro = "Hallo $namerec,<br><br>Diese Grußkarte wurde von unserer Website an dich verschickt.<br><br>";

// start code:

if (($namesend == '') || ($emailsend == '') || ($namerec == '')|| ($message == ''))
{
echo "<div align=center>You <b>must</b> fill out all the fields in the form. <a href=javascript:history.back(-1)>Return to the form</a>.</div>";
}
else {?>

 
    <? if($layout==1){
    $message='
        <table><!-- Format 1 -->
        <tr>
        <td><font style="color: '.$color.'; font-family: '.$font.'; font-size: '.$fontsize.';">
        '.$message.'</font></td>
        <td><img src="http://'.SERVER_ADDRESS.'/content/image/'.$image->attributes["file"].'" alt="" border="0"></td>
        </tr>
        </table>';
    }elseif($layout==2){
    $message='
        <table><!-- Format 2 -->
        <tr>
        <td><img src="http://'.SERVER_ADDRESS.'/content/image/'.$image->attributes["file"].'" alt="" border="0"></td>
        <td><font style="color: '.$color.'; font-family: '.$font.'; font-size: '.$fontsize.';">
        '.$message.'</font></td>
        </tr>
        </table>';
    }elseif($layout==3){
    $message='
        <table><!-- Format 3 -->
        <tr>
        <td align="center"><img src="http://'.SERVER_ADDRESS.'/content/image/'.$image->attributes["file"].'" alt="" border="0"><p>
        <font style="color: '.$color.'; font-family: '.$font.'; font-size: '.$fontsize.';">
        '.$message.'</font></td>
        </tr>
        </table>';
    }

# Check for valid email address

$x = ereg("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$",$email);

if($x==0)
# if no valid email address entered, display no email message
{
echo "<div align=center>You <b>must</b> specify a valid email address for your friend. <a href=javascript:history.back(-1)>Return to the form</a>.</div>";
}
else {

error_reporting(63);

        /***************************************
        ** Create the mail object. Optional headers
        ***************************************/
        
        $mail = new html_mime_mail('X-Mailer: Html Mime Mail Class');

        /***************************************
        ** Create the message
        ***************************************/
        
        $text = '';
        $html = ($intro.$message.$footer);

        /***************************************
        ** Add the text, html and embedded images.
        ***************************************/

        $mail->add_html($html, $text);

        /***************************************
        ** Set Character Set
        ***************************************/
        
        $mail->set_charset('utf-8', TRUE);

        /***************************************
        ** Builds message.
        ***************************************/
        
        $mail->build_message();

        /***************************************
        ** Sends the message.
        ***************************************/
        
        if (isset($send_ok)) {
            $mail->send(($namerec), ($email), ($namesend), ($emailsend), ($subject));
            ?>
    <p align=center><b><font face=Arial size=2>&nbsp;Die Nachricht wurde gesendet!</font></b></p>
    </td>
    </tr>
    <tr> 
    <td height=7> 
      <p align=center><input type="submit" name="schliessen" value="schliessen" onclick="self.close()" style="COLOR: #000066; FONT-FAMILY: Tahoma"></p>
    </td>
  </tr><?
        }else{?>

      <p align=center><b><font face=Arial size=2>&nbsp;Vorschau:</font></b></p>
    </td>
  </tr>
  <form action="<?echo $PHP_SELF."?form_window=1&form_template=ecard_preview.php";?>" method="post">
  <?while (list($key, $value) = each($HTTP_POST_VARS) ) {  ?>
  <input type="hidden" name="<?echo $key;?>" value="<?echo $value;?>">
  <?}?>
  <input type="hidden" name="send_ok" value="1">
  
  <tr> 
    <td height=7> 
      <p align=center>&nbsp;</p>
    </td>
  </tr>
  <tr> 
    <td align="center" bgcolor="#808080"> <?echo $message;?></td></tr>
  <tr> 
    <td height=7> 
      <p align=center><input type="submit" name="vorschau" value="senden" style="COLOR: #000066; FONT-FAMILY: Tahoma"></p>
    </td>
  </tr>
    </form>
        <?}
        
        }
        }?>

    </td>
  </tr>
  

</table>

