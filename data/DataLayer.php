<?php

function connectionToDatabase(){
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

function attemptLogin($email, $pwd){
    $conn = connectionToDatabase();

    if ($conn != null){
        $sql = "SELECT * FROM Customers WHERE Email = '$email' AND Passwd = md5('$pwd')";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $conn->close();
            return array("status" => "SUCCESS");
        } else {
            // There is no user with this information
            $conn->close();
            return array("status" => "NOT FOUND");
        }
    }else {
        $conn->close();
        return array("status" => "COULD NOT CONNECT TO DATABASE");
    }
}

function attemptRegistration($name, $last_name, $address, $city, $state, $country, $zip, $phone, $email, $pwd){
    $conn = connectionToDatabase();
    
    if ($conn != null){

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

function attemptGetProduct() {
    //Create connection to database
    $conn = connectionToDatabase();

    $sql = "SELECT Name, Description FROM Products";
    $result = $conn->query($sql);
    if ($conn != null){
        if ($result->num_rows > 0) {
            $full_response = array("status" => "SUCCESS");
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $response = array("ProductName" => $row['Name'], "ProductDescription" => $row['Description']);
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