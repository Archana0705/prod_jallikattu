<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);
    $params['p_STATUS'] = 'Y';
    $params['R_STATUS'] = 'Y';

    $requiredFields = ["p_EVENT_TYPE","p_EVENT_DATE","p_PLACE_OF_EVENT","p_DISTRICT","p_SELECT_PART","p_MOBILE_NUMBER", "p_CREATED_BY"];
       
    foreach($requiredFields as $param) {
        if(!isset($param) || empty(trim($param)))
        throw new Exception("Missing or empty required parameter: $param");
    }
      
    try {
        $sql ="INSERT INTO TNEA_JALLIKATTU_PART_EVENT_T(EVENT_TYPE,EVENT_DATE,PLACE_OF_EVENT,DISTRICT,SELECT_PART,MOBILE_NUMBER,CREATED_BY)VALUES (:p_EVENT_TYPE,:p_EVENT_DATE,:p_PLACE_OF_EVENT,:p_DISTRICT,:p_SELECT_PART,:p_MOBILE_NUMBER,:p_CREATED_BY);";

        $stmt = $jk_write_db->prepare($sql);
        foreach ($params as $key => $value) {
            if (strpos($sql, ":$key") !== false) {
                $stmt->bindValue(":$key", $value);
            }
        }
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["success" => 1, "message" => "Data successfully inserted."]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => 0, "message" => "Error inserting data."]);
        }
    } catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => 0, "message" => "Query Execution failed."]);
}
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
?>
