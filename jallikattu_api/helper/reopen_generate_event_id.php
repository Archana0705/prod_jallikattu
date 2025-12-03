<?php
require_once('header.php');
header("Access-Control-Allow-Methods: POST");
require_once('db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $app_employee_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $district = isset($_POST['district']) ? $_POST['district'] : null;
    $event_name = isset($_POST['event_type']) ? $_POST['event_type'] : null;
    $errors = [];
    !$app_employee_id ? $errors[] = "User does not exist" : null;
    !$district ? $errors[] = "District not found" : null;
    !$event_name ? $errors[] = "Event type not found" : null;
    if (!empty($errors)) {
        http_response_code(401);
        echo json_encode(["success" => 0, "message" => implode(", ", $errors)]);
        die();
    }
    $checksql = "SELECT created_by from TNEA_JALLIKATTU_EVENT_REGISTERATION_T where created_by = :user_id";
    $bind = $jk_read_db->prepare($checksql);
    $bind->bindParam(':user_id', $app_employee_id, PDO::PARAM_STR);
    if($bind->execute()){
        $result = $bind->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            $sql = "SELECT SUBSTRING(UPPER(:district), 1, 3) || '/' || TO_CHAR(CURRENT_DATE, 'YYMMDD') || '/' || 'RO' || '/' || SUBSTRING(UPPER(:event_name),1,3) || '/' || LPAD(NEXTVAL('TNEA_JALLIKATTU_EVENT_REOPEN_SEQ')::text, 5, '0');";
            $stmt = $jk_read_db->prepare($sql);
            $stmt->bindParam(':district', $district, PDO::PARAM_STR);
            $stmt->bindParam(':event_name', $event_name, PDO::PARAM_STR);
            if ($stmt->execute()) {
                $data_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $response_data = array_map(function ($item) {
                    return ['event_id' => $item['event_id']];
                }, $data_result);
                $data = [
                    "success" => $data_result ? 1 : 2,
                    "message" => $data_result ? "Data found" : "Failed to generate event ID",
                    "data" => $data_result ?: null
                ];
                http_response_code(200);
                echo json_encode($data);
                die();
            } 
        }else {
            http_response_code(500);
            $data = array("success" => 3, "message" => "User not found in database");
            echo json_encode($data);
            die();
        }
    } else {
        http_response_code(500);
        $data = array("success" => 0, "message" => "Problem in executing the query in db");
        echo json_encode($data);
        die();
    }
} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
