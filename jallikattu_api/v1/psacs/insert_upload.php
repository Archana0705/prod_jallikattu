<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);

    $app_employee_id = isset($params['user_id']) ? $params['user_id'] : null;   
    $event_id = isset($params['event_id']) ? $params['event_id'] : null;

    if (empty($event_id) || empty($app_employee_id)) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }

    $R_status = 'GO1';
    $fwdTo = 'Magesterial huzur sharishthadhar MHS';

    $sql = "INSERT INTO TNEA_JALLIKATTU_EVENT_GO_T (
                GO_ID, EVENT_ID, EVENT_DATE, MOBILE_OF_PRESIDENT, 
                NAME_OF_DISTRICT, GO, GO_ATTACH_MIMETYPE, GO_ATTACH_FILENAME, 
                CREATED_BY, EVENT_TYPE, PLACE_EVENT, GO_NUMBER
            ) VALUES (
                :p_GO_ID, :p_EVENT_ID, :p_EVENT_DATE, :p_MOBILE_OF_PRESIDENT, 
                :p_NAME_OF_DISTRICT, :p_GO, :p_GO_ATTACH_MIMETYPE, :p_GO_ATTACH_FILENAME, 
                :p_CREATED_BY, :p_EVENT_TYPE, :p_PLACE_EVENT, :p_GO_NUMBER
            );";

    $bind = $jk_write_db->prepare($sql);
    $bind->bindParam(':p_GO_ID', $params['GO_ID'], PDO::PARAM_INT);
    $bind->bindParam(':p_EVENT_ID', $params['event_id']);
    $bind->bindParam(':p_EVENT_DATE', $params['EVENT_DATE']);
    $bind->bindParam(':p_MOBILE_OF_PRESIDENT', $params['MOBILE_NO']);
    $bind->bindParam(':p_NAME_OF_DISTRICT', $params['DISTRICT']);
    $bind->bindParam(':p_GO', $params['P_GO']);
    $bind->bindParam(':p_GO_ATTACH_MIMETYPE', $params['MIMETYPE']);
    $bind->bindParam(':p_GO_ATTACH_FILENAME', $params['FILENAME']);
    $bind->bindParam(':p_CREATED_BY', $params['user_id']);
    $bind->bindParam(':p_EVENT_TYPE', $params['EVENT_TYPE']);
    $bind->bindParam(':p_PLACE_EVENT', $params['EVENT_PLACE']);
    $bind->bindParam(':p_GO_NUMBER', $params['GO_NUMBER']);

    if ($bind->execute()) {
        $update = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
                   SET REQUEST_STATUS = :Request_status,
                       FORWARD_TO = :forward,
                       UPDATED_BY = :APP_EMPLOYEE_ID
                   WHERE NAME_OF_DISTRICT = :P44_NAME_OF_DISTRICT 
                         AND EVENT_ID = :P44_EVENT_ID;";
        
        $stmt = $jk_write_db->prepare($update);
        $stmt->bindParam(':Request_status', $R_status);
        $stmt->bindParam(':forward', $fwdTo);
        $stmt->bindParam(':APP_EMPLOYEE_ID', $params['user_id']);
        $stmt->bindParam(':P44_NAME_OF_DISTRICT', $params['DISTRICT']);
        $stmt->bindParam(':P44_EVENT_ID', $params['event_id']);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["success" => 1, "message" => "Data successfully updated."]);
        } else {
            http_response_code(200);
            echo json_encode(["success" => 0, "message" => "Data not updated."]);
        }
        die();
    } else {
        http_response_code(500);
        echo json_encode(["success" => 0, "message" => "Error updating data."]);
        die();
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
    die();
}
?>
