<?php

class blogpost extends model
{
    /**
     * @var int blogpost's id
     */
    public $blogpostId;

    /**
     * @var string blogpost's title
     */
    public $title;

    /**
     * @var string blogpost's summary
     */
    public $summary;

    /**
     * @var string blogpost's content
     */
    public $content;

    /**
     * @var int blogpost's author id
     */
    public $author;

    /**
     * @var datetime blogpost's creation date
     */
    public $creationDate;

    /**
     * @var datetime blogpost's modification date
     */
    public $modificationDate;

    /**
     * @return int
     */
    public function getBlogpostId(): int
    {
        return $this->blogpostId;
    }

    /**
     * @param int $blogpostId
     */
    public function setBlogpostId(int $blogpostId): void
    {
        $this->blogpostId = $blogpostId;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getAuthor(): int
    {
        return $this->author;
    }

    /**
     * @param int $author
     */
    public function setAuthor(int $author): void
    {
        $this->author = $author;
    }

    /**
     * @return datetime
     */
    public function getCreationDate(): datetime
    {
        return $this->creationDate;
    }

    /**
     * @param datetime $creationDate
     */
    public function setCreationDate(datetime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return datetime
     */
    public function getModificationDate(): datetime
    {
        return $this->modificationDate;
    }

    /**
     * @param datetime $modificationDate
     */
    public function setModificationDate(datetime $modificationDate): void
    {
        $this->modificationDate = $modificationDate;
    }

    public $attributesType = [
        'blogpost_id' => PDO::PARAM_INT,
        'title' => PDO::PARAM_STR,
        'summary' => PDO::PARAM_STR,
        'content' => PDO::PARAM_STR,
        'author' => PDO::PARAM_INT,
        'creation_date' => PDO::PARAM_STR,
        'modification_date' => PDO::PARAM_STR
    ];

    public $tableName = 'blogpost';

    /**
     * Returns an array of all the comments related to the blogpost whose id
     * is defined inside the url blogpost_id parameter
     *
     * @param bool $onlyValidatedComments true if only validated comments need to be returned
     * @param string $blogpostId id of the blogpost for which comments are looked for
     * @param string $userId id of the person currently viewing the blogpost
     * @return array
     */
    public function getBlogpostComments($onlyValidatedComments,$blogpostId, $userId)
    {
        $comment = new comment();
        $where = "WHERE blogpost=$blogpostId";
        if ($onlyValidatedComments) {
            $where .= " AND is_validated=true";
        }
        $orderBy = 'ORDER BY creation_date DESC';
        $comments = $comment->findRowsBy($where, $orderBy);
        $currentUserId = $userId;
        $treatedComments = [];
        foreach ($comments as $comment) {
            $isUserAuthor = false;
            if ($currentUserId == $comment['author']) {
                $isUserAuthor = true;
            }
            $comment += ['isUserAuthor' => $isUserAuthor];
            $treatedComments[] = $comment;
        }
        return $treatedComments;
    }


}