<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;
    $district = isset($_GET['district']) ? $_GET['district'] : null;
    $taluk = isset($_GET['taluk']) ? $_GET['taluk'] : null;
    
    // if($user_id === null){
    //     http_response_code(401);
    //     $data = array("success" => 0, "message" => "User does not exist");
    //     echo json_encode($data);
    //     die();
    // }
     if($district === null || $taluk === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "District or Taluk is missing");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT DISTINCT INITCAP(Event_Place) AS d, INITCAP(Event_Place) AS r FROM TNEA_JALLIKATTU_ADDRESS_TB WHERE District_name = :P5_NAME_OF_DISTRICT AND Taluk_name = :P5_NAME_OF_TALUK ORDER BY d;";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':P5_NAME_OF_DISTRICT', $district);
    $stmt->bindParam(':P5_NAME_OF_TALUK', $taluk);
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
