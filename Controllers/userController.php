<?php
require('Controllers/accessController.php');

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
    const USER_UPDATED="Les modifications ont été enregistrées";



    public function __construct()
    {
        parent::__construct();
    }

    public function displayUsers(){
        $rightsChecker=new accessController();
        //we check if the user can access this page that is for admin only
        if (!($rightsChecker->checkAccessRights())){
            $page='index.html.twig';
            $msg=new userFeedback('error',$rightsChecker::ACCESS_ERROR);
            $feedback = $msg->getFeedback();
        } else {
            $page='usersList.html.twig';
            $user=new user();
            $users=$user->findAll();
        }
        echo $this->twig->render($page,
        ['userFeedbacks' => $feedback,
            'users' => $users]);
    }

    public function getOneUser()
    {
        $rightsChecker = new accessController();
        //we check if the user can access this page that is for admin only
        if (!($rightsChecker->checkAccessRights())) {
            $page = 'index.html.twig';
            $msg = new userFeedback('error', $rightsChecker::ACCESS_ERROR);
            $feedback = $msg->getFeedback();
        } else {
            if (!$this->userFound) {
                $page = 'index.html.twig';
                $msg = new userFeedback('error', self::ERROR_USER_NOT_FOUND);
            } else {
                $page = 'userPage.html.twig';
                $msg = new userFeedback('success', self::USER_FOUND);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['user' => $this->userFound,
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
                    'roles' => 'user'];
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
                        $this->saveLoginInSession($userCreation);
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

    public function login()
    {
        $user = new user();
        if (!$_POST) {
            //display the form
            $page = 'login.html.twig';
        } else {
            //handle the form submission
            $email=$_POST['email'];
            $encodedPwd=md5($_POST['password']);
            $userFound=$user->findRowsBy("WHERE email='$email' AND password='$encodedPwd'");
            if ($userFound==[]){
                $msg = new userFeedback('error', self::LOGIN_FAIL);
            } else {
                $this->saveLoginInSession($userFound[0]['user_id']);
                $msg = new userFeedback('success', self::LOGIN_OK);
            }
            $page = 'index.html.twig';
            $feedback = $msg->getFeedback();
        }
        echo $this->twig->render($page,
            ['userFeedbacks' => $feedback]);
    }


    public function logout(){
        $_SESSION['isLoggedIn']=false;
        $_SESSION['roles']=[];
        $msg=new userFeedback('success',self::LOGOUT_OK);
        $feedback=$msg->getFeedback();
        session_unset();
        echo $this->twig->render('index.html.twig',
            ['userFeedbacks' => $feedback]);
    }

    public function displayUpdateUser()
    {
        $rightsChecker = new accessController();
        //we check if the user can access this page that is for admin only
        if (!($rightsChecker->checkAccessRights())) {
            $page = 'index.html.twig';
            $msg = new userFeedback('error', $rightsChecker::ACCESS_ERROR);
            $feedback = $msg->getFeedback();
        } else {
            if (!$this->userFound) {
                $page = 'index.html.twig';
                $msg = new userFeedback('error', self::ERROR_USER_NOT_FOUND);
            } else {
                $page = 'userEditPage.html.twig';
                $msg = new userFeedback('success', self::USER_FOUND);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['user' => $this->userFound,
                'userFeedbacks' => $feedback]);
    }

    public function saveUpdateUser()
    {
        $rightsChecker = new accessController();
        //we check if the user can access this page that is for admin only
        if (!($rightsChecker->checkAccessRights())) {
            $page = 'index.html.twig';
            $msg = new userFeedback('error', $rightsChecker::ACCESS_ERROR);
            $feedback = $msg->getFeedback();
        } else {
            if (!$this->userFound) {
                $page = 'index.html.twig';
                $msg = new userFeedback('error', self::ERROR_USER_NOT_FOUND);
            } else {
                //handle the form submission
                //as we use a checkbox, if it is not checked, $_POST['roles'] is not sent by the form
                if ($_POST['roles'] == 'admin') {
                    $roles = 'user,admin';
                } else {
                    $roles = 'user';
                }
                $now = new DateTime();
                $datas = ['email' => $_POST['email'],
                    'roles' => $roles];
                //change password only if needed
                if ($_POST['password'] != '') {

                    $datas = $datas + ['password' => $_POST['password']];
                }
                $user = new user();
                $user->updateRow($datas, $this->userFound['user_id']);
                $page = 'usersList.html.twig';
                $users = $user->findAll();
                $msg = new userFeedback('success', self::USER_UPDATED);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['users' => $users,
                'userFeedbacks' => $feedback]);
    }

    public function deleteUser($id){

    }


    public function saveLoginInSession($userId){
        $user=new user();
        $userConnected = $user->findById($userId);
        $userRoles=explode(',',$userConnected['roles']);
        $_SESSION['isLoggedIn']=true;
        $_SESSION['roles']=$userRoles;
    }

    /**
     * Checks if an email is already associated to a DB user
     * @param $userMail
     * @return bool
     */
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


}