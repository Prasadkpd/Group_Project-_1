<?php

namespace App\Controllers;

use Core\View;
use \App\Models\SpArenaModel;

class Arenareg extends \Core\Controller
{
    //Rendering sports arena signup form
    public function indexAction()
    {
        $location = SpArenaModel::getAllLocation();
        $category = SpArenaModel::getAllSportsCategory();
        View::renderTemplate('LoginSignup/spArenaSignupView.html',['locations' => $location, 'categorys' => $category]);
    }

    // Start of Registering a new Sports Arena
    public function createAction()
    {
        //Creating a sports arena object
        $sp_arena = new SpArenaModel($_POST);
        //Assigning the backend errors of the sports arena signup form
        $errors = $sp_arena->validate();

        //If a sports arena's  data insertion happens
        if ($sp_arena->save()) {
            //Retrieving mobile number input from the manager
            otp::sendSMS($_POST["mobile_number"]);

            //Saving the page to be redirected after mobile verification is success (OTP)
            $_SESSION['direct_url'] = ('/');
            $this->redirect('/Otp');
            exit;
        }
        //If not returning the sports arena signup page with errors
        else {
            View::renderTemplate('LoginSignup/spArenaSignupView.html', [
                'sp_arena' => $sp_arena, 'errors' => $errors
            ]);
        }
    }
    // End of Registering a new Sports Arena
}
