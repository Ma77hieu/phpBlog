<?php
require_once('dbSingleton.class.php');

class model {


    /**
     * @var dbSingleton|PDO|void
     */
    public $database;

    public $attributesType;

    public function __construct()
    {
        $this->database=dbSingleton::getInstance();
    }


    /**
     * Add a line to one of the DB table
     * @param string $table name of the table on which to execute the function
     * @param array $datas array with ['column_name'=>'value']

     */
    public function insertRow(string $table, array $datas){
        $columnsString='';
        $valuesString="";
        /*var_dump($datas);die;*/
        foreach ($datas as $k=>$v){
            /*var_dump($k);
            var_dump($v);*/
            $columnsString.=$k.',';
            $valuesString.='?,';
        }
        /*var_dump($valuesWithType);die;*/
        $columnsString=rtrim($columnsString,',');
        $valuesString=substr($valuesString,0,-1);

        $sql="INSERT INTO $table($columnsString) VALUES ($valuesString)";
        /*var_dump($sql);die;*/
        $statement=$this->database->prepare($sql);
        $i=1;
        foreach ($datas as $k=>&$v){
            $type=$this->attributesType[$k];
            $statement->bindParam($i,$v,$type);
            $i++;
        }
        /*$statement->debugDumpParams();*/

        return $statement->execute();
    }

    /**
     * Returns a specific table row based on its id
     * @param string $table name of the table on which to execute the function
     * @param int $id the id of the line in the db
     * @return array ['column'=>'value', ... ]
     */
    public function read(string $table,int $id){
        $sql="SELECT * FROM $table WHERE $table"."_id=$id";
        /*var_dump($sql);die;*/
        $statement=$this->database->prepare($sql);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Add a line to one of the DB table
     * @param string $table name of the table on which to execute the function
     * @param array $datas array with ['column_name'=>'value']

     */
    public function updateRow(string $table, array $datas,int $id){
        $sql = "UPDATE $table SET ";
        foreach ($datas as $k => $v) {
            if ($this->attributesType[$k] == PDO::PARAM_STR) {
                $sql .= "$k='$v',";
            } else {
                $sql .= "$k=$v,";
            }
        }
        $sql = substr($sql, 0, -1);
        $sql .= " WHERE $table" . "_id=$id";
        /*var_dump($sql);die;*/
        $statement = $this->database->prepare($sql);
        return $statement->execute();
    }

    /**
     * @param string $table name of the table on which to execute the function
     * @param int $id the id of the line in the db
     */
    private function delete(string $table,int $id){

    }


}

