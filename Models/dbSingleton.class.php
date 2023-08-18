<?php

class dbSingleton{

    /**
     * @var self the database instance
     */
    public static $instance;

    public function __construct()
    {
        self::getInstance();
    }

    /**
     * Connects to the database if not already connected and return the database instance
     * @return dbSingleton|PDO|void
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = self::dbConnection();
        }
        return self::$instance;
    }

    /**
     * Connection to the database
     */
    public static function dbConnection()
    {
        $dbName=$_ENV['DB_NAME'];
        $dbUser=$_ENV['DB_USER'];
        $dbPwd=$_ENV['DB_PWD'];
        try {
            $database = new PDO("mysql:host=localhost;dbname=$dbName;charset=utf8", $dbUser, $dbPwd);
        } catch(Exception $e) {
            die('Erreur : '.$e->getMessage());
        }
        return $database;
    }


}