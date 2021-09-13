<?php


namespace App\Controllers;


use Core\View;

class Customer extends \Core\Controller
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