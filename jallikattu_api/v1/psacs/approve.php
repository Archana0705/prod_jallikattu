<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $app_employee_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;   
    $comments = isset($_POST['comments']) ? $_POST['comments'] : null;
    $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : null;
    if (empty($event_id) || empty($app_employee_id)) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }
    $R_status = 'A';
    $sql = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
    SET
        Request_status = :REQUEST_STATUS,ACS_PS_COMMENTS=:P20_ACS_PS_COMMENTS,UPDATED_BY=:APP_EMPLOYEE_ID
    WHERE
        EVENT_ID = :P20_EVENT_ID;";
    $bind = $jk_write_db->prepare($sql);
    $bind->bindParam(':REQUEST_STATUS', $R_status);
    $bind->bindParam(':P20_ACS_PS_COMMENTS', $comments);
    $bind->bindParam(':APP_EMPLOYEE_ID', $app_employee_id);
    $bind->bindParam(':P20_EVENT_ID', $event_id);
    if ($bind->execute()) {
        http_response_code(200);
        $data = [
            "success" => 1,
            "message" => "Data successfully updated."
        ];
    } else {
        http_response_code(500);
        $data = [
            "success" => 0,
            "message" => "Error updating data."
        ];
    }

    echo json_encode($data);
    die();
} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
?>
