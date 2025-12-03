<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);
    if (empty($params['EVENT_ID']) || empty($params['MONITORING_ID'])) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields: EVENT_ID or MONITORING_ID"]);
        die();
    }
    $R_status = 'D2';
    $fwdTo='District Collector';
    try {
        $sql = "UPDATE TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T 
                SET 
                    PARTICIPANTS = :p_PARTICIPANTS, 
                    NUMBER_OF_BULLS = :p_NUMBER_OF_BULLS, 
                    PREEVENT_ARRANGEMENTS = :p_PREEVENT_ARRANGEMENTS, 
                    ARENA_SIZE = :p_ARENA_SIZE, 
                    ARENA_LENGTH = :p_ARENA_LENGTH, 
                    ARENA_BREATH = :p_ARENA_BREATH, 
                    BULL_RUN_AREA = :p_BULL_RUN_AREA, 
                    BULL_ARENA_LENGTH = :p_BULL_ARENA_LENGTH, 
                    BULL_ARENA_BREATH = :p_BULL_ARENA_BREATH, 
                    BULL_EXAMINATION_AREA_SIZE = :p_BULL_EXAMINATION_AREA_SIZE, 
                    BULL_EXAMINATION_LENGTH = :p_BULL_EXAMINATION_LENGTH, 
                    BULL_EXAMINATION_BREATH = :p_BULL_EXAMINATION_BREATH, 
                    JOIN_MONITORING_COMMITEE_REPORT = :p_JOIN_MONITORING_COMMITEE_REPORT, 
                    JOIN_ATTACH_MIMETYPE = :p_JOIN_ATTACH_MIMETYPE, 
                    JOIN_ATTACH_FILENAME = :p_JOIN_ATTACH_FILENAME, 
                    JOINT_REPORT_COMMENTS = :p_JOINT_REPORT_COMMENTS 
                WHERE 
                    MONITORING_ID = :p_MONITORING_ID;";

        $stmt = $jk_write_db->prepare($sql);

        $stmt->bindValue(':p_PARTICIPANTS', $params['PARTICIPANTS'] ?? null);
        $stmt->bindValue(':p_NUMBER_OF_BULLS', $params['NUMBER_OF_BULLS'] ?? null);
        $stmt->bindValue(':p_PREEVENT_ARRANGEMENTS', $params['PREEVENT_ARRANGEMENTS'] ?? null);
        $stmt->bindValue(':p_ARENA_SIZE', $params['ARENA_SIZE'] ?? null);
        $stmt->bindValue(':p_ARENA_LENGTH', $params['ARENA_LENGTH'] ?? null);
        $stmt->bindValue(':p_ARENA_BREATH', $params['ARENA_BREATH'] ?? null);
        $stmt->bindValue(':p_BULL_RUN_AREA', $params['BULL_RUN_AREA'] ?? null);
        $stmt->bindValue(':p_BULL_ARENA_LENGTH', $params['BULL_ARENA_LENGTH'] ?? null);
        $stmt->bindValue(':p_BULL_ARENA_BREATH', $params['BULL_ARENA_BREATH'] ?? null);
        $stmt->bindValue(':p_BULL_EXAMINATION_AREA_SIZE', $params['BULL_EXAMINATION_AREA_SIZE'] ?? null);
        $stmt->bindValue(':p_BULL_EXAMINATION_LENGTH', $params['BULL_EXAMINATION_LENGTH'] ?? null);
        $stmt->bindValue(':p_BULL_EXAMINATION_BREATH', $params['BULL_EXAMINATION_BREATH'] ?? null);
        $stmt->bindValue(':p_JOIN_MONITORING_COMMITEE_REPORT', $params['JOIN_MONITORING_COMMITEE_REPORT'] ?? null);
        $stmt->bindValue(':p_JOIN_ATTACH_MIMETYPE', $params['JOIN_ATTACH_MIMETYPE'] ?? null);
        $stmt->bindValue(':p_JOIN_ATTACH_FILENAME', $params['JOIN_ATTACH_FILENAME'] ?? null);
        $stmt->bindValue(':p_JOINT_REPORT_COMMENTS', $params['JOINT_REPORT_COMMENTS'] ?? null);
        $stmt->bindValue(':p_MONITORING_ID', $params['MONITORING_ID']);

        if ($stmt->execute()) {
            $statusSql="UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
                        SET Request_status = :Request_status,forward_to= :forward,updated_by=:app_employee_id
                        WHERE EVENT_ID = :P52_EVENT_ID;";
            $stmt = $jk_write_db->prepare($statusSql);
            $stmt->bindParam(':Request_status', $R_status);
            $stmt->bindParam(':forward', $fwdTo);
            $stmt->bindParam(':app_employee_id', $params['USER_ID']);
            $stmt->bindParam(':P52_EVENT_ID', $params['EVENT_ID']);
            if ($stmt->execute()) {
                http_response_code(200);
                echo json_encode(["success" => 1, "message" => "Updated and Forward to District collector done successfully."]);
            }else{
                http_response_code(500);
                echo json_encode(["success" => 0, "message" => "Forward to District collector not done"]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["success" => 0, "message" => "Error updating data."]);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["success" => 0, "message" => "Database error occurred."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
?>
