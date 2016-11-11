<?php
header('Content-type: application/json');
require_once __DIR__ . '/DataLayer.php';
$action = $_POST['action'];
switch ($action){
    case "REGISTER": registerFunction();
        break;
    case "LOGIN": loginFunction();
        break;
}

function loginFunction(){
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];
    
    $result = attemptLogin($email, $pwd);

    if ($result['status'] == "SUCCESS"){
        echo json_encode(array("message"=>"Bienvenido de nuevo", "status"=>1));
        // Set session here
    } else {
        if ($result['status'] == "NOT FOUND"){
            echo json_encode(array("message"=>"Datos de usuario incorrectos", "status"=>2));
        } else {
            header("HTTP/1.1 " . $result['status']);
            die($result['status']);
        }
    }
}

function registerFunction(){
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $zip = $_POST['zip'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];


    $result = attemptRegistration($name, $last_name, $address, $city, $state, $country, $zip, $phone, $email, $pwd);

    if ($result['status'] == "SUCCESS"){
        echo json_encode(array("message"=>"Usuario registrado exitosamente.", "status" =>1));
    } else {
        if ($result['status'] == "EMAIL IN USE"){
            echo json_encode(array("message"=>"Email already in use. ", "status"=>2));
        } else {
            header("HTTP/1.1 " . $result['status']);
            die($result['status']);
        }
    }
}

?>