<?php

namespace App\Controllers;

use \Core\View;
use App\Models\Post;


class Posts extends \Core\Controller
{


    public function indexAction()
    {
        //Pass values from get all function in Post model
        $posts = Post::getAll();

        View::renderTemplate('hello.html', [
            'posts' => $posts
        ]);
    }


    public function addNewAction()
    {
        echo 'Hello from the addNew action in the Posts controller!';
    }


}
