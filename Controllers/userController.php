<?php


class userController extends baseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function displayUsers(){
        $rightsChecker=new accessController();
        //we check if the user can access this page that is for admin only
        if (!($rightsChecker->checkAccessRights())){
            $page='index.html.twig';
            $msg=new userFeedback('error',ACCESS_ERROR);
            $feedback = $msg->getFeedback();
        } else {
            $page='usersList.html.twig';
            $user=new user();
            $users=$user->findAll();
        }
        echo $this->twig->render($page,
        ['userFeedbacks' => $feedback,
            'loggedIn'=>$this->isLoggedIn,
            'isUserAdmin'=>$this->isUserAdmin,
            'users' => $users]);
    }

    public function getOneUser()
    {
        $rightsChecker = new accessController();
        //we check if the user can access this page that is for admin only
        if (!($rightsChecker->checkAccessRights())) {
            $page = 'index.html.twig';
            $msg = new userFeedback('error', ACCESS_ERROR);
            $feedback = $msg->getFeedback();
        } else {
            if (!$this->userFound) {
                $page = 'index.html.twig';
                $msg = new userFeedback('error', ERROR_USER_NOT_FOUND);
            } else {
                $page = 'userPage.html.twig';
            }
        }
        if($msg){
            $feedback = $msg->getFeedback();
        }

        echo $this->twig->render($page,
            ['user' => $this->userFound,
                'loggedIn'=>$this->isLoggedIn,
                'isUserAdmin'=>$this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    public function createUser(){
        $user = new user();
        if(!$this->postVars){
            //display the form
            $page = 'signup.html.twig';
        } else {
            //handle the form submission only if password = password reentry inside the form
            if ($this->postVars['password'] == $this->postVars['password_reentry']) {
                $datas = ['email' => htmlspecialchars($this->postVars['email']),
                    'password' => htmlspecialchars($this->postVars['password']),
                    'roles' => 'user'];
                //check if the email is already used
                $alreadyUsedMail = $this->checkAlreadyExistsMail(htmlspecialchars($this->postVars['email']));
                if ($alreadyUsedMail) {
                    $page = 'signup.html.twig';
                    $msg = new userFeedback('error', USER_ALREADY_EXISTS);
                } else {
                    if ($this->postVars['csrf_token'] != $this->sessionVars['csrfToken']) {
                        $page = 'signup.html.twig';
                        $msg = new userFeedback('error', USER_NOT_CREATED);
                    } else {
                        $userCreation = $user->insertRow($datas);
                        if (!$userCreation) {
                            $page = 'signup.html.twig';
                            $msg = new userFeedback('error', USER_NOT_CREATED);
                        } else {
                            $this->saveLoginInSession($userCreation);
                            $page = 'homepage.html.twig';
                            $msg = new userFeedback('success', USER_CREATED);
                        }
                    }
                }
            } else {
                $page = 'signup.html.twig';
                $msg = new userFeedback('error', WRONG_PWD_REENTRY);
            }
            $feedback = $msg->getFeedback();
        }
        echo $this->twig->render($page,
            ['userFeedbacks' => $feedback,
                'isUserAdmin'=>$this->isUserAdmin,
                'loggedIn'=>$this->isLoggedIn]);
    }

    public function login()
    {
        $user = new user();
        if (!$this->postVars) {
            //display the form
            $page = 'login.html.twig';
        } else {
            //handle the form submission
            $email=htmlspecialchars($this->postVars['email']);
            $encodedPwd=md5(htmlspecialchars($this->postVars['password']));
            $userFound=$user->findRowsBy("WHERE email='$email' AND password='$encodedPwd'");
            if ($userFound==[]){
                $msg = new userFeedback('error', LOGIN_FAIL);
            } else {
                $this->saveLoginInSession($userFound[0]['user_id']);
                $msg = new userFeedback('success', LOGIN_OK);
            }
            $page = 'homepage.html.twig';
            $feedback = $msg->getFeedback();
        }
        $this->isUserAdmin();
        echo $this->twig->render($page,
            ['userFeedbacks' => $feedback,
                'isUserAdmin'=>$this->isUserAdmin,
                'loggedIn'=>true]);
    }

    public function logout(){
        $this->sessionVars['isLoggedIn']=false;
        $this->sessionVars['roles']=[];
        $msg=new userFeedback('success',LOGOUT_OK);
        $feedback=$msg->getFeedback();
        session_unset();
        echo $this->twig->render('homepage.html.twig',
            ['userFeedbacks' => $feedback,
                'isUserAdmin' => false,
                'loggedIn' => false]);
    }

    public function displayUpdateUser()
    {
        $rightsChecker = new accessController();
        //we check if the user can access this page that is for admin only
        if (!($rightsChecker->checkAccessRights())) {
            $page = 'index.html.twig';
            $msg = new userFeedback('error', ACCESS_ERROR);
            $feedback = $msg->getFeedback();
        } else {
            if (!$this->userFound) {
                $page = 'index.html.twig';
                $msg = new userFeedback('error', ERROR_USER_NOT_FOUND);
            } else {
                $page = 'userEditPage.html.twig';
                $viewedUserIsAdmin=false;
                $user=new User;
                $viewedUser=$user->findById($this->getVars['id']);
                $viewedUserRoles=$viewedUser['roles'];
                if (str_contains($viewedUserRoles,'admin')){
                    $viewedUserIsAdmin=true;
                }
            }
        }
        if($msg){
        $feedback = $msg->getFeedback();
        }
        echo $this->twig->render($page,
            ['user' => $this->userFound,
                'isUserAdmin' => $this->isUserAdmin,
                'loggedIn' => $this->isLoggedIn,
                'viewedUserIsAdmin'=>$viewedUserIsAdmin,
                'userFeedbacks' => $feedback]);
    }

    public function saveUpdateUser()
    {
        $rightsChecker = new accessController();
        //we check if the user can access this page that is for admin only
        if (!($rightsChecker->checkAccessRights())) {
            $page = 'index.html.twig';
            $msg = new userFeedback('error', ACCESS_ERROR);
        } else {
            if (!$this->userFound || $this->postVars['csrf_token'] != $this->sessionVars['csrfToken'] ) {
                $page = 'index.html.twig';
                $msg = new userFeedback('error', USER_NOT_CREATED);
            } else {
                //handle the form submission
                //as we use a checkbox, if it is not checked, $this->>$this->postVars['roles'] is not sent by the form
                if ($this->postVars['edit_roles'] == 'admin') {
                    $roles = 'user,admin';
                } else {
                    $roles = 'user';
                }
                $datas = ['email' => htmlspecialchars($this->postVars['edit_email']),
                    'roles' => $roles];
                //change password only if needed
                if ($this->postVars['password'] != '') {
                    $datas = $datas + ['password' => htmlspecialchars($this->postVars['edit_password'])];
                }
                $user = new user();
                $user->updateRow($datas, $this->userFound['user_id']);
                $page = 'usersList.html.twig';
                $users = $user->findAll();
                $msg = new userFeedback('success', USER_UPDATED);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['users' => $users,
                'isUserAdmin' => $this->isUserAdmin,
                'loggedIn' => $this->isLoggedIn,
                'userFeedbacks' => $feedback]);
    }

    public function saveLoginInSession($userId){
        $this->sessionVars['id']=$userId;
    }

    /**
     * Checks if an email is already associated to a DB user
     * @param $userMail
     * @return bool
     */
    public function checkAlreadyExistsMail($userMail){
        $searchUser=new User();
        $userFound=$searchUser->findRowsBy("WHERE email='$userMail'");
        if($userFound==[]){
            return false;
        } else {
            return true;
        }

    }


}