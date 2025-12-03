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
    $R_status = 'F3';
    $fwdTo = 'Assistant Director ( J Section)';
    $sql = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
    SET
        Request_status = :REQUEST_STATUS, forward_to= :forward, JSECTION_REMARKS=:P19_JSECTION_REMARKS,updated_by=:app_employee_id
    WHERE
        EVENT_ID = :P19_EVENT_ID;";
    $bind = $jk_write_db->prepare($sql);
    $bind->bindParam(':REQUEST_STATUS', $R_status);
    $bind->bindParam(':forward', $fwdTo);
    $bind->bindParam(':P19_JSECTION_REMARKS', $comments);
    $bind->bindParam(':app_employee_id', $app_employee_id);
    $bind->bindParam(':P19_EVENT_ID', $event_id);
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
