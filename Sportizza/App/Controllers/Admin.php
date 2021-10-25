<?php

namespace App\Controllers;

use App\Models\AdminModel;
use Core\View;
use App\Auth;

class Admin extends Authenticated
{
    //Start of blocking a user after login
    //Blocking unauthorised access after login as a user
    protected function before()
    {
        //Checking whether the user type is admin
        if (Auth::getUser()->type == 'Admin') {
            return true;
        }
        //Return to error page
        else {
            View::renderTemplate('500.html');
            return false;
        }
    }
    protected function after()
    {
        
    }
    //End of blocking a user after login

    //Start of Landing page of admin
    public function indexAction()
    {
        $this->redirect('/Admin/chart');
    }
    //End of Landing page of admin

    //Start of FAQ page
    public function faqAction()
    {
        //Retreiving FAQs from admin model
        $viewFAQs = AdminModel::adminViewFAQ();

        //Rendering the admin FAQ view
        View::renderTemplate(
            'Admin/adminFAQView.html',
            ['viewFAQs' => $viewFAQs]
        );
    }

    //Start of create FAQ 
    public function createfaqAction()
    {

        //Obtaining current user's id from auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Passing the FAQ input data from the view to the admin model
        $user = AdminModel::adminAddFAQ(
            $_POST['type'],
            $_POST['question'],
            $_POST['solution'],
            $id
        );

        //Redirect to admin's FAQ page
        $this->redirect('/Admin/faq');
    }
    //End of create FAQ 

    // Start of delete FAQ

    public function deletefaqAction()
    {
        //Obtaining faq id sent from href
        $faq_id = $this->route_params['id'];

        // Pass FAQ id to delete FAQ function in admin model
        AdminModel::adminDeleteFAQ($faq_id);
        
        //Redirect to admin's FAQ page
        $this->redirect('/Admin/faq');
    }

    // End of delete FAQ

    //Start of Update FAQ
    
    //Passing the FAQ type from AddFAQ view (Html and JS) and getting FAQ questions
    public function getquestionsAction()
    {
        //Obtaining faq type sent from JS
        $type = $this->route_params['arg'];
        //Echo HTML tag sent by Model with FAQs questions and it gets triggered with success in JS
        echo AdminModel::adminGetQuestionDetails($type);
    }

    //Passing the FAQ Question from AddFAQ view (Html and JS) and getting FAQ answers
    public function getsolutionsAction()
    {
        //Obtaining faq question sent from JS
        $question = $this->route_params['id'];
        //Echo HTML tag sent by Model with FAQs answers and it gets triggered with success in JS
        echo AdminModel::adminGetSolutionDetails($question);
    }

    // Updating FAQ solution

    public function updatefaqAction()
    {
        //Obtaining faq id and answer sent from href
        $combined = $this->route_params['arg'];
        $combined = explode("__",$combined);

        $faq_id = $combined[0];
        $answer = str_replace("_", " ", $combined[1]);

        // Pass FAQ id and answer to update FAQ function in admin model
        AdminModel::adminUpdateFAQ($faq_id, $answer);
        
        //Redirect to admin's FAQ page
        $this->redirect('/Admin/faq');
    }

    //End of Update FAQ 


    //Start of manage users view
    public function manageuserAction()
    {
        //Retreiving all the customers from admin model
        $customers = AdminModel::adminDisplayRemoveCustomers();
        //Retreiving all the pending requests of sports arenas from admin model
        $inactiveSportsArenas = AdminModel::adminDisplayAddSportsArenas();
        //Retreiving all the sports arenas from admin model
        $activeSportsArenas = AdminModel::adminDisplayRemoveSportsArenas();

        //Render admin's manage users view
        View::renderTemplate(
            'Admin/adminManageUsersView.html',
            [
                'customers' => $customers, 'inactiveArenas' => $inactiveSportsArenas,
                'activeArenas' => $activeSportsArenas
            ]
        );
    }
    //End of manage users view

    // Start of deleting customers
    public function deletecustomersAction(){
        
        //Obtaining customer id sent from JS
        $id = $this->route_params['id'];

        // Pass feedback id to delete ratings function in admin model
        AdminModel::adminDeleteCustomers($id);

        //Redirect to admin's ratings page
        $this->redirect('/Admin/manageuser');

    }
    // End of deleting customers

    //Start of removing negative ratings view
    public function ratingsAction()
    {
        //Retreiving all the negative ratings (ratings < 3) from admin model
        $ratings = AdminModel::adminDisplayRemoveRatings();

        //Render admin's negative ratings view
        View::renderTemplate(
            'Admin/adminRatingsView.html',
            ['ratings' => $ratings]
        );
    }
    //End of removing negative ratings view

    // Start of deleting ratings
    public function deleteratingsAction(){
        
        //Obtaining rating id sent from JS
        $feedback_id = $this->route_params['id'];

        // Pass feedback id to delete ratings function in admin model
        AdminModel::adminDeleteRatings($feedback_id);

        //Redirect to admin's ratings page
        $this->redirect('/Admin/ratings');

    }
    // End of deleting ratings

    //Start of Chart view
    public function chartAction()
    {
        //Retreiving all the system charts from admin model
        $chart1 = AdminModel::adminChart1();
        $chart2 = AdminModel::adminChart2();
        $chart3 = AdminModel::adminChart3();
        $chart4 = AdminModel::adminChart4();
        $chart5 = AdminModel::adminChart5();
        $chart6 = AdminModel::adminChart6();

        //Rendering admin analytics view
        View::renderTemplate(
            'Admin/adminAnalyticsView.html',
            [
                'chart1' => $chart1, 'chart2' => $chart2, 'chart3' => $chart3,
                'chart4' => $chart4, 'chart5' => $chart5, 'chart6' => $chart6
            ]
        );
    }
    //End of Chart view

}
