<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $app_employee_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;   
    if($app_employee_id === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "User id not exist");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT e.EVENT_ID, e.EVENT_DATE, e.NAME_OF_ORGANIZATION, e.COMMITEE_REGISTER_NUMBER, e.NAME_OF_PRESIDENT, e.CONTACT_OF_PRESIDENT, e.MOBILE_OF_PRESIDENT, e.NAME_OF_VILLAGE, e.NAME_OF_TALUK, e.NAME_OF_DISTRICT, e.PINCODE, e.STATUS, e.CREATED_BY, e.CREATED_DATE, e.UPDATED_BY, e.UPDATED_DATE, e.EVENT_TYPE, e.REQUEST_STATUS, e.FORWARD_TO, e.MHS_COMMENTS, e.REGISTERING_DATE_FOR_THE_EVENT, e.DC_COMMENTS, e.DAH_VS_COMMENTS, e.ACS_PS_COMMENTS, e.PLACE_OF_EVENT, d.PARTICIPANTS, d.NUMBER_OF_BULLS, d.PREEVENT_ARRANGEMENTS, d.ARENA_SIZE, d.ARENA_LENGTH, d.ARENA_BREATH, d.LENGTH_ARENA, d.LENGTH_ARENA_LENGTH, d.LENGTH_ARENA_BREATH, d.BULL_RUN_AREA, d.BULL_ARENA_LENGTH, d.BULL_ARENA_BREATH, d.BULL_EXAMINATION_AREA_SIZE, d.BULL_EXAMINATION_LENGTH, d.BULL_EXAMINATION_BREATH
    from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e, TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T D WHERE E.EVENT_ID=D.EVENT_ID";
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
