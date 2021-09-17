<?php

namespace App\Controllers\Customer;

use \Core\View;
/**
 * User customer controller
 *
 * PHP version 7.4.12
 */
class Users extends \Core\Controller{

    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        // Make sure a customer user is logged in for example
        // return false;
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Customer/customer.php');
        // echo 'User customer index';
    }
}

class MyBookings extends \Core\Controller{

    public function share(){
            //have to write customer share booking part
    }

    
    public function cancel(){
        
    }

    public function rate(){

    }
    public function delete(){

        
    }


}
