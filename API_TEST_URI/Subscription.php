<?php


class Subscription
{
    private $conn;
    private $table_name = "subscription";
    public $subscriptionID;
    public $nbDays;
    public $beginningHour;
    public $lateHour;
    public $pricePerYear;
    public $hourPerMonth;
    public $languageSubscription;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getSubscriptionID()
    {
        return $this->subscriptionID;
    }

    public function setSubscriptionID($subscriptionID): void
    {
        $this->subscriptionID = $subscriptionID;
    }

    public function getNbDays()
    {
        return $this->nbDays;
    }

    public function setNbDays($nbDays): void
    {
        $this->nbDays = $nbDays;
    }

    public function getBeginningHour()
    {
        return $this->beginningHour;
    }

    public function setBeginningHour($beginningHour): void
    {
        $this->beginningHour = $beginningHour;
    }

    public function getLateHour()
    {
        return $this->lateHour;
    }

    public function setLateHour($lateHour): void
    {
        $this->lateHour = $lateHour;
    }

    public function getPricePerYear()
    {
        return $this->pricePerYear;
    }

    public function setPricePerYear($pricePerYear): void
    {
        $this->pricePerYear = $pricePerYear;
    }

    public function getHourPerMonth()
    {
        return $this->hourPerMonth;
    }

    public function setHourPerMonth($hourPerMonth): void
    {
        $this->hourPerMonth = $hourPerMonth;
    }

    public function getLanguageSubscription()
    {
        return $this->languageSubscription;
    }

    public function setLanguageSubscription($languageSubscription): void
    {
        $this->languageSubscription = $languageSubscription;
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

    function read_info($stmt)
    {
        $num = $stmt->rowCount();

        if ($num > 0) {
            // department array
            $User_arr = array();

            // retrieve table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $User_arr[] = $row;
            }
            return json_encode($User_arr, JSON_PRETTY_PRINT);
        } else {
            return json_encode(["error" => "Not found."]);
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