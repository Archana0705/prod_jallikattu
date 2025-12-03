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
    $sql = "SELECT e.event_id as eventid, e.event_type, e.EVENT_DATE, e.NAME_OF_ORGANIZATION, e.COMMITEE_REGISTER_NUMBER, e.NAME_OF_PRESIDENT, e.CONTACT_OF_PRESIDENT, e.MOBILE_OF_PRESIDENT, e.NAME_OF_VILLAGE, e.NAME_OF_TALUK, e.NAME_OF_DISTRICT,
    e.PLACE_OF_EVENT, e.PINCODE, e.mhs_comments, E.DAH_VS_COMMENTS, d.event_id, d.PARTICIPANTS, d.NUMBER_OF_BULLS, d.PREEVENT_ARRANGEMENTS, d.ARENA_SIZE, d.ARENA_LENGTH, d.ARENA_BREATH, d.ARENA_UPLOAD, d.ARENA_UPLOAD_MIME_TYPE, d.ARENA_UPLOAD_FILENAME, d.LENGTH_ARENA, d.LENGTH_ARENA_LENGTH, d.LENGTH_ARENA_BREATH, d.LENGTH_ARENA_UPLOAD, d.LENGTH_ARENA_UPLOAD_MIME_TYPE, d.LENGTH_ARENA_UPLOAD_FILENAME, d.BULL_RUN_AREA, d.BULL_ARENA_LENGTH, d.BULL_ARENA_BREATH, d.BULL_ARENA_UPLOAD, d.BULL_ARENA_UPLOAD_MIME_TYPE, d.BULL_ARENA_UPLOAD_FILENAME, d.BULL_EXAMINATION_AREA_SIZE, d.BULL_EXAMINATION_LENGTH, d.BULL_EXAMINATION_BREATH, d.BULL_EXAMINATION_UPLOAD, d.BULL_EXAMINATION_UPLOAD_MIME_TYPE, d.BULL_EXAMINATION_UPLOAD_FILENAME, d.RECOMMENTATION, d.RECOMMENTATION_COMMENTS, d.STATUS, d.CREATED_BY, d.CREATED_DATE, d.UPDATED_BY, d.UPDATED_DATE, D.JOIN_MONITORING_COMMITEE_REPORT, E.ASSISTANT_REMARKS, E.MANAGER_REMARKS, E.JSECTION_REMARKS,E.ASSISTANT_DIRECTOR_REMARKS,E.JOINT_REMARKS,E.ADDITIONAL_DIRECTOR_REMARKS FROM TNEA_JALLIKATTU_EVENT_REGISTERATION_T e, TNEA_JALLIKATTU_APPROVER_LOGIN_T a,TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T d WHERE TRIM(a.role) = 'Magesterial huzur sharishthadhar MHS' AND UPPER(e.NAME_OF_DISTRICT) = UPPER(a.DISTRICT) AND e.REQUEST_status IN ('GO1') and  e.event_id=d.event_id AND A.APPROVER_ID = :APP_EMPLOYEE_ID;";
    $stmt = $jk_read_db->prepare($sql);
    $stmt->bindParam(':APP_EMPLOYEE_ID', $app_employee_id, PDO::PARAM_INT);
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
