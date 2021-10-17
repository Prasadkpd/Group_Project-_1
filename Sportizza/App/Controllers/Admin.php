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

        //Retreiving FAQs from admin model
        $deleteFAQs = AdminModel::adminDisplayDeleteFAQ();

        //Rendering the admin FAQ view
        View::renderTemplate(
            'Admin/adminFAQView.html',
            ['viewFAQs' => $viewFAQs, 'deleteFAQs' => $deleteFAQs]
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

    //Start of Update FAQ 
    //Add FAQ action

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
