<?php


namespace App\Controllers;

use Core\View;
use \App\Auth;
class Customer extends Authenticated
{

  
    

    public function indexAction()
    {

        
        
       View::renderTemplate('Customer/new_customer_dashboard.html');
    }

    public  function cartAction()
    {
        

        View::renderTemplate('Customer/newcart.html');
    }

    public function bookingAction()
    {
        View::renderTemplate('Customer/newCustomerBooking.html');
    }

   
}