<?php

class accessController extends baseController {



    // All uri that should be accessed only with admin rights need
    // to be inside this array, the controller checks for each value of the array if
    // it is INCLUDED inside the uri (not an exact match)
    const URI_ADMIN_REQUIRED=['/users',
        '/user/',
        '/comments'];

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
        if (!$_SESSION['id']) {
            return false;
        } else {
            $userId = $_SESSION['id'];
            $user = new user();
            $userConnected = $user->findById($userId);
            $userRoles = explode(',', $userConnected['roles']);
            if (in_array('admin', $userRoles)) {
                return true;
            } else {
                return false;
            }
        }
    }


    /**
     * Returns true if the user is logged in, false if not
     * @return bool
     */
    public function isLoggedIn(){
        if ($_SESSION['id']){
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


    /**
     * Checks if the route requires adin rights and if the user has admin rights
     * returns true for access granted and false for access denied
     * @return bool
     */
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
            /*echo("user_id: $isLoggedIn, isAdmin: $isAdmin");*/
            if(!($isLoggedIn && $isAdmin)){
                $isAccessOk=false;
            }
        }
        return $isAccessOk;
    }

    /**
     * Returns true if the current user is admin or if it is the author of the blogpost or comment
     * whose id is passed as parameter
     * @param array $model
     * @return bool
     */
    public function isUpdateAuthorized($model){
        $author=$model['author'];
        if($author==$_SESSION['id'] || $this->isAdmin()){
            return true;
        } else {
            return false;
        }
    }
}
