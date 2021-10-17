<?php

namespace App\Controllers;

use Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Models\LoginModel;
use \App\Models\AdminModel;

class Directtoprofile extends Authenticated
{
    //Start of Directing the users after login process
    public function indexAction()
    {
        //Obtaining the User's type via session
        $user = Auth::getUser();

        if ($user->type == 'Admin') {
            $this->redirect('/Admin');
        } elseif ($user->type == 'Customer') {
            $this->redirect('/Customer');
        } elseif ($user->type == 'Manager') {
            $this->redirect('/Sparenamanager');
        } elseif ($user->type == 'AdministrationStaff') {
            $this->redirect('/Spadministrationstaff');
        } elseif ($user->type == 'BookingHandlingStaff') {
            $this->redirect('/Spbookstaff');
        }
    }
    //End of Directing the users after login process
}
