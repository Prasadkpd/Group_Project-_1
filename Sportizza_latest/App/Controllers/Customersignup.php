<?php


namespace App\Controllers;


use Core\View;

class Customersignup extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('Admin/admin-FAQ.html');
    }
}