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
    $sql = "SELECT  P.S_NUMBER, P.DISTRICT_WISE_S_NO, P.EVENT_ID, P.DISTRICT, P.TALUK, P.EVENT_PLACE, P.GO_NUMBER, P.GO_DATE, P.PERMITTED_DATE, P.ACTUAL_DATE_OF_CONDUCT_EVENT, P.TYPE_OF_EVENT, P.NO_OF_BULLS_REGISTERED, P.NO_OF_BULLS_REJECTED, P.REASON_FOR_REJECTION, P.NO_OF_BULLS_PARTICIPATED, P.NO_OF_BULLS_INJURED, P.NO_OF_BULLS_DIED, P.NO_OF_PARTICIPANTS_REGISTERED, P.NO_OF_PARTICIPANTS_REJECTED, P.NO_OF_PARTICIPANTS_PERMITTED, P.NO_OF_PARTICIPANTS_INJURED_MAJOR, P.NO_OF_PARTICIPANTS_INJURED_MINOR, P.NO_OF_PARTICIPANTS_DIED, P.SPECTATORS_OWNERS_INJURED_MAJOR, P.SPECTATORS_OWNERS_INJURED_MINOR, P.SPECTATORS_OWNER_DIED, P.NUMBER_OF_POLICE_PERSONNEL_INJURED, P.NUMBER_OF_POLICE_DIED, P.FIR_No, P.FIR_REMARKS, P.FIR_ATTACHMENT, P.FIR_ATTACH_MIMETYPE, P.FIR_ATTACH_FILENAME, P.DEATH_REPORT_ATTACHMENT, P.DEATH_REPORT_ATTACH_MIMETYPE, P.DEATH_REPORT_ATTACH_FILENAME, P.STATUS, P.CREATED_BY, P.CREATED_DATE, P.UPDATED_BY, P.UPDATED_DATE from TNEA_JALLIKATTU_POST_EVENT_T P, TNEA_JALLIKATTU_APPROVER_LOGIN_T A WHERE P.DISTRICT=A.DISTRICT AND A.APPROVER_ID=:APP_EMPLOYEE_ID;";
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
