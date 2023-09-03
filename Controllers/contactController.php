<?php
use \PHPMailer\PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class contactController extends baseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function sendContactForm(){
        $errors = [];
        if (!empty($_POST)) {
            $lastName = $_POST['last_name'];
            $firstName = $_POST['first_name'];
            $email = $_POST['email'];
            $message = $_POST['message'];

            if (empty($lastName)) {
                $errors[] = MISSING_LASTNAME;
            }
            if (empty($firstName)) {
                $errors[] = MISSING_FIRSTNAME;
            }

            if (empty($email)) {
                $errors[] = MISSING_EMAIL;
            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = NOT_EMAIL_TYPE;
            }

            if (empty($message)) {
                $errors[] = MISSING_MESSAGE;
            }
            if (empty($errors)) {
                $mail = new PHPMailer();
                    $mail->isSMTP();
                    $mail->Host = $_ENV['SMTP_HOST'];
                    $mail->SMTPAuth = true;
                    $mail->SMTPDebug = SMTP::DEBUG_OFF;
                    $mail->Username = $_ENV['SMTP_USERNAME'];
                    $mail->Password = $_ENV['SMTP_PASSWORD'];
                    $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
                    $mail->Port = $_ENV['SMTP_PORT'];
                    $mail->setFrom($_ENV['CONTACT_FORM_FROM_EMAIL'], $_ENV['CONTACT_FORM_FROM_NAME']);
                    $mail->addAddress($_ENV['CONTACT_FORM_TO']);
                    $mail->Subject = MAIL_SUBJECT;
                    $mail->isHTML(true);
                    $bodyParagraphs = [
                        LASTNAME_FIELD_NAME . $lastName,
                        FIRSTNAME_FIELD_NAME . $firstName,
                        EMAIL_FIELD_NAME . $email,
                        MESSAGE_FIELD_NAME . nl2br($message)];
                    $body = join('<br />', $bodyParagraphs);
                    $mail->Body = $body;
                if (!$mail->send()) {
                    if ($mail->SMTPDebug != SMTP::DEBUG_OFF){
                    $errorDetails= "MailerError: ".$mail->ErrorInfo;
                    } else {
                        $errorDetails='';
                    }
                    $msg=new userFeedback('error',CONTACT_FORM_ERROR.".".$errorDetails);
                } else {
                    $msg=new userFeedback('success',CONTACT_FORM_OK);
                }
            } else {
                $msg = new userFeedback('error',join('<br/>', $errors));
            }
        } else {
            $msg=new userFeedback('error',CONTACT_FORM_ERROR);
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render('homepage.html.twig',
            [
                'userFeedbacks' => $feedback]);
    }
}