<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Test;


class Tests extends \Core\Controller
{


    public function indexAction()
    {
        //Pass values from get all function in Post model
        $tests = Test::getAll();

        View::renderTemplate('Test/test.html', [
            'tests' => $tests
        ]);
    }


    public function addNewAction()
    {
        echo 'Hello from the addNew action in the Posts controller!';
    }


}
