<?php
require('Controllers/baseController.php');
class Router{

    private $uri;
    private $controllersDir;

    public function __construct()
    {
        $this->uri=strtok($_SERVER['REQUEST_URI'],'?');
        if (str_contains($this->uri,'/index.php')!==false){
            $this->uri=str_replace('/index.php','',$this->uri);
        }
        $this->controllersDir=BASEDIR.'/Controllers/';
        /*printf('debug: URI reconnue: '.$this->uri.'    ');*/
    }

    public function goToRoute(){
        switch ($this->uri){
            case('/blogposts'):
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController();
                $controller->displayBlogposts();
                break;
            case('/blogpost'):
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController();
                $controller->getOneBlogpost();
                break;
            case('/blogpost/create'):
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController();
                $controller->createBlogpost();
                break;
            case('/blogpost/edit'):
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController();
                $controller->displayUpdateBlogpost();
                break;
            case('/blogpost/save'):
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController();
                $controller->saveUpdateBlogpost();
                break;
            case('/blogpost/delete'):
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController();
                $controller->deleteBlogpost();
                break;
            case('/comments'):
                require $this->controllersDir.'commentController.php';
                $controller=new commentController();
                $controller->displayCommentsAdmin();
                break;
            case('/comment'):
                require $this->controllersDir.'commentController.php';
                $controller=new commentController();
                $controller->getOneComment();
                break;
            case('/comment/create'):
                require $this->controllersDir.'commentController.php';
                $controller=new commentController();
                $controller->createComment();
                break;
            case('/comment/edit'):
                require $this->controllersDir.'commentController.php';
                $controller=new commentController();
                $controller->displayUpdateComment();
                break;
            case('/comment/save'):
                require $this->controllersDir.'commentController.php';
                $controller=new commentController();
                $controller->saveUpdateComment();
                break;
            case('/signup'):
                require $this->controllersDir.'userController.php';
                $controller=new userController();
                $controller->createUser();
                break;
            case('/login'):
                require $this->controllersDir.'userController.php';
                $controller=new userController();
                $controller->login();
                break;
            case('/logout'):
                require $this->controllersDir.'userController.php';
                $controller=new userController();
                $controller->logout();
                break;
            case('/users'):
                require $this->controllersDir.'userController.php';
                $controller=new userController();
                $controller->displayUsers();
                break;
            case('/user'):
                require $this->controllersDir.'userController.php';
                $controller=new userController();
                $controller->getOneUser();
                break;
            case('/user/edit'):
                require $this->controllersDir.'userController.php';
                $controller=new userController();
                $controller->displayUpdateUser();
                break;
            case('/user/save'):
                require $this->controllersDir.'userController.php';
                $controller=new userController();
                $controller->saveUpdateUser();
                break;
            case('/'):
                require $this->controllersDir.'homepageController.php';
                $controller=new homepageController();
                $controller->displayHome();
                break;
            default:
                echo('404 ERROR');
                break;
        }
    }

}