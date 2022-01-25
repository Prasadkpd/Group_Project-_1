<?php

namespace App\Controllers;

use \Core\View;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Models\HomeModel;

class Home extends \Core\Controller
{
    //Before action to return true
    protected function before()
    {
    }
     //After action to return true
     protected function after()
     {
     }
    //Start of rendering the visitor's view (Landing page)
    public function indexAction()
    {
        //Displaying the feedbacks, FAQs, arenas, etc in the view
        $feedbacks = HomeModel::homeViewfeedbacks();
        $customerFAQs = HomeModel::homeViewCustomerfaqs();
        $arenas = HomeModel::homeViewarenas();
        $arenaFAQs = HomeModel::homeViewArenafaqs();
        $locations = HomeModel::homeSelectLocations();
        $categories = HomeModel::homeSelectCategories();
        //Rendering the visitor view
        View::renderTemplate('Visitor/visitorView.html', [
            'feedbacks' => $feedbacks, 'faqs' => $customerFAQs,
            'arenafaqs' => $arenaFAQs, 'arenas' => $arenas, 'locations' => $locations, 'categories' => $categories
        ]);
    }
    //End of rendering the visitor's view (Landing page)

    //Start of searching sports arenas in visitor's view
    public function searcharenasAction()
    {
        //Displaying the feedbacks, FAQs, arenas, etc in the view
        $feedbacks = HomeModel::homeViewfeedbacks();
        $customerFAQs = HomeModel::homeViewCustomerfaqs();
        $arenaFAQs = HomeModel::homeViewArenafaqs();

        $arenas = HomeModel::homeSearchArenas($_POST['location'], $_POST['category'], $_POST['name']);
        $search_result['location'] = $_POST['location'];
        $search_result['category'] = $_POST['category'];
        $locations = HomeModel::homeSelectLocations();
        $categories = HomeModel::homeSelectCategories();
        $result = "Search Results:";

        //Rendering the visitor view with search results
        View::renderTemplate('Visitor/visitorView.html', [
            'feedbacks' => $feedbacks, 'faqs' => $customerFAQs,
            'arenafaqs' => $arenaFAQs, 'arenas' => $arenas, 'search_result' => $search_result,
            'locations' => $locations, 'categories' => $categories, 'result' => $result
        ]);
    }

    public function searcharenasajaxAction(){
        $combined = $this->route_params['arg'];
        $temp = explode("__",$combined);

        $searchValue = str_replace("_", " ", $temp[0]);
        $categoryValue = str_replace("_", "-", $temp[1]);;
        $locationValue = str_replace("_", "-", $temp[2]);;

        if($locationValue === "0")
        {
            $locationValue = "select location";
        }

        if($categoryValue === "0")
        {
            $categoryValue = "select category";
        }

        echo HomeModel::homeSearchArenas($locationValue, $categoryValue, $searchValue);
    }
    
    //Start of contact us form's mail
    public function contactAction()
    {
        //Assigning the inputs entered by the user to our variables
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $fullname = $firstname . " " . $lastname;
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        //Disabling exceptions in PHPMailer (Not displaying the log page)
        $mail = new PHPMailer(false);

        //Using SMTP to send an email with localhost
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "contact.sportizza@gmail.com";
        $mail->Password = "sportizza123@";
        $mail->Port = 465; //Default port of google
        $mail->SMTPSecure = "ssl";

        //Format of the mail body made using HTML
        $mail->isHTML(true);
        //Sender's email is our email used in the contact form
        $mail->setFrom("contact.sportizza@gmail.com", $fullname);
        //Reciever's email is our email used in the contact form
        $mail->addAddress("contact.sportizza@gmail.com");
        //Subject of the email
        $mail->Subject = 'Message from Sportizza Contact Form';
        //Mail body
        $mail->Body = '<b>Mailed-By:</b><br>' . $email . '<br><b>Subject:</b>' . $subject . '<br><b>Message:</b><br>' . nl2br(strip_tags($message));

        $mail->send();
        //After mail is successfully sent, redirect to visitor view's contact us page
        $this->redirect("/#contact");
    }
}
