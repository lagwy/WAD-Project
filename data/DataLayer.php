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

?>