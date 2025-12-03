<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the password from POST data
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    // Check if the password is empty
    if (empty($password)) {
        http_response_code(400); // Bad Request
        $data = array("success" => 0, "message" => "Please enter a password");
        echo json_encode($data);
        exit;
    }

    // Encrypt the password
    $encrypted = password_hash($password, PASSWORD_BCRYPT);

    // Check if encryption was successful
    if ($encrypted) {
        http_response_code(200); // OK
        $data = array('data' => $encrypted);
        echo json_encode($data);
        exit;
    } else {
        // Fallback if encryption fails (rare case)
        http_response_code(500); // Internal Server Error
        $data = array("success" => 0, "message" => "Password encryption failed");
        echo json_encode($data);
        exit;
    }
} else {
    // Handle invalid HTTP methods
    http_response_code(405); // Method Not Allowed
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    exit;
}


?>