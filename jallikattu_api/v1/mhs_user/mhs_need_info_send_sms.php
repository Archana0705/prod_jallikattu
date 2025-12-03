<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/mhs_need_more_info_send_sms.php');

try {
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        http_response_code(405);
        echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
        exit;
    }

    $app_employee_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $mobile_number = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : null;

    if ($app_employee_id === null || $mobile_number === null) {
        http_response_code(400); 
        echo json_encode(["success" => 0, "message" => "User ID or mobile number is missing"]);
        exit;
    }

    if (!sendSMS($mobile_number)) {
        throw new Exception("Failed to send SMS to mobile number: " . $mobile_number);
    }

    http_response_code(200);
    echo json_encode(["success" => 1, "message" => "SMS sent successfully to mobile number: " . $mobile_number]);

} catch (Exception $e) {
    http_response_code(500); 
    error_log("Error: " . $e->getMessage());
    echo json_encode(["success" => 0, "message" => "A database error occurred."]);
}
