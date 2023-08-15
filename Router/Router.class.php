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
            case('blogposts'):
                echo('uri reconnue /blogposts ');
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController();
                $controller->displayBlogposts();
                break;
            case('/blogpost'):
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController();
                $controller->getOneBlogpost();
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