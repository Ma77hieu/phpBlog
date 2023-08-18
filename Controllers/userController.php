<?php

class userController extends baseController {

    const USER_CREATED="Vous êtes désormais enregistré.";
    const USER_NOT_CREATED="Problème lors de l'enregistrement.";
    const LOGIN_FAIL="Vos identifiants ne sont pas reconnus, veuillez rééssayer.";
    const LOGIN_OK="Vous êtes connecté.";
    const LOGOUT_OK="Vous avez été déconnecté.";
    const WRONG_PWD_REENTRY="Les deux mots de passe insérés ne correspondent pas, merci de réesayer";
    const USER_ALREADY_EXISTS="Cet email est déjà utilisé";
    const ERROR_USER_NOT_FOUND="Problème de récupération des informations de l'utilisateur";
    const USER_FOUND="Voici les informations de l'utilisateur";



    public function __construct()
    {
        parent::__construct();
    }

    public function displayUsers(){
        $user=new user();
        $users=$user->findAll();
        echo $this->twig->render('usersList.html.twig',
            ['users' => $users]);
    }

    public function getOneUser()
    {
        if ($_GET['id']){
            $userId=$_GET['id'];
        }
        $user = new user();
        $userFound=$user->findById($userId);
        if (!$userFound){
            $page='index.html.twig';
            $msg=new userFeedback('error',self::ERROR_USER_NOT_FOUND);
        } else {
            $page='userPage.html.twig';
            $msg=new userFeedback('success',self::USER_FOUND);
        }
        $feedback=$msg->getFeedback();
        echo $this->twig->render($page,
            [ 'user' => $userFound,
                'userFeedbacks' => $feedback]);
    }

    public function createUser(){

        $user = new user();

        if(!$_POST){
            //display the form
            $page = 'signup.html.twig';
        } else {
            //handle the form submission only if password = password reentry inside the form
            if ($_POST['password'] == $_POST['password_reentry']) {
                $datas = ['email' => $_POST['email'],
                    'password' => $_POST['password'],
                    'roles' => '[user]'];
                //check if the email is already used
                $alreadyUsedMail = $this->checkAlreadyExistsMail($_POST['email']);
                if ($alreadyUsedMail) {
                    $page = 'signup.html.twig';
                    $msg = new userFeedback('error', self::USER_ALREADY_EXISTS);
                } else {
                    $userCreation = $user->insertRow($datas);
                    if (!$userCreation) {
                        $page = 'signup.html.twig';
                        $msg = new userFeedback('error', self::USER_NOT_CREATED);
                    } else {
                        $user = new User();
                        $userConnected = $user->findById($userCreation);
                        /*var_dump($userConnected);die;*/
                        $this->saveLoginInSession($userConnected['roles']);
                        $page = 'index.html.twig';
                        $userFound = $user->findById($userCreation);
                        $msg = new userFeedback('success', self::USER_CREATED);
                    }
                }
            } else {
                $page = 'signup.html.twig';
                $msg = new userFeedback('error', self::WRONG_PWD_REENTRY);
            }
            $feedback = $msg->getFeedback();
        }
        echo $this->twig->render($page,
            ['userFeedbacks' => $feedback]);
    }

    public function updateUser($id){

    }

    public function deleteUser($id){

    }

    public function checkCredentials(){
        //check if the password and the password reentry are equivalent

    }

    public function login(){

    }

    public function saveLoginInSession($userRoles){
        $_SESSION['isLoggedIn']=true;
        $_SESSION['roles']=$userRoles;
    }

    public function checkAlreadyExistsMail($userMail){
        $existingMail=false;
        $searchUser=new User();
        $userFound=$searchUser->findRowsBy("WHERE email='$userMail'");
        if($userFound==[]){
            return false;
        } else {
            return true;
        }

    }

    public function logout(){
        $_SESSION['isLoggedIn']=false;
        $_SESSION['roles']=[];
        $msg=new userFeedback('success',self::LOGOUT_OK);
        $feedback=$msg->getFeedback();
        echo $this->twig->render('index.html.twig',
            ['userFeedbacks' => $feedback]);
    }

}