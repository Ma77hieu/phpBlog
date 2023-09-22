<?php
require('Controllers/baseController.php');
class Router{

    private $uri;
    /*private $controllersDir;*/
    private $homepageController;
    private $blogpostController;
    private $commentController;
    private $userController;
    private $controllersInstanciated;

    public function __construct()
    {
        $this->uri=strtok($_SERVER['REQUEST_URI'],'?');
        if (str_contains($this->uri,'/index.php')!==false){
            $this->uri=str_replace('/index.php','',$this->uri);
        }
        /*if(!isset($this->controllersInstanciated)){
        $this->instanciateControllers();
        }*/
        /*printf('debug: URI reconnue: '.$this->uri.'    ');*/
        self::instanciateControllers();
    }

    public function instanciateControllers(){
        var_dump('q');
        $controllersDir=BASEDIR.'/Controllers/';
        require $controllersDir.'homepageController.php';
        $this->homepageController=new homepageController();
        var_dump($this->homepageController);die;
        require $controllersDir.'blogpostController.php';
        $this->blogpostController=new blogpostController();
        require $controllersDir.'commentController.php';
        $this->commentController=new commentController();
        require $controllersDir.'userController.php';
        $this->userController=new userController();
        $this->controllersInstanciated=true;
    }

    public function goToRoute(){
        //remove duplicates from the uri (can happen when same route called multiple times)
        $uriBlocks=explode('/',$this->uri);
        $cleanUriBlocks=array_unique($uriBlocks);
        $this->uri=implode('/',$cleanUriBlocks);
        switch ($this->uri){
            case('/blogposts'):
                $this->blogpostController->displayBlogposts();
                break;
            case('/blogpost'):
                $this->blogpostController->getOneBlogpost();
                break;
            case('/blogpost/create'):
                $this->blogpostController->createBlogpost();
                break;
            case('/blogpost/edit'):
                $this->blogpostController->displayUpdateBlogpost();
                break;
            case('/blogpost/save'):
                $this->blogpostController->saveUpdateBlogpost();
                break;
            case('/blogpost/delete'):
                $this->blogpostController->deleteBlogpost();
                break;
            case('/comments'):
                $this->commentController->displayCommentsAdmin();
                break;
            case('/comment'):
                $this->commentController->getOneComment();
                break;
            case('/comment/create'):
                $this->commentController->createComment();
                break;
            case('/comment/edit'):
                $this->commentController->displayUpdateComment();
                break;
            case('/comment/save'):
                $this->commentController->saveUpdateComment();
                break;
            case('/comment/moderate'):
                $this->commentController->changeCommentVisibility();
                break;
            case('/comment/delete'):
                $this->commentController->deleteComment();
                break;
            case('/signup'):
                $this->userController->createUser();
                break;
            case('/login'):
                $this->userController->login();
                break;
            case('/logout'):
                $this->userController->logout();
                break;
            case('/users'):
                $this->userController->displayUsers();
                break;
            case('/user'):
                $this->userController->getOneUser();
                break;
            case('/user/edit'):
                $this->userController->displayUpdateUser();
                break;
            case('/user/save'):
                $this->userController->saveUpdateUser();
                break;
            case('/'):
                $this->homepageController->displayHome(false);
                break;
            default:
                $this->homepageController->displayHome(true);
                break;
        }
    }

}