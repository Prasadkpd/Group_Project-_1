<?php
namespace App;
use App\Models\LoginModel;
class Auth
{
    //  Login the user
    // Obtaining the user-id from the database and assigning it to session
    public static function login($user)
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->user_id; 

        
    }

    // Start of Logout user
    public static function logout()
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
    }
    // End of logout user

    // Start of blocking unauthorised access
    // Blocking a user accessing its functionality by not logging in to the system.
  
    //Blocking a user if not logged in

    // Have to check
    public static function rememberRequestedPage()
    {
        $_SESSION['return_to'] = $_SERVER['REQUEST_URI'];
    }

    // Get the originally-requested page to return to after requiring login, or default to the homepage
     
    public static function getReturnToPage()
    {
        return $_SESSION['return_to'] ?? '/';
    }
  //End of blocking unauthorised access

//Start of obtaining user details
    public static function getUser()
    {
        if (isset($_SESSION['user_id'])) {
            return LoginModel::findByID($_SESSION['user_id']);
        }
    }
    //End of obtaining user details
}
