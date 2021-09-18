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
        View::renderTemplate('Admin/adminAnalyticsView.php');
    }

    public function faqAction()
    {
        View::renderTemplate('Admin/admin-FAQ.html');
    }

    public function manageuserAction()
    {
        
        View::renderTemplate('Admin/adminManageUsersView.html');
    }

    public function ratingsAction()
    {
        View::renderTemplate('Admin/admin-ratings.html');
    }

}