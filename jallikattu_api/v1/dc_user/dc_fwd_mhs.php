<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $comments = isset($_POST['comments']) ? $_POST['comments'] : null;
    $event_id = isset($_POST['event_id']) ? $_POST['event_id'] : null;
   
    if (empty($user_id) || empty($comments) || empty($event_id)) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }

    $fwdTo = 'Magesterial huzur sharishthadhar MHS';
    $R_status = 'D1';
    $sql = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T SET Request_status = :Request_status, forward_to = :forward, DC_COMMENTS = :DC_COMMENTS, UPDATED_BY = :APP_EMPLOYEE_ID WHERE EVENT_ID = :EVENT_ID";
    $stmt = $jk_write_db->prepare($sql);
    $stmt->bindParam(':forward', $fwdTo);
    $stmt->bindParam(':Request_status', $R_status);
    $stmt->bindParam(':DC_COMMENTS',$comments);
    $stmt->bindParam(':APP_EMPLOYEE_ID', $user_id);
    $stmt->bindParam(':EVENT_ID', $event_id);

    
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
