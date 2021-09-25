<?php


namespace App\Controllers;
use Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Models\LoginModel;
use \App\Models\AdminModel;


class Directtoprofile extends Authenticated
{
    public function indexAction()
    {
        $user= Auth::getUser();
        // if(is_null($user)) {
        //     // $message="invalid username or password";
        //     View::renderTemplate('LoginSignup/loginView.html');  
        // }

        if($user->type=='Admin'){
            $this->redirect('/Admin');
        }

        elseif($user->type=='Customer'){
            
            // $this->redirect('/login/customerlogin');
            $this->redirect('/Customer');
            // $this->redirect(Auth::getReturnToPage());
        }
        elseif($user->type=='Manager'){
            $this->redirect('/Sparenamanager');
        }
        elseif($user->type=='AdministrationStaff'){
            $this->redirect('/Spadministrationstaff');
        }

        elseif($user->type=='BookingHandlingStaff'){
            $this->redirect('/Spbookstaff');
        }

       
    }
}
