<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $event_type = isset($_GET['event_type']) ? $_GET['event_type'] : null;
    $sql = "SELECT distinct(Initcap(e.NAME_OF_DISTRICT)) as d,initcap(e.NAME_OF_DISTRICT) as r  from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e, TNEA_JALLIKATTU_EVENT_GO_T g WHERE g.NAME_OF_DISTRICT = e.NAME_OF_DISTRICT AND e.request_status in ('GO1' , 'C') AND e.EVENT_TYPE = :P24_EVENT_TYPE_1 ORDER BY 1;";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':P24_EVENT_TYPE_1', $event_type);
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
