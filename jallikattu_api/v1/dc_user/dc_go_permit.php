<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve POST data
    $app_employee_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $comments = isset($_POST['comments']) ? $_POST['comments'] : null;
    $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : null;
    $event_date = isset($_POST['event_date']) ? $_POST['event_date'] : null;
    $event_place = isset($_POST['event_place']) ? $_POST['event_place'] : null;
    $dc_fileupload = isset($_POST['dc_fileupload']) ? $_POST['dc_fileupload'] : null;
    $mimetype = isset($_POST['mimetype']) ? $_POST['mimetype'] : null;
    $filename = isset($_POST['filename']) ? $_POST['filename'] : null;
    $event_type = isset($_POST['event_type']) ? $_POST['event_type'] : null;
    $created_by = isset($_POST['created_by']) ? $_POST['created_by'] : null;

    // Validate required fields
    if (empty($event_id) || empty($app_employee_id)) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }

    $R_status = 'C'; // Status for update

    try {
        // Insert query
        $insertSql = "INSERT INTO TNEA_JALLIKATTU_EVENT_PROCEED_T
	(
       EVENT_ID,
       EVENT_DATE,
       PROCEED,
       PROCEED_ATTACH_MIMETYPE,
       PROCEED_ATTACH_FILENAME,
       CREATED_BY,
       EVENT_TYPE,
       PROCEED_COMMENTS,
       PLACE_OF_EVENT)
  VALUES(
       :p58_EVENT_ID,
       :p58_EVENT_DATE,
       :p58_PROCEED,
       :p58_PROCEED_ATTACH_MIMETYPE,
       :p58_PROCEED_ATTACH_FILENAME,
       :p58_CREATED_BY,
       :p58_EVENT_TYPE,
       :p58_PROCEED_COMMENTS,
       :p58_PLACE_OF_EVENT);";

        $insert = $jk_write_db->prepare($insertSql);
        $insert->bindParam(':p58_EVENT_ID', $event_id);
        $insert->bindParam(':p58_EVENT_DATE', $event_date);
        $insert->bindParam(':p58_PROCEED', $dc_fileupload);
        $insert->bindParam(':p58_PROCEED_ATTACH_MIMETYPE', $mimetype);
        $insert->bindParam(':p58_PROCEED_ATTACH_FILENAME', $filename);
        $insert->bindParam(':p58_CREATED_BY', $created_by);
        $insert->bindParam(':p58_EVENT_TYPE', $event_type);
        $insert->bindParam(':p58_PROCEED_COMMENTS', $comments);
        $insert->bindParam(':p58_PLACE_OF_EVENT', $event_place);

        if (!$insert->execute()) {
            throw new Exception("Error inserting data.");
        }

        // Update query
        $updateSql = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
            SET Request_status = :REQUEST_STATUS, 
                PROCEED_COMMENTS = :P58_PROCEED_COMMENTS,
                UPDATED_BY = :APP_EMPLOYEE_ID
             WHERE EVENT_ID = :P58_EVENT_ID";

        $update = $jk_write_db->prepare($updateSql);
        $update->bindParam(':REQUEST_STATUS', $R_status);
        $update->bindParam(':P58_PROCEED_COMMENTS', $comments);
        $update->bindParam(':APP_EMPLOYEE_ID', $app_employee_id);
        $update->bindParam(':P58_EVENT_ID', $event_id);

        if (!$update->execute()) {
            throw new Exception("Error updating data.");
        }

        // Response
        http_response_code(200);
        echo json_encode(["success" => 1, "message" => "Data successfully inserted and updated."]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["success" => 0, "message" => "A database error occurred."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
?>
