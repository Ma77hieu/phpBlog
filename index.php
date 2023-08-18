<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//loading vendor
require_once realpath(__DIR__ . '/vendor/autoload.php');
const BASEDIR=__DIR__;

//loading environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

//including all classes from the Models directory
spl_autoload_register(function ($class_name) {
    $fullPath = 'Models/' . $class_name . '.class.php';
    if(file_exists($fullPath)) include $fullPath;
});

//initialize user's session
session_start();

//initialize session login status if needed
if(!isset($_SESSION['isLoggedIn'])){
    $_SESSION['isLoggedIn']=false;
}

//initialize role storage for the user if needed
if(!isset($_SESSION['roles'])){
    $_SESSION['roles']='[]';
}

/*foreach($_SESSION as $k=>$v){
    var_dump("debug session: $k = $v");
    echo('</br>');
}*/

require('Router/Router.class.php');
$router=new Router();
$router->goToRoute();
