<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);
    $mobileNumbers = isset($params['mobile_no']) ? $params['mobile_no'] : null;

    if (empty($mobileNumbers) || !is_array($mobileNumbers)) {
        http_response_code(400);
        echo json_encode([
            "success" => 0,
            "message" => "Invalid or missing mobile numbers."
        ]);
        die();
    }

    $R_status = 'came_for_event';
    $successCount = 0;
    $failureCount = 0;
    $failedNumbers = [];

    foreach ($mobileNumbers as $mobileNumber) {
        try {
            $sql = "UPDATE TNEA_JALLIKATTU_BULL_EVENT_T 
                    SET Request_status = :Request_status 
                    WHERE MOBILE = :p_Selected";
            $bind = $jk_write_db->prepare($sql);
            $bind->bindParam(':Request_status', $R_status);
            $bind->bindParam(':p_Selected', $mobileNumber);

            if ($bind->execute()) {
                $successCount++;
            } else {
                $failureCount++;
                $failedNumbers[] = $mobileNumber;
            }
        } catch (Exception $e) {
            $failureCount++;
            $failedNumbers[] = $mobileNumber;
            error_log("Error updating mobile number {$mobileNumber}" . $e->getMessage());
        }
    }

    if ($failureCount === 0) {
        http_response_code(200);
        $response = [
            "success" => 1,
            "message" => "All mobile numbers updated successfully.",
            "updated_count" => $successCount
        ];
    } else {
        http_response_code(207); 
        $response = [
            "success" => 0,
            "message" => "Some updates failed.",
            "updated_count" => $successCount,
            "failed_count" => $failureCount,
            "failed_numbers" => $failedNumbers
        ];
    }

    echo json_encode($response);
    die();
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
    die();
}
?>
