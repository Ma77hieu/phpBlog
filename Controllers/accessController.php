<?php

class accessController extends baseController {



    // All uri that should be accessed only with admin rights need
    // to be inside this array
    const URI_ADMIN_REQUIRED=['/users',
        '/user/'];

    public function __construct()
    {
        parent::__construct();
    }

    /** Returns true if the user is admin, false if not
     * (always call after checking if the user is logged in)
     * @return bool
     */
    public function isAdmin()
    {
        if (in_array('admin', $_SESSION['roles'])) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Returns true if the user is logged in, false if not
     * @return bool
     */
    public function isLoggedIn(){
        if ($_SESSION['isLoggedIn']){
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $route name of the route for which we want to check if
     * admin rights are required to access
     * @return bool
     */
    public function isAdminRightRequired($route)
    {
        $isAdminsRequired=false;
        foreach (self::URI_ADMIN_REQUIRED as $uri) {
            if (str_contains($route, $uri)) {
                $isAdminsRequired= true;
            }
        }
        return $isAdminsRequired;
    }


    public function checkAccessRights()
    {
        $route=strtok($_SERVER['REQUEST_URI'],'?');
        if (str_contains($route,'/index.php')!==false){
            $route=str_replace('/index.php','',$route);
        }
        $isAccessOk=true;
        $adminRightNeeded=$this->isAdminRightRequired($route);
        if ($adminRightNeeded){
            $isLoggedIn=$this->isLoggedIn();
            $isAdmin=$this->isAdmin();
            echo("isLoggedIn: $isLoggedIn, isAdmin: $isAdmin");
            if(!($isLoggedIn && $isAdmin)){
                $isAccessOk=false;
            }
        }
        return $isAccessOk;
    }
}
