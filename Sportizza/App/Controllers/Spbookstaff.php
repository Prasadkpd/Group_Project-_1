<?php


namespace App\Controllers;


use Core\View;

class Spbookstaff extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('BookHandelStaff/manage-bookings.html');
    }
}