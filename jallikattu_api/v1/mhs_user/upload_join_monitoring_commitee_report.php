<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;

    $params = json_decode(file_get_contents('php://input'), true);

    if (empty($user_id) || empty($params['p_MONITORING_ID'])) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }
    $sql = "UPDATE TNEA_JALLIKATTU_DISTRICT_LEVEL_MONITORING_REGISTERATION_T SET   PARTICIPANTS =  :p_PARTICIPANTS, NUMBER_OF_BULLS = :p_NUMBER_OF_BULLS, PREEVENT_ARRANGEMENTS = :p_PREEVENT_ARRANGEMENTS, ARENA_SIZE = :p_ARENA_SIZE, ARENA_LENGTH = :p_ARENA_LENGTH, ARENA_BREATH = :p_ARENA_BREATH, BULL_RUN_AREA = :p_BULL_RUN_AREA, BULL_ARENA_LENGTH = :p_BULL_ARENA_LENGTH, BULL_ARENA_BREATH = :p_BULL_ARENA_BREATH, BULL_EXAMINATION_AREA_SIZE = :p_BULL_EXAMINATION_AREA_SIZE, BULL_EXAMINATION_LENGTH = :p_BULL_EXAMINATION_LENGTH, BULL_EXAMINATION_BREATH = :p_BULL_EXAMINATION_BREATH, JOIN_MONITORING_COMMITEE_REPORT = :p_JOIN_MONITORING_COMMITEE_REPORT, JOIN_ATTACH_MIMETYPE = :p_JOIN_ATTACH_MIMETYPE, JOIN_ATTACH_FILENAME = :p_JOIN_ATTACH_FILENAME, JOINT_REPORT_COMMENTS = :p_JOINT_REPORT_COMMENTS WHERE MONITORING_ID = :p_MONITORING_ID;";

    $bind = $jk_write_db->prepare($sql);

    foreach ($params as $key => $value) {
        $bind->bindValue(':' . $key, $value);
    }

    if ($bind->execute()) {
        http_response_code(200);
        $data = [
            "success" => 1,
            "message" => "Data successfully updated."
        ];
    } else {
        http_response_code(500);
        $data = [
            "success" => 0,
            "message" => "Error updating data."
        ];
    }

    echo json_encode($data);
    die();

} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
?>
