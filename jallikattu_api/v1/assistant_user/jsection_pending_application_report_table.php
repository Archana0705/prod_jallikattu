<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $app_employee_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;   
    $event_id = isset($_GET['event_id']) ? $_GET['event_id'] : null;   
    if(empty($app_employee_id) || empty($event_id)){
        http_response_code(401);
        $data = array("success" => 0, "message" => "Missing required field");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT COMMITEE_ID, INSPECTION_DATE, COMMITEE_DEPARTMENT, COMMITEE_NAME, COMMITEE_DESIGNATION, COMMITEE_MAIL, COMMITEE_MOBILE, STATUS, CREATED_BY, CREATED_DATE, UPDATED_BY, UPDATED_DATE, EVENT_ID, REMARKS FROM TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T where event_id=:P19_EVENT_ID;";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':P19_EVENT_ID', $event_id, PDO::PARAM_INT);
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
