<?php
header('Content-type: application/json');
require_once __DIR__ . '/DataLayer.php';
$action = $_POST['action'];
switch ($action) {
    case "REGISTER":
        registerFunction();
        break;
    case "LOGIN":
        loginFunction();
        break;
    case "SESSION":
        checkSession();
        break;
    case "PRODUCTS":
        getProducts();
        break;
    case "RECOMMENDED":
        getRecommendedProducts();
        break;
    case "DESCRIPTION":
        getDescription();
        break;
    case "ADD_PRODUCT":
        addProductToCart();
        break;
    default:
        logout();
}

function addProductToCart()
{
    session_start();
    $product = $_POST['IdProduct'];
    $qty = $_POST['Quantity'];
    // Send the user id, product id and quantity
    $result = attemptAddToCart($_SESSION['IdUser'], $product, $qty);
    if ($result['status'] == "SUCCESS") {
        echo json_encode($result);
    } else {
        if ($result['status'] == "COULD NOT ADD PRODUCT TO CART") {
            echo json_encode(array("message" => "El producto ya está en el carrito."));
        } else {
            echo json_encode(array("message" => $result['status']));
        }
    }
}

function getDescription()
{
    $IdProduct = $_POST['IdProduct'];
    $result = attemptGetDescription($IdProduct);
    echo json_encode($result);
}

function getRecommendedProducts()
{
    $result = attemptRecommendedProducts();
    echo json_encode($result);
}

function logout()
{
    session_start();
    unset($_SESSION['email']);
    unset($_SESSION['pswd']);
    session_destroy();
    // Redirect to main page
    header('Location: ../home.html');
    die();
}

function getProducts()
{
    $result = attemptGetProduct();
    echo json_encode($result);
}

function checkSession()
{
    session_start();
    if (isset($_SESSION['email'])) {
        echo json_encode(array("status" => 1));
    } else {
        echo json_encode(array("status" => 2));
    }
}

function loginFunction()
{
    $email = $_POST['email'];
    $pwd = $_POST['pwd'];

    $result = attemptLogin($email, $pwd);

    if ($result['status'] == "SUCCESS") {
        echo json_encode(array("message" => "Bienvenido de nuevo", "status" => 1));
        // Set session here
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['pswd'] = $pwd;
    } else {
        if ($result['status'] == "NOT FOUND") {
            echo json_encode(array("message" => "Datos de usuario incorrectos", "status" => 2));
        } else {
            header("HTTP/1.1 " . $result['status']);
            die($result['status']);
        }
    }
}

function registerFunction()
{
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

    if ($result['status'] == "SUCCESS") {
        echo json_encode(array("message" => "Usuario registrado exitosamente.", "status" => 1));
    } else {
        if ($result['status'] == "EMAIL IN USE") {
            echo json_encode(array("message" => "Email already in use. ", "status" => 2));
        } else {
            header("HTTP/1.1 " . $result['status']);
            die($result['status']);
        }
    }
}

?>