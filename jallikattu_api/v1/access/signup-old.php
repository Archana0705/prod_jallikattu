<?php
header("Access-Control-Allow-Origin: *");
require_once('../../helper/header.php');
require_once('../../helper/db/jk_write.php');
require_once('../../helper/db/jk_read.php');

header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
    exit;
}

try {
    $params = json_decode(file_get_contents('php://input'), true);
    $created_date = date('Y-m-d H:i:s');
    $requiredFields = [
        'name' => 'Name is required.',
        'email' => 'Email is required.',
        'password' => 'Password is required.',
        'mobile_no' => 'Mobile number is required.',
        'role' => 'Role is required.'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($params[$field])) {
            http_response_code(400);
            echo json_encode(["success" => 0, "message" => $errorMessage]);
            exit;
        }
    }

    $hashed_password = password_hash($params['password'], PASSWORD_BCRYPT);

    $checkSql = "SELECT COUNT(*) FROM TNEA_JALLIKATTU_REGISTER_LOGIN_T WHERE MOBILE = :p_MOBILE";
    $checkStmt = $jk_read_db->prepare($checkSql);
    $checkStmt->bindParam(':p_MOBILE', $params['mobile_no']);
    $checkStmt->execute();
    $mobileCount = $checkStmt->fetchColumn();

    if ($mobileCount > 0) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Mobile number already exists."]);
        exit;
    }

    $sql = "INSERT INTO TNEA_JALLIKATTU_REGISTER_LOGIN_T(NAME, EMAIL, PASSWORD, MOBILE, ROLE) VALUES(:p_NAME, :p_EMAIL, :p_PASSWORD, :p_MOBILE, :p_ROLE)";
    $bind = $jk_write_db->prepare($sql);

    $bind->bindParam(':p_NAME', $params['name']);
    $bind->bindParam(':p_EMAIL', $params['email']);
    $bind->bindParam(':p_PASSWORD', $hashed_password); 
    $bind->bindParam(':p_MOBILE', $params['mobile_no']);
    $bind->bindParam(':p_ROLE', $params['role']);

    if ($bind->execute()) {
        http_response_code(200);
        echo json_encode(["success" => 1, "message" => "Data successfully added."]);
    } else {
        throw new Exception("Failed to insert data.");
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => 0,"message" => "A database error occurred.",]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => 0,"message" => "An error occurred.",]);
}
?>
