<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$error = '';
function mailer($conn){
    //Import PHPMailer classes into the global namespace
    //These must be at the top of your script, not inside a function
    // include_once './includes/dbh.inc.php';

    //Load Composer's autoloader
    require __DIR__. '/vendor/autoload.php';

    //Create an instance; passing `true` enables exceptions

    try {
        

        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output | to enable = SMTP::DEBUG_SERVER
        $mail->isSMTP();                                   //Send using SMTP
        $mail->Host       = 'smtp.hostinger.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'receipt@benjpharmacy.online';                     //SMTP username
        $mail->Password   = '!FJyg!b6D3H6g63';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                         //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('receipt@benjpharmacy.online', 'benjpharmacy.online');
        $mail->addAddress($user['email']);          //Name is optional
        $mail->addAddress('copyreceipt@benjpharmacy.online');          //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Benj Pharmacy Online Receipt - order: '.$order_id;
        $mail->Body    = $template;
        $mail->AltBody = $altbodyTemplate;

        $mail->send();
        return true;
    } catch (Exception $e) {
        $error .=  "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;

    }
}
$mailNotSent = true;
while($mailNotSent){
    if(mailer($conn)) $mailNotSent = false;
}
?>