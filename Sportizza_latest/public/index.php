<?php



require '../vendor/autoload.php';



Twig_Autoloader::register();
//spl_autoload_register(function ($class)
//{
//    $root = dirname(__DIR__);
//    $file = $root . '/' . str_replace('\\','/', $class) . '.php';
//    if (is_readable($file)) {
//        require $root . '/' . str_replace('\\', '/', $class) . '.php';
//    }
//});

error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

// Sessions
session_start();

$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('posts', ['controller' => 'Posts', 'action' => 'index']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);
// $router->add('signup', ['controller' => 'Signup', 'action' => 'index']);


$router->add('{controller}',['action' => 'index']);
$router->add('{controller}/',['action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}/{action}/');

$router->dispatch($_SERVER['QUERY_STRING']);