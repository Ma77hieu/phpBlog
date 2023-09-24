<?php

/**
 * class used in order to display some alert messages to the user
 * based on success or failure of the requested action
 *
 * The type variable can be 'success' 'error' or 'warning'
 * The msgTxt variable represents the actual message to be displayed
 */
class userFeedback
{

    private $type;
    private $msgTxt;

    public function __construct($type, $msgTxt)
    {
        $this->type = $type;
        $this->msgTxt = $msgTxt;
    }

    /**
     * Used to associace a message to its type (error or success)
     * this type is then used in front in order to modify css class
     * @return array[]
     */
    public function getFeedback()
    {
        return [['type' => $this->type, 'text' => $this->msgTxt]];
    }

}