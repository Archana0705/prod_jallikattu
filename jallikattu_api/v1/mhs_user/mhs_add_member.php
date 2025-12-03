<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = json_decode(file_get_contents("php://input"), true);
    $errors = [];
    $event_id = $input['p_EVENT_ID'] ?? null;
    $inspection_date = $input['p_INSPECTION_DATE'] ?? null;
    $commitee_dept = $input['p_COMMITEE_DEPARTMENT'] ?? null;
    $commitee_name = $input['p_COMMITEE_NAME'] ?? null;
    $commitee_design = $input['p_COMMITEE_DESIGNATION'] ?? null;
    $commitee_mail = $input['p_COMMITEE_MAIL'] ?? null;
    $commitee_mobile = $input['p_COMMITEE_MOBILE'] ?? null;

  
    !$event_id && $errors[] = "Event ID";
    !$inspection_date && $errors[] = "Inspection Date";
    !$commitee_dept && $errors[] = "Department";
    !$commitee_name && $errors[] = "Committee Name";
    !$commitee_design && $errors[] = "Designation";
    !$commitee_mail && $errors[] = "Mail ID";
    !$commitee_mobile && $errors[] = "Mobile Number";

    if (!empty($errors)) {
        http_response_code(400); 
        echo json_encode(["success" => 0, "message" => implode(", ", $errors) . " is missing"]);
        die();
    }

    $InsertSql = "INSERT INTO TNEA_JALLIKATTU_MONITORING_COMMITEE_REGISTERATION_T(INSPECTION_DATE, COMMITEE_DEPARTMENT, COMMITEE_NAME, COMMITEE_DESIGNATION, COMMITEE_MAIL, COMMITEE_MOBILE, EVENT_ID) VALUES(:p_INSPECTION_DATE, :p_COMMITEE_DEPARTMENT, :p_COMMITEE_NAME, :p_COMMITEE_DESIGNATION, :p_COMMITEE_MAIL, :p_COMMITEE_MOBILE, :p_EVENT_ID);";

    $insertBind = $jk_write_db->prepare($InsertSql);
    $insertBind->bindParam(':p_EVENT_ID', $event_id);
    $insertBind->bindParam(':p_INSPECTION_DATE', $inspection_date);
    $insertBind->bindParam(':p_COMMITEE_DEPARTMENT', $commitee_dept);
    $insertBind->bindParam(':p_COMMITEE_NAME', $commitee_name);
    $insertBind->bindParam(':p_COMMITEE_DESIGNATION', $commitee_design);
    $insertBind->bindParam(':p_COMMITEE_MAIL', $commitee_mail);
    $insertBind->bindParam(':p_COMMITEE_MOBILE', $commitee_mobile);

    if ($insertBind->execute()) {
        http_response_code(200);
        $data = [
            "success" => 1,
            "message" => "Data successfully inserted."
        ];
    } else {
        http_response_code(500);
        $data = [
            "success" => 0,
            "message" => "Error while inserting data.",
            "error" => $insertBind->errorInfo()
        ];
    }

    echo json_encode($data);
    die();
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
    die();
}
?>
