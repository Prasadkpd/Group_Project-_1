<?php
/**
 * Front controller
 *
 * PHP version 7.4.12
 */

/**
 * Composer
 */
require '../vendor/autoload.php';

/**
 * Twig
 */
Twig_Autoloader::register();

// spl_autoload_register(function ($class){
//     $root = dirname(__DIR__); //get parent directory
//     $file = $root . '/' .str_replace('\\', '/', $class).'.php';
//     if (is_readable($file)){
//         require $root . '/' .str_replace('\\', '/', $class).'.php';
//     }
// });
/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */

$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('{controller}/{action}');
$router->add('{controller}', ['action' => 'index']);

// $router->add('login', ['controller' => 'Login', 'action' => 'new']);
// $router->add('{controller}/{id:\d+}/{action}');
// $router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
// $router->add('visitor/{controller}/{action}', ['namespace' => 'Visitor']);
// $router->add('customer/{controller}/{action}', ['namespace' => 'Customer']);
// $router->add('manager/{controller}/{action}', ['namespace' => 'Manager']);
// $router->add('administrationstaff/{controller}/{action}', ['namespace' => 'AdministrationStaff']);
// $router->add('bookinghandlingstaff/{controller}/{action}', ['namespace' => 'BookingHandlingStaff']);

//Dispatching URLs to controllers and methods
$router->dispatch($_SERVER['QUERY_STRING']);  
// echo '<pre>';
// var_dump($router->getRoutes());
// echo '<pre>';