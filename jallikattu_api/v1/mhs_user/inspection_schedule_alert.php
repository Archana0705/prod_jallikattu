<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
require_once('../../helper/send_preInspection_sms.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $district = isset($_POST['district']) ? $_POST['district'] : null;
    $mobileNumbers = isset($_POST['mobile_number']) ? json_decode($_POST['mobile_number'], true) : null;
    $eventPlace = isset($_POST['event_place']) ? $_POST['event_place'] : null;
    $inspectionDate = isset($_POST['inspection_date']) ? $_POST['inspection_date'] : null;

    if (empty($district) || empty($mobileNumbers) || empty($eventPlace) || empty($inspectionDate)) {
        http_response_code(400);
        echo json_encode([
            "success" => 0,
            "message" => "Missing or invalid parameters"
        ]);
        die();
    }

    if (!is_array($mobileNumbers)) {
        http_response_code(400);
        echo json_encode([
            "success" => 0,
            "message" => "Invalid format for mobile numbers"
        ]);
        die();
    }

    function sendSMSToNumbers($mobileNumbers, $district, $eventPlace, $inspectionDate) {
        $successCount = 0;
        $failedCount = 0;

        foreach ($mobileNumbers as $mobileNumber) {
            $result = sendSMS($mobileNumber, $district, $eventPlace, $inspectionDate);

            if ($result) {
                $successCount++;
                error_log("SMS sent successfully to: $mobileNumber");
            } else {
                $failedCount++;
                error_log("Failed to send SMS to: $mobileNumber");
            }
        }

        return [
            'success_count' => $successCount,
            'failed_count' => $failedCount,
        ];
    }

    $result = sendSMSToNumbers($mobileNumbers, $district, $eventPlace, $inspectionDate);
    http_response_code(200);
    echo json_encode([
        "success" => 1,
        "message" => "SMS processing completed",
        "data" => $result
    ]);
} else {
    http_response_code(405);
    echo json_encode([
        "success" => 0,
        "message" => "Method Not Allowed"
    ]);
    die();
}
?>
