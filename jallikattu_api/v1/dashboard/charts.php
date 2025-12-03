<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $sql = "SELECT distinct name_of_district, event_type, count(*) as  Total  from TNEA_JALLIKATTU_EVENT_REGISTERATION_T  WHERE SUBSTR(REVERSE(name_of_district), 1,3) NOT IN ('111', '000') GROUP BY name_of_district, event_type order by 1";
    $stmt = $jk_read_db->prepare($sql);
        if ($stmt->execute()) {
            $data_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = array(
                "success" =>$data_result ? 1 : 2, 
                "message" => $data_result ? "Data found" : "No data Found", 
                "data" => $data_result ?: null
            );
            http_response_code(200);
        } else {
            $data = array("success" => 3, "message" => "Problem in executing the query in db");
            http_response_code(500);
        }
        echo json_encode($data);
        die();
} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
