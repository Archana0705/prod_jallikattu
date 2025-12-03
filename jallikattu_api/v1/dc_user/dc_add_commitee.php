<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);
    if (empty($params['COMMITEE_ID']) || empty($params['event_id']) || empty($params['INSPECTION_DATE'])) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }
    $sql = "INSERT INTO TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T 
            (COMMITEE_ID, event_id, INSPECTION_DATE, COMMITEE_DEPARTMENT, COMMITEE_NAME, COMMITEE_DESIGNATION, COMMITEE_MAIL, COMMITEE_MOBILE) 
            VALUES (:COMMITEE_ID, :event_id, :INSPECTION_DATE, :COMMITEE_DEPARTMENT, :COMMITEE_NAME, :COMMITEE_DESIGNATION, :COMMITEE_MAIL, :COMMITEE_MOBILE);";
    $bind = $jk_write_db->prepare($sql);

    $bind->bindValue(':COMMITEE_ID', $params['COMMITEE_ID']);
    $bind->bindValue(':event_id', $params['event_id']);
    $bind->bindValue(':INSPECTION_DATE', $params['INSPECTION_DATE']);
    $bind->bindValue(':COMMITEE_DEPARTMENT', $params['COMMITEE_DEPARTMENT']);
    $bind->bindValue(':COMMITEE_NAME', $params['COMMITEE_NAME']);
    $bind->bindValue(':COMMITEE_DESIGNATION', $params['COMMITEE_DESIGNATION']);
    $bind->bindValue(':COMMITEE_MAIL', $params['COMMITEE_MAIL']);
    $bind->bindValue(':COMMITEE_MOBILE', $params['COMMITEE_MOBILE']);
   
    if ($bind->execute()) {
        http_response_code(200);
        $data = [
            "success" => 1,
            "message" => "Data successfully insert."
        ];
    } else {
        http_response_code(500);
        $data = [
            "success" => 0,
            "message" => "Error Inserting data."
        ];
    }

    echo json_encode($data);
    die();
} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
?>
