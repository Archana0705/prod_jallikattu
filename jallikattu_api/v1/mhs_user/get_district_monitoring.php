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
    $sql = "SELECT  d.MONITORING_ID, d.PARTICIPANTS, d.NUMBER_OF_BULLS, d.PREEVENT_ARRANGEMENTS,
    CASE WHEN e.REQUEST_status = 'E' THEN 'Save' WHEN e.REQUEST_status = 'S' THEN 'Pending for Approval' WHEN e.REQUEST_status = 'C' THEN 'Closed' WHEN e.REQUEST_status = 'D1' THEN 'District Collector Approved for Inspection' WHEN e.REQUEST_status = 'D2' THEN 'Pre-Inspection Completed, Waiting for District Collector Approval' WHEN e.REQUEST_status = 'DNN' THEN 'Need more Information - District Collector' WHEN e.REQUEST_status = 'N' THEN 'Need more Information' WHEN e.REQUEST_status = 'A' THEN 'Need to upload GO from Secretariat' WHEN e.REQUEST_status = 'A1' THEN 'Waiting for District Collector Approval' WHEN e.REQUEST_status = 'A2' THEN 'Waiting for DAH Approval' WHEN e.REQUEST_status IN ('F1', 'F2', 'F3', 'F4', 'F5', 'F6') THEN 'Waiting for DAH Approval' WHEN e.REQUEST_status = 'GO1' THEN 'GO Issued from Secretariat' WHEN e.REQUEST_status = 'GO2' THEN 'GO Issued, Waiting for Final/Pre-Event Inspection' WHEN e.REQUEST_status = 'GO3' THEN 'GO Issued, Final/Pre-Event Inspection Completed, Waiting for DC Approval' WHEN e.REQUEST_status = 'A3' THEN 'Waiting for Principal Secretary Approval' WHEN e.REQUEST_status = 'A4' THEN 'Principal Secretary Approved' WHEN e.REQUEST_status IN ('DN', 'AN', 'SN', 'N2', 'N1', 'N3', 'MN') THEN 'Need more Information' WHEN e.REQUEST_status = 'RO' THEN 'Application Resubmitted from Event Organizer' WHEN e.REQUEST_status = 'R1' THEN 'DC Rejected Based on JMC Report' WHEN e.REQUEST_status = 'RM1' THEN 'REJECTED BY MHS' WHEN e.REQUEST_status = 'R11' THEN 'DC Not permitted the Event' WHEN e.REQUEST_status IN ('R2', 'R3', 'R4') THEN 'Rejected'
    END AS REQUEST_status, d.ARENA_SIZE , d.ARENA_LENGTH, d.ARENA_BREATH, d.ARENA_UPLOAD, d.ARENA_UPLOAD_MIME_TYPE, d.ARENA_UPLOAD_FILENAME, d.LENGTH_ARENA, d.LENGTH_ARENA_UPLOAD, d.LENGTH_ARENA_UPLOAD_MIME_TYPE, d.LENGTH_ARENA_UPLOAD_FILENAME, d.BULL_RUN_AREA, d.BULL_ARENA_LENGTH, d.BULL_ARENA_BREATH, d.BULL_ARENA_UPLOAD, d.BULL_ARENA_UPLOAD_MIME_TYPE, d.BULL_ARENA_UPLOAD_FILENAME, d.BULL_EXAMINATION_AREA_SIZE, d.BULL_EXAMINATION_LENGTH, d.BULL_EXAMINATION_BREATH, d.BULL_EXAMINATION_UPLOAD, d.BULL_EXAMINATION_UPLOAD_MIME_TYPE, d.BULL_EXAMINATION_UPLOAD_FILENAME, d.STATUS, d.CREATED_BY, d.CREATED_DATE, d.UPDATED_BY, d.UPDATED_DATE, d.EVENT_ID, d.JOIN_MONITORING_COMMITEE_REPORT, d.JOIN_ATTACH_MIMETYPE, d.JOIN_ATTACH_FILENAME, e.dc_comments
    FROM TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d
    JOIN TNEA_JALLIKATTU_EVENT_REGISTERATION_T e ON e.EVENT_ID = d.EVENT_ID
    JOIN TNEA_JALLIKATTU_APPROVER_LOGIN_T a ON UPPER(e.NAME_OF_DISTRICT) = UPPER(a.DISTRICT)
    WHERE e.request_status = 'D1' AND TRIM(a.role) = 'Magesterial huzur sharishthadhar MHS' AND A.APPROVER_ID = :APP_EMPLOYEE_ID;";
    
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
