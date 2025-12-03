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
    $sql = "SELECT b.PART_ID, b.EVENT_TYPE, b.EVENT_DATE, b.PLACE_OF_EVENT, b.DISTRICT, b.STATUS, b.CREATED_BY, b.CREATED_DATE, b.UPDATED_DATE, b.UPDATED_BY, b.SELECT_PART, b.MOBILE_NUMBER from TNEA_JALLIKATTU_PART_EVENT_T b,TNEA_JALLIKATTU_APPROVER_LOGIN_T a where a.district = b.DISTRICT AND B.REQUEST_STATUS ='AB' and a.approver_id=:app_employee_id;";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':app_employee_id', $app_employee_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $data_result_count = $stmt->rowCount();
        if ($data_result_count > 0) {
            $data_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            $data = array("success" => 1, "message" => "Data found", "data" => $data_result);
        } else {
            http_response_code(200);
            $data = array("success" => 2, "message" => "No data Found");
        }
        echo json_encode($data);
        die();
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
