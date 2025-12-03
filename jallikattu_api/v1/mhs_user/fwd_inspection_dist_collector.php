<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $comments = isset($_POST['comments']) ? $_POST['comments'] : null;
    // $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : null;
    $event_id = isset($_POST['event_id']) ? urldecode($_POST['event_id']) : null;
    $fileType = isset($_POST['mimetype']) ? $_POST['mimetype'] : null;
    $fileName = isset($_POST['filename']) ? $_POST['filename'] : null;
    $pre_event = isset($_POST['pre_event_file']) ? $_POST['pre_event_file'] : null;
    if (empty($user_id)) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }
    $R_status = 'GO3';
    $forwardTo = 'District Collector';
    $sql = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T SET Request_status = :Request_status, forward_to= :forward, updated_by=:app_employee_id, PRE_EVENT = :p_PREEVENT, PRE_EVENT_ATTACH_MIMETYPE = :p_PREEVENT_ATTACH_MIMETYPE, PRE_EVENT_ATTACH_FILENAME = :p_PREEVENT_ATTACH_FILENAME, PRE_COMMENTS=:P61_PREEVENT_COMMENTS WHERE EVENT_ID = :P61_EVENT_ID;";

    $bind = $jk_write_db->prepare($sql);
    $bind->bindParam(':Request_status', $R_status);
    $bind->bindParam(':forward', $forwardTo);
    $bind->bindParam(':app_employee_id', $user_id);
    $bind->bindParam(':p_PREEVENT', $pre_event);
    $bind->bindParam(':p_PREEVENT_ATTACH_MIMETYPE', $fileType);
    $bind->bindParam(':p_PREEVENT_ATTACH_FILENAME', $filename);
    $bind->bindParam(':P61_PREEVENT_COMMENTS', $comments);
    $bind->bindParam(':P61_EVENT_ID', $event_id);
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