<?php

function connectionToDatabase()
{
    $servername = "db650115939.db.1and1.com";
    $username = "dbo650115939";
    $password = "wadaug16";
    $dbname = "db650115939";

    $conn = new mysqli($servername, $username, $password, $dbname);
    // Set charset to utf8
    mysqli_set_charset($conn, "utf8");

    if ($conn->connect_error) {
        return null;
    } else {
        return $conn;
    }
}

function attemptAddToCart($user, $product, $qty)
{
    $conn = connectionToDatabase();

    if ($conn != null) {
        $sql = "SELECT * FROM ProductsInCart WHERE Id_Customer = $user AND Id_Product = $product";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $conn->close();
            return array("status" => "Product already in the cart");
        } else {
            $sql = "INSERT INTO ProductsInCart (Id_Customer, Id_Product, Quantity) VALUES ($user, $product, $qty)";
            if (mysqli_query($conn, $sql)) {
                $conn->close();
                return array("status" => "SUCCESS");
            } else {
                $conn->close();
                return array("status" => "COULD NOT ADD PRODUCT TO CART");
            }
        }
    } else {
        $conn->close();
        return array("status" => "COULD NOT CONNECT TO DATABASE");
    }
}

function sendEmail()
{
    $headers = "From: hola@lagwy.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    $subject = "Cotización solicitada.";

    // Data of the user
    $name = "Something";
    $mobile = "Some number";
    $email = "Some email";
    $message = "Some text";

    // Message body
    $body = "This is the body of the email.\n\n";
    $body .= "The name is: " . $name . "\n";
    $body .= "The mobile is: " . $mobile . "\n";
    $body .= "The email is: " . $email . "\n";
    $body .= "The message is: " . $message . "\n";
    $body .= "This is the end of the message";

    $to = "lamg@itesm.mx";

    $result = mail($to, $subject, $body, $headers);
    return array("status" => $result);
}

function attemptGetDescription($id)
{
    $conn = connectionToDatabase();

    if ($conn != null) {
        $sql = "SELECT * FROM Products WHERE Id_Product = " . $id;
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $response = array("status" => "SUCCESS", "IdProduct" => $row['Id_Product'], "ProductName" => $row['Name'], "ProductDescription" => $row['Description'], "Category" => $row['Category'], "Subcategory" => $row['Subcategory']);
            $conn->close();
            return $response;
        } else {
            $response = array("status" => "FAILED");
            $conn->close();
            return $response;
        }
    } else {
        $conn->close();
        return array("status" => "COULD NOT CONNECT TO DATABASE");
    }
}

function attemptRecommendedProducts()
{
    $conn = connectionToDatabase();

    if ($conn != null) {
        $sql = "SELECT * FROM `Products` GROUP BY Category";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $full_response = array();
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $response = array("IdProduct" => $row['Id_Product'], "ProductName" => $row['Name'], "ProductDescription" => $row['Description'], "Category" => $row['Category'], "Subcategory" => $row['Subcategory']);
                array_push($full_response, $response);
            }
            $conn->close();
            return $full_response;
        } else {
            $conn->close();
            return array("status" => "FAILED");
        }
    } else {
        $conn->close();
        return array("status" => "COULD NOT CONNECT TO DATABASE");
    }
}

function attemptLogin($email, $pwd)
{
    $conn = connectionToDatabase();

    if ($conn != null) {
        $sql = "SELECT * FROM Customers WHERE Email = '$email' AND Passwd = md5('$pwd')";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            session_start();
            $_SESSION['IdUser'] = $row['Id_Customer'];
            $conn->close();
            return array("status" => "SUCCESS");
        } else {
            // There is no user with this information
            $conn->close();
            return array("status" => "NOT FOUND");
        }
    } else {
        $conn->close();
        return array("status" => "COULD NOT CONNECT TO DATABASE");
    }
}

function attemptRegistration($name, $last_name, $address, $city, $state, $country, $zip, $phone, $email, $pwd)
{
    $conn = connectionToDatabase();

    if ($conn != null) {

        $sql = "SELECT * FROM Customers WHERE Email = '$email'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $conn->close();
            return array("status" => "EMAIL IN USE");
        } else {
            // Register user in database
            $sql = "INSERT INTO Customers (First_Name, Last_Name, Address, City, State, Country, ZIP_Code, Phone_Number, Email, Passwd) " .
                "VALUES ('$name', '$last_name', '$address', '$city', '$state', '$country', '$zip', '$phone', '$email', md5('$pwd'))";

            if (mysqli_query($conn, $sql)) {
                $conn->close();
                return array("status" => "SUCCESS");
            } else {
                $conn->close();
                return array("status" => "COULD NOT REGISTER USER");
            }
        }

    } else {
        $conn->close();
        return array("status" => "COULD NOT CONNECT TO DATABASE");
    }
}

function attemptGetProduct()
{
    //Create connection to database
    $conn = connectionToDatabase();

    $sql = "SELECT * FROM Products";
    $result = $conn->query($sql);
    if ($conn != null) {
        if ($result->num_rows > 0) {
            $full_response = array();
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $response = array("IdProduct" => $row['Id_Product'], "ProductName" => $row['Name'], "ProductDescription" => $row['Description'], "Category" => $row['Category'], "Subcategory" => $row['Subcategory']);
                array_push($full_response, $response);
            }
            $conn->close();
            return $full_response;
        } else {
            $conn->close();
            return array("status" => "FAILED");
        }
    } else {
        $conn->close();
        return array("status" => "COULD NOT CONNECT TO DATABASE");
    }
}

?>