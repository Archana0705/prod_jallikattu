<?php
header("Access-Control-Allow-Origin: *");
require_once('../../helper/header.php');
require_once('../../helper/db/jk_write.php');
require_once('../../helper/db/jk_read.php');
require_once('../../helper/send_otp.php');
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
    exit;
}

try {
    $decryptedData = isset($_POST['data']) ? decryptData($_POST['data']) : null;
    $created_date = date('Y-m-d H:i:s');

    $requiredFields = [
        'name' => 'Name is required.',
        'email' => 'Email is required.',
        'password' => 'Password is required.',
        'mobile_no' => 'Mobile number is required.',
        'role' => 'Role is required.',
        'otp' => 'OTP is required.'
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($decryptedData[$field])) {
            http_response_code(400);
            echo json_encode(["success" => 0, "message" => $errorMessage]);
            exit;
        }
    }

    $otpCheckSql = "SELECT otp FROM tnea_Jallikattu_Otp_tb WHERE mobile = :mobile";
    $otpStmt = $jk_read_db->prepare($otpCheckSql);
    $otpStmt->bindParam(':mobile', $decryptedData['mobile_no'], PDO::PARAM_INT);
    $otpStmt->execute();
    $otpOutput = $otpStmt->fetch(PDO::FETCH_ASSOC);
    // echo $decryptedData['otp'];
    // echo $otpOutput['otp'];
    if ($decryptedData['otp'] != $otpOutput['otp']) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Invalid OTP."]);
        exit;
    }

    $checkSql = "SELECT COUNT(*) FROM u
    $checkStmt->bindParam(':mobile', $decryptedData['mobile_no'], PDO::PARAM_INT);
    $checkStmt->execute();
    $mobileCount = $checkStmt->fetchColumn();

    if ($mobileCount > 0) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Mobile number already exists."]);
        exit;
    }

    $hashed_password = password_hash($decryptedData['password'], PASSWORD_BCRYPT);

    $insertSql ="INSERT INTO TNEA_JALLIKATTU_APPROVER_LOGIN_T (NAME, EMAIL_ADDRESS, PASSWORD, MOBILE_NUMBER, ROLE) 
                  VALUES (:name, :email, :password, :mobile, :role)";
    $insertStmt = $jk_write_db->prepare($insertSql);
    $insertStmt->bindParam(':name', $decryptedData['name'], PDO::PARAM_STR);
    $insertStmt->bindParam(':email', $decryptedData['email'], PDO::PARAM_STR);
    $insertStmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $insertStmt->bindParam(':mobile', $decryptedData['mobile_no'], PDO::PARAM_INT);
    $insertStmt->bindParam(':role', $decryptedData['role'], PDO::PARAM_STR);

    if ($insertStmt->execute()) {
        http_response_code(200);
        echo json_encode(["success" => 1, "message" => "User registered successfully."]);
    } else {
        throw new Exception("Failed to register the user.");
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "success" => 0,
        "message" => "A database error occurred."
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => 0,
        "message" => "An error occurred."
    ]);
}
?>
