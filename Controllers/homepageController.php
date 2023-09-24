<?php

class homepageController extends baseController
{

    /**
     *Constructor function of homepageController class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Displays the homepage.
     * The homepage is also used as redirection for 404 errors with a specific
     * error message.
     *
     * @return void
     * @param boolean $with404Message true if error 404 message needs to be displayed
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function displayHome($with404Message)
    {
        if ($with404Message) {
            $msg = new userFeedback('error', ERROR_404);
            $feedback = $msg->getFeedback();
        }
        echo $this->twig->render('homepage.html.twig',
            [
                'userFeedbacks' => $feedback,
                'isUserAdmin' => $this->isUserAdmin,
                'loggedIn' => $this->isLoggedIn]);
    }
}
