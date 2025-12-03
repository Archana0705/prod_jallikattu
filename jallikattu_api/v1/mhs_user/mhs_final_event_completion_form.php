<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);

    if (empty($params['p_EVENT_ID'])) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }

    $sql = "INSERT INTO TNEA_JALLIKATTU_POST_EVENT_T (EVENT_ID, DISTRICT, TALUK, EVENT_PLACE, GO_NUMBER, GO_DATE, PERMITTED_DATE, ACTUAL_DATE_OF_CONDUCT_EVENT, TYPE_OF_EVENT, NO_OF_BULLS_REGISTERED, NO_OF_BULLS_REJECTED, REASON_FOR_REJECTION, NO_OF_BULLS_PARTICIPATED, NO_OF_BULLS_INJURED, NO_OF_BULLS_DIED, NO_OF_PARTICIPANTS_REGISTERED, NO_OF_PARTICIPANTS_REJECTED, NO_OF_PARTICIPANTS_PERMITTED, NO_OF_PARTICIPANTS_INJURED_MAJOR, NO_OF_PARTICIPANTS_INJURED_MINOR, NO_OF_PARTICIPANTS_DIED, SPECTATORS_OWNERS_INJURED_MAJOR, SPECTATORS_OWNERS_INJURED_MINOR, SPECTATORS_OWNER_DIED, NUMBER_OF_POLICE_PERSONNEL_INJURED, NUMBER_OF_POLICE_DIED, FIR_No, FIR_REMARKS, FIR_ATTACHMENT, FIR_ATTACH_MIMETYPE, FIR_ATTACH_FILENAME, DEATH_REPORT_ATTACHMENT, DEATH_REPORT_ATTACH_MIMETYPE, DEATH_REPORT_ATTACH_FILENAME) values (:p_EVENT_ID, :p_DISTRICT, :p_TALUK, :p_EVENT_PLACE, :p_GO_NUMBER, :p_GO_DATE, :p_PERMITTED_DATE, :p_ACTUAL_DATE_OF_CONDUCT_EVENT, :p_TYPE_OF_EVENT, :p_NO_OF_BULLS_REGISTERED, :p_NO_OF_BULLS_REJECTED, :p_REASON_FOR_REJECTION, :p_NO_OF_BULLS_PARTICIPATED, :p_NO_OF_BULLS_INJURED, :p_NO_OF_BULLS_DIED, :p_NO_OF_PARTICIPANTS_REGISTERED, :p_NO_OF_PARTICIPANTS_REJECTED, :p_NO_OF_PARTICIPANTS_PERMITTED, :p_NO_OF_PARTICIPANTS_INJURED_MAJOR, :p_NO_OF_PARTICIPANTS_INJURED_MINOR, :p_NO_OF_PARTICIPANTS_DIED, :p_SPECTATORS_OWNERS_INJURED_MAJOR, :p_SPECTATORS_OWNERS_INJURED_MINOR, :p_SPECTATORS_OWNER_DIED, :p_NUMBER_OF_POLICE_PERSONAL_INJURED, :p_NUMBER_OF_POLICE_DIED, :p_FIR_No, :p_FIR_REMARKS, :p_FIR_ATTACHMENT, :p_FIR_ATTACH_MIMETYPE, :p_FIR_ATTACH_FILENAME, :p_DEATH_REPORT_ATTACHMENT, :p_DEATH_REPORT_ATTACH_MIMETYPE, :p_DEATH_REPORT_ATTACH_FILENAME);";

    $stmt = $jk_write_db->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }
    $result = $stmt->execute();
    http_response_code($result ? 200 : 500);
    $data = [
        "success" => $result ? 1 : 0,
        "message" => $result ? "Data successfully inserted." : "Error inserting data."
    ];
    echo json_encode($data);
    die();

} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
?>
