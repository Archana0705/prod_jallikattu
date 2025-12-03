<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
    $district = isset($_GET['district']) ? $_GET['district'] : null;
    $event_type = isset($_GET['event_type']) ? $_GET['event_type'] : null;
    $sql = "SELECT DISTINCT(Initcap(e.PLACE_OF_EVENT)) as D,initcap(e.PLACE_OF_EVENT) as R  from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e, TNEA_JALLIKATTU_EVENT_GO_T g where e.EVENT_TYPE = :P97_EVENT_TYPE AND e.request_status in ('GO1' , 'C')   AND g.NAME_OF_DISTRICT = e.NAME_OF_DISTRICT AND e.NAME_OF_DISTRICT = :P97_DISTRICT  AND g.PLACE_EVENT = e.PLACE_OF_EVENT AND g.EVENT_ID= e.EVENT_ID And e.PLACE_OF_EVENT <> 'Thirukanurpatti' ORDER BY 1;
    ";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':P97_EVENT_TYPE', $event_type);
    $stmt->bindParam(':P97_DISTRICT', $district);
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
