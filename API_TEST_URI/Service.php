<?php


class Service
{
    public $conn;
    public $table_name = "service";
    public $serviceID;
    public $nameService;
    public $priceTypeService;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // print json
    function read_info($stmt)
    {
        $num = $stmt->rowCount();

        if ($num > 0) {
            // department array
            $User_arr = array();
            // retrieve table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $User_arr[]=$row;
            }
            return json_encode($User_arr,JSON_PRETTY_PRINT);
        } else {
            return json_encode(["error" => "Not found."]);
        }
    }

    function read_put($stmt)
    {
        $num = $stmt->rowCount();
        if ($num > 0) {
            // department array
            // retrieve table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $User_arr=$row;
            }
            return ($User_arr);
        } else {
            return (["error" => "Not found."]);
        }
    }

    function read2($params, $s_params)
    {
        // query to select array
        if (count($s_params) > 0) {
            $query = "SELECT ";
            foreach ($s_params as $key => $value) {
                $query .= $key . " ,";
            }
            $query = substr($query, 0, strlen($query) - 2);
        } else
            $query = "SELECT *";
        $query .= " FROM " . $this->table_name;

        // prepare query statement
        if (count($params) > 0) {
            $query .= " WHERE";
            foreach ($params as $key => $value) {
                $query .= " `" . $key . "` = " . $value . " AND";
            }
            $query = substr($query, 0, strlen($query) - 4);
        }
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    function create()
    {
        // query to insert record
        //$this->send_mail_verif();
        $this->planningID = $this->last_id();
        $query = "INSERT INTO " . $this->table_name . " VALUES('$this->serviceID','$this->nameService','$this->priceTypeService')";
        // prepare w
        $stmt = $this->conn->prepare($query);
        // execute query
        if ($stmt->execute()) {
            return json_encode(["serviceID"=>"$this->serviceID"],JSON_PRETTY_PRINT);

            //$query = "SELECT userID FROM " . $this->table_name . " WHERE email = '$this->email' ORDER BY userID DESC LIMIT 1";
            //$stmt = $this->conn->prepare($query);
            //if ($stmt->execute()) {
            //    return $stmt;
            //} return FALSE;
        } else {
            return FALSE;
        }
    }

    function last_id(){
        $query = "SELECT " .$this->table_name. "ID FROM " . $this->table_name . " WHERE ".$this->table_name."ID ORDER BY ".$this->table_name."ID DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return (++$stmt->fetch(PDO::FETCH_ASSOC)[$this->table_name. "ID"]);
        } return FALSE;
    }

}