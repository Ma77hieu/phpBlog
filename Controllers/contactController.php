<?php

use \PHPMailer\PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class contactController extends baseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handle the mail sending when the main contact form on homepage
     * is posted
     *
     * @return void
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendContactForm()
    {
        $errors = [];
        if (!empty($this->postVars) && $this->postVars['csrf_token'] != $this->sessionVars['csrfToken']) {
            $lastName = htmlspecialchars($this->postVars['last_name']);
            $firstName = htmlspecialchars($this->postVars['first_name']);
            $email = htmlspecialchars($this->postVars['email']);
            $message = htmlspecialchars($this->postVars['message']);

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
                $mail->Host = $this->envVars['SMTP_HOST'];
                $mail->SMTPAuth = true;
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->Username = $this->envVars['SMTP_USERNAME'];
                $mail->Password = $this->envVars['SMTP_PASSWORD'];
                $mail->SMTPSecure = $this->envVars['SMTP_SECURE'];
                $mail->Port = $this->envVars['SMTP_PORT'];
                $mail->setFrom($this->envVars['CONTACT_FORM_FROM_EMAIL'], $this->envVars['CONTACT_FORM_FROM_NAME']);
                $mail->addAddress($this->envVars['CONTACT_FORM_TO']);
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
                    if ($mail->SMTPDebug != SMTP::DEBUG_OFF) {
                        $errorDetails = "MailerError: " . $mail->ErrorInfo;
                    } else {
                        $errorDetails = '';
                    }
                    $msg = new userFeedback('error', CONTACT_FORM_ERROR . "." . $errorDetails);
                } else {
                    $msg = new userFeedback('success', CONTACT_FORM_OK);
                }
            } else {
                $msg = new userFeedback('error', join('<br/>', $errors));
            }
        } else {
            $msg = new userFeedback('error', CONTACT_FORM_ERROR);
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render('homepage.html.twig',
            ['isUserAdmin' => $this->isUserAdmin,
                'loggedIn' => $this->isLoggedIn,
                'userFeedbacks' => $feedback]);
    }
}
