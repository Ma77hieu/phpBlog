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
                var_dump('404 ERROR');
                break;
        }
    }

}