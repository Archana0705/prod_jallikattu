<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $app_employee_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
    $app_employee_id = "'$app_employee_id'";
    if($app_employee_id === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "User does not exist");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT MONITORING_ID,event_id,PARTICIPANTS,NUMBER_OF_BULLS,PREEVENT_ARRANGEMENTS,ARENA_SIZE,ARENA_LENGTH,ARENA_BREATH,ARENA_UPLOAD,ARENA_UPLOAD_MIME_TYPE,ARENA_UPLOAD_FILENAME,LENGTH_ARENA,LENGTH_ARENA_LENGTH,LENGTH_ARENA_BREATH,LENGTH_ARENA_UPLOAD,LENGTH_ARENA_UPLOAD_MIME_TYPE,LENGTH_ARENA_UPLOAD_FILENAME,BULL_RUN_AREA,BULL_ARENA_LENGTH,BULL_ARENA_BREATH,BULL_ARENA_UPLOAD,BULL_ARENA_UPLOAD_MIME_TYPE,BULL_ARENA_UPLOAD_FILENAME,BULL_EXAMINATION_AREA_SIZE,BULL_EXAMINATION_LENGTH,BULL_EXAMINATION_BREATH,BULL_EXAMINATION_UPLOAD,BULL_EXAMINATION_UPLOAD_MIME_TYPE,BULL_EXAMINATION_UPLOAD_FILENAME,RECOMMENTATION,RECOMMENTATION_COMMENTS,STATUS,CREATED_BY,CREATED_DATE,UPDATED_BY,UPDATED_DATE,REMARKS_DC from TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T where event_id= $app_employee_id;";
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
