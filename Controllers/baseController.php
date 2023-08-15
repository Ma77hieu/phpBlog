<?php

class baseController {

    /**
     * @var \Twig\Environment
     */
    public \Twig\Environment $twig;

    public function __construct()
    {
        $this->generateTwig();
    }

    public function generateTwig(){
        $loader = new \Twig\Loader\FilesystemLoader(BASEDIR .'/Templates');
        $this->twig = new \Twig\Environment($loader, [
            'cache' => false,
            'debug' => true,
        ]);
        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

    }
}
