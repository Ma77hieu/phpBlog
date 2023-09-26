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
if ($request_method === 'GET') {
    manageCsrf();
}

$router=new Router();
$router->goToRoute();
