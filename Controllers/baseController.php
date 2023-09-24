<?php
require(BASEDIR.'/Translations/fr/userDisplayedMessages.php');
require('Controllers/accessController.php');
class baseController {

    /**
     * @var \Twig\Environment
     */
    public \Twig\Environment $twig;

    /**
     * Associative array sent by PDO::FETCH_ASSOC
     * exple:['user_id'=>1,'password'=>'xxxxxx','roles'=>'user,admin']
     * @var array
     */
    public array $userFound;

    /**
     * true if user is logged in, false if not
     * @var bool
     */
    public bool $isLoggedIn;

    /**
     * true if current user is admin
     * @var bool
     */
    public bool $isUserAdmin;

    /**
     * store the superglobal $_SESSION variables
     * @var array
     */
    public array $sessionVars;

    /**
     * store the superglobal $_GET variables
     * @var array
     */
    public array $getVars;

    /**
     * store the superglobal $_POST variables
     * @var array
     */
    public array $postVars;

    /**
     * store the superglobal $_ENV variables
     * @var array
     */
    public array $envVars;

    public function __construct()
    {
        $this->sessionVars = &$_SESSION;
        $this->getVars = &$_GET;
        $this->postVars = &$_POST;
        $this->envVars = &$_ENV;
        $this->generateTwig();
        $this->getUserId();
        $this->isUserLoggedIn();
        $this->isUserAdmin();
    }

    public function generateTwig(){
        $loader = new \Twig\Loader\FilesystemLoader(BASEDIR .'/Templates');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig->addGlobal('session', $_SESSION);

    }

    public function getUserId(){
        if ($this->getVars['id']){
            $userId=htmlspecialchars($this->getVars['id']);
            $user = new user();
            $userFound=$user->findById($userId);
        } else {
            $userFound=[];
        }
        $this->userFound=$userFound;
    }

    public function isUserLoggedIn(){
        if ($this->sessionVars['id']){
            $this->isLoggedIn=true;
        } else {
            $this->isLoggedIn=false;
        }
    }

    public function isUserAdmin(){
        if (!$this->sessionVars['id']) {
            $this->isUserAdmin=false;
        } else {
            $userId = $this->sessionVars['id'];
            $user = new user();
            $userConnected = $user->findById($userId);
            $userRoles = explode(',', $userConnected['roles']);
            if (in_array('admin', $userRoles)) {
                $this->isUserAdmin=true;
            } else {
                $this->isUserAdmin=false;
            }
        }
    }
}
