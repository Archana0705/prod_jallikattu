<?php
require_once('../../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $app_employee_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;   
    if($app_employee_id === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "User does not exist");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT ROW_NUMBER() OVER () AS rownum, C.* FROM (select S_NUMBER, DISTRICT_WISE_S_NO, EVENT_ID, DISTRICT, TALUK, EVENT_PLACE, GO_NUMBER, GO_DATE, PERMITTED_DATE, ACTUAL_DATE_OF_CONDUCT_EVENT, TYPE_OF_EVENT, NO_OF_BULLS_REGISTERED, NO_OF_BULLS_REJECTED, REASON_FOR_REJECTION, NO_OF_BULLS_PARTICIPATED, NO_OF_BULLS_INJURED, NO_OF_BULLS_DIED, NO_OF_PARTICIPANTS_REGISTERED, NO_OF_PARTICIPANTS_REJECTED, NO_OF_PARTICIPANTS_PERMITTED, NO_OF_PARTICIPANTS_INJURED_MAJOR, NO_OF_PARTICIPANTS_INJURED_MINOR, NO_OF_PARTICIPANTS_DIED, SPECTATORS_OWNERS_INJURED_MAJOR, SPECTATORS_OWNERS_INJURED_MINOR, SPECTATORS_OWNER_DIED, FIR_No, FIR_REMARKS, FIR_ATTACHMENT, FIR_ATTACH_MIMETYPE, FIR_ATTACH_FILENAME, DEATH_REPORT_ATTACHMENT, DEATH_REPORT_ATTACH_MIMETYPE, DEATH_REPORT_ATTACH_FILENAME, DEATH_REPORT_ATTACH_LAST_UPDATE, DEATH_REPORT_ATTACH_CHARSET, STATUS, CREATED_BY, CREATED_DATE, UPDATED_BY, UPDATED_DATE, NUMBER_OF_POLICE_PERSONNEL_INJURED, NUMBER_OF_POLICE_DIED from TNEA_JALLIKATTU_POST_EVENT_T) C;";
    $stmt = $jk_read_db->prepare($sql);
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
