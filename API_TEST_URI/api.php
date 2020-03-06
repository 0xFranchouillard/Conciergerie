<?php

require_once('router.php');
require_once('User.php');
require_once('db.php');
require_once('jwt.php');
require_once('Subscription.php');

header("Content-Type: application/json; charset=UTF-8");


class API
{
    //Input datas
    private $_URI;          //URI - /password/cat/id
    private $_method;       //GET - POST - PUT - DELETE
    private $_rawInput;     //Raw input

    function __construct($inputs)
    {
        //HTTP inputs
        $this->_URI =       $this->_checkKey('URI', $inputs);
        $this->_rawInput =  $this->_checkKey('raw_input', $inputs);
        $this->_method =    $this->_checkKey('method', $inputs);
    }

    //Return NULL if the key does not exist
    private function _checkKey($key, $array){
        return array_key_exists($key, $array) ? $array[$key] : NULL;
    }

    public function generateToken() {
        $email = $this->validateParameter('email', $this->param['email'], STRING);
        $pass = $this->validateParameter('pass', $this->param['pass'], STRING);
        try {
            $stmt = $this->dbConn->prepare("SELECT * FROM users WHERE email = :email AND password = :pass");
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":pass", $pass);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if(!is_array($user)) {
                $this->returnResponse(INVALID_USER_PASS, "Email or Password is incorrect.");
            }

            if( $user['active'] == 0 ) {
                $this->returnResponse(USER_NOT_ACTIVE, "User is not activated. Please contact to admin.");
            }

            $paylod = [
                'iat' => time(),
                'iss' => 'localhost',
                'exp' => time() + (15*60),
                'userId' => $user['id']
            ];

            $token = JWT::encode($paylod, SECRETE_KEY);

            $data = ['token' => $token];
            $this->returnResponse(SUCCESS_RESPONSE, $data);
        } catch (Exception $e) {
            $this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());
        }
    }



    public function run() {

        //Create the router
        $router = new Router();

        // Populate the router

        // GET homepage
        $router->addRoute('GET', '/v1', function() {
            echo "Home page";
            echo " ".$_SERVER['REQUEST_URI'];
            echo " ".$_SERVER['REQUEST_METHOD'];
            exit();

        });

        $router->addRoute('GET', '/v1/agency', function() {
            $database = new Db();
            $db = $database->getConnection();
            $reqType = $db->prepare("SELECT name FROM agency");
            $reqType->execute();
            while($agency = $reqType->fetch()){
                $data[] = $agency[0];
            }
            echo json_encode($data);
        });
        $router->addRoute('GET', '/v1/planning', function() {
            $database = new Db();
            $db = $database->getConnection();
            $Planning = new User($db);
            $params = array();
            $s_params = array();

                $stmt = $Planning->read2($params,$s_params);

                if(($stmt)->rowCount() <= 0) {
                    echo json_encode(["error" => "Wrong password !"]);
                    exit();
                }

                echo $Planning->read_info($stmt);
                exit();

        });
        $router->addRoute('GET', '/v1/users', function() {
            $database = new Db();
            $db = $database->getConnection();
            $User = new User($db);
            $params = array();
            $s_params = array();
            if(isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])){

                $params['email'] = "\"".$_SERVER['PHP_AUTH_USER']."\"";
                $stmt = $User->read2($params,$s_params);

                if(($stmt)->rowCount() <= 0) {
                    echo json_encode(["error" => "Email Not found."]);
                    exit();
                }

                $params['password'] = "\"".$_SERVER['PHP_AUTH_PW']."\"";
                $stmt = $User->read2($params,$s_params);

                if(($stmt)->rowCount() <= 0) {
                    echo json_encode(["error" => "Wrong password !"]);
                    exit();
                }

                echo $User->read_info($stmt);
                exit();
            }

            //$stmt = $User->read2($params,$s_params);
            //echo $User->read_info($stmt);
            //exit();
        });

        $router->addRoute('GET', '/v1/subscriptions', function() {
            $database = new Db();
            $db = $database->getConnection();
            $Subscription = new Subscription($db);
            $params = array();
            $stmt = $Subscription->read2($params,$params);
            echo $Subscription->read_info($stmt);
            echo $Subscription->last_id();
        });

        $router->addRoute('GET', '/v1/users/:request', function($request) {
            if(in_array($request,["userID","lastName","firstName","email","password","UserFunction","city","address","phoneNumber","qrcode"])) {
            $database = new Db();
            $db = $database->getConnection();
            $User = new User($db);
            $params = array();
            $s_params = array();
            $s_params[$request] = $request;

            $stmt = $User->read2($params, $s_params);
            echo $User->read_info($stmt);
            exit();
            }
        });

        $router->addRoute('GET', '/v1/users/:id', function($id) {
            if(preg_match('/^\d+$/',$id)) {
                $database = new Db();
                $db = $database->getConnection();
                $User = new User($db);

                $params = array();
                $params['userID'] = $id;
                $s_params = array();

                $stmt = $User->read2($params, $s_params);
                echo $User->read_info($stmt);
            }
        });

        $router->addRoute('GET', '/v1/users/:id/:request', function($id,$request) {
            $database = new Db();
            $db = $database->getConnection();
            $User = new User($db);

            $params = array();
            $params['userID'] = $id;
            $s_params = array();
            $s_params[$request] = $request;

            $stmt = $User->read2($params,$s_params);
            echo $User->read_info($stmt);
            exit();
        });

        $router->addRoute('DELETE', '/v1/users/:id', function($id) {
            $database = new Db();
            $db = $database->getConnection();
            $User = new User($db);
            $val = $User->delete($id);

            if($val)
                echo "ok";
            else
                echo "non ok";
            exit();
        });

        $router->addRoute('POST', '/v1/users', function() {
            $database = new Db();
            $db = $database->getConnection();
            $User = new User($db);
            $err = array();

                if (isset($_POST['lastName']) && !empty($_POST['lastName']))
                    $User->lastName = htmlspecialchars($_POST['lastName']);
                    else
                        $err += ["lastName" => "Not found."];
                    if (isset($_POST['firstName']) && !empty($_POST['firstName']))
                        $User->firstName = htmlspecialchars($_POST['firstName']);
                        else
                            $err += ["firstName" => "Not found."];
                        if (isset($_POST['email']) && !empty($_POST['email']))
                            $User->email = htmlspecialchars($_POST['email']);
                            else
                                $err += ["email" => "Not found."];
                            if (isset($_POST['password']) && !empty($_POST['password']))
                                $User->password = htmlspecialchars($_POST['password']);
                                else
                                    $err += ["password" => "Not found."];
                                if (isset($_POST['userFunction']) && !empty($_POST['userFunction']))
                                    $User->userFunction = htmlspecialchars($_POST['userFunction']);
                                    else
                                        $err += ["userFunction" => "Not found."];
                                    if (isset($_POST['city']) && !empty($_POST['city']))
                                        $User->city = htmlspecialchars($_POST['city']);
                                        else
                                            $err += ["city" => "Not found."];
                                        if (isset($_POST['address']) && !empty($_POST['address']))
                                            $User->address = htmlspecialchars($_POST['address']);
                                            else
                                                $err += ["address" => "Not found."];
                                            if (isset($_POST['phoneNumber']) && !empty($_POST['phoneNumber']))
                                                $User->phoneNumber = htmlspecialchars($_POST['phoneNumber']);
                                                else
                                                    $err += ["phoneNumber" => "Not found."];
                                                if (isset($_POST['qrCode']) && !empty($_POST['qrCode']))
                                                    $User->qrCode = htmlspecialchars($_POST['qrCode']);
                                                    else
                                                        $err += ["qrCode" => "Not found."];
                                                    if (isset($_POST['agency']) && !empty($_POST['agency']))
                                                        $User->agency = htmlspecialchars($_POST['agency']);
                                                        else
                                                            $err += ["agency" => "Not found."];


            $User->hash = $_POST['hash'];
            if($err){
                echo json_encode($err);
                exit();
            }
            $s_params = array();
            $params['email'] = "\"".$_POST['email']."\"";
            $stmt = $User->read2($params,$s_params);
            if(($stmt)->rowCount() > 0) {
                echo json_encode(["error" => "Email already used !"]);
                exit();
            }

            echo $stmt = $User->create();

            exit();
            /*$error = "";
foreach ($err as $key => $value) {
    $error .= json_encode($key." => ".$value);
}

//$error = substr($error, 0, strlen($error) - 1);

printf(($error));
*/

        });


        $router->addRoute('PUT', '/v1/users/:id', function($id) {

            $database = new Db();
            $db = $database->getConnection();
            $User = new User($db);

            $data = array();
            $_PUT = array();
            parse_str(file_get_contents("php://input"), $_PUT);

            foreach ($_PUT as $key => $value)
            {
                $data[$key] = $value;
            }

            $params = array();
            $params['userID'] = $id;
            $s_params = array();

            $stmt = $User->read2($params, $s_params);
            $d = ($User->read_put($stmt));

            //$params = array();

            foreach ($d as $key => $value) {
                if($data[$key] != $value)
                    $params[$key] = $data[$key];
            }

            $User->userID = $id;
            echo $User->update($params);





            /*
            $putdata = fopen("php://input", "r");

            $fp = fopen("test.ext", "w");

            while ($data = fread($putdata, 1024))
                echo $data;
                //fwrite($fp, $data);

            fclose($fp);
            fclose($putdata);
            */

            /*$error = "";
            foreach ($err as $key => $value) {
                $error .= json_encode($key." => ".$value);
            }

            //$error = substr($error, 0, strlen($error) - 1);
            printf(($error));*/
            //$User->update($id);
            exit();


        });



        //Run the router
        $router->run($this->_method, $this->_URI);
    }
}