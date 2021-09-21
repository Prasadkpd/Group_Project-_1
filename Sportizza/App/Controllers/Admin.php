<?php


namespace App\Controllers;


use App\Models\AdminManage;
use Core\View;
use App\Auth;
use App\Models\AdminModel;

class Admin extends \Core\Controller
{
    protected function before()
    {
        if(Auth::getUser()->type=='Admin'){
            
            return true;
        }
        else{
            View::renderTemplate('500.html');
            return false;
        }
    }

    public function after()
    {
    }
    public function indexAction()
    {
        // View::renderTemplate('Admin/adminManageUsersView.html');


        $customers=AdminModel::adminRemoveCustomers();
        $inactiveSportsArenas= AdminModel::adminAddSportsArenas();
        $activeSportsArenas= AdminModel::adminRemoveSportsArenas();
        //direct to the admin page
        View::renderTemplate('Admin/adminManageUsersView.html',
        ['customers'=>$customers,'inactiveArenas'=>$inactiveSportsArenas,'activeArenas'=>$activeSportsArenas]);
    }

    public function analyticsAction()
    {
        View::renderTemplate('Admin/adminAnalytics.html');
    }

    public function faqAction()
    {

        $viewFAQs=AdminModel::adminViewFAQ();
        $deleteFAQs= AdminModel::adminDeleteFAQ();
        //direct to the admin page
        View::renderTemplate('Admin/adminFAQView.html',
        ['viewFAQs'=>$viewFAQs,'deleteFAQs'=>$deleteFAQs]);


        // View::renderTemplate('Admin/adminFAQView.html');
    }

    public function manageuserAction()
    {
        
        $customers=AdminModel::adminRemoveCustomers();
        $inactiveSportsArenas= AdminModel::adminAddSportsArenas();
        $activeSportsArenas= AdminModel::adminRemoveSportsArenas();
        //direct to the admin page
        View::renderTemplate('Admin/adminManageUsersView.html',
        ['customers'=>$customers,'inactiveArenas'=>$inactiveSportsArenas,'activeArenas'=>$activeSportsArenas]);
    }

    public function ratingsAction()
    {
        
        $ratings=AdminModel::adminRemoveRatings();
        //direct to the admin page
        View::renderTemplate('Admin/adminRatingsView.html',
        ['ratings'=>$ratings]);
    }

}