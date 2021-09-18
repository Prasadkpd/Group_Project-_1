<?php


namespace App\Controllers;

use Core\View;
use \App\Auth;
class Customer extends Authenticated
{

    protected function before()
    {
        if(Auth::getUser()->type=='customer'){
            
            return true;
        }
        else{
            View::renderTemplate('500.html');
            return false;
        }
    }
  
    

    public function indexAction()
    {

        
        
       View::renderTemplate('Customer/customerDashboardView.html');
    }

    public  function cartAction()
    {
        

        View::renderTemplate('Customer/customerCartView.html');
    }

    public function bookingAction()
    {
        View::renderTemplate('Customer/customerBookingView.html');
    }

   
}