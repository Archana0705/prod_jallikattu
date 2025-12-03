<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');
require_once('../../helper/bull_approved_send_sms.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $mobile_numbers = isset($_POST['mobile_no']) ? $_POST['mobile_no'] : null;
    $event_place = isset($_POST['event_place']) ? $_POST['event_place'] : null;
    $eventDate = isset($_POST['event_date']) ? $_POST['event_date'] : null;
    if (empty($user_id) || empty($mobile_numbers) ||  empty($event_place)) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields: user_id or mobile_no or event place"]);
        die();
    }

    try {
        $mobile_array = array_filter(array_map('trim', explode(',', $mobile_numbers))); 
        $R_status = 'AB';

        $jk_write_db->beginTransaction();

        foreach ($mobile_array as $mobile_no) {
            $sql = "UPDATE TNEA_JALLIKATTU_BULL_EVENT_T
                    SET Request_status = :Request_status
                    WHERE MOBILE = :Mobile";

            $stmt = $jk_write_db->prepare($sql);
            $stmt->bindParam(':Request_status', $R_status);
            $stmt->bindParam(':Mobile', $mobile_no);
            if (!$stmt->execute()) {
                throw new Exception("Failed to update mobile number: $mobile_no");
            }
        
            if (!sendSMS($mobile_no, $event_place, $eventDate)) {
                throw new Exception("Failed to send SMS to mobile number: $mobile_no");
            }
        }

        $jk_write_db->commit();

        http_response_code(200);
        echo json_encode([
            "success" => 1,
            "message" => "Data successfully updated for the provided mobile numbers."
        ]);
    } catch (Exception $e) {
        $jk_write_db->rollBack();
        http_response_code(500);
        echo json_encode([
            "success" => 0,
            "message" => "Error updating data"
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
?>
