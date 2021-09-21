<?php


namespace App\Controllers;


use App\Models\AdminModel;
use Core\View;
use App\Auth;

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
        View::renderTemplate('Admin/adminManageUsersView.html');
    }

    public function analyticsAction()
    {
        View::renderTemplate('Admin/adminAnalyticsView.html');
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

    public function chartAction()
    {
        
        $chart1=AdminModel::adminChart1();
        $chart2=AdminModel::adminChart2();
        $chart3=AdminModel::adminChart3();
        $chart4=AdminModel::adminChart4();
        $chart5=AdminModel::adminChart5();
        $chart6=AdminModel::adminChart6();
        //direct to the admin page
        View::renderTemplate('Admin/adminAnalyticsView.html',
        ['chart1'=>$chart1, 'chart2'=>$chart2, 'chart3'=>$chart3, 'chart4'=>$chart4, 'chart5'=>$chart5, 'chart6'=>$chart6]);


    }

}