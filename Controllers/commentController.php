<?php
require('Controllers/blogpostController.php');
class commentController extends baseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function displayUnvalidatedComments(){
        $rightsChecker = new accessController();
        if(!$rightsChecker->isAdmin()){
            $page='index.html.twig';
            $msg=new userFeedback('error',ACCESS_ERROR);
            $feedback=$msg->getFeedback();
            $data=['isUserAdmin'=>$this->isUserAdmin,
                'loggedIn'=>$this->isLoggedIn,
                'userFeedbacks' => $feedback];
        } else {
            $page='commentsList.html.twig';
            $comment=new comment();
            $whereClause='WHERE is_validated=false';
            $orderBy='ORDER BY creation_date DESC';
            $comments=$comment->findRowsBy($whereClause,$orderBy);
            $data=['comments' => $comments,
                'isUserAdmin'=>$this->isUserAdmin,
                'loggedIn'=>$this->isLoggedIn];
        }
        echo $this->twig->render($page,$data);
    }


    public function getOneComment()
    {
        if ($_GET['id']){
            $commentId=htmlspecialchars($_GET['id']);
        }
        $comment = new comment();
        $commentFound=$comment->findById($commentId);
        if (!$commentFound){
            $page='index.html.twig';
            $msg=new userFeedback('error',ERROR_COMMENT_NOT_FOUND);
        } else {
            $page='commentPage.html.twig';
            $msg=new userFeedback('success',COMMENT_FOUND);
        }
        $feedback=$msg->getFeedback();
        echo $this->twig->render($page,
            [ 'comment' => $commentFound,
                'loggedIn'=>$this->isLoggedIn,
                'isUserAdmin'=>$this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    public function createComment(){
        $comment = new comment();
        $author=$_SESSION['id'];
        $blogpostId=htmlspecialchars($_GET['blogpost_id']);
        $blogpost=new blogpost();
        $blogpostFound=$blogpost->findById($blogpostId);
        $now = new DateTime();
        if (!$_POST) {
            //display the form
            $page = 'commentCreation.html.twig';
        } else {
            //handle the form submission
            $datas = ['title' => htmlspecialchars($_POST['title']),
                'text' => htmlspecialchars($_POST['content']),
                'author' => intval($author),
                'blogpost' => intval($blogpostId),
                'creation_date' => $now->format('Y-m-d H:i:s'),
                'is_validated' => false];
            $commentCreation = $comment->insertRow($datas);
            if (!$commentCreation) {
                $page = 'commentCreation.html.twig';
                $msg = new userFeedback('error', ERROR_COMMENT_CREATION);
            } else {
                $page = 'blogpostPage.html.twig';
                $commentFound = $comment->findById($commentCreation);
                $msg = new userFeedback('success', COMMENT_CREATED);
            }
            $feedback = $msg->getFeedback();
        }
        echo $this->twig->render($page,
            ['blogpost' => $blogpostFound,
                'loggedIn'=>$this->isLoggedIn,
                'isUserAdmin'=>$this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Display the form to edit a comment
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function displayUpdateComment()
    {
        $commentId=htmlspecialchars($_GET['comment_id']);
        $comment=new comment();
        $commentFound=$comment->findById($commentId);
        $blogpostId=htmlspecialchars($_GET['blogpost_id']);
        $blogpost=new blogpost();
        $blogpostFound=$blogpost->findById($blogpostId);
        $rightsChecker = new accessController();
        //we check if the user tries to access one of its own comments
        if (!($rightsChecker->isUpdateAuthorized($commentFound))) {
            $page = 'blogpostPage.html.twig';
            $msg = new userFeedback('error', NOT_OWNER_COMMENT);
        } else {
            if (!$commentFound) {
                $page = 'blogpostPage.html.twig';
                $msg = new userFeedback('error', COMMENT_NOT_FOUND);
            } else {
                $page = 'commentEditPage.html.twig';
                $msg = new userFeedback('success', COMMENT_FOUND);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['blogpost'=>$blogpostFound,
                'comment' => $commentFound,
                'loggedIn'=>$this->isLoggedIn,
                'isUserAdmin'=>$this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Handle the form submission to edit a comment
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function saveUpdateComment()
    {
        $commentId=htmlspecialchars($_GET['comment_id']);
        $blogpostId=htmlspecialchars($_GET['blogpost_id']);
        $author=$_SESSION['id'];
        $blogpost=new blogpost();
        $blogpostFound=$blogpost->findById($blogpostId);
        $comment=new comment();
        $commentFound=$comment->findById($commentId);
        $rightsChecker = new accessController();
        $page = 'blogpostPage.html.twig';
        //we check if the user tries to access one of its own comments
        if (!($rightsChecker->isUpdateAuthorized($commentFound))) {
            $msg = new userFeedback('error', NOT_OWNER_COMMENT);
        } else {
            if (!$commentFound) {
                $msg = new userFeedback('error', COMMENT_NOT_FOUND);
            } else {
                //handle the form submission
                $now = new DateTime();
                $datas = ['title' => htmlspecialchars($_POST['comment_edit_title']),
                    'text' => htmlspecialchars($_POST['comment_edit_content']),
                    'author' => intval($author),
                    'blogpost' => intval($blogpostId),
                    'creation_date' => $now->format('Y-m-d H:i:s'),
                    'is_validated' => 0];
                $comment = new comment();
                $commentUpdate = $comment->updateRow($datas, $commentId);
                if (!$commentUpdate) {
                    $msg = new userFeedback('error', COMMENT_NOT_UPDATED);
                } else {
                    $msg = new userFeedback('success', COMMENT_UPDATED);
                }
            }
        }
        $feedback = $msg->getFeedback();
        $blogpostsController=new blogpostController();
        $onlyValidatedComments=true;
        if($this->isUserAdmin){
            $onlyValidatedComments=false;
        }
        $comments=$blogpostsController->getBlogpostComments($onlyValidatedComments);
        echo $this->twig->render($page,
            ['blogpost'=>$blogpostFound,
                'comments' => $comments,
                'loggedIn'=>$this->isLoggedIn,
                'isUserAdmin'=>$this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    public function deleteComment(){
        $commentId=htmlspecialchars($_GET['comment_id']);
        $comment=new comment();
        $commentFound=$comment->findById($commentId);
        $rightsChecker = new accessController();
        $page='homepage.html.twig';
        if (!($rightsChecker->isUpdateAuthorized($commentFound))) {
            $msg = new userFeedback('error', NOT_OWNER);
        } else {
            if (!$commentFound) {
                $msg = new userFeedback('error', COMMENT_NOT_FOUND);
            } else {
                $comment = new comment();
                $comment->deleteRow($commentId);
                $msg = new userFeedback('success', COMMENT_DELETED);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['userFeedbacks' => $feedback,
                'isUserAdmin'=>$this->isUserAdmin,
                'loggedIn'=>$this->isLoggedIn]);
    }

    public function changeCommentVisibility(){
        $commentId=intval(htmlspecialchars($_GET['comment_id']));
        $comment=new comment();
        $commentFound=$comment->findById($commentId);
        $rightsChecker = new accessController();
        $page = 'commentsList.html.twig';
        //we check if the user tries to access one of its own comments
        if (!($rightsChecker->isUpdateAuthorized($commentFound))) {
            $msg = new userFeedback('error', NOT_OWNER_COMMENT);
        } else {
            if (!$commentFound) {
                $msg = new userFeedback('error', COMMENT_NOT_FOUND);
            } else {
                $currentValidationState=$commentFound['is_validated'];
                if($currentValidationState==1){
                    $validation=0;
                }else{
                    $validation=1;
                }
                $datas = ['is_validated' => $validation];
                $comment = new comment();
                $comment->updateRow($datas, $commentId);
                $msg = new userFeedback('success', VISIBILITY_UPDATED);

            }
        }
        $orderBy='ORDER BY creation_date DESC';
        $comments=$comment->findAll($orderBy);
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['loggedIn'=>$this->isLoggedIn,
                'isUserAdmin'=>$this->isUserAdmin,
                'comments'=> $comments,
                'userFeedbacks' => $feedback]);
    }




}