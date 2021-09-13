<?php


namespace App\Controllers;
use Core\View;
use \App\Models\User;


class Login extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/login.html');
    }


    //Login for a user
    public function createAction(){
    
        $user = User::authenticate($_POST['username'], $_POST['password']);

        if ($user) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user->id;

            //Redirects to home page atm (Change it to customised home page)
            $this->redirect('/');

        } else {

            View::renderTemplate('Login/login.html', [
                'username' => $_POST['username'],
            ]);
        }
    }
     //Logout for a user
    public function destroyAction()
    {
        // Unset all of the session variables
        $_SESSION = [];

        // Delete the session cookie
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Finally destroy the session
        session_destroy();

        $this->redirect('/');
    }
    }
