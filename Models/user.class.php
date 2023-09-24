<?php

class user extends model
{
    /**
     * @var int user's id
     */
    public $userId;

    /**
     * @var string user's email
     */
    public $email;

    /**
     * @var string user's paswword
     */
    public $password;

    /**
     * @var array user's roles, can be user and/or admin
     */
    public $roles;

    public $attributesType = [
        'user_id' => PDO::PARAM_INT,
        'email' => PDO::PARAM_STR,
        'password' => PDO::PARAM_STR,
        'roles' => PDO::PARAM_STR
    ];

    public $tableName = 'user';


    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Find user by its email
     * @param string $email email of the searched user
     */
    public function findUserByEmail($email)
    {
        $whereClause = "WHERE email='$email'";
        $find = $this->findRowsBy($whereClause);
        return $find;
    }

    /**
     * Find all users with admin rights
     */
    public function findAllAdminUsers()
    {
        $whereClause = "WHERE roles LIKE '%admin%'";
        $find = $this->findRowsBy($whereClause);
        return $find;
    }

}