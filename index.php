<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ERROR);

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
if(!isset($_SESSION['id'])){
    $_SESSION['id']=null;
}

//UNCOMMENT FOLLOWING FOR DEBUG
/*foreach($_SESSION as $k=>$v){
    var_dump("debug session: $k = $v");
    echo('</br>');
    var_dump($v);
}*/

//CSRF token generation
$request_method = strtoupper($_SERVER['REQUEST_METHOD']);
$csrfToken=bin2hex(random_bytes(35));
if ($request_method === 'GET') {
    $_SESSION['csrfToken'] = $csrfToken;
}

require('Router/Router.class.php');
$router=new Router();
$router->goToRoute();
