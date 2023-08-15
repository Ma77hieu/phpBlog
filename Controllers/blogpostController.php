<?php

class blogpostController extends baseController {

    const BLOGPOST_FOUND="Voici votre blogpost";
    const ERROR_BLOGPOST_NOT_FOUND="Problème dans la récupération du post";
    const ERROR_BLOGPOST_CREATION="Problème lors de la création du blogpost";
    const BLOGPOST_CREATED="Votre post a été enregistré";


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
            $msg=new userFeedback('success',self::BLOGPOST_FOUND);
        }
        $feedback=$msg->getFeedback();
        echo $this->twig->render($page,
            [ 'blogpost' => $blogpostFound,
                'userFeedbacks' => $feedback]);
    }

    public function createBlogpost(){
        $blogpost = new blogpost();
        //TODO to variabilise
        $userId=16;
        $now=new DateTime();

        if(!$_POST){
            //display the form
            $page='blogpostCreation.html.twig';
        } else {
            //handle the form submission
            $datas = ['title' => $_POST['title'],
                'summary' => $_POST['summary'],
                'content' => $_POST['content'],
                'author'=> $userId,
                'creation_date'=> $now->format('Y-m-d H:i:s')];
            $blogpostCreation=$blogpost->insertRow($datas);
            if (!$blogpostCreation){
                $page='blogpostCreation.html.twig';
                $msg=new userFeedback('error',self::ERROR_BLOGPOST_CREATION);
            } else {
                $page='blogpostPage.html.twig';
                $blogpostFound=$blogpost->findById($blogpostCreation);
                $msg=new userFeedback('success',self::BLOGPOST_CREATED);
            }
            $feedback=$msg->getFeedback();

        }
        echo $this->twig->render($page,
            [ 'blogpost' => $blogpostFound,
                'userFeedbacks' => $feedback]);
    }

    public function updateBlogpost($id){

    }

    public function deleteBlogpost($id){

    }




}