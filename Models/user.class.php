<?php

class user extends model {
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

    public $tableName='user';


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
     * Saves a new user in the database
     * @param array $values array with ['attribute_name'=>'value', ... ]
     */
    public function createUser($values){
        $values=self::hashPwd($values);
        $creation=$this->insertRow($values);
        return $creation;
    }

    /**
     * Get user's data
     * @param int $id the user id
     */
    public function getUser($id){
        $read=$this->read($id);
        return $read;
    }

    /**
     * Updates some attributes of a user in the database
     * @param array $values array with ['attribute_name'=>'value', ... ]
     * @param int $id the id of the user to be modified
     * @return mixed
     */
    public function updateUser($values,$id){
        $values=self::hashPwd($values);
        $update=$this->updateRow($values,$id);
        return $update;
    }

    /**
     * Delete a user based on it's id
     * @param int $userId Id of the user to delete
     */
    public function deleteUser($userId){
        $delete=$this->deleteRow($userId);
        return $delete;
    }

    /**
     * Find user by its email
     * @param array $criterias array of the criteria, exple:['column_name'=>value]
     */
    public function findUserByName($email){
        $whereClause="WHERE email='$email'";
        $find=$this->findRowsBy($whereClause);
        return $find;
    }

    /**
     * Find all users with admin rights
     */
    public function findAllAdminUsers(){
        $whereClause="WHERE roles LIKE '%admin%'";
        $find=$this->findRowsBy($whereClause);
        return $find;
    }

    /** Detect if there is a password inside a data array with
     * the structure ['column_name'=>value] and encode it if there is
     * @param array $datas
     * @return array
     */
    private function hashPwd(array $datas){
        foreach($datas as $k=>$v){
            if ($k=='password'){
                $v=md5($v);
            }
        }
        return $datas;
    }



}