<?php


namespace App\Controllers;


use Core\View;

class Sparenamanager extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('SpArenaManager/manage-bookings.html');
    }

    public  function managetimeslotsAction()
    {
        View::renderTemplate('SpArenaManager/manage-timeslots.html');
    }

    public function managebookingsAction()
    {
        View::renderTemplate('SpArenaManager/manage-bookings.html');
    }

    public function managefacilityAction()
    {
        View::renderTemplate('SpArenaManager/manage-facility.html');
    }

    public function manageusersAction()
    {
        View::renderTemplate('SpArenaManager/manage-users.html');
    }
}