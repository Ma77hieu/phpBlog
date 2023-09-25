<?php

class commentController extends baseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Echo the twig template showing all unvalidated comments (waiting for an 
     * admin validation)
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function displayUnvalidatedComments()
    {
        $twigData = ['isUserAdmin' => $this->isUserAdmin,
            'loggedIn' => $this->isLoggedIn];
        $rightsChecker = new accessController();
        if (!$rightsChecker->isAdmin()) {
            $page = 'index.html.twig';
            $msg = new userFeedback('error', ACCESS_ERROR);
        } else {
            $page = 'commentsList.html.twig';
            $comment = new comment();
            $whereClause = 'WHERE is_validated=false';
            $orderBy = 'ORDER BY creation_date DESC';
            $comments = $comment->findRowsBy($whereClause, $orderBy);
            $twigData += ['comments' => $comments];
        }
        if ($msg) {
            $feedback = $msg->getFeedback();
        }
        $twigData += ['userFeedbacks' => $feedback];
        echo $this->twig->render($page, $twigData);
    }



    /**
     * Echo the twig template to create a comment (if get request) and
     * manage the form submission (if post request)
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createComment()
    {
        $comment = new comment();
        $author = $this->sessionVars['id'];
        $blogpostId = htmlspecialchars($this->getVars['blogpost_id']);
        $blogpost = new blogpost();
        $blogpostFound = $blogpost->findById($blogpostId);
        $now = new DateTime();
        if (!$this->postVars) {
            //display the form
            $page = 'commentCreation.html.twig';
        } else {
            $page = 'blogpostPage.html.twig';
            //handle the form submission
            $datas = ['title' => htmlspecialchars($this->postVars['comment_title']),
                'text' => htmlspecialchars($this->postVars['comment_content']),
                'author' => (int) $author,
                'blogpost' => (int) $blogpostId,
                'creation_date' => $now->format('Y-m-d H:i:s'),
                'is_validated' => false];
            $commentCreation = $comment->insertRow($datas);
            if (!$commentCreation || $this->postVars['csrf_token'] != $this->sessionVars['csrfToken']) {
                $msg = new userFeedback('error', ERROR_COMMENT_CREATION);
            } else {
                $msg = new userFeedback('success', COMMENT_CREATED);
            }
            $userId=$this->sessionVars['id'];
            $comments = $blogpost->getBlogpostComments(true,$blogpostId, $userId);
            $feedback = $msg->getFeedback();
        }
        echo $this->twig->render($page,
            ['blogpost' => $blogpostFound,
                'comments' => $comments,
                'loggedIn' => $this->isLoggedIn,
                'isUserAdmin' => $this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Display the form to edit a comment
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function displayUpdateComment()
    {
        $commentId = htmlspecialchars($this->getVars['comment_id']);
        $comment = new comment();
        $commentFound = $comment->findById($commentId);
        $blogpostId = htmlspecialchars($this->getVars['blogpost_id']);
        $blogpost = new blogpost();
        $blogpostFound = $blogpost->findById($blogpostId);
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
            }
        }
        if ($msg) {
            $feedback = $msg->getFeedback();
        }
        echo $this->twig->render($page,
            ['blogpost' => $blogpostFound,
                'comment' => $commentFound,
                'loggedIn' => $this->isLoggedIn,
                'isUserAdmin' => $this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Handle the form submission to edit a comment
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function saveUpdateComment()
    {
        $commentId = htmlspecialchars($this->getVars['comment_id']);
        $blogpostId = htmlspecialchars($this->getVars['blogpost_id']);
        $author = htmlspecialchars($this->sessionVars['id']);
        $blogpost = new blogpost();
        $blogpostFound = $blogpost->findById($blogpostId);
        $comment = new comment();
        $commentFound = $comment->findById($commentId);
        $rightsChecker = new accessController();
        $page = 'blogpostPage.html.twig';
        //we check if the user tries to access one of its own comments
        if (!($rightsChecker->isUpdateAuthorized($commentFound))) {
            $msg = new userFeedback('error', NOT_OWNER_COMMENT);
        } else {
            if (!$commentFound || $this->postVars['csrf_token'] != $this->sessionVars['csrfToken']) {
                $msg = new userFeedback('error', ERROR_COMMENT_CREATION);
            } else {
                //handle the form submission
                $now = new DateTime();
                $datas = ['title' => htmlspecialchars($this->postVars['comment_edit_title']),
                    'text' => htmlspecialchars($this->postVars['comment_edit_content']),
                    'author' => (int) $author,
                    'blogpost' => (int) $blogpostId,
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
        $onlyValidatedComments = true;
        if ($this->isUserAdmin) {
            $onlyValidatedComments = false;
        }
        $comments = $blogpost->getBlogpostComments($onlyValidatedComments,$blogpostId, $author);
        echo $this->twig->render($page,
            ['blogpost' => $blogpostFound,
                'comments' => $comments,
                'loggedIn' => $this->isLoggedIn,
                'isUserAdmin' => $this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Handle the deletion of a comment
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function deleteComment()
    {
        $commentId = htmlspecialchars($this->getVars['comment_id']);
        $comment = new comment();
        $commentFound = $comment->findById($commentId);
        $rightsChecker = new accessController();
        $page = 'homepage.html.twig';
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
                'isUserAdmin' => $this->isUserAdmin,
                'loggedIn' => $this->isLoggedIn]);
    }

    /**
     * Handle the validation of a comment by an admin or its unvalidation
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function changeCommentVisibility()
    {
        $commentId = (int) htmlspecialchars($this->getVars['comment_id']);
        $comment = new comment();
        $commentFound = $comment->findById($commentId);
        $rightsChecker = new accessController();
        $page = 'commentsList.html.twig';
        $twigData = ['isUserAdmin' => $this->isUserAdmin,
            'loggedIn' => $this->isLoggedIn];
        //we check if the user tries to access one of its own comments
        if (!$rightsChecker->isAdmin()) {
            $msg = new userFeedback('error', ACCESS_ERROR);
        } else {
            if (!$commentFound) {
                $page = 'index.html.twig';
                $msg = new userFeedback('error', ACCESS_ERROR);
            } else {
                $currentValidationState = $commentFound['is_validated'];
                if ($currentValidationState == 1) {
                    $validation = 0;
                } else {
                    $validation = 1;
                }
                $datas = ['is_validated' => $validation];
                $comment = new comment();
                $comment->updateRow($datas, $commentId);
                $whereClause = 'WHERE is_validated=false';
                $orderBy = 'ORDER BY creation_date DESC';
                $comments = $comment->findRowsBy($whereClause, $orderBy);
                $msg = new userFeedback('success', VISIBILITY_UPDATED);
                $twigData += ['comments' => $comments];
            }
        }
        $feedback = $msg->getFeedback();
        $twigData += ['userFeedbacks' => $feedback];
        echo $this->twig->render($page, $twigData);
    }


}