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
        return $statement->execute();
    }

    /**
     * Returns a specific table row based on its id
     * @param int $id the id of the line in the db
     * @return array ['column'=>'value', ... ]
     */
    public function read(int $id){
        $table=$this->tableName;
        $sql="SELECT * FROM $table WHERE $table"."_id=$id";
        /*var_dump($sql);die;*/
        $statement=$this->database->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
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


}

