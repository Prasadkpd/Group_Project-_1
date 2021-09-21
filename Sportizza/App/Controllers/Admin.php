<?php


namespace App\Controllers;


use App\Models\AdminManage;
use Core\View;
use App\Auth;

class Admin extends \Core\Controller
{
    protected function before()
    {
        if(Auth::getUser()->type=='admin'){
            
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
        View::renderTemplate('Admin/adminManageUsersView.html');
    }

    public function analyticsAction()
    {
        View::renderTemplate('Admin/adminAnalytics.html');
    }

    public function faqAction()
    {
        View::renderTemplate('Admin/adminFAQView.html');
    }

    public function manageuserAction()
    {
        
        View::renderTemplate('Admin/adminManageUsersView.html');
    }

    public function ratingsAction()
    {
        View::renderTemplate('Admin/adminRatingsView.html');
    }

}