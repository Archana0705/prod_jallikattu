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
    $sql = "SELECT DISTINCT e.EVENT_ID,e.EVENT_DATE,e.NAME_OF_ORGANIZATION,e.COMMITEE_REGISTER_NUMBER,e.NAME_OF_president,e.CONTACT_OF_PRESIDENT,e.MOBILE_OF_PRESIDENT,e.NAME_OF_VILLAGE,e.NAME_OF_TALUK,e.NAME_OF_DISTRICT,e.PLACE_OF_EVENT,e.PINCODE, CASE WHEN e.REQUEST_STATUS = 'E' THEN 'Save'WHEN e.REQUEST_STATUS = 'S' THEN 'Pending for Approval'WHEN e.REQUEST_STATUS = 'C' THEN 'Closed'WHEN e.REQUEST_STATUS = 'D1' THEN 'District Collector Approved for Inspection'WHEN e.REQUEST_STATUS = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval'WHEN e.REQUEST_STATUS = 'DNN' THEN 'Need more Information - District Collector'WHEN e.REQUEST_STATUS = 'N' THEN 'Need more Information'WHEN e.REQUEST_STATUS = 'A' THEN 'Need to upload GO from Secretariat'WHEN e.REQUEST_STATUS = 'A1' THEN 'Waiting for District Collector Approval'WHEN e.REQUEST_STATUS = 'A2' THEN 'Waiting for DAH Approval'WHEN e.REQUEST_STATUS IN ('F1', 'F2', 'F3', 'F4', 'F5', 'F6') THEN 'Waiting for DAH Approval'WHEN e.REQUEST_STATUS = 'GO1' THEN 'GO Issued from Secretariat'WHEN e.REQUEST_STATUS = 'GO2' THEN 'GO Issued, Waiting for Final/Pre-Event Inspection'WHEN e.REQUEST_STATUS = 'GO3' THEN 'GO Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval'WHEN e.REQUEST_STATUS = 'A3' THEN 'Waiting for Principal Secretary Approval'WHEN e.REQUEST_STATUS = 'A4' THEN 'Principal Secretary Approved'WHEN e.REQUEST_STATUS IN ('DN', 'AN', 'SN', 'N2', 'N1', 'N3', 'MN') THEN 'Need more Information'WHEN e.REQUEST_STATUS = 'RO' THEN 'Application Resubmitted from Event Organizer'WHEN e.REQUEST_STATUS = 'R1' THEN 'DC Rejected Based on JMC Report'WHEN e.REQUEST_STATUS = 'RM1' THEN 'REJECTED BY MHS'WHEN e.REQUEST_STATUS = 'R11' THEN 'DC Not Permitted the Event'WHEN e.REQUEST_STATUS IN ('R2', 'R3', 'R4') THEN 'Rejected'ELSE 'Unknown'END AS REQUEST_STATUS,e.REQUEST_LETTER,e.ATTACH_MIMETYPE,e.ATTACH_FILENAME,e.ASSURANCE_BOND,e.ASSURANCE_BOND_ATTACH_MIMETYPE,e.ASSURANCE_BOND_ATTACH_FILENAME,e.INSURANCE_COPY,e.INSURANCE_COPY_ATTACH_MIMETYPE,e.INSURANCE_COPY_ATTACH_FILENAME,e.LAYOUT_SKETCH,e.LAYOUT_SKETCH_ATTACH_MIMETYPE,e.LAYOUT_SKETCH_ATTACH_FILENAME,e.OTHER_DOCS,e.OTHER_DOCS_ATTACH_MIMETYPE,e.OTHER_DOCS_ATTACH_FILENAME,e.PREVIOUS_EVENT,e.PREVIOUS_EVENT_ATTACH_MIMETYPE,e.PREVIOUS_EVENT_ATTACH_FILENAME,e.PANCHAYAT_UNION,e.PANCHAYAT_UNION_ATTACH_MIMETYPE,e.PANCHAYAT_UNION_ATTACH_FILENAME,e.FIR,e.FIR_ATTACH_MIMETYPE,e.FIR_ATTACH_FILENAME,e.UPLOAD_PHOTO,e.UPLOAD_PHOTO_ATTACH_MIMETYPE,e.UPLOAD_PHOTO_ATTACH_FILENAME, CASE WHEN TRIM(e.forward_to) = 'Magesterial huzur sharishthadhar MHS' THEN 'Magesterial huzur sharishthadhar MHS'WHEN TRIM(e.forward_to) = 'District Collector' THEN 'District Collector'WHEN TRIM(e.forward_to) = 'DAH & VS' THEN 'DAH & VS'WHEN TRIM(e.forward_to) = 'ACS & PS' THEN 'ACS & PS'ELSE 'Unknown' END AS pending_with,e.mhs_COMMENTS,e.EVENT_TYPE,a.name,d.ARENA_UPLOAD,d.ARENA_UPLOAD_MIME_TYPE,d.ARENA_UPLOAD_FILENAME,d.LENGTH_ARENA_UPLOAD,d.LENGTH_ARENA_UPLOAD_MIME_TYPE,d.LENGTH_ARENA_UPLOAD_FILENAME,d.BULL_ARENA_UPLOAD,d.BULL_ARENA_UPLOAD_MIME_TYPE,d.BULL_ARENA_UPLOAD_FILENAME,d.BULL_EXAMINATION_UPLOAD,d.BULL_EXAMINATION_UPLOAD_MIME_TYPE,d.BULL_EXAMINATION_UPLOAD_FILENAME,d.JOIN_MONITORING_COMMITEE_REPORT,d.JOIN_ATTACH_MIMETYPE,d.JOIN_ATTACH_FILENAME,e.JOINT_REMARKS FROM TNEA_JALLIKATTU_EVENT_REGISTERATION_T e , TNEA_JALLIKATTU_APPROVER_LOGIN_T a, TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d WHERE e.EVENT_ID = d.EVENT_ID AND TRIM(a.role) IN ('Additional Director')  AND e.REQUEST_STATUS IN ('F5') AND a.APPROVER_ID = :APP_EMPLOYEE_ID;";
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
