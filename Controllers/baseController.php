<?php
require(BASEDIR . '/Translations/fr/userDisplayedMessages.php');
require('Controllers/accessController.php');

class baseController
{

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

    /**
     * Constructor of baseController class
     */
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

    /**
     * setup the Twig parameters to be used each time a template is rendered
     */
    public function generateTwig()
    {
        $loader = new \Twig\Loader\FilesystemLoader(BASEDIR . '/Templates');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        $this->twig->addGlobal('session', $_SESSION);

    }

    /**
     * associate the user id stored in session to the
     * userFound attribute of baseController class
     */
    public function getUserId()
    {
        if ($this->getVars['id']) {
            $userId = htmlspecialchars($this->getVars['id']);
            $user = new user();
            $userFound = $user->findById($userId);
        } else {
            $userFound = [];
        }
        $this->userFound = $userFound;
    }

    /**
     * defines the isLoggedIn attribute of baseController class to true if
     * there is a user id stored in session
     */
    public function isUserLoggedIn()
    {
        if ($this->sessionVars['id']) {
            $this->isLoggedIn = true;
        } else {
            $this->isLoggedIn = false;
        }
    }

    /**
     * defines the isUserAdmin attribute of baseController class to true if
     * the user corresponding to the session variable id is admin
     */
    public function isUserAdmin()
    {
        if (!$this->sessionVars['id']) {
            $this->isUserAdmin = false;
        } else {
            $userId = $this->sessionVars['id'];
            $user = new user();
            $userConnected = $user->findById($userId);
            $userRoles = explode(',', $userConnected['roles']);
            if (in_array('admin', $userRoles)) {
                $this->isUserAdmin = true;
            } else {
                $this->isUserAdmin = false;
            }
        }
    }
}
