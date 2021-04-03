<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

session_start();

if (isset($_SERVER['HTTP_ORIGIN'])) {
	$allowed_origin = array('http://localhost:8080', $_SERVER["HTTP_ORIGIN"]);
	if(in_array($_SERVER["HTTP_ORIGIN"], $allowed_origin)) {
		header('Access-Control-Allow-Origin: ' . $_SERVER["HTTP_ORIGIN"]);
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');
	}
	//header('Access-Control-Allow-Origin: http://localhost:8080');
	//header('Access-Control-Allow-Origin: {$_SERVER["HTTP_ORIGIN"]}');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
		header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
	}
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
		header('Access-Control-Allow-Headers:        {$_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]}');
	}
	exit(0);
}
require '../config.php';
require 'constant.php';
require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

use PondokCoder\Authorization as Authorization;
use PondokCoder\Connection as Connection;
use \Firebase\JWT\JWT;
use \Sentry;
Sentry\init(['dsn' => 'https://9754244694444cccaf869914e1e4f5a3@o412931.ingest.sentry.io/5294475' ]);
try {
	$PDO = Connection::get()->connect();
	$requestTarget = 'PondokCoder\\' . ucfirst(__REQUEST__[0]);
	
	try {
		$refMethod = new ReflectionMethod($requestTarget, '__construct');
		$params = $refMethod->getParameters();
		$re_args = array();
		$refClass = new ReflectionClass($requestTarget);
		$class_instance = $refClass->newInstanceArgs(array($PDO));
		$ParameterBuilder = array();
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$delete_target = explode('/', $actual_link);

		switch ($_SERVER['REQUEST_METHOD']) {
			case 'GET':
				$ParameterBuilder = (empty(__REQUEST__[1])) ? array() : __REQUEST__;
				break;
			case 'POST':
				//print_r($_SERVER['CONTENT_TYPE']);
				if(count($_POST) == 0) {
					$ParameterBuilder = json_decode(file_get_contents('php://input'),true);
				} else {
					$ParameterBuilder = $_POST;
				}

				break;
			case 'PUT':
				$ParameterBuilder = array($_PUT);
				break;
			case 'DELETE':
				//$ParameterBuilder = $delete_target[count($delete_target) - 1];
				$ParameterBuilder = $delete_target;
				break;
			default:
				$ParameterBuilder = array();
				break;
		}
		
		$jwt = JWT::encode($payload, $key);



		
		$exclude_auth = array('login', 'get_module', 'register', 'register_android');
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		    if(
                (
                    $requestTarget == 'PondokCoder\\Pegawai' ||
                    $requestTarget == 'PondokCoder\\Membership' ||
                    $requestTarget == 'PondokCoder\\JKN'
                ) && in_array($ParameterBuilder['request'], $exclude_auth)
            ) {

                $unauthorized = false;
                if(in_array($ParameterBuilder['request'], $exclude_auth)) {
                    header('Content-Type: application/json');
                    if($requestTarget == 'PondokCoder\\Pegawai') {
                        $ClassMethod = call_user_func_array('PondokCoder\\Pegawai::__POST__', array($ParameterBuilder));
                    } else {
                        $ClassMethod = call_user_func_array('PondokCoder\\Membership::__POST__', array($ParameterBuilder));
                    }
                    echo json_encode($ClassMethod);
                } else {
                    header('Content-Type: application/json');
                    if($requestTarget == 'PondokCoder\\JKN') {
                        $ClassMethod = call_user_func_array('PondokCoder\\JKN::__POST__', array($ParameterBuilder));
                        echo json_encode($ClassMethod);
                    } else {
                        http_response_code(403);
                        echo json_encode(array(
                            'data' => $_POST,
                            'message' => 'Unknown Request',
                            'status' => 'Forbidden Access',
                        ));
                    }
                }
            } else {
                $unauthorized = true;
            }
		} else if($_SERVER['REQUEST_METHOD'] === 'GET') {
            if($requestTarget == 'PondokCoder\\Membership') {
                if($ParameterBuilder[1] === 'activate') {
                    $unauthorized = false;
                    $ClassMethod = call_user_func_array('PondokCoder\\Membership::__GET__', array($ParameterBuilder));
                    if($ClassMethod) {
                        //require '../miscellaneous/display_template/activate_success.php';
                        echo 'Berhasil';
                    } else {
                        //require '../miscellaneous/display_template/activate_failed.php';
                        echo 'Akun sudah di aktivasi';
                    }
                } else if($ParameterBuilder[1] === 'decline') {
                    $unauthorized = false;
                    $ClassMethod = call_user_func_array('PondokCoder\\Membership::__GET__', array($ParameterBuilder));
                    echo json_encode($ClassMethod);
                } else {
                    $unauthorized = true;
                }
            } else if($requestTarget == 'PondokCoder\\Inventori') {
                header('Content-Type: application/json');
                $unauthorized = false;
                $ClassMethod = call_user_func_array('PondokCoder\\Inventori::__GET__', array($ParameterBuilder));
                echo json_encode($ClassMethod);
            } else {
                $unauthorized = true;
            }
        } else {
			$unauthorized = true;
		}


		if($unauthorized) {
            header('Content-Type: application/json');
            $Authorization = new Authorization();
            $BearerToken = $Authorization->getBearerToken($_SERVER);

            $key = file_get_contents('taknakal.pub');




            //Permission Manager


            try {
                http_response_code(200);
                JWT::$leeway = 60;
                //Check Token Expiration
                $decoded = JWT::decode($BearerToken, $key, array('HS256'));
                $decoded_array = (array) $decoded;
                if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
                    $ParameterBuilder['access_token'] = $BearerToken;
                } else if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET') {
                    array_push($ParameterBuilder, $BearerToken);
                }
                $ClassMethod = call_user_func_array($requestTarget . '::__' . strtoupper($_SERVER['REQUEST_METHOD']) . '__', array($ParameterBuilder));
                //echo json_encode($ClassMethod);
                if($requestTarget == 'PondokCoder\\JKN') {
                    echo json_encode($ClassMethod);
                } else {
                    echo json_encode(array('token' => $newToken, 'response_package' => $ClassMethod));
                }
            } catch ( \Firebase\JWT\ExpiredException $e ) {
                http_response_code(202);
                JWT::$leeway = 720000;
                $decoded = JWT::decode($BearerToken, $key, array('HS256'));
                $decoded_array = (array) $decoded;

                // TODO: test if token is blacklisted
                $decoded_array['iat'] = time();
                $decoded_array['exp'] = time() + 60;
                $newToken = JWT::encode($decoded_array, $key);
                if(strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
                    $ParameterBuilder['access_token'] = $newToken;
                } else if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET') {
                    array_push($ParameterBuilder, $newToken);
                }
                $ClassMethod = call_user_func_array($requestTarget . '::__' . strtoupper($_SERVER['REQUEST_METHOD']) . '__', array($ParameterBuilder));
                if($requestTarget == 'PondokCoder\\JKN') {
                    echo json_encode($ClassMethod);
                } else {
                    echo json_encode(array('token' => $newToken, 'response_package' => $ClassMethod));
                }
            } catch(\Exception $e){
                http_response_code(403);
                echo json_encode(array(
                    'message' => $e->getMessage(),
                    'status' => 'Forbidden Access',
                    'request' => __REQUEST__,
                    'request_target' => $requestTarget,
                    'param' => $ParameterBuilder,
                    'method' => $_SERVER['REQUEST_METHOD'],
                    'post' => $_POST
                ));

            }
        }



	} catch (Exception $reflectionClass) {
		echo $reflectionClass->getMessage();
	}
		


		
} catch (\PDOException $e) {
	echo $e->getMessage();
}
?>