<?php

class Router{

    private $uri;

    public function __construct()
    {
        $this->uri=strtok($_SERVER['REQUEST_URI'],'?');
        $this->controllersDir=BASEDIR.'/Controllers/';
    }

    public function goToRoute(){
        switch ($this->uri){
            case('/blogposts'):
                $controller=new blogpostController();
                $controller->displayBlogposts();
                break;
            case('/blogpost'):
                $controller=new blogpostController();
                $controller->getOneBlogpost();
                break;
            case('/blogpost/create'):
                $controller=new blogpostController();
                $controller->createBlogpost();
                break;
            case('/blogpost/edit'):
                $controller=new blogpostController();
                $controller->displayUpdateBlogpost();
                break;
            case('/blogpost/save'):
                $controller=new blogpostController();
                $controller->saveUpdateBlogpost();
                break;
            case('/blogpost/delete'):
                $controller=new blogpostController();
                $controller->deleteBlogpost();
                break;
            case('/comments'):
                $controller=new commentController();
                $controller->displayUnvalidatedComments();
                break;
            case('/comment/create'):
                $controller=new commentController();
                $controller->createComment();
                break;
            case('/comment/edit'):
                $controller=new commentController();
                $controller->displayUpdateComment();
                break;
            case('/comment/save'):
                $controller=new commentController();
                $controller->saveUpdateComment();
                break;
            case('/comment/moderate'):
                $controller=new commentController();
                $controller->changeCommentVisibility();
                break;
            case('/comment/delete'):
                $controller=new commentController();
                $controller->deleteComment();
                break;
            case('/signup'):
                $controller=new userController();
                $controller->createUser();
                break;
            case('/login'):
                $controller=new userController();
                $controller->login();
                break;
            case('/logout'):
                $controller=new userController();
                $controller->logout();
                break;
            case('/users'):
                $controller=new userController();
                $controller->displayUsers();
                break;
            case('/user/edit'):
                $controller=new userController();
                $controller->displayUpdateUser();
                break;
            case('/user/save'):
                $controller=new userController();
                $controller->saveUpdateUser();
                break;
            case('/contact'):
                $controller=new contactController();
                $controller->sendContactForm();
                break;
            case(''):
            case('/'):
                $controller=new homepageController();
                $controller->displayHome(false);
                break;
            default:
                $controller=new homepageController();
                $controller->displayHome(true);
                break;
        }
    }

}