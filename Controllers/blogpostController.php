<?php

class blogpostController extends baseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Echo the twig template showing all blogposts
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function displayBlogposts()
    {
        $blogpost = new blogpost();
        $orderBy = 'ORDER BY creation_date DESC';
        $blogposts = $blogpost->findAll($orderBy);
        echo $this->twig->render('blogpostsList.html.twig',
            ['blogposts' => $blogposts,
                'loggedIn' => $this->isLoggedIn,
                'isUserAdmin' => $this->isUserAdmin,]);
    }

    /**
     * Echo the twig template showing one blogpost and its comments
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function getOneBlogpost()
    {
        if ($this->getVars['blogpost_id']) {
            $blogpostId = htmlspecialchars($this->getVars['blogpost_id']);
        }
        $blogpost = new blogpost();
        $blogpostFound = $blogpost->findById($blogpostId);
        $authorId = intval($blogpostFound['author']);
        $isCurrentUserAuthor = false;
        if (!$blogpostFound) {
            $page = 'index.html.twig';
            $msg = new userFeedback('error', ERROR_BLOGPOST_NOT_FOUND);
        } else {
            $page = 'blogpostPage.html.twig';
            $user = new user();
            $userFound = $user->findById($authorId);
            $author = $userFound['email'];
            if ($this->sessionVars['id'] == $authorId) {
                $isCurrentUserAuthor = true;
            }

        }
        $commentsFound = $blogpost->getBlogpostComments(true);
        if ($msg) {
            $feedback = $msg->getFeedback();
        }
        echo $this->twig->render($page,
            ['blogpost' => $blogpostFound,
                'author' => $author,
                'authorId' => $authorId,
                'comments' => $commentsFound,
                'loggedIn' => $this->isLoggedIn,
                'isCurrentUserAuthor' => $isCurrentUserAuthor,
                'isUserAdmin' => $this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Based on the type of http request (get or post),
     * echo the twig template with the create blogpost form or manage
     * the form submission
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function createBlogpost()
    {
        $blogpost = new blogpost();
        $userId = $this->sessionVars['id'];
        $now = new DateTime();

        if (!$this->postVars) {
            //display the form
            $page = 'blogpostCreation.html.twig';
        } else {
            //CSRF token check
            if ($this->postVars['csrf_token'] != $this->sessionVars['csrfToken']) {
                $page = 'blogpostCreation.html.twig';
                $msg = new userFeedback('error', ERROR_BLOGPOST_CREATION);
            } else {
                //handle the form submission
                $datas = ['title' => htmlspecialchars($this->postVars['blogpost_title']),
                    'summary' => htmlspecialchars($this->postVars['blogpost_summary']),
                    'content' => htmlspecialchars($this->postVars['blogpost_content']),
                    'author' => $userId,
                    'creation_date' => $now->format('Y-m-d H:i:s')];
                $blogpostCreation = $blogpost->insertRow($datas);
                if (!$blogpostCreation) {
                    $page = 'blogpostCreation.html.twig';
                    $msg = new userFeedback('error', ERROR_BLOGPOST_CREATION);
                } else {
                    $page = 'blogpostPage.html.twig';
                    $blogpostFound = $blogpost->findById($blogpostCreation);
                    $msg = new userFeedback('success', BLOGPOST_CREATED);
                }
            }
            $feedback = $msg->getFeedback();

        }
        echo $this->twig->render($page,
            ['blogpost' => $blogpostFound,
                'loggedIn' => $this->isLoggedIn,
                'isUserAdmin' => $this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Display the form to edit a blogpost
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function displayUpdateBlogpost()
    {
        $blogpostId = htmlspecialchars($this->getVars['blogpost_id']);
        $blogpost = new blogpost();
        $blogpostFound = $blogpost->findById($blogpostId);
        $rightsChecker = new accessController();
        $orderBy = 'ORDER BY creation_date DESC';
        $blogposts = $blogpost->findAll($orderBy);
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
            }
        }
        if ($msg) {
            $feedback = $msg->getFeedback();
        }
        echo $this->twig->render($page,
            ['blogposts' => $blogposts,
                'blogpost' => $blogpostFound,
                'loggedIn' => $this->isLoggedIn,
                'isUserAdmin' => $this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Handle the form submission to edit a blogpost
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function saveUpdateBlogpost()
    {
        $blogpostId = htmlspecialchars($this->getVars['blogpost_id']);
        $blogpost = new blogpost();
        $blogpostFound = $blogpost->findById($blogpostId);
        $rightsChecker = new accessController();
        $orderBy = 'ORDER BY creation_date DESC';
        $blogposts = $blogpost->findAll($orderBy);
        $page = 'blogpostsList.html.twig';
        //we check if the user tries to access one of its own blogposts
        if (!($rightsChecker->isUpdateAuthorized($blogpostFound))) {
            $msg = new userFeedback('error', NOT_OWNER);
        } else {
            if (!$blogpostFound || $this->postVars['csrf_token'] != $this->sessionVars['csrfToken']) {
                $msg = new userFeedback('error', ERROR_BLOGPOST_CREATION);
            } else {
                //handle the form submission
                $now = new DateTime();
                $datas = ['title' => htmlspecialchars($this->postVars['blogpost_edit_title']),
                    'summary' => htmlspecialchars($this->postVars['blogpost_edit_summary']),
                    'content' => htmlspecialchars($this->postVars['blogpost_edit_content']),
                    'modification_date' => $now->format('Y-m-d H:i:s')];
                $blogpost = new blogpost();
                $blogpost->updateRow($datas, $blogpostFound['blogpost_id']);
                $msg = new userFeedback('success', BLOGPOST_UPDATED);
            }
        }
        $feedback = $msg->getFeedback();
        echo $this->twig->render($page,
            ['blogposts' => $blogposts,
                'loggedIn' => $this->isLoggedIn,
                'isUserAdmin' => $this->isUserAdmin,
                'userFeedbacks' => $feedback]);
    }

    /**
     * Handle the deletion of a blogpost
     *
     * @return void
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function deleteBlogpost()
    {
        $blogpostId = htmlspecialchars($this->getVars['blogpost_id']);
        $blogpost = new blogpost();
        $blogpostFound = $blogpost->findById($blogpostId);
        $rightsChecker = new accessController();
        $page = 'homepage.html.twig';
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
                'loggedIn' => $this->isLoggedIn,
                'isUserAdmin' => $this->isUserAdmin]);
    }
}