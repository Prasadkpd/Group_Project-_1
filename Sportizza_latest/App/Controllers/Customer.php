<?php


namespace App\Controllers;

use Core\View;
use \App\Auth;
class Customer extends \Core\Controller
{

    public function indexAction()
    {

        if(! Auth::isLoggedIn()){
            $this->redirect('/login');
        }
        
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