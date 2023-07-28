<?php

class comment extends model{
    /**
     * @var int comment's id
     */
    public $commentId;

    /**
     * @var string comment's title
     */
    public $title;

    /**
     * @var int comment's author id
     */
    public $author;

    /**
     * @var datetime comment's creation date
     */
    public $creationDate;

    /**
     * @var datetime comment's modification date
     */
    public $modificationDate;


    public $attributesType = [
        'comment_id' => PDO::PARAM_INT,
        'title' => PDO::PARAM_STR,
        'text' => PDO::PARAM_STR,
        'author' => PDO::PARAM_INT,
        'creation_date' => PDO::PARAM_STR,
        'modification_date' => PDO::PARAM_STR
    ];

    public $tableName='comment';

    /**
     * @return string
     */
    public function getCommentId(): string
    {
        return $this->commentId;
    }

    /**
     * @param string $commentId
     */
    public function setCommentId(string $commentId): void
    {
        $this->commentId = $commentId;
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
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    /**
     * @param string $creationDate
     */
    public function setCreationDate(string $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @return string
     */
    public function getModificationDate(): string
    {
        return $this->modificationDate;
    }

    /**
     * @param string $modificationDate
     */
    public function setModificationDate(string $modificationDate): void
    {
        $this->modificationDate = $modificationDate;
    }

    /**
     * @return string
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @param string $dbName
     */
    public function setDbName(string $dbName): void
    {
        $this->dbName = $dbName;
    }


}