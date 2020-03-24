<?php

/**
 * Description of Department
 *
 * @author https://www.roytuts.com
 */
class ServiceProvider
{

    // database connection and table name
    private $conn;
    private $table_name = "serviceprovider";
    // object properties
    public $providerID;
    public $agency;
    public $lastName;
    public $firstName;
    public $email;
    public $password;
    public $city;
    public $address;
    public $phoneNumber;
    public $hash;


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
                $query .= " `" . $key . "` = \"" . $value . "\" AND";
            }
            $query = substr($query, 0, strlen($query) - 4);

        }
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();
        return $stmt;
    }


    // create user
    function create()
    {
        // query to insert record
        //$this->send_mail_verif();
        $this->clientID = $this->last_id();
        $query = "INSERT INTO " . $this->table_name . " VALUES('$this->providerID','$this->agency','$this->lastName','$this->firstName','$this->email','$this->password','$this->city','$this->address','$this->phoneNumber','$this->hash')";
        // prepare w
        echo $query;
        $stmt = $this->conn->prepare($query);
        // execute query
        if ($stmt->execute()) {
            return json_encode(["userID"=>"$this->providerID"],JSON_PRETTY_PRINT);

            //$query = "SELECT userID FROM " . $this->table_name . " WHERE email = '$this->email' ORDER BY userID DESC LIMIT 1";
            //$stmt = $this->conn->prepare($query);
            //if ($stmt->execute()) {
            //    return $stmt;
            //} return FALSE;
        } else {
            return FALSE;
        }
    }

    function update($params)
    {
        if (count($params) > 0) {
            $query = "UPDATE " . $this->table_name . " SET";

            foreach ($params as $key => $value) {
                if($value != NULL)
                    $query .= " `" . $key . "` = \"" . $value . "\",";
            }
            if($query == "UPDATE " . $this->table_name . " SET")
                return json_encode(["false" => "nothing to modified"]);
            $query = substr($query, 0, strlen($query) - 1);
            $query .= " WHERE providerID = ".$this->providerID;
            $query .= " AND agency = \"".$this->agency."\"";
        }
        $stmt = $this->conn->prepare($query);
        // execute query
        if ($stmt->execute()) {
            return json_encode(["valid" => "Account has been updated ! "]);
        } else {
            return json_encode(["error" => "Fatal Error"]);
        }

    }

    // delete the user
    function delete($id) {
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE providerID = ?";
        // prepare query
        $stmt = $this->conn->prepare($query);
        //$this->id = htmlspecialchars(strip_tags($this->id));
        // bind id of record to delete
        $stmt->bindParam(1, $id);
        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    function send_mail_verif(){


        $hash = md5( rand(0,10000) ); // hash md5 d'un chiffre entre 0 & 10000
        $destinataire = $this->email ;

        $req = $this->conn->prepare('UPDATE serviceprovider SET hash = ? WHERE providerID = ?');
        $req->execute(array($hash,$this->userID));

        $sujet = "Activer votre compte !";
        $entete = "From: confirmation@luxuryservice.fr";

        $mailmessage = 'Bienvenue sur Luxury Service,

                            Pour activer votre compte, veuillez cliquer sur le lien ci dessous
                            ou copier/coller dans votre navigateur internet.

                            https://51.77.221.39/verif_mail.php?email=';

        $mailmessage .= $destinataire;

        $mailmessage .= '&hash='.$hash;

        $mailmessage .= '
                            ---------------
                            Ceci est un mail automatique, Merci de ne pas y répondre.';

        try
        {
            mail($destinataire,$sujet,$mailmessage,$entete);
        }
        catch (Exception $e)
        {
            echo "Message could not be sent.";
            echo "Mailer Error: " . $destinataire->ErrorInfo;
        }

        echo "Message has been sent.";
        $this->hash = $hash;
    }

    function send_mail(){



        $destinataire = $GLOBALS['email'] ;
        $sujet = $GLOBALS['sujet'];
        $entete = "From: admin@luxuryservice.fr";

        $mailmessage = $GLOBALS['msg'];
        $mailmessage .='
                             ---------------
                             Ceci est un mail automatique, Merci de ne pas y répondre.';


        try
        {
            mail($destinataire,$sujet,$mailmessage,$entete);
        }
        catch (Exception $e)
        {
            echo "Message could not be sent.";
            echo "Mailer Error: " . $destinataire->ErrorInfo;
        }

        echo "Message has been sent.";
    }

    function last_id(){
        $query = "SELECT " .$this->table_name. "ID FROM " . $this->table_name . " WHERE agency = \"".$this->agency."\" ORDER BY ".$this->table_name."ID DESC LIMIT 1";echo $query;
        $stmt = $this->conn->prepare($query);
        if ($stmt->execute()) {
            return (++$stmt->fetch(PDO::FETCH_ASSOC)[$this->table_name. "ID"]);
        } return FALSE;
    }

}

/*$stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":UserFunction", $this->UserFunction);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":phoneNumber", $this->phoneNumber);
        $stmt->bindParam(":qrcode", $this->qrcode);*/