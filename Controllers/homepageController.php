<?php

class homepageController extends baseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function displayHome(){
        $blogpost=new blogpost();
        $blogposts=$blogpost->findAll();
        echo $this->twig->render('index.html.twig',
            ['requested_page' => 'homepage',
                'blogposts' => $blogposts]);
    }
}
