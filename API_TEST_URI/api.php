<?php

require_once('router.php');
require_once('Client.php');
require_once('Intervention.php');
require_once('Planning.php');
require_once('Service.php');
require_once('db.php');
require_once('jwt.php');
require_once('Subscription.php');
require_once('ServiceProvider.php');
require_once('Tariff.php');

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
            function read($csv){
                $file = fopen($csv, 'r');
                while (!feof($file) ) {
                    $line[] = fgetcsv($file, 1024);
                }
                fclose($file);
                return $line;
            }
            $csv = 'agency.csv';
            $csv = read($csv);
            echo json_encode($csv);
        });

        $router->addRoute('GET', '/v1/agency/:id/', function($id) {
            $database = new Db();
            $db = $database->getConnection();
            $reqType = $db->prepare("SELECT agency FROM client WHERE clientID = $id");
            $reqType->execute();
            echo json_encode($reqType->fetchAll()[0]);
        });

        $router->addRoute('GET', '/v1/planning', function() {
            $database = new Db();
            $db = $database->getConnection();
            $Planning = new Planning($db);
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

        $router->addRoute('GET', '/v1/service', function() {
            $database = new Db();
            $db = $database->getConnection();
            $Service = new Service($db);
            $params = array();
            $s_params = array();

            $stmt = $Service->read2($params,$s_params);

            if(($stmt)->rowCount() <= 0) {
                echo json_encode(["error" => "Not Found !"]);
                exit();
            }

            echo $Service->read_info($stmt);
            exit();

        });
        $router->addRoute('GET', '/v1/service/:id', function($id) {
            $database = new Db();
            $db = $database->getConnection();
            $Service = new Service($db);
            $s_params = array();
            $params = array("serviceID"=>$id);

            $stmt = $Service->read2($params,$s_params);

            if(($stmt)->rowCount() <= 0) {
                echo json_encode(["error" => "Not Found !"]);
                exit();
            }

            echo $Service->read_info($stmt);
            exit();

        });

        $router->addRoute('GET', '/v1/tariff', function() {
            $database = new Db();
            $db = $database->getConnection();
            $tariff = new Tariff($db);
            $params = array();
            $s_params = array();

            $stmt = $tariff->read2($params,$s_params);
            echo $tariff->read_info($stmt);
            exit();

        });
        $router->addRoute('GET', '/v1/tariff/:serviceID', function($serviceID) {
            $database = new Db();
            $db = $database->getConnection();
            $tariff = new Tariff($db);
            $params = array("tariff.serviceID"=>"service.serviceID = $serviceID");
            $s_params = array();

            $stmt = $tariff->read2($params,$s_params);
            echo $tariff->read_info($stmt);
            exit();
        });
        $router->addRoute('GET', '/v1/tariff/:serviceID/:id', function($serviceID,$id) {
            $database = new Db();
            $db = $database->getConnection();
            $tariff = new Tariff($db);
            $params = array("tariff.serviceID"=>"service.serviceID = $serviceID","tariffID"=>$id);
            $s_params = array();

            $stmt = $tariff->read2($params,$s_params);
            echo $tariff->read_info($stmt);
            exit();
        });


        $router->addRoute('GET', '/v1/client', function() {
            $database = new Db();
            $db = $database->getConnection();
            $User = new Client($db);
            $params = array();
            $s_params = array();
            if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {

                $params['email'] = $_SERVER['PHP_AUTH_USER'];
                $stmt = $User->read2($params, $s_params);

                if (($stmt)->rowCount() <= 0) {
                    echo json_encode(["error" => "Email Not found."]);
                    exit();
                }

                $params['password'] = $_SERVER['PHP_AUTH_PW'];
                $stmt = $User->read2($params, $s_params);

                if (($stmt)->rowCount() <= 0) {
                    echo json_encode(["error" => "Wrong password !"]);
                    exit();
                }

                echo $User->read_info($stmt);
                exit();
            } else {
                $stmt = $User->read2($params, $s_params);
                echo $User->read_info($stmt);
                exit();
            }
        });

            $router->addRoute('GET', '/v1/client/search/:data', function($data) {
                $database = new Db();
                $db = $database->getConnection();
                $User = new Client($db);
                $stmt = $User->search($data);
                echo $User->read_info($stmt);
                exit();

        });

        $router->addRoute('GET', '/v1/prestataire', function() {
            $database = new Db();
            $db = $database->getConnection();
            $User = new ServiceProvider($db);
            $params = array();
            $s_params = array();
            if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {

                $params['email'] = $_SERVER['PHP_AUTH_USER'];
                $stmt = $User->read2($params, $s_params);

                if (($stmt)->rowCount() <= 0) {
                    echo json_encode(["error" => "Email Not found."]);
                    exit();
                }

                $params['password'] = $_SERVER['PHP_AUTH_PW'];
                $stmt = $User->read2($params, $s_params);

                if (($stmt)->rowCount() <= 0) {
                    echo json_encode(["error" => "Wrong password !"]);
                    exit();
                }

                echo $User->read_info($stmt);
                exit();
            } else {
                $stmt = $User->read2($params, $s_params);
                echo $User->read_info($stmt);
                exit();
            }
        });

        $router->addRoute('GET', '/v1/prestataire/:agency', function($agency) {
            $database = new Db();
            $db = $database->getConnection();
            $User = new ServiceProvider($db);
            $params = array("agency"=>$agency);
            $s_params = array();
            if($agency){
                $stmt = $User->read2($params, $s_params);
                echo $User->read_info($stmt);
                exit();
            }
        });




            $router->addRoute('GET', '/v1/subscriptions', function () {
                $database = new Db();
                $db = $database->getConnection();
                $Subscription = new Subscription($db);
                $params = array();
                $stmt = $Subscription->read2($params, $params);
                echo $Subscription->read_info($stmt);
                echo $Subscription->last_id();
            });

            $router->addRoute('GET', '/v1/client/:request', function ($request) {
                $database = new Db();
                $db = $database->getConnection();
                $User = new Client($db);
                $params = array();
                $s_params = array();
                if (in_array($request, ["clientID", "lastName", "firstName", "email", "password", "city", "address", "phoneNumber", "hash"])) {
                    $s_params[$request] = $request;

                    $stmt = $User->read2($params, $s_params);
                    echo $User->read_info($stmt);
                    exit();
                }else{
                    $params["agency"] = $request;
                    $stmt = $User->read2($params, $s_params);
                    echo $User->read_info($stmt);
                    exit();
                }
            });

            $router->addRoute('GET', '/v1/client/:id', function ($id) {
                if (preg_match('/^\d+$/', $id)) {
                    $database = new Db();
                    $db = $database->getConnection();
                    $User = new Client($db);

                    $params = array();
                    $params['clientID'] = $id;
                    $s_params = array();

                    $stmt = $User->read2($params, $s_params);
                    echo $User->read_info($stmt);
                }
            });

            $router->addRoute('GET', '/v1/client/:id/:request', function ($id, $request) {
                if (in_array($request, ["clientID", "lastName", "firstName", "email", "password", "city", "address", "phoneNumber", "hash"]) && preg_match('/^\d+$/', $id)) {
                    $database = new Db();
                    $db = $database->getConnection();
                    $User = new Client($db);

                    $params = array();
                    $params['clientID'] = $id;
                    $s_params = array();
                    $s_params[$request] = $request;

                    $stmt = $User->read2($params, $s_params);
                    echo $User->read_info($stmt);
                    exit();
                }
            });

            $router->addRoute('DELETE', '/v1/client/:id', function ($id) {
                if (preg_match('/^\d+$/', $id)) {

                    $database = new Db();
                    $db = $database->getConnection();
                    $User = new Client($db);
                    $val = $User->delete($id);

                    if ($val)
                        echo "ok";
                    else
                        echo "non ok";
                    exit();
                }
            });

            $router->addRoute('POST', '/v1/client', function () {
                $database = new Db();
                $db = $database->getConnection();
                $User = new Client($db);
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
                if (isset($_POST['agency']) && !empty($_POST['agency']))
                    $User->agency = htmlspecialchars($_POST['agency']);
                else
                    $err += ["agency" => "Not found."];


                $User->hash = $_POST['hash'];
                if ($err) {
                    echo json_encode($err);
                    exit();
                }

                $s_params = array();
                $params['email'] = "\"" . $_POST['email'] . "\"";
                $stmt = $User->read2($params, $s_params);
                if (($stmt)->rowCount() > 0) {
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



            $router->addRoute('PUT', '/v1/client', function () {

                $database = new Db();
                $db = $database->getConnection();
                $User = new Client($db);

                if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {
                    $s_params = array();
                    $params['email'] = $_SERVER['PHP_AUTH_USER'];
                    $params['password'] = $_SERVER['PHP_AUTH_PW'];

                    $stmt = $User->read2($params, $s_params);

                    if (($stmt)->rowCount() <= 0) {
                        echo json_encode(["error" => "Connection error !"]);
                        exit();
                    }

                    $data = array();
                    $_PUT = array();
                    $params = array();

                    parse_str(file_get_contents("php://input"), $_PUT);

                    foreach ($_PUT as $key => $value) {
                        $data[$key] = $value;
                    }

                    $s_params = array();

                    $stmt = $User->read2($params, $s_params);
                    $d = ($User->read_put($stmt));

                    $params = array();

                    foreach ($d as $key => $value) {
                        if ($data[$key] != $value)
                            $params[$key] = $data[$key];
                        if ($key == "agency")
                            $User->agency = $value;
                        if ($key == "clientID")
                            $User->clientID = $value;
                    }

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

                }
            });

            $router->addRoute('PUT', '/v1/prestataire', function () {

                $database = new Db();
                $db = $database->getConnection();
                $User = new ServiceProvider($db);

                if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {
                    $s_params = array();
                    $params['email'] = $_SERVER['PHP_AUTH_USER'];
                    $params['password'] = $_SERVER['PHP_AUTH_PW'];

                    $stmt = $User->read2($params, $s_params);

                    if (($stmt)->rowCount() <= 0) {
                        echo json_encode(["error" => "Connection error !"]);
                        exit();
                    }

                    $data = array();
                    $_PUT = array();
                    $params = array();

                    parse_str(file_get_contents("php://input"), $_PUT);

                    foreach ($_PUT as $key => $value) {
                        $data[$key] = $value;
                    }

                    $s_params = array();

                    $stmt = $User->read2($params, $s_params);
                    $d = ($User->read_put($stmt));

                    $params = array();

                    foreach ($d as $key => $value) {
                        if ($data[$key] != $value)
                            $params[$key] = $data[$key];
                        if ($key == "agency")
                            $User->agency = $value;
                        if ($key == "providerID")
                            $User->providerID = $value;
                    }

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

                }
            });


            //Run the router
            $router->run($this->_method, $this->_URI);
          }
}