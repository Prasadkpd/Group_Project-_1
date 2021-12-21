<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\NotificationModel;
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
        $faq_id = $this->route_params['id'];

        // Pass FAQ id and answer to update FAQ function in admin model
        AdminModel::adminUpdateFAQ($faq_id, $_POST['updated_solution']);

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
    public function deletecustomersAction()
    {
        //Obtaining customer id sent from JS
        $id = $this->route_params['id'];

        // Pass customer id to remove customers function in admin model
        AdminModel::adminDeleteCustomers($id);

        // Notify the customer about his account removal
        NotificationModel::customerRemoveNotification($id);

        //Redirect to admin's manage user page
        $this->redirect('/Admin/manageuser');
    }
    // End of deleting customers

    //Start of Edit Arena profile of manager
    public  function viewarenaprofileAction()
    {
        //Obtaining sports arena profile id sent from JS
        $id = $this->route_params['id'];

        $arena_details = AdminModel::arenaProfileView($id);
        $arena_details['google_map_link'] = preg_replace('/\%\d\w/', ' , ', substr($arena_details['google_map_link'], 48));

        //Rendering the manager's edit profile arena view
        View::renderTemplate('Admin/adminViewArenaProfile.html', ['arena_details' => $arena_details]);
    }
    //End of Edit Arena profile of manager staff

    // Start of adding sports arenas
    public function addarenasAction()
    {
        //Obtaining sports arena profile id sent from JS
        $id = $this->route_params['id'];

        // Pass sports arena profile id to remove arenas function in admin model
        AdminModel::adminAddArenas($id);

        //Redirect to admin's manage user page
        $this->redirect('/Admin/manageuser');
    }
    // End of adding sports arenas

    //Passing the sports_arena_id from RemoveArenas view (Html and JS) and getting complaints (negative feedback description)
    public function getcomplaintsAction()
    {
        //Obtaining faq question sent from JS
        $arena_id = $this->route_params['id'];
        //Echo HTML tag sent by Model with FAQs answers and it gets triggered with success in JS
        echo AdminModel::adminDisplayComplaints($arena_id);
    }

    // Start of deleting sports arenas
    public function deletearenasAction()
    {

        //Obtaining sports arena profile id sent from JS
        $id = $this->route_params['id'];

        // Pass sports arena profile id to remove arenas function in admin model
        AdminModel::adminDeleteArenas($id);

        //Redirect to admin's manage user page
        $this->redirect('/Admin/manageuser');
    }
    // End of deleting sports arenas

    // Start of blacklisting sports arenas
    public function blacklistarenasAction()
    {
        //Obtaining sports arena profile id sent from JS
        $id = $this->route_params['id'];

        // Pass sports arena profile id to blacklist arenas function in admin model
        AdminModel::adminBlacklistArenas($id);

        //Redirect to admin's manage user page
        $this->redirect('/Admin/manageuser');
    }
    // End of blacklisting sports arenas

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
    public function deleteratingsAction()
    {
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

    // Start of reshaping pie charts
    public function reshapepiechartAction()
    {
        $dateValue = $this->route_params['id'];

        $temp1 = [];
        $temp2 = [];

        $chart4 = AdminModel::adminReshapePieCharts($dateValue);
        // echo AdminModel::adminReshapePieCharts($dateValue);

        $i = 0;

        for ($i; $i < count($chart4); $i++) {
            $temp1[$i] = $chart4[$i]->payment_method;
            $temp2[$i] = $chart4[$i]->No_Of_Bookings;
        }

        $payment_method = implode(",", $temp1);
        $booking_count = implode(",", $temp2);

        echo $payment_method . "_" . $booking_count;
    }
    // End of reshaping pie charts

    // Start of reshaping table charts
    public function reshapetablechartAction()
    {
        $dateValue = $this->route_params['id'];

        echo AdminModel::adminReshapeTableCharts($dateValue);
    }
    // End of reshaping table charts

}
