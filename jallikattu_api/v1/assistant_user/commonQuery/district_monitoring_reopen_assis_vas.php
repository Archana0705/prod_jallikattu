<?php
require_once('../../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../../helper/db/jk_write.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);
    if ($params === null) {
        error_log('Payload is invalid or missing: ' . file_get_contents('php://input'));
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Invalid JSON payload"]);
        die();
    }   
    $R_status = 'RO';
    $fwdTo = 'Magesterial huzur sharishthadhar MHS';
    $sql = "UPDATE TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T SET PARTICIPANTS = :p_PARTICIPANTS, NUMBER_OF_BULLS = :p_NUMBER_OF_BULLS, PREEVENT_ARRANGEMENTS = :p_PREEVENT_ARRANGEMENTS, ARENA_SIZE= :p_ARENA_SIZE, ARENA_LENGTH = :p_ARENA_LENGTH, ARENA_BREATH = :p_ARENA_BREATH, ARENA_UPLOAD = :p_ARENA_UPLOAD, ARENA_UPLOAD_MIME_TYPE = :p_ARENA_UPLOAD_MIME_TYPE, ARENA_UPLOAD_FILENAME = :p_ARENA_UPLOAD_FILENAME, BULL_RUN_AREA = :p_BULL_RUN_AREA, BULL_ARENA_LENGTH = :p_BULL_ARENA_LENGTH, BULL_ARENA_BREATH = :p_BULL_ARENA_BREATH, BULL_ARENA_UPLOAD = :p_BULL_ARENA_UPLOAD, BULL_ARENA_UPLOAD_MIME_TYPE = :p_BULL_ARENA_UPLOAD_MIME_TYPE, BULL_ARENA_UPLOAD_FILENAME= :p_BULL_ARENA_UPLOAD_FILENAME, BULL_EXAMINATION_AREA_SIZE= :p_BULL_EXAMINATION_AREA_SIZE, BULL_EXAMINATION_LENGTH = :p_BULL_EXAMINATION_LENGTH, BULL_EXAMINATION_BREATH = :p_BULL_EXAMINATION_BREATH, BULL_EXAMINATION_UPLOAD = :p_BULL_EXAMINATION_UPLOAD, BULL_EXAMINATION_UPLOAD_MIME_TYPE = :p_BULL_EXAMINATION_UPLOAD_MIME_TYPE, BULL_EXAMINATION_UPLOAD_FILENAME = :p_BULL_EXAMINATION_UPLOAD_FILENAME, EVENT_ID = :p_EVENT_ID, PLACE_EVENT = :p_PLACE_EVENT, EVENT_DATE= :p_EVENT_DATE, DISTRICT = :p_DISTRICT where EVENT_ID = :p_EVENT_ID;";
  
    $bind = $jk_write_db->prepare($sql);
    foreach ($params as $key => $value) {
        if (strpos($sql, ":$key") !== false) {
            $bind->bindValue(":$key", $value);
        }
    }

    if ($bind->execute()){
        $update = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
        SET
            Request_status = :Request_status,FORWARD_TO= :forward
        WHERE
            EVENT_ID = :p_EVENT_ID;";
         $bind = $jk_write_db->prepare($update);
         $bind->bindValue(':Request_status', $R_status);
         $bind->bindValue(':forward', $fwdTo);
         $bind->bindValue(':p_EVENT_ID', $params['p_EVENT_ID']);
        if ($bind->execute()) {
            $updateSql = "UPDATE TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T
            SET EVENT_ID = :NEW_EVENT_ID
            WHERE EVENT_ID = :OLD_EVENT_ID;";
             $bind = $jk_write_db->prepare($updateSql);
             $bind->bindValue(':NEW_EVENT_ID', $params['NEW_EVENT_ID']);
             $bind->bindValue(':OLD_EVENT_ID', $params['p_EVENT_ID']);
             if ($bind->execute()) {
                http_response_code(200);
                $data = [
                    "success" => 1,
                    "message" => "Data successfully updated."
                ];
             }else{
                http_response_code(200);
                $data = [
                    "success" => 1,
                    "message" => "Data not updated."
                ];
             }
             echo json_encode($data);
             exit;
        } else {
            http_response_code(500);
            $data = [
                "success" => 0,
                "message" => "Error updating data."
            ];
        }
    
        echo json_encode($data);
        die();
    }
    
} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
?>
