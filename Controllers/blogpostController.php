<?php

class blogpostController extends baseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function displayBlogposts(){
        $blogpost=new blogpost();
        $orderBy='ORDER BY creation_date DESC';
        $blogposts=$blogpost->findAll($orderBy);
        echo $this->twig->render('blogpostsList.html.twig',
            ['blogposts' => $blogposts,
                'loggedIn'=>$this->isLoggedIn]);
    }

    public function getOneBlogpost()
    {
        if ($_GET['blogpost_id']){
            $blogpostId=$_GET['blogpost_id'];
        }
        $blogpost = new blogpost();
        $blogpostFound=$blogpost->findById($blogpostId);
        if (!$blogpostFound){
            $page='index.html.twig';
            $msg=new userFeedback('error',ERROR_BLOGPOST_NOT_FOUND);
        } else {
            $page='blogpostPage.html.twig';
            $user=new user();
            $userFound=$user->findById(intval($blogpostFound['author']));
            $author=$userFound['email'];
            $msg=new userFeedback('success',BLOGPOST_FOUND);
        }
        $commentsFound=$this->getBlogpostComments();
        $feedback=$msg->getFeedback();
        echo $this->twig->render($page,
            [ 'blogpost' => $blogpostFound,
                'author' => $author,
                'comments'=>$commentsFound,
                'loggedIn'=>$this->isLoggedIn,
                'userFeedbacks' => $feedback]);
    }

    public function createBlogpost(){
        $blogpost = new blogpost();
        $userId=$_SESSION['id'];
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
                $msg=new userFeedback('error',ERROR_BLOGPOST_CREATION);
            } else {
                $page='blogpostPage.html.twig';
                $blogpostFound=$blogpost->findById($blogpostCreation);
                $msg=new userFeedback('success',BLOGPOST_CREATED);
            }
            $feedback=$msg->getFeedback();

        }
        echo $this->twig->render($page,
            [ 'blogpost' => $blogpostFound,
                'loggedIn'=>$this->isLoggedIn,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Display the form to edit a blogpost
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function displayUpdateBlogpost()
    {
        $blogpostId=$_GET['blogpost_id'];
        $blogpost=new blogpost();
        $blogpostFound=$blogpost->findById($blogpostId);
        $rightsChecker = new accessController();
        $orderBy='ORDER BY creation_date DESC';
        $blogposts=$blogpost->findAll($orderBy);
        //we check if the user tries to access one of its own blogposts
        if (!($rightsChecker->isUpdateAuthorized($blogpostFound))) {
            $page = 'blogpostsList.html.twig';
            $msg = new userFeedback('error', NOT_OWNER);
        } else {
            if (!$blogpostFound) {
                $page = 'blogpostsList.html.twig';
                $msg = new userFeedback('error', BLOGPOST_NOT_FOUND);
            } else {
                $page = 'blogpostEditPage.html.twig';
                $msg = new userFeedback('success', BLOGPOST_FOUND);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['blogposts'=>$blogposts,
                'blogpost' => $blogpostFound,
                'loggedIn'=>$this->isLoggedIn,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Handle the form submission to edit a blogpost
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function saveUpdateBlogpost()
    {
        $blogpostId=$_GET['blogpost_id'];
        $blogpost=new blogpost();
        $blogpostFound=$blogpost->findById($blogpostId);
        $rightsChecker = new accessController();
        $orderBy='ORDER BY creation_date DESC';
        $blogposts=$blogpost->findAll($orderBy);
        $page = 'blogpostsList.html.twig';
        //we check if the user tries to access one of its own blogposts
        if (!($rightsChecker->isUpdateAuthorized($blogpostFound))) {
            $msg = new userFeedback('error', NOT_OWNER);
        } else {
            if (!$blogpostFound) {
                $msg = new userFeedback('error', BLOGPOST_NOT_FOUND);
            } else {
                //handle the form submission
                $now = new DateTime();
                $datas = ['title' => $_POST['title'],
                    'summary' => $_POST['summary'],
                    'content' => $_POST['content'],
                    'modification_date'=>$now->format('Y-m-d H:i:s')];
                $blogpost = new blogpost();
                $blogpost->updateRow($datas, $blogpostFound['blogpost_id']);
                $msg = new userFeedback('success', BLOGPOST_UPDATED);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['blogposts' => $blogposts,
                'loggedIn'=>$this->isLoggedIn,
                'userFeedbacks' => $feedback]);
    }

    public function deleteBlogpost(){
        $blogpostId=$_GET['blogpost_id'];
        $blogpost=new blogpost();
        $blogpostFound=$blogpost->findById($blogpostId);
        $rightsChecker = new accessController();
        $page='index.html.twig';
        if (!($rightsChecker->isUpdateAuthorized($blogpostFound))) {
            $msg = new userFeedback('error', NOT_OWNER);
        } else {
            if (!$blogpostFound) {
                $msg = new userFeedback('error', BLOGPOST_NOT_FOUND);
            } else {
                $blogpost = new blogpost();
                $blogpost->deleteRow($blogpostId);
                $msg = new userFeedback('success', BLOGPOST_DELETED);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['userFeedbacks' => $feedback,
                'loggedIn'=>$this->isLoggedIn]);
    }

    /**
     * Returns an array of all the comments related to the blogpost whose id
     * is defined inside the url blogpost_id parameter
     * @return array
     */
    public function getBlogpostComments(){
        $blogpostId=$_GET['blogpost_id'];
        if(!$blogpostId){
            $blogpostId=$_POST['blogpost_id'];
        }
        $comment=new comment();
        $where="WHERE blogpost=$blogpostId AND is_validated=true";
        $orderBy='ORDER BY creation_date DESC';
        $comments=$comment->findRowsBy($where,$orderBy);
        return $comments;
    }




}