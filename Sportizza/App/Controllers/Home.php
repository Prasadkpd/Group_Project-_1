<?php


namespace App\Controllers;
use \Core\View;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Models\HomeModel;

class Home extends \Core\Controller
{
   
    public function indexAction()
    {
        $feedbacks=HomeModel::homeViewfeedbacks();
        $faqs=HomeModel::homeViewfaqs();
        $arenas=HomeModel::homeViewarenas();
       View::renderTemplate('Visitor/visitorView.html',['feedbacks'=>$feedbacks,'faqs'=>$faqs,'arenas'=>$arenas]);
    }


    /**
     * @throws Exception
     */
    public function contactAction()
    {
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $fullname = $firstname. " " . $lastname;
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];

            $mail = new PHPMailer(false);

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "contact.sportizza@gmail.com";
            $mail->Password = "sportizza123@";
            $mail->Port = 465;
            $mail->SMTPSecure = "ssl";

            $mail->isHTML(true);
            $mail->setFrom("contact.sportizza@gmail.com", $fullname);
            $mail->addAddress("contact.sportizza@gmail.com");
            $mail->Subject = 'Message from Sportizza Contact Form';
            $mail->Body = '<b>Mailed-By:</b><br>' . $email . '<br><b>Subject:</b>' . $subject. '<br><b>Message:</b><br>' . nl2br(strip_tags($message));

            $mail->send();
            $this->redirect("/#contact/success");
    }

}