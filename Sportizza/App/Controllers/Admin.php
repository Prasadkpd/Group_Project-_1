<?php


namespace App\Controllers;


use App\Models\AdminManage;
use Core\View;

class Admin extends \Core\Controller
{
    protected function before()
    {

    }

    public function after()
    {
    }
    public function indexAction()
    {
        View::renderTemplate('Admin/adminFAQView.html');
    }

    public function analyticsAction()
    {
        View::renderTemplate('Admin/adminFAQView.html');
    }

    public function faqAction()
    {
        View::renderTemplate('Admin/adminFAQView.html');
    }

    public function manageuserAction()
    {
        $users = AdminManage::getAlluser();
        View::renderTemplate('Admin/adminManageUsersView.html', [
            'users' => $users
        ]);
    }

    public function ratingsAction()
    {
        View::renderTemplate('Admin/adminRatingsView.html');
    }

}