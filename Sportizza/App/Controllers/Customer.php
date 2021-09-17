<?php


namespace App\Controllers;

use Core\View;
use \App\Auth;
class Customer extends Authenticated
{

  
    

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