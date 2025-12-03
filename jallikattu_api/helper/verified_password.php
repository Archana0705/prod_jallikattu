<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $hash_password = isset($_POST['hash']) ? $_POST['hash'] : null;
    if(empty($password) || empty($hash_password)){
        http_response_code(401);
        $data = array("success" => 0, "message" => "pls enter password and hashpassword");
        echo json_encode($data);
        exit;
    }

    if(password_verify($password, $hash_password)){
        http_response_code(200);
        $data = array("success" => 1, "message" => "password is valid");
        echo json_encode($data);
        exit;
    }else{
        http_response_code(200);
        $data = array("success" => 0, "message" => "password is invalid");
        echo json_encode($data);
        exit;
    }
}else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}

?>