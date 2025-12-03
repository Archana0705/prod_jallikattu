<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $event_place = isset($_GET['event_place']) ? $_GET['event_place'] : null;
    $event_type = isset($_GET['event_type']) ? $_GET['event_type'] : null;
  $sql = "SELECT e.event_date as d, e.event_date as r 
        FROM TNEA_JALLIKATTU_EVENT_REGISTERATION_T e, TNEA_JALLIKATTU_EVENT_GO_T g 
        WHERE e.EVENT_TYPE = :P97_EVENT_TYPE 
        AND e.request_status IN ('GO1', 'C')  
        AND g.NAME_OF_DISTRICT = e.NAME_OF_DISTRICT 
        AND g.PLACE_EVENT = e.PLACE_OF_EVENT 
        AND g.PLACE_EVENT = :P97_PLACE_OF_EVENT 
        AND g.EVENT_ID = e.EVENT_ID 
        AND e.PLACE_OF_EVENT != 'Thirukanurpatti' 
        AND CURRENT_DATE - e.event_date <= 1;";
  $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':P97_EVENT_TYPE', $event_type);
    $stmt->bindParam(':P97_PLACE_OF_EVENT', $event_place);
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
