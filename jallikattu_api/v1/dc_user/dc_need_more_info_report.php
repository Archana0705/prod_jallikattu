<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $app_employee_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;   
    if($app_employee_id === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "User id not exist");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT L.EVENT_ID,L.EVENT_DATE,L.NAME_OF_ORGANIZATION,L.COMMITEE_REGISTER_NUMBER,L.NAME_OF_president,L.CONTACT_OF_PRESIDENT,L.MOBILE_OF_PRESIDENT,L.NAME_OF_VILLAGE,L.NAME_OF_TALUK,L.NAME_OF_DISTRICT,L.PLACE_OF_EVENT, CASE  WHEN L.REQUEST_status = 'E' THEN 'Save' WHEN L.REQUEST_status = 'S' THEN 'Pending for Approval' WHEN L.REQUEST_status = 'C' THEN 'Closed' WHEN L.REQUEST_status = 'D1' THEN 'District Collector Approved for Inspection' WHEN L.REQUEST_status = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval' WHEN L.REQUEST_status = 'DNN' THEN 'Need more Information - District Collector' WHEN L.REQUEST_status = 'N' THEN 'Need more Information' WHEN L.REQUEST_status = 'A' THEN 'Need to upload GO from Secretariat' WHEN L.REQUEST_status = 'A1' THEN 'Waiting for District Collector Approval' WHEN L.REQUEST_status = 'A2' THEN 'Waiting for DAH Approval' WHEN L.REQUEST_status = 'F1' THEN 'Waiting for DAH Approval' WHEN L.REQUEST_status = 'F2' THEN 'Waiting for DAH Approval' WHEN L.REQUEST_status = 'F3' THEN 'Waiting for DAH Approval' WHEN L.REQUEST_status = 'F4' THEN 'Waiting for DAH Approval' WHEN L.REQUEST_status = 'F5' THEN 'Waiting for DAH Approval' WHEN L.REQUEST_status = 'F6' THEN 'Waiting for DAH Approval' WHEN L.REQUEST_status = 'GO1' THEN 'GO Issued from Secretariat' WHEN L.REQUEST_status = 'GO2' THEN 'Go Issued, Waiting for Final/Pre-Event Inspection' WHEN L.REQUEST_status = 'GO3' THEN 'Go Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval' WHEN L.REQUEST_status = 'A3' THEN 'Waiting for Principal Secretary Approval' WHEN L.REQUEST_status = 'A4' THEN 'Principal Secretary Approved' WHEN L.REQUEST_status = 'DN' THEN 'Need more information from District Collector' WHEN L.REQUEST_status = 'AN' THEN 'Need more Information' WHEN L.REQUEST_status = 'SN' THEN 'Resubmitted from Event Organizer' WHEN L.REQUEST_status = 'N2' THEN 'Need more Information' WHEN L.REQUEST_status = 'N1' THEN 'Need more Information' WHEN L.REQUEST_status = 'N3' THEN 'Need more Information' WHEN L.REQUEST_status = 'MN' THEN 'Need more Information' WHEN L.REQUEST_status = 'RO' THEN 'Application Resubmitted from Event Organizer' WHEN L.REQUEST_status = 'R1' THEN 'DC Rejected Based on JMC Report' WHEN L.REQUEST_status = 'RM1' THEN 'REJECTED BY MHS' WHEN L.REQUEST_status = 'R11' THEN 'DC Not permitted the Event' WHEN L.REQUEST_status = 'R2' THEN 'Rejected' WHEN L.REQUEST_status = 'R3' THEN 'Rejected' WHEN L.REQUEST_status = 'R4' THEN 'Rejected' END AS REQUEST_status,L.PINCODE,l.REQUEST_LETTER,l.ATTACH_MIMETYPE,l.ATTACH_FILENAME,l.ASSURANCE_BOND,l.ASSURANCE_BOND_ATTACH_MIMETYPE,l.ASSURANCE_BOND_ATTACH_FILENAME,l.INSURANCE_COPY,l.INSURANCE_COPY_ATTACH_MIMETYPE,l.INSURANCE_COPY_ATTACH_FILENAME,l.LAYOUT_SKETCH,l.LAYOUT_SKETCH_ATTACH_MIMETYPE,l.LAYOUT_SKETCH_ATTACH_FILENAME,l.OTHER_DOCS,l.OTHER_DOCS_ATTACH_MIMETYPE,l.OTHER_DOCS_ATTACH_FILENAME,l.PREVIOUS_EVENT,l.PREVIOUS_EVENT_ATTACH_MIMETYPE,l.PREVIOUS_EVENT_ATTACH_FILENAME,l.PANCHAYAT_UNION,l.PANCHAYAT_UNION_ATTACH_MIMETYPE,l.PANCHAYAT_UNION_ATTACH_FILENAME,l.FIR,l.FIR_ATTACH_MIMETYPE,l.FIR_ATTACH_FILENAME,l.UPLOAD_PHOTO,l.UPLOAD_PHOTO_ATTACH_MIMETYPE,l.UPLOAD_PHOTO_ATTACH_FILENAME, CASE  WHEN TRIM(L.forward_to) = 'Magesterial huzur sharishthadhar MHS' THEN 'Magesterial huzur sharishthadhar MHS' WHEN TRIM(L.forward_to) = 'District Collector' THEN 'District Collector' WHEN TRIM(L.forward_to) = 'DAH & VS' THEN 'DAH & VS' WHEN TRIM(L.forward_to) = 'ACS & PS' THEN 'ACS & PS' END AS pending_with, d.ARENA_UPLOAD, d.ARENA_UPLOAD_MIME_TYPE, d.ARENA_UPLOAD_FILENAME, d.LENGTH_ARENA_UPLOAD, d.LENGTH_ARENA_UPLOAD_MIME_TYPE, d.LENGTH_ARENA_UPLOAD_FILENAME, d.BULL_ARENA_UPLOAD, d.BULL_ARENA_UPLOAD_MIME_TYPE, d.BULL_ARENA_UPLOAD_FILENAME, d.BULL_EXAMINATION_UPLOAD, d.BULL_EXAMINATION_UPLOAD_MIME_TYPE, d.BULL_EXAMINATION_UPLOAD_FILENAME, L.MHS_COMMENTS, L. EVENT_TYPE, L.ASSISTANT_REMARKS, L.DAH_VS_COMMENTS, L.ACS_PS_COMMENTS, a.name, A.DISTRICT, L.PROCEED_COMMENTS from  TNEA_JALLIKATTU_EVENT_REGISTERATION_T L, TNEA_JALLIKATTU_APPROVER_LOGIN_T a,TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T D where a.role='District Collector'AND UPPER(L.NAME_OF_DISTRICT) = UPPER(A.DISTRICT) AND L.EVENT_ID=D.EVENT_ID and L.request_status in ('AN','NM','NMM','R3')  and a.approver_id=:APP_EMPLOYEE_ID;";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':APP_EMPLOYEE_ID', $app_employee_id, PDO::PARAM_INT);
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
