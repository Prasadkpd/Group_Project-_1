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
        View::renderTemplate('Admin/admin-FAQ.html');
    }

    public function analyticsAction()
    {
        View::renderTemplate('Admin/adminAnalytics.html');
    }

    public function faqAction()
    {
        View::renderTemplate('Admin/admin-FAQ.html');
    }

    public function manageuserAction()
    {
        $users = AdminManage::getAlluser();
        View::renderTemplate('Admin/admin-manage-users.html', [
            'users' => $users
        ]);
    }

    public function ratingsAction()
    {
        View::renderTemplate('Admin/admin-ratings.html');
    }

}