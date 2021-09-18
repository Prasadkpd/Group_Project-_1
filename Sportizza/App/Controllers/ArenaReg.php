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
        
        if ($_POST["category"] == "1"){
            $_POST["category"] = $_POST["other category"];
        }
        if ($_POST["location"] == "1"){
            $_POST["location"] = $_POST["other location"];
        }

        $sp_arena = new SpArenaModel($_POST);
        var_dump($sp_arena);

        if ($sp_arena->save()) {

            //Have redirect instead of this
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/Arenareg/success', true, 303);
            exit;

        } else {

            View::renderTemplate('LoginSignup/spArenaSignupView.html', [
                'sp_arena' => $sp_arena
            ]);

        }
        // var_dump($_POST);
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
