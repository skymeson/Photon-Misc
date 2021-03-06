<?php

include "connect.php";
include "secure.php";

$username = ($_GET['usernamePost']);
$password = ($_GET['passwordPost']);

$salt = $secure->get_salt();

if ($stmt = $conn->prepare("SELECT password,pepper FROM accounts WHERE name=:username")) {
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $hash = $result[0][0];
    $pepper = $result[0][1];

    $combinedPassword = $salt.$password.$pepper;

    if (password_verify($combinedPassword, $hash)) {
        $login_info_success = array(
            "ResultCode" => 1,
            "Message" => "Success!",
        );
        $json_success = json_encode($login_info_success);
        echo $json_success;
    } else {
        $login_info_error = array(
            "ResultCode" => 2,
            "Message" => "Wrong username or password",
        );
        $json_error = json_encode($login_info_error);
        echo $json_error;
    }
}