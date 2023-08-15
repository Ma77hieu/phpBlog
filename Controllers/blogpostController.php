<?php

class blogpostController extends baseController {

    const SUCCESS_MESSAGE_BLOGPOST="Voici votre blogpost";
    const ERROR_BLOGPOST_NOT_FOUND="Problème dans la récupération du post";

    public function __construct()
    {
        parent::__construct();
    }

    public function displayBlogposts(){
        $blogpost=new blogpost();
        $blogposts=$blogpost->findAll();
        echo $this->twig->render('blogpostsList.html.twig',
            ['blogposts' => $blogposts]);
    }

    public function getOneBlogpost()
    {
        if ($_GET['id']){
            $blogpostId=$_GET['id'];
        }
        $blogpost = new blogpost();
        $blogpostFound=$blogpost->findById($blogpostId);
        if (!$blogpostFound){
            $page='index.html.twig';
            $msg=new userFeedback('error',self::ERROR_BLOGPOST_NOT_FOUND);
        } else {
            $page='blogpostPage.html.twig';
            $msg=new userFeedback('success',self::SUCCESS_MESSAGE_BLOGPOST);
        }
        $feedback=$msg->getFeedback();
        echo $this->twig->render($page,
            [ 'blogpost' => $blogpostFound,
                'userFeedbacks' => $feedback]);
    }

    public function createBlogpost(){

    }

    public function updateBlogpost($id){

    }

    public function deleteBlogpost($id){

    }




}