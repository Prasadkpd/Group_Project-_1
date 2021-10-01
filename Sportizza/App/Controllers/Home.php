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
        $customerFAQs=HomeModel::homeViewCustomerfaqs();
        $arenas=HomeModel::homeViewarenas();
        // $arenas=["name"=>"janitha","age"=>21];
        $arenaFAQs=HomeModel::homeViewArenafaqs();
        $locations=HomeModel::homeSelectLocations();
        $categories=HomeModel::homeSelectCategories();
       View::renderTemplate('Visitor/visitorView.html',['feedbacks'=>$feedbacks,'faqs'=>$customerFAQs,
       'arenafaqs'=>$arenaFAQs,'arenas'=>$arenas,'locations'=>$locations,'categories'=>$categories]);
    }


    public function searcharenasAction(){
        // var_dump($_POST);
        $feedbacks=HomeModel::homeViewfeedbacks();
        $customerFAQs=HomeModel::homeViewCustomerfaqs();
        $arenaFAQs=HomeModel::homeViewArenafaqs();
        $arenas=HomeModel::homeSearchArenas($_POST['location'],
        $_POST['category'],$_POST['name']);
        $search_result['location']=$_POST['location'];
        $search_result['category']=$_POST['category'];
        $locations=HomeModel::homeSelectLocations();
        $categories=HomeModel::homeSelectCategories();
        $result="Search Results:";
        
        // var_dump($arenas);
        View::renderTemplate('Visitor/visitorView.html',['feedbacks'=>$feedbacks,'faqs'=>$customerFAQs,
        'arenafaqs'=>$arenaFAQs,'arenas'=>$arenas,'search_result'=> $search_result,
        'locations'=>$locations,'categories'=>$categories,'result'=>$result]);
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