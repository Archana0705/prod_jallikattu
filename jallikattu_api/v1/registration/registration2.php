<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');
require_once('../../helper/send_sms.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $params = json_decode(file_get_contents('php://input'), true);
    $errors = [];

    // Validation
    if (empty($params['p_DISTRICT'])) {
        $errors[] = "District is missing";
    }
    if (empty($params['p_PLACE_EVENT'])) {
        $errors[] = "Event Place is missing";
    }
    if (empty($params['p_EVENT_DATE'])) {
        $errors[] = "Event Date is missing";
    }
    if (empty($params['p_EVENT_ID'])) {
        $errors[] = "Event ID is missing";
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            "success" => 0,
            "message" => "The required fields are missing: " . implode(', ', $errors)
        ]);
        die();
    }

    $event_id = $params['p_EVENT_ID'];
    $mobile_number = $params['MOBILE_NO'] ?? null;
    $district = $params['p_DISTRICT'];
    $event_place = $params['p_PLACE_EVENT'];
    $event_date = $params['p_EVENT_DATE'];

    try {
        $jk_write_db->beginTransaction();

        $sqlInsert = "INSERT INTO TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T
            (PARTICIPANTS, NUMBER_OF_BULLS, PREEVENT_ARRANGEMENTS, ARENA_SIZE, ARENA_LENGTH, ARENA_BREATH,
             ARENA_UPLOAD, ARENA_UPLOAD_MIME_TYPE, ARENA_UPLOAD_FILENAME, BULL_RUN_AREA, BULL_ARENA_LENGTH, BULL_ARENA_BREATH,
             BULL_ARENA_UPLOAD, BULL_ARENA_UPLOAD_MIME_TYPE, BULL_ARENA_UPLOAD_FILENAME, BULL_EXAMINATION_AREA_SIZE,
             BULL_EXAMINATION_LENGTH, BULL_EXAMINATION_BREATH, BULL_EXAMINATION_UPLOAD, BULL_EXAMINATION_UPLOAD_MIME_TYPE,
             BULL_EXAMINATION_UPLOAD_FILENAME, EVENT_ID, PLACE_EVENT, EVENT_DATE, DISTRICT)
            VALUES (:p_PARTICIPANTS, :p_NUMBER_OF_BULLS, :p_PREEVENT_ARRANGEMENTS, :p_ARENA_SIZE,
                    :p_ARENA_LENGTH, :p_ARENA_BREATH, :p_ARENA_UPLOAD, :p_ARENA_UPLOAD_MIME_TYPE, :p_ARENA_UPLOAD_FILENAME,
                    :p_BULL_RUN_AREA, :p_BULL_ARENA_LENGTH, :p_BULL_ARENA_BREATH, :p_BULL_ARENA_UPLOAD, :p_BULL_ARENA_UPLOAD_MIME_TYPE,
                    :p_BULL_ARENA_UPLOAD_FILENAME, :p_BULL_EXAMINATION_AREA_SIZE, :p_BULL_EXAMINATION_LENGTH,
                    :p_BULL_EXAMINATION_BREATH, :p_BULL_EXAMINATION_UPLOAD, :p_BULL_EXAMINATION_UPLOAD_MIME_TYPE,
                    :p_BULL_EXAMINATION_UPLOAD_FILENAME, :p_EVENT_ID, :p_PLACE_EVENT, :p_EVENT_DATE, :p_DISTRICT);";

        $stmt = $jk_write_db->prepare($sqlInsert);
        foreach ($params as $key => $value) {
            if (strpos($sqlInsert, ":$key") !== false) {
                $stmt->bindValue(":$key", $value);
            }
        }

        if (!$stmt->execute()) {
            throw new Exception("Error inserting data into the database.");
        }

        $R_status = 'S';
        $forward = 'Magesterial huzur sharishthadhar MHS';
        $sqlUpdate = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T SET Request_status = :Request_status, forward_to = :forward WHERE EVENT_ID = :p_EVENT_ID;";
        $updateStmt = $jk_write_db->prepare($sqlUpdate);
        $updateStmt->bindParam(':p_EVENT_ID', $params['p_EVENT_ID']);
        $updateStmt->bindParam(':Request_status', $R_status);
        $updateStmt->bindParam(':forward', $forward);

        if (!$updateStmt->execute()) {
            throw new Exception("Error updating request status.");
        }

        $jk_write_db->commit();

        $smsStatus = sendSMS($mobile_number, $district, $event_place, $event_date);

        http_response_code($smsStatus ? 200 : 500);
        echo json_encode([
            "success" => $smsStatus ? 1 : 0,
            "message" => $smsStatus
                ? "Data inserted, request status updated, and SMS sent successfully."
                : "Data inserted, request status updated, but failed to send SMS."
        ]);
    } catch (Exception $e) {
        $jk_write_db->rollBack();
        http_response_code(500);
        echo json_encode([
            "success" => 0,
            "message" => "A database error occurred."
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
?>
