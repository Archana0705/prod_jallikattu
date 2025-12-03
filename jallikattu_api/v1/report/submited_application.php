<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $app_employee_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;   
    if($app_employee_id === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "User does not exist");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT EVENT_ID, EVENT_DATE, NAME_OF_ORGANIZATION, COMMITEE_REGISTER_NUMBER, NAME_OF_PRESIDENT, CONTACT_OF_PRESIDENT, MOBILE_OF_PRESIDENT, NAME_OF_VILLAGE, NAME_OF_TALUK, NAME_OF_DISTRICT, PINCODE, REQUEST_LETTER, ATTACH_MIMETYPE, ATTACH_FILENAME, ASSURANCE_BOND, ASSURANCE_BOND_ATTACH_MIMETYPE, ASSURANCE_BOND_ATTACH_FILENAME, INSURANCE_COPY, INSURANCE_COPY_ATTACH_MIMETYPE, INSURANCE_COPY_ATTACH_FILENAME, LAYOUT_SKETCH, LAYOUT_SKETCH_ATTACH_MIMETYPE, LAYOUT_SKETCH_ATTACH_FILENAME, OTHER_DOCS, OTHER_DOCS_ATTACH_MIMETYPE, OTHER_DOCS_ATTACH_FILENAME, PREVIOUS_EVENT, PREVIOUS_EVENT_ATTACH_MIMETYPE, PREVIOUS_EVENT_ATTACH_FILENAME, PANCHAYAT_UNION, PANCHAYAT_UNION_ATTACH_MIMETYPE, PANCHAYAT_UNION_ATTACH_FILENAME, FIR, FIR_ATTACH_MIMETYPE, FIR_ATTACH_FILENAME, UPLOAD_PHOTO, UPLOAD_PHOTO_ATTACH_MIMETYPE, UPLOAD_PHOTO_ATTACH_FILENAME, EVENT_TYPE, CASE REQUEST_status WHEN 'E' THEN 'Save' WHEN 'S' THEN 'Waiting for Approval' WHEN 'C' THEN 'Closed' WHEN 'D1' THEN 'Waiting for Approval' WHEN 'D2' THEN 'Waiting for Approval' WHEN 'DNN' THEN 'Waiting for Approval' WHEN 'N' THEN 'Waiting for Approval' WHEN 'A' THEN 'Waiting for Approval' WHEN 'A1' THEN 'Waiting for Approval' WHEN 'A2' THEN 'Waiting for Approval' WHEN 'F1' THEN 'Waiting for Approval' WHEN 'F2' THEN 'Waiting for Approval' WHEN 'F3' THEN 'Waiting for Approval' WHEN 'F4' THEN 'Waiting for Approval' WHEN 'F5' THEN 'Waiting for Approval' WHEN 'F6' THEN 'Waiting for Approval' WHEN 'GO1' THEN 'Waiting for Approval' WHEN 'GO2' THEN 'Waiting for Approval' WHEN 'GO3' THEN 'Waiting for Approval' WHEN 'A3' THEN 'Waiting for Approval' WHEN 'A4' THEN 'Waiting for Approval' WHEN 'DN' THEN 'Waiting for Approval' WHEN 'AN' THEN 'Waiting for Approval' WHEN 'SN' THEN 'Waiting for Approval' WHEN 'N2' THEN 'Waiting for Approval' WHEN 'N1' THEN 'Waiting for Approval' WHEN 'N3' THEN 'Waiting for Approval' WHEN 'MN' THEN 'Waiting for Approval' WHEN 'RO' THEN 'Waiting for Approval' WHEN 'R1' THEN 'Waiting for Approval' WHEN 'RM1' THEN 'Waiting for Approval' WHEN 'R11' THEN 'Application Rejected' WHEN 'R2' THEN 'Waiting for Approval' WHEN 'R3' THEN 'Waiting for Approval' WHEN 'R4' THEN 'Waiting for Approval' ELSE NULL  END AS REQUEST_status, PLACE_OF_EVENT from TNEA_JALLIKATTU_EVENT_REGISTERATION_T where request_status NOT IN('N','N1','N3','DN','RS','DNN','R1', 'RM1') and FORWARD_TO IS NOT NULL AND STATUS IN ('Y') and created_by=:app_employee_id;";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':app_employee_id', $app_employee_id, PDO::PARAM_INT);
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
