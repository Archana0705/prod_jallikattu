<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);

    if (empty($params['DC_COMMENTS']) || empty($params['EVENT_ID']) || empty($params['user_id'])) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }
    $fwdTo = 'Magesterial huzur sharishthadhar MHS';
    $R_status = 'DN';
    $sql = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T SET Request_status = :Request_status, forward_to = :forward, DC_COMMENTS = :DC_COMMENTS WHERE EVENT_ID = :EVENT_ID";
    $stmt = $jk_write_db->prepare($sql);
    $stmt->bindParam(':Request_status', $R_status);
    $stmt->bindParam(':forward', $fwdTo);
    $stmt->bindParam(':DC_COMMENTS', $params['DC_COMMENTS']);
    $stmt->bindParam(':EVENT_ID', $params['EVENT_ID']);

    if ($stmt->execute()) {
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
    $data = ["success" => 0, "message" => "Method Not Allowed"];
    echo json_encode($data);
    die();
}
?>
