<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);

    if (empty($params['p_COMMITEE_ID'])) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }

    $sql = "UPDATE TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T SET INSPECTION_DATE = :p_INSPECTION_DATE,  COMMITEE_DEPARTMENT = :p_COMMITEE_DEPARTMENT,  COMMITEE_NAME = :p_COMMITEE_NAME,COMMITEE_DESIGNATION = :p_COMMITEE_DESIGNATION,  COMMITEE_MAIL = :p_COMMITEE_MAIL,  COMMITEE_MOBILE = :p_COMMITEE_MOBILE WHERE COMMITEE_ID = :p_COMMITEE_ID;";

    $stmt = $jk_write_db->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }

    $execution_result = $stmt->execute();

    if ($execution_result) {
        http_response_code(200); 
        $data = [
            "success" => 1,
            "message" => "Data successfully updated."
        ];
    } else {
        http_response_code(500); 
        $data = [
            "success" => 0,
            "message" => "Error updating data."
        ];
    }

    echo json_encode($data);
    die();

} else {
    http_response_code(405);
    $data = ["success" => 0, "message" => "Method Not Allowed"];
    echo json_encode($data);
    die();
}
?>
