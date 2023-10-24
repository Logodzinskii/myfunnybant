<?php

namespace App\Listeners;

use App\Events\CartConfirmEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class CartReportUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\CartConfirmEvent  $event
     * @return void
     */
    public function handle(CartConfirmEvent $event)
    {
        $mail = new PHPMailer(true);

        try {
            $userEmail = $event->email;
            $userName  = $event->name;
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'mail.hosting.reg.ru';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = config('mail.mailers.smtp.MAIL_USERNAME');                     //SMTP username
            $mail->Password   = config('mail.mailers.smtp.MAIL_PASSWORD');                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom('noreply@myfunnybant.ru', 'Myfunnybant');
            $mail->addAddress($userEmail, $userName);     //Add a recipient
            //$mail->addAddress('ekasaitlim@gmail.com');               //Name is optional
            // $mail->addReplyTo('ekasaitlim@gmail.com', 'Information');
            //$mail->addCC('ekasaitlim@gmail.com');
            //$mail->addBCC('ekasaitlim@gmail.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'MYFUNNYBANT';
            $mail->Body    = $event->message;
            $mail->AltBody = $event->message;
            $mail->CharSet = "UTF-8";
            $mail->send();

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
