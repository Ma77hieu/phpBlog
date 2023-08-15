<?php
require('Controllers/baseController.php');
class Router{

    private $uri;
    private $controllersDir;
    private $params;

    public function __construct()
    {
        $this->uri=strtok($_SERVER['REQUEST_URI'],'?');
        /*var_dump(strpos($this->uri,'/index.php'));die;*/
        if (str_contains($this->uri,'/index.php')!==false){
            $this->uri=str_replace('/index.php','',$this->uri);
        }
        $this->controllersDir=BASEDIR.'/Controllers/';
        $this->params=$_GET;
        printf('URI reconnue: '.$this->uri.'    ');
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
                /*echo('uri reconnue /blogpost ');*/
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController();
                $controller->getOneBlogpost();
                break;
            case('/'):
                require $this->controllersDir.'homepageController.php';
                $controller=new homepageController();
                $controller->displayHome();
                break;
            //test case
            /*case('/'):
                require $this->controllersDir.'blogpostController.php';
                $controller=new blogpostController(1);
                $controller->getOneBlogpost();
                break;*/
            default:
                var_dump('404 ERROR');die;
                break;
        }
    }

}
/*class Router {

    private $url;
    private $routes = [];
    private $namedRoutes = [];

    public function __construct($url){
        $this->url = $url;
    }

    public function get($path, $callable, $name = null){
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post($path, $callable, $name = null){
        return $this->add($path, $callable, $name, 'POST');
    }

    private function add($path, $callable, $name, $method){
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;
        if(is_string($callable) && $name === null){
            $name = $callable;
        }
        if($name){
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    public function run(){
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            throw new RouterException('REQUEST_METHOD does not exist');
        }
        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
            if($route->match($this->url)){
                return $route->call();
            }
        }
        throw new RouterException('No matching routes');
    }

    public function url($name, $params = []){
        if(!isset($this->namedRoutes[$name])){
            throw new RouterException('No route matches this name');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

}*/