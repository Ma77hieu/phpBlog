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

    public function __construct()
    {
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

    }

    public function getUserId(){
        if ($_GET['id']){
            $userId=$_GET['id'];
            $user = new user();
            $userFound=$user->findById($userId);
        } else {
            $userFound=[];
        }
        $this->userFound=$userFound;
    }

    public function isUserLoggedIn(){
        if ($_SESSION['id']){
            $this->isLoggedIn=true;
        } else {
            $this->isLoggedIn=false;
        }
    }

    public function isUserAdmin(){
        if (!$_SESSION['id']) {
            return false;
        } else {
            $userId = $_SESSION['id'];
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
