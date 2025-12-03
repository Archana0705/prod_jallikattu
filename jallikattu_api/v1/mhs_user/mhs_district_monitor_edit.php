<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $app_employee_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;   
    if($app_employee_id === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "User does not exist");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT d.MONITORING_ID, d.PARTICIPANTS, d.NUMBER_OF_BULLS, d.PREEVENT_ARRANGEMENTS, d.ARENA_SIZE, d.ARENA_LENGTH, d.ARENA_BREATH, d.LENGTH_ARENA, d.LENGTH_ARENA_LENGTH, d.LENGTH_ARENA_BREATH, d.BULL_RUN_AREA, d.BULL_ARENA_LENGTH, d.BULL_ARENA_BREATH, d.BULL_EXAMINATION_AREA_SIZE, d.BULL_EXAMINATION_LENGTH, d.BULL_EXAMINATION_BREATH, d. EVENT_ID, d.JOIN_ATTACH_MIMETYPE, d.JOIN_MONITORING_COMMITEE_REPORT, d.JOIN_ATTACH_FILENAME, d.JOIN_ATTACH_LAST_UPDATE, d.JOIN_ATTACH_CHARSET, d. REMARKS_DC, D.CREATED_BY, d. JOINT_REPORT_COMMENTS
    FROM TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d;";
    $stmt = $jk_read_db->prepare($sql);
    if ($stmt->execute()) {
        $data_result_count = $stmt->rowCount();
        if ($data_result_count > 0) {
            $data_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            $data = array("success" => 1, "message" => "Data found", "data" => $data_result);
            echo json_encode($data);
            die();
        } else {
            http_response_code(200);
            $data = array("success" => 2, "message" => "No data Found");
            echo json_encode($data);
            die();
        }
    } else {
        http_response_code(500);
        $data = array("success" => 3, "message" => "Problem in executing the query in db");
        echo json_encode($data);
        die();
    }
} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
