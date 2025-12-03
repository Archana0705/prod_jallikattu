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
    $sql = "SELECT DISTINCT e.EVENT_ID,e.EVENT_DATE,e.NAME_OF_ORGANIZATION,e.COMMITEE_REGISTER_NUMBER,e.NAME_OF_president,e.CONTACT_OF_PRESIDENT,e.MOBILE_OF_PRESIDENT,e.NAME_OF_VILLAGE,e.NAME_OF_TALUK,e.NAME_OF_DISTRICT,e.PLACE_OF_EVENT,e.PINCODE, CASE REQUEST_status WHEN 'E' THEN 'Save' WHEN 'S' THEN 'Pending for Approval' WHEN 'C' THEN 'Closed' WHEN 'D1' THEN 'District Collector Approved for Inspection' WHEN 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval' WHEN 'DNN' THEN 'Need more Information - District Collector' WHEN 'N' THEN 'Need more Information' WHEN 'A' THEN 'Need to upload GO from Secretariat' WHEN 'A1' THEN 'Waiting for District Collector Approval' WHEN 'A2' THEN 'Waiting for DAH Approval' WHEN 'F1' THEN 'Waiting for DAH Approval' WHEN 'F2' THEN 'Waiting for DAH Approval' WHEN 'F3' THEN 'Waiting for DAH Approval' WHEN 'F4' THEN 'Waiting for DAH Approval' WHEN 'F5' THEN 'Waiting for DAH Approval' WHEN 'F6' THEN 'Waiting for DAH Approval' WHEN 'GO1' THEN 'GO Issued from Secretariat' WHEN 'GO2' THEN 'Go Issued, Waiting for Final/Pre-Event Inspection' WHEN 'GO3' THEN 'Go Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval' WHEN 'A3' THEN 'Waiting for Principal Secretary Approval' WHEN 'A4' THEN 'Principal Secretary Approved' WHEN 'DN' THEN 'Need more information from District Collector' WHEN 'AN' THEN 'Need more Information' WHEN 'SN' THEN 'Resubmitted from Event Organizer' WHEN 'N2' THEN 'Need more Information' WHEN 'N1' THEN 'Need more Information' WHEN 'N3' THEN 'Need more Information' WHEN 'MN' THEN 'Need more Information' WHEN 'RO' THEN 'Application Resubmitted from Event Organizer' WHEN 'R1' THEN 'DC Rejected Based on JMC Report' WHEN 'RM1' THEN 'REJECTED BY MHS' WHEN 'R11' THEN 'DC Not permitted the Event' WHEN 'R2' THEN 'Rejected' WHEN 'R3' THEN 'Rejected' WHEN 'R4' THEN 'Rejected' ELSE REQUEST_status END AS REQUEST_status,e.REQUEST_LETTER,e.ATTACH_MIMETYPE,e.ATTACH_FILENAME,e.ASSURANCE_BOND,e.ASSURANCE_BOND_ATTACH_MIMETYPE,e.ASSURANCE_BOND_ATTACH_FILENAME,e.INSURANCE_COPY,e.INSURANCE_COPY_ATTACH_MIMETYPE,e.INSURANCE_COPY_ATTACH_FILENAME,e.LAYOUT_SKETCH,e.LAYOUT_SKETCH_ATTACH_MIMETYPE,e.LAYOUT_SKETCH_ATTACH_FILENAME,e.OTHER_DOCS,e.OTHER_DOCS_ATTACH_MIMETYPE,e.OTHER_DOCS_ATTACH_FILENAME,e.PREVIOUS_EVENT,e.PREVIOUS_EVENT_ATTACH_MIMETYPE,e.PREVIOUS_EVENT_ATTACH_FILENAME,e.PANCHAYAT_UNION,e.PANCHAYAT_UNION_ATTACH_MIMETYPE,e.PANCHAYAT_UNION_ATTACH_FILENAME,e.FIR,e.FIR_ATTACH_MIMETYPE,e.FIR_ATTACH_FILENAME,e.UPLOAD_PHOTO,e.UPLOAD_PHOTO_ATTACH_MIMETYPE,e.UPLOAD_PHOTO_ATTACH_FILENAME, CASE TRIM(e.forward_to)WHEN 'Magesterial huzur sharishthadhar MHS' THEN 'Magesterial huzur sharishthadhar MHS'WHEN 'District Collector' THEN 'District Collector'WHEN 'DAH & VS' THEN 'DAH & VS'WHEN 'ACS & PS' THEN 'ACS & PS'ELSE e.forward_to END AS pending_with,e.mhs_COMMENTS,e. EVENT_TYPE,a.name,d.ARENA_UPLOAD,d.ARENA_UPLOAD_MIME_TYPE,d.ARENA_UPLOAD_FILENAME,d.LENGTH_ARENA_UPLOAD,d.LENGTH_ARENA_UPLOAD_MIME_TYPE,d.LENGTH_ARENA_UPLOAD_FILENAME,d.BULL_ARENA_UPLOAD,d.BULL_ARENA_UPLOAD_MIME_TYPE,d.BULL_ARENA_UPLOAD_FILENAME,d.BULL_EXAMINATION_UPLOAD,d.BULL_EXAMINATION_UPLOAD_MIME_TYPE,d.BULL_EXAMINATION_UPLOAD_FILENAME,e.JSECTION_REMARKS,d.JOIN_MONITORING_COMMITEE_REPORT,d.JOIN_ATTACH_MIMETYPE,d.JOIN_ATTACH_FILENAME from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e,TNEA_JALLIKATTU_APPROVER_LOGIN_T a,TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T D where trim(a.role) IN ('Assistant Director ( J Section)')  AND E.EVENT_ID=D.EVENT_ID and e.request_status in ('F3') and A.APPROVER_ID=:APP_EMPLOYEE_ID;";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':APP_EMPLOYEE_ID', $app_employee_id);
    if ($stmt->execute()) {
        $data_result_count = $stmt->rowCount();
        if ($data_result_count > 0) {
            $data_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data_result = array_map(fn($item) => array_filter($item, fn($value) => gettype($value) !== 'resource'), $data_result);
            http_response_code(200);
            $data = array("success" => 1, "message" => "Data found", "data" => $data_result);
        } else {
            http_response_code(200);
            $data = array("success" => 2, "message" => "No data Found");
           
        }
        echo json_encode($data);
        die();
    }else {
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
