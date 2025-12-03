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
    $sql = "SELECT distinct  e.EVENT_ID, e.EVENT_DATE, e.NAME_OF_ORGANIZATION, e.COMMITEE_REGISTER_NUMBER, e.NAME_OF_president, e.CONTACT_OF_PRESIDENT, e.MOBILE_OF_PRESIDENT, e.NAME_OF_VILLAGE, e.NAME_OF_TALUK, e.NAME_OF_DISTRICT, e.PLACE_OF_EVENT, e.PINCODE,
    CASE  WHEN e.REQUEST_status = 'E' THEN 'Save' WHEN e.REQUEST_status = 'S' THEN 'Pending for Approval' WHEN e.REQUEST_status = 'C' THEN 'Closed' WHEN e.REQUEST_status = 'D1' THEN 'District Collector Approved for Inspection' WHEN e.REQUEST_status = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval' WHEN e.REQUEST_status = 'DNN' THEN 'Need more Information - District Collector' WHEN e.REQUEST_status = 'N' THEN 'Need more Information' WHEN e.REQUEST_status = 'A' THEN 'Need to upload GO from Secretariat' WHEN e.REQUEST_status = 'A1' THEN 'Waiting for District Collector Approval' WHEN e.REQUEST_status = 'A2' THEN 'Waiting for DAH Approval' WHEN e.REQUEST_status IN ('F1', 'F2', 'F3', 'F4', 'F5', 'F6') THEN 'Waiting for DAH Approval' WHEN e.REQUEST_status = 'GO1' THEN 'GO Issued from Secretariat' WHEN e.REQUEST_status = 'GO2' THEN 'Go Issued, Waiting for Final/Pre-Event Inspection' WHEN e.REQUEST_status = 'GO3' THEN 'Go Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval' WHEN e.REQUEST_status = 'A3' THEN 'Waiting for Principal Secretary Approval' WHEN e.REQUEST_status = 'A4' THEN 'Principal Secretary Approved' WHEN e.REQUEST_status IN ('DN', 'AN', 'SN', 'N2', 'N1', 'N3', 'MN') THEN 'Need more Information' WHEN e.REQUEST_status = 'RO' THEN 'Application Resubmitted from Event Organizer' WHEN e.REQUEST_status = 'R1' THEN 'DC Rejected Based on JMC Report' WHEN e.REQUEST_status = 'RM1' THEN 'REJECTED BY MHS' WHEN e.REQUEST_status = 'R11' THEN 'DC Not permitted the Event' WHEN e.REQUEST_status IN ('R2', 'R3', 'R4') THEN 'Rejected' END AS REQUEST_status, e.REQUEST_LETTER, e.ATTACH_MIMETYPE, e.ATTACH_FILENAME, e.ASSURANCE_BOND, e.ASSURANCE_BOND_ATTACH_MIMETYPE, e.ASSURANCE_BOND_ATTACH_FILENAME, e.INSURANCE_COPY, e.INSURANCE_COPY_ATTACH_MIMETYPE, e.INSURANCE_COPY_ATTACH_FILENAME, e.LAYOUT_SKETCH, e.LAYOUT_SKETCH_ATTACH_MIMETYPE, e.LAYOUT_SKETCH_ATTACH_FILENAME, e.OTHER_DOCS, ATTACH_MIMETYPE, ATTACH_FILENAME,e.PREVIOUS_EVENT, ATTACH_MIMETYPE, ATTACH_FILENAME, e.PANCHAYAT_UNION, ATTACH_MIMETYPE, ATTACH_FILENAME, e.FIR, ATTACH_MIMETYPE, ATTACH_FILENAME, e.UPLOAD_PHOTO, e.UPLOAD_PHOTO_ATTACH_MIMETYPE, e.UPLOAD_PHOTO_ATTACH_FILENAME, CASE TRIM(e.forward_to) WHEN 'Magesterial huzur sharishthadhar MHS' THEN 'Magesterial huzur sharishthadhar MHS' WHEN 'District Collector' THEN 'District Collector' WHEN 'DAH & VS' THEN 'DAH & VS' WHEN 'ACS & PS' THEN 'ACS & PS' END AS pending_with, e.mhs_COMMENTS, e. EVENT_TYPE, a.name, G.GO, G.GO_ATTACH_MIMETYPE, G.GO_ATTACH_FILENAME, D.JOIN_MONITORING_COMMITEE_REPORT, d.JOIN_ATTACH_MIMETYPE, d.JOIN_ATTACH_FILENAME from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e,TNEA_JALLIKATTU_APPROVER_LOGIN_T a,TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d,TNEA_JALLIKATTU_EVENT_GO_T G where TRIM(a.role)='Magesterial huzur sharishthadhar MHS'AND UPPER(e.NAME_OF_DISTRICT) = UPPER(A.DISTRICT) AND E.EVENT_ID=D.EVENT_ID AND G.EVENT_ID=E.EVENT_ID and  a.Approver_id = :APP_Employee_Id  AND e.request_status  in ('GO2')  and d.JOIN_MONITORING_COMMITEE_REPORT is NOT null;";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':APP_Employee_Id', $app_employee_id);
    if ($stmt->execute()) {
        $data_result_count = $stmt->rowCount();
        if ($data_result_count > 0) {
            $data_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            $data = array("success" => 1, "message" => "Data found", "data" => $data_result);
        } else {
            http_response_code(200);
            $data = array("success" => 2, "message" => "No data Found");
        }
        echo json_encode($data);
        die();
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
