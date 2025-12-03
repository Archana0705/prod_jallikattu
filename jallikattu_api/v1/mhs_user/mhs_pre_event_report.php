<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $app_employee_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : null;
    $event_date = isset($_POST['event_date']) ? $_POST['event_date'] : null;
    $event_place = isset($_POST['event_place']) ? $_POST['event_place'] : null;
    $pre_event = isset($_POST['pre_event']) ? $_POST['pre_event'] : null;
    $mimetype = isset($_POST['mimetype']) ? $_POST['mimetype'] : null;
    $filename = isset($_POST['filename']) ? $_POST['filename'] : null;
    $event_type = isset($_POST['event_type']) ? $_POST['event_type'] : null;
    $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : null;
    $comments = isset($_POST['comments']) ? $_POST['comments'] : null;

    if (empty($app_employee_id)) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }

    $R_status = 'GO2';
    $forwardTo = 'District Collector';

    // INSERT Query
    $sql = "INSERT INTO TNEA_JALLIKATTU_EVENT_PREEVENT_T(
        EVENT_ID, EVENT_DATE, PLACE_OF_EVENT, PREEVENT, 
        PREEVENT_ATTACH_MIMETYPE, PREEVENT_ATTACH_FILENAME, 
        CREATED_BY, UPDATED_BY, EVENT_TYPE, PREEVENT_COMMENTS
    ) VALUES (
        :p61_EVENT_ID, :p61_EVENT_DATE, :p61_PLACE_OF_EVENT, 
        :p61_PREEVENT, :p61_PREEVENT_ATTACH_MIMETYPE, 
        :p61_PREEVENT_ATTACH_FILENAME, :p61_CREATED_BY, 
        :p61_UPDATED_BY, :p61_EVENT_TYPE, :p61_PREEVENT_COMMENTS
    );";

    $insert = $jk_write_db->prepare($sql);
    $insert->bindParam(':p61_EVENT_ID', $event_id);
    $insert->bindParam(':p61_EVENT_DATE', $event_date);
    $insert->bindParam(':p61_PLACE_OF_EVENT', $event_place);
    $insert->bindParam(':p61_PREEVENT', $pre_event);
    $insert->bindParam(':p61_PREEVENT_ATTACH_MIMETYPE', $mimetype);
    $insert->bindParam(':p61_PREEVENT_ATTACH_FILENAME', $filename);
    $insert->bindParam(':p61_CREATED_BY', $created_by);
    $insert->bindParam(':p61_UPDATED_BY', $app_employee_id);
    $insert->bindParam(':p61_EVENT_TYPE', $event_type);
    $insert->bindParam(':p61_PREEVENT_COMMENTS', $comments);

    if ($insert->execute()) {
        // UPDATE Query
        $updateSql = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
            SET
                Request_status = :request_status,
                forward_to = :forward,
                updated_by = :app_employee_id,
                PRE_COMMENTS = :P61_PREEVENT_COMMENTS
            WHERE EVENT_ID = :P61_EVENT_ID;";

        $update = $jk_write_db->prepare($updateSql);
        $update->bindParam(':request_status', $R_status);
        $update->bindParam(':forward', $forwardTo);
        $update->bindParam(':app_employee_id', $app_employee_id);
        $update->bindParam(':P61_PREEVENT_COMMENTS', $comments);
        $update->bindParam(':P61_EVENT_ID', $event_id);

        if ($update->execute()) {
            http_response_code(200);
            echo json_encode([
                "success" => 1,
                "message" => "Data successfully updated."
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => 0,
                "message" => "Data not updated."
            ]);
        }
    } else {
        http_response_code(500);
        echo json_encode([
            "success" => 0,
            "message" => "Error inserting data."
        ]);
    }
    die();
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
    die();
}
?>
