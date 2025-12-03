<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $user_id = $_POST['user_id'] ?? null;
    $comments = $_POST['comments'] ?? null;
    $event_id = $_POST['event_id'] ?? null;
    
    if (!$user_id) {
        $errors[] = "User ID";
    }
    if (!$comments) {
        $errors[] = "Remark";
    }
    if (!$event_id) {
        $errors[] = "Event ID";
    }
    
    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            "success" => 0,
            "message" => implode(", ", $errors) . " is missing."
        ]);
        die();
    }
    
    $R_status = 'A1';
    $forwardTo = 'District Collector';
    $sql = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T SET Request_status= :Request_status,forward_to=:forward,MHS_COMMENTS=:P9_MHS_COMMENTS, updated_by=:app_employee_id WHERE EVENT_ID = :P9_EVENT_ID;";

    $bind = $jk_write_db->prepare($sql);
    $bind->bindParam(':Request_status', $R_status);
    $bind->bindParam(':forward', $forwardTo);
    $bind->bindParam(':P9_MHS_COMMENTS', $comments);
    $bind->bindParam(':app_employee_id', $user_id);
    $bind->bindParam(':P9_EVENT_ID', $event_id);
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
