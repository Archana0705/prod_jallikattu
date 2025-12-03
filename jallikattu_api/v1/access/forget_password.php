<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1); 
require_once('../../helper/header.php');
require_once('../../helper/db/jk_write.php');
header("Access-Control-Allow-Methods: POST");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $decryptedData = isset($_POST['data']) ? decryptData($_POST['data']) : null;
    $mobile_no = isset($decryptedData['mobile_no']) ? trim($decryptedData['mobile_no']) : null;
    $email = isset($decryptedData['email']) ? trim($decryptedData['email']) : null;
    $new_password = isset($decryptedData['password']) ? trim($decryptedData['password']) : null;

    $errors = [];
    $requiredFields = [
        'mobile_no' => 'Mobile No',
        'email' => 'Email id',
        'new_password' => 'New Password',
    ];
    foreach ($requiredFields as $fieldKey => $fieldLabel) {
        if (empty($$fieldKey)) { 
            $errors[] = $fieldLabel;
        }
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            "success" => 0,
            "message" => "Missing fields: " . implode(', ', $errors)
        ]);
        die();
    }

    if (!$jk_write_db) {
        http_response_code(500);
        echo json_encode(["success" => 0, "message" => "Database connection failed."]);
        exit;
    }
    $hashed_password_new = password_hash($new_password, PASSWORD_BCRYPT);
    $changeSql = "SELECT * FROM forget_password(:p_mobile_no, :p_email, :p_new_password)";
    $changePassword = $jk_write_db->prepare($changeSql);
    $changePassword->bindParam(':p_mobile_no', $mobile_no, PDO::PARAM_STR);
    $changePassword->bindParam(':p_email', $email, PDO::PARAM_STR);
    $changePassword->bindParam(':p_new_password', $hashed_password_new, PDO::PARAM_STR);

    if ($changePassword->execute()) {
        $result = $changePassword->fetch(PDO::FETCH_ASSOC);

        $responseMessage = isset($result['forget_password']) && stripos($result['forget_password'], 'successfully') !== false
            ? "Password Changed successfully"
            : "Password not Changed";

        echo json_encode([
            "success" => $responseMessage === "Password Changed successfully" ? 1 : 0,
            "message" => $responseMessage,
                   ]);
    } else {
        $errorInfo = $changePassword->errorInfo();
        http_response_code(500);
        echo json_encode([
            "success" => 0,
            "message" => "Query execution failed",
            "error" => $errorInfo[2],
            "debug" => [
                "query_executed" => $changeSql,
                "inputs" => [
                    "mobile_no" => $mobile_no,
                    "email" => $email,
                    "new_password" => $new_password
                ]
            ]
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
$jk_write_db = null;
exit;
?>
