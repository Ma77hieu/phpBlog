<?php
require('../Translations/fr/userDisplayedMessages.php');
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

    public function __construct()
    {

        $this->generateTwig();
        $this->getUserId();
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
}
