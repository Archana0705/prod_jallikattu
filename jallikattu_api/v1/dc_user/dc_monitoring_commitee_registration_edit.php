<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

function sendResponse($statusCode, $success, $message) {
    http_response_code($statusCode);
    echo json_encode(["success" => $success, "message" => $message]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);

    $app_employee_id = isset($params['user_id']) ? $params['user_id'] : null;   
    $request_type = isset($params['REQUEST_CASE']) ? $params['REQUEST_CASE'] : null;
    $commitee_id = isset($params['COMMITEE_ID']) ? $params['COMMITEE_ID'] : null;

    if (empty($request_type)) {
        sendResponse(400, 0, "Missing 'REQUEST_CASE' field");
    }
    if (empty($commitee_id)) {
        sendResponse(400, 0, "Missing 'COMMITEE_ID' field");
    }

    if ($request_type === 'REMOVE') {
        $sql = "DELETE FROM TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T WHERE COMMITEE_ID = :P_COMMITEE_ID;";
        $bind = $jk_write_db->prepare($sql);
        $bind->bindParam(':P_COMMITEE_ID', $params['COMMITEE_ID']);

        if ($bind->execute()) {
            sendResponse(200, 1, "User successfully removed.");
        } else {
            sendResponse(500, 0, "Error while removing user.");
        }
    } elseif ($request_type === 'CHANGE') {
        $requiredFields = ['COMMITEE_DEPARTMENT', 'COMMITEE_NAME', 'COMMITEE_DESIGNATION', 'COMMITEE_MAIL', 'COMMITEE_MOBILE', 'COMMITEE_ID'];
        foreach ($requiredFields as $field) {
            if (empty($params[$field])) {
                sendResponse(400, 0, "Missing required field: $field");
            }
        }

        $sql = "UPDATE TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T 
                SET INSPECTION_DATE = :p_INSPECTION_DATE, 
                    COMMITEE_DEPARTMENT = :p_COMMITEE_DEPARTMENT,
                    COMMITEE_NAME = :p_COMMITEE_NAME,
                    COMMITEE_DESIGNATION = :p_COMMITEE_DESIGNATION,
                    COMMITEE_MAIL = :p_COMMITEE_MAIL,
                    COMMITEE_MOBILE = :p_COMMITEE_MOBIL
                WHERE COMMITEE_ID = :P_COMMITEE_ID;";
        $bind = $jk_write_db->prepare($sql);
        $bind->bindParam(':p_INSPECTION_DATE', $inspection_date);
        $bind->bindParam(':p_COMMITEE_DEPARTMENT', $params['COMMITEE_DEPARTMENT']);
        $bind->bindParam(':p_COMMITEE_NAME', $params['COMMITEE_NAME']);
        $bind->bindParam(':p_COMMITEE_DESIGNATION', $params['COMMITEE_DESIGNATION']);
        $bind->bindParam(':p_COMMITEE_MAIL', $params['COMMITEE_MAIL']);
        $bind->bindParam(':p_COMMITEE_MOBIL', $params['COMMITEE_MOBILE']);
        $bind->bindParam(':P_COMMITEE_ID', $params['COMMITEE_ID']);

        if ($bind->execute()) {
            sendResponse(200, 1, "User successfully updated.");
        } else {
            sendResponse(500, 0, "Error while updating user.");
        }
    } else {
        sendResponse(400, 0, "Invalid 'REQUEST_CASE' value. Allowed values are 'REMOVE' or 'CHANGE'.");
    }
} else {
    sendResponse(405, 0, "Method Not Allowed");
}
?>
