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
    $sql = "SELECT ROW_NUMBER() OVER () AS Sl_No, abc.* FROM (select E.EVENT_ID,E.EVENT_DATE,E.NAME_OF_ORGANIZATION,E.COMMITEE_REGISTER_NUMBER,E.NAME_OF_president,E.CONTACT_OF_PRESIDENT,E.MOBILE_OF_PRESIDENT,E.NAME_OF_VILLAGE,E.NAME_OF_TALUK,E.NAME_OF_DISTRICT,e.PLACE_OF_EVENT,E.PINCODE, CASE WHEN e.REQUEST_STATUS = 'E' THEN 'Save'WHEN e.REQUEST_STATUS = 'S' THEN 'Pending for Approval'WHEN e.REQUEST_STATUS = 'C' THEN 'Closed'WHEN e.REQUEST_STATUS = 'D1' THEN 'District Collector Approved for Inspection'WHEN e.REQUEST_STATUS = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval'WHEN e.REQUEST_STATUS = 'DNN' THEN 'Need more Information - District Collector'WHEN e.REQUEST_STATUS = 'N' THEN 'Need more Information'WHEN e.REQUEST_STATUS = 'A' THEN 'Need to upload GO from Additional Chief Secretary'WHEN e.REQUEST_STATUS = 'A1' THEN 'Waiting for District Collector Approval'WHEN e.REQUEST_STATUS = 'A2' THEN 'Waiting for DAH Approval'WHEN e.REQUEST_STATUS = 'F1' THEN 'Waiting for DAH Approval'WHEN e.REQUEST_STATUS = 'F2' THEN 'Waiting for DAH Approval'WHEN e.REQUEST_STATUS = 'F3' THEN 'Waiting for DAH Approval'WHEN e.REQUEST_STATUS = 'F4' THEN 'Waiting for DAH Approval'WHEN e.REQUEST_STATUS = 'F5' THEN 'Waiting for DAH Approval'WHEN e.REQUEST_STATUS = 'F6' THEN 'Waiting for DAH Approval'WHEN e.REQUEST_STATUS = 'GO1' THEN 'GO Issued by Government'WHEN e.REQUEST_STATUS = 'GO2' THEN 'Go Issued, Waiting for Final/Pre-Event Inspection'WHEN e.REQUEST_STATUS = 'GO3' THEN 'Go Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval'WHEN e.REQUEST_STATUS = 'A3' THEN 'Waiting for Additional Chief Secretary Approval'WHEN e.REQUEST_STATUS = 'A4' THEN 'Principal Additional Chief Secretary Approved'WHEN e.REQUEST_STATUS = 'DN' THEN 'Need more information from District Collector'WHEN e.REQUEST_STATUS = 'AN' THEN 'Need more Information'WHEN e.REQUEST_STATUS = 'SN' THEN 'Resubmitted from Event Organizer'WHEN e.REQUEST_STATUS = 'N2' THEN 'Need more Information'WHEN e.REQUEST_STATUS = 'N1' THEN 'Need more Information'WHEN e.REQUEST_STATUS = 'N3' THEN 'Need more Information'WHEN e.REQUEST_STATUS = 'MN' THEN 'Need more Information'WHEN e.REQUEST_STATUS = 'RO' THEN 'Application Resubmitted from Event Organizer'WHEN e.REQUEST_STATUS = 'R1' THEN 'DC Rejected Based on JMC Report'WHEN e.REQUEST_STATUS = 'RM1' THEN 'REJECTED BY MHS'WHEN e.REQUEST_STATUS = 'R11' THEN 'DC Not permitted the Event'WHEN e.REQUEST_STATUS = 'R2' THEN 'Rejected'WHEN e.REQUEST_STATUS = 'R3' THEN 'Rejected'WHEN e.REQUEST_STATUS = 'R4' THEN 'Rejected' END AS REQUEST_STATUS,E.REQUEST_LETTER,E.ATTACH_MIMETYPE,E.ATTACH_FILENAME,E.ASSURANCE_BOND,E.ASSURANCE_BOND_ATTACH_MIMETYPE,E.ASSURANCE_BOND_ATTACH_FILENAME,E.INSURANCE_COPY,E.INSURANCE_COPY_ATTACH_MIMETYPE,E.INSURANCE_COPY_ATTACH_FILENAME,E.LAYOUT_SKETCH,E.LAYOUT_SKETCH_ATTACH_MIMETYPE,E.LAYOUT_SKETCH_ATTACH_FILENAME,E.OTHER_DOCS,E.OTHER_DOCS_ATTACH_MIMETYPE,E.OTHER_DOCS_ATTACH_FILENAME,E.PREVIOUS_EVENT,E.PREVIOUS_EVENT_ATTACH_MIMETYPE,E.PREVIOUS_EVENT_ATTACH_FILENAME,E.PANCHAYAT_UNION,E.PANCHAYAT_UNION_ATTACH_MIMETYPE,E.PANCHAYAT_UNION_ATTACH_FILENAME,E.FIR,E.FIR_ATTACH_MIMETYPE,E.FIR_ATTACH_FILENAME,E.UPLOAD_PHOTO,E.UPLOAD_PHOTO_ATTACH_MIMETYPE,E.UPLOAD_PHOTO_ATTACH_FILENAME, CASE TRIM(E.forward_to) WHEN 'Magesterial huzur sharishthadhar MHS' THEN 'Magesterial huzur sharishthadhar MHS' WHEN 'District Collector' THEN 'District Collector' WHEN 'DAH & VS' THEN 'DAH & VS' WHEN 'ACS & PS' THEN 'ACS & PS' ELSE NULL END AS pending_with,E.EVENT_TYPE,a.name,d.MONITORING_ID, CASE WHEN E.REQUEST_status = 'A' THEN 'Click To Upload GO' END AS GO,d.JOIN_MONITORING_COMMITEE_REPORT,d.JOIN_ATTACH_MIMETYPE,d.JOIN_ATTACH_FILENAME,d.ARENA_UPLOAD,d.ARENA_UPLOAD_MIME_TYPE,d.ARENA_UPLOAD_FILENAME,d.LENGTH_ARENA_UPLOAD,d.LENGTH_ARENA_UPLOAD_MIME_TYPE,d.LENGTH_ARENA_UPLOAD_FILENAME,d.BULL_ARENA_UPLOAD,d.BULL_ARENA_UPLOAD_MIME_TYPE,d.BULL_ARENA_UPLOAD_FILENAME,d.BULL_EXAMINATION_UPLOAD,d.BULL_EXAMINATION_UPLOAD_MIME_TYPE,d.BULL_EXAMINATION_UPLOAD_FILENAME FROM  TNEA_JALLIKATTU_EVENT_REGISTERATION_T E, TNEA_JALLIKATTU_APPROVER_LOGIN_T A, TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T D WHERE TRIM(A.ROLE) = 'Principal Secretary/Additional Chief Secretary' AND E.EVENT_ID = D.EVENT_ID AND E.REQUEST_STATUS IN ('A') AND E.REQUEST_STATUS NOT IN ('GO1')  AND   A.APPROVER_ID=:APP_EMPLOYEE_ID ) abc;";
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
