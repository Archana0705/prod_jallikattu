<?php
require_once('../../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $app_employee_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;   
    if($app_employee_id === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "User does not exist");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT e.EVENT_ID,e. REQUEST_STATUS,a. MONITORING_ID,a.PARTICIPANTS,a.NUMBER_OF_BULLS,a.PREEVENT_ARRANGEMENTS,a.ARENA_SIZE,a.ARENA_LENGTH,a.ARENA_BREATH,a.ARENA_UPLOAD,a.ARENA_UPLOAD_MIME_TYPE,a.ARENA_UPLOAD_FILENAME,a.LENGTH_ARENA,a.LENGTH_ARENA_LENGTH,a.LENGTH_ARENA_BREATH,a.LENGTH_ARENA_UPLOAD_MIME_TYPE,a.LENGTH_ARENA_UPLOAD_FILENAME,a.BULL_RUN_AREA,a.BULL_ARENA_LENGTH,a.BULL_ARENA_BREATH,a.BULL_ARENA_UPLOAD,a.BULL_ARENA_UPLOAD_MIME_TYPE,a.BULL_ARENA_UPLOAD_FILENAME,a.BULL_EXAMINATION_AREA_SIZE,a.BULL_EXAMINATION_LENGTH,a.BULL_EXAMINATION_BREATH,a.BULL_EXAMINATION_UPLOAD,a.BULL_EXAMINATION_UPLOAD_MIME_TYPE,a.BULL_EXAMINATION_UPLOAD_FILENAME,a.STATUS,a.CREATED_BY,a.UPDATED_BY,a.EVENT_ID as e_id,a.JOIN_ATTACH_MIMETYPE,a.JOIN_ATTACH_FILENAME,a.JOIN_ATTACH_CHARSET,a.REMARKS_DC,a.JOINT_REPORT_COMMENTS,a.DUP_MOBILE,a.PLACE_EVENT,a.EVENT_DATE,a.DISTRICT from TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T a, TNEA_JALLIKATTU_EVENT_REGISTERATION_T e where e.event_id=a.event_id;";
    $stmt = $jk_read_db->prepare($sql);
    if ($stmt->execute()) {
        $data_result_count = $stmt->rowCount();
        if ($data_result_count > 0) {
            $data_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data_result = array_map(fn($item) => array_filter($item, fn($value) => gettype($value) !== 'resource'), $data_result);
            http_response_code(200);
            $data = array("success" => 1, "message" => "Data found", "data" => $data_result);
        } else {
            http_response_code(200);
            $data = array("success" => 2, "message" => "No data Found");
           
        }
        echo json_encode($data);
        die();
    }else {
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
