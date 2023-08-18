<?php
require_once('dbSingleton.class.php');

class model {


    /**
     * @var dbSingleton|PDO|void
     */
    public $database;

    public $attributesType;

    // the tablename will never be declared inside this object
    // it will always be declared inside the object that extend this base model
    public $tableName;

    public function __construct()
    {
        $this->database=dbSingleton::getInstance();
    }


    /**
     * Add a line to one of the DB table
     * If successfull, returns the id of the inserted line
     * @param array $datas array with ['column_name'=>'value']
     */
    public function insertRow(array $datas){
        $table=$this->tableName;
        $columnsString='';
        $valuesString="";
        foreach ($datas as $k=>$v){
            $columnsString.=$k.',';
            $valuesString.='?,';
        }
        $columnsString=rtrim($columnsString,',');
        $valuesString=substr($valuesString,0,-1);

        $sql="INSERT INTO $table($columnsString) VALUES ($valuesString)";
        $statement=$this->database->prepare($sql);
        $i=1;
        foreach ($datas as $k=>&$v){
            $type=$this->attributesType[$k];
            //in case of the inserted data being a password, need to hash it
            if($k=='password'){
                $v=md5($v);
            }
            $statement->bindParam($i,$v,$type);
            $i++;
        }
        if ($statement->execute()){
            $retour=$this->database->lastInsertId();
        } else {
            $retour=$statement->execute();
        }
        return $retour;
    }

    /**
     * Returns a specific table row based on its id
     * @param int $id the id of the line in the db
     * @return array ['column'=>'value', ... ]
     */
    public function findById(int $id){
        $table=$this->tableName;
        $sql="SELECT * FROM $table WHERE $table"."_id=$id";
        $statement=$this->database->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Returns all rows from a table
     * @return array ['column'=>'value', ... ]
     */
    public function findAll(){
        $table=$this->tableName;
        $sql="SELECT * FROM $table ";
        /*var_dump($sql);die;*/
        $statement=$this->database->prepare($sql);
        $statement->execute();
        $results=[];
        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $result){
            $results[]=$result;
        }
        return $results;
    }

    /**
     * Add a line to one of the DB table
     * @param array $datas array with ['column_name'=>'value']
     */
    public function updateRow( array $datas,int $id){
        $table=$this->tableName;
        $sql = "UPDATE $table SET ";
        foreach ($datas as $k => $v) {
            if ($this->attributesType[$k] == PDO::PARAM_STR) {
                if($k=='password'){
                    $v=md5($v);
                }
                $sql .= "$k='$v',";
            } else {
                $sql .= "$k=$v,";
            }
        }
        $sql = substr($sql, 0, -1);
        $sql .= " WHERE $table" . "_id=$id";
        $statement = $this->database->prepare($sql);
        return $statement->execute();
    }

    /**
     * Delete a line from the db table
     * @param int $id the id of the line in the db
     */
    public function deleteRow(int $id){
        $table=$this->tableName;
        $sql="DELETE FROM $table WHERE $table"."_id=$id";
        /*var_dump($sql);die;*/
        $statement=$this->database->prepare($sql);
        $statement->execute();
        return $statement->rowCount();
    }

    /**
     * Find one or several rows of a specific database table based on the where clause passed in parameter
     * @param string $whereClause
     */
    public function findRowsBy(string $whereClause){
        $table=$this->tableName;
        $sql="SELECT * FROM $table $whereClause";
        $statement=$this->database->prepare($sql);
        $statement->execute();
        $results=[];
        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $result){
            $results[]=$result;
        }
        return $results;
    }

    /**
     * @param string $dateColumnName name of the date column in the database
     * @param string $comparisonType can be '=','>','<','BETWEEN'
     * @param string $firstDate the first or only argument in the where clause
     * @param string $secondDate the second argument of the where clause in case of BETWEEN
     */
    public function findByDate($dateColumnName, $comparisonType, $firstDate, $secondDate=null){
        $table=$this->tableName;
        if ($secondDate == null){
            $whereClause="WHERE $dateColumnName $comparisonType '$firstDate'";
        } else {
            $whereClause="WHERE $dateColumnName $comparisonType '$firstDate' AND '$secondDate'";
        }
        $sql="SELECT * FROM $table $whereClause";
        $statement=$this->database->prepare($sql);
        $statement->execute();
        $results=[];
        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $result){
            $results[]=$result;
        }
        return $results;
    }

    /**
     * Find a model by its author
     * @param int $author id of the author of the searched model
     */
    public function findByAuthor($author){
        $whereClause="WHERE author='$author'";
        $find=$this->findRowsBy($whereClause);
        return $find;
    }

    /**
     * Find a model by its title
     * @param string $title the title of the searched model
     */
    public function findByTitle($title){
        $whereClause="WHERE title='$title'";
        $find=$this->findRowsBy($whereClause);
        return $find;
    }

}

