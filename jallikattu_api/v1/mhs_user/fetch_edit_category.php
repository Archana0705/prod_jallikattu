<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $event_id = isset($_GET['event_id']) ? $_GET['event_id'] : null;   
    if($event_id === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "Event Id is invalid");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT e.event_id as eventid, e.event_type, e.EVENT_DATE, e.NAME_OF_ORGANIZATION, e.COMMITEE_REGISTER_NUMBER, e.NAME_OF_PRESIDENT, e.CONTACT_OF_PRESIDENT, e.MOBILE_OF_PRESIDENT, e.NAME_OF_VILLAGE, e.NAME_OF_TALUK, e.NAME_OF_DISTRICT, e.PLACE_OF_EVENT, e.PINCODE, e.mhs_comments, d.PARTICIPANTS, d.NUMBER_OF_BULLS, d.PREEVENT_ARRANGEMENTS, d.ARENA_SIZE, d.ARENA_LENGTH, d.ARENA_BREATH, d.LENGTH_ARENA, d.LENGTH_ARENA_LENGTH, d.LENGTH_ARENA_BREATH, d.BULL_RUN_AREA, d.BULL_ARENA_LENGTH, d.BULL_ARENA_BREATH, d.BULL_EXAMINATION_AREA_SIZE, d.BULL_EXAMINATION_LENGTH, d.BULL_EXAMINATION_BREATH, e.created_by, e.NMI_COMMENTS_MHS from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e,
    TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d WHERE e.event_id = d.event_id AND e.event_id = :event_id;";
    
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':event_id', $event_id);
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
