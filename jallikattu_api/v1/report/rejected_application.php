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
    $sql = "SELECT l.EVENT_ID, l.EVENT_DATE, l.NAME_OF_ORGANIZATION, l.COMMITEE_REGISTER_NUMBER, l.NAME_OF_PRESIDENT, l.CONTACT_OF_PRESIDENT, l.MOBILE_OF_PRESIDENT, l.NAME_OF_VILLAGE, l.NAME_OF_TALUK, l.NAME_OF_DISTRICT, l.PINCODE, CASE l.REQUEST_status WHEN 'E' THEN 'Save' WHEN 'S' THEN 'Waiting for Approval' WHEN 'C' THEN 'Closed' WHEN 'D1' THEN 'Waiting for Approval' WHEN 'D2' THEN 'Waiting for Approval' WHEN 'DNN' THEN 'Waiting for Approval' WHEN 'N'  THEN 'Waiting for Approval' WHEN 'A'  THEN 'Waiting for Approval' WHEN 'A1' THEN 'Waiting for Approval' WHEN 'A2' THEN 'Waiting for Approval' WHEN 'F1' THEN 'Waiting for Approval' WHEN 'F2' THEN 'Waiting for Approval' WHEN 'F3' THEN 'Waiting for Approval' WHEN 'F4' THEN 'Waiting for Approval' WHEN 'F5' THEN 'Waiting for Approval' WHEN 'F6' THEN 'Waiting for Approval' WHEN 'GO1' THEN 'Waiting for Approval' WHEN 'GO2' THEN 'Waiting for Approval' WHEN 'GO3' THEN 'Waiting for Approval' WHEN 'A3' THEN 'Waiting for Approval' WHEN 'A4' THEN 'Waiting for Approval' WHEN 'DN' THEN 'Waiting for Approval' WHEN 'AN' THEN 'Waiting for Approval' WHEN 'SN' THEN 'Waiting for Approval' WHEN 'N2' THEN 'Waiting for Approval' WHEN 'N1' THEN 'Waiting for Approval' WHEN 'N3' THEN 'Waiting for Approval' WHEN 'MN' THEN 'Waiting for Approval' WHEN 'RO' THEN 'Waiting for Approval' WHEN 'R1' THEN 'Waiting for Approval' WHEN 'RM1' THEN 'Waiting for Approval' WHEN 'R11' THEN 'Application Rejected' WHEN 'R2' THEN 'Waiting for Approval' WHEN 'R3' THEN 'Waiting for Approval' WHEN 'R4' THEN 'Waiting for Approval' ELSE l.REQUEST_status END AS REQUEST_status, length(l.REQUEST_LETTER) AS REQUEST_LETTER, l.ATTACH_MIMETYPE, l.ATTACH_FILENAME, length(l.ASSURANCE_BOND) AS ASSURANCE_BOND, l.ASSURANCE_BOND_ATTACH_MIMETYPE, l.ASSURANCE_BOND_ATTACH_FILENAME,  length(l.INSURANCE_COPY) AS INSURANCE_COPY, l.INSURANCE_COPY_ATTACH_MIMETYPE, l.INSURANCE_COPY_ATTACH_FILENAME, length(l.LAYOUT_SKETCH) AS LAYOUT_SKETCH, l.LAYOUT_SKETCH_ATTACH_MIMETYPE, l.LAYOUT_SKETCH_ATTACH_FILENAME, COALESCE(length(l.OTHER_DOCS), 0) AS OTHER_DOCS, l.OTHER_DOCS_ATTACH_MIMETYPE, l.OTHER_DOCS_ATTACH_FILENAME, length(l.PREVIOUS_EVENT) AS PREVIOUS_EVENT, l.PREVIOUS_EVENT_ATTACH_MIMETYPE, l.PREVIOUS_EVENT_ATTACH_FILENAME, COALESCE(length(l.PANCHAYAT_UNION), 0) AS PANCHAYAT_UNION, l.PANCHAYAT_UNION_ATTACH_MIMETYPE, l.PANCHAYAT_UNION_ATTACH_FILENAME,COALESCE(length(l.FIR), 0) AS FIR, l.FIR_ATTACH_MIMETYPE, l.FIR_ATTACH_FILENAME, length(l.UPLOAD_PHOTO) AS UPLOAD_PHOTO, l.UPLOAD_PHOTO_ATTACH_MIMETYPE, l.UPLOAD_PHOTO_ATTACH_FILENAME, l.mhs_comments, l.EVENT_TYPE, l.place_of_event, COALESCE(length(d.ARENA_UPLOAD), 0) AS ARENA_UPLOAD, d.ARENA_UPLOAD_MIME_TYPE, d.ARENA_UPLOAD_FILENAME, COALESCE(length(d.LENGTH_ARENA_UPLOAD), 0) AS LENGTH_ARENA_UPLOAD, d.LENGTH_ARENA_UPLOAD_MIME_TYPE, d.LENGTH_ARENA_UPLOAD_FILENAME, COALESCE(length(d.BULL_ARENA_UPLOAD), 0) AS BULL_ARENA_UPLOAD, d.BULL_ARENA_UPLOAD_MIME_TYPE, d.BULL_ARENA_UPLOAD_FILENAME, COALESCE(length(d.BULL_EXAMINATION_UPLOAD), 0) AS BULL_EXAMINATION_UPLOAD, d.BULL_EXAMINATION_UPLOAD_MIME_TYPE, d.BULL_EXAMINATION_UPLOAD_FILENAME FROM TNEA_JALLIKATTU_EVENT_REGISTERATION_T l JOIN TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d ON l.EVENT_ID = d.EVENT_ID WHERE l.REQUEST_STATUS IN ('R1', 'N', 'AN', 'N1', 'N3', 'R2', 'R3', 'MN','RM1') AND l.STATUS = 'Y' AND l.FORWARD_TO = :app_employee_id;";
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
