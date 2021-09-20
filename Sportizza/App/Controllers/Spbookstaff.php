<?php


namespace App\Controllers;


use Core\View;
use App\Auth;

class Spbookstaff extends \Core\Controller
{


    protected function before()
    {
        if(Auth::getUser()->type=='BookingHandlingStaff'){
            
            return true;
        }
        else{
            View::renderTemplate('500.html');
            return false;
        }
    }
    
    public function indexAction()
    {
        View::renderTemplate('BookHandlingStaff/bStaffViewBookingsView.html');
    }
}