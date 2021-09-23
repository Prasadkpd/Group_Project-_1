<?php


namespace App\Controllers;
use Core\View;
use \App\Models\SpArenaModel;


class Arenareg extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/spArenaSignupView.html');
    }

    /**
     * Register a new Sports Arena
     *
     * @return void
     */
    public function createAction()
    {
     
        $sp_arena = new SpArenaModel($_POST);
        
        if ($sp_arena->save()) {
            // otp::sendSMS($_POST["mobile_number"]);
            
            $this->redirect('/Arenareg/success');
            exit;

        } else {
            View::renderTemplate('LoginSignup/spArenaSignupView.html', [
                'sp_arena' => $sp_arena
            ]);

        }
    
    }
    /**
     * Show the signup success page
     *
     * @return void
     */
    public function successAction()
    {
        //direct to the message modal page
        View::renderTemplate('otp.html');
    }
}
