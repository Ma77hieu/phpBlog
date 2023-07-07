<?php
include_once 'Constants.php';

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
            self::$instance = Model::dbConnection();
        }
        return self::$instance;
    }


}