<?php

class accessController extends baseController {

    const ACCESS_ERROR="Vous devez être connecté et avoir les droits suffisants pour accéder à cette page";

    // All pages that should be accessed only with admin rights need
    // to be inside this array
    const PAGE_ADMIN_REQUIRED=['usersList.html.twig','userCard.html.twig'];



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
     * @param string $page name of the .html.twig file for which we want to check if
     * admin rights are required to access
     * @return bool
     */
    public function isAdminRightRequired($page){
        if(in_array($page,self::PAGE_ADMIN_REQUIRED)){
            return true;
        } else {
            return false;
        }
    }


    public function checkAccessRights($page)
    {
        $isAccessOk=true;
        $adminRightNeeded=$this->isAdminRightRequired($page);
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
