<?php
header("Access-Control-Allow-Origin: *");
require_once('../../helper/header.php');
require_once('../../helper/db/jk_write.php');
require_once('../../helper/db/jk_read.php');
require_once('../../helper/send_otp.php');
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

function generateOTP($n)
{
    $generator = "1357902468";
    $result = "";
    for ($i = 1; $i <= $n; $i++) {
        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }
    return $result;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
    exit;
}

$decryptedData = isset($_POST['data']) ? decryptData($_POST['data']) : null;
if (!isset($decryptedData)) {
    http_response_code(400);
    echo json_encode([
        "success" => 0,
        "message" => "Invalid input!",
    ]);
    exit;
}
$mobile_no = isset($decryptedData['mobile_no']) ? $decryptedData['mobile_no'] : null;
if (empty($mobile_no)) {
    http_response_code(400);
    echo json_encode(["success" => 0, "message" => "Mobile number is required."]);
    exit;
}
$generatedOtp = generateOTP(6);
$currentTimestamp = date('Y-m-d H:i:s');

try {
    // Check if mobile exists and get the otp_cttm
    $checkSql = "SELECT otp_cttm FROM tnea_Jallikattu_Otp_tb WHERE mobile = :mobile";
    $stmt = $jk_read_db->prepare($checkSql);
    $stmt->bindParam(':mobile', $mobile_no, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (!empty($row['otp_cttm'])) {
            $lastOtpTime = strtotime($row['otp_cttm']);
            $now = time();
            $diffInSeconds = $now - $lastOtpTime;

            if ($diffInSeconds < 120) {
                // Less than 2 minutes
                http_response_code(429);
                echo json_encode(["success" => 0, "message" => "Please wait for 2 minutes before requesting another OTP."]);
                exit;
            }
        }

        // Update OTP and timestamp
        $updateSql = "UPDATE tnea_Jallikattu_Otp_tb SET otp = :otp, otp_cttm = :cttm WHERE mobile = :mobile";
        $updt = $jk_write_db->prepare($updateSql);
        $updt->bindParam(':mobile', $mobile_no, PDO::PARAM_INT);
        $updt->bindParam(':otp', $generatedOtp, PDO::PARAM_INT);
        $updt->bindParam(':cttm', $currentTimestamp);
        if ($updt->execute()) {
            sendSMS($mobile_no, $generatedOtp);
            http_response_code(200);
            echo json_encode(["success" => 1, "message" => "OTP updated successfully"]);
        } else {
            throw new Exception("Failed to update OTP.");
        }
    } else {
        // Insert new OTP
        $insertSql = "INSERT INTO tnea_Jallikattu_Otp_tb (mobile, otp, otp_cttm) VALUES (:mobile, :otp, :cttm)";
        $stmt = $jk_write_db->prepare($insertSql);
        $stmt->bindParam(':mobile', $mobile_no, PDO::PARAM_INT);
        $stmt->bindParam(':otp', $generatedOtp, PDO::PARAM_INT);
        $stmt->bindParam(':cttm', $currentTimestamp);
        if ($stmt->execute()) {
            sendSMS($mobile_no, $generatedOtp);
            http_response_code(200);
            echo json_encode(["success" => 1, "message" => "OTP generated and saved"]);
        } else {
            throw new Exception("Failed to generate OTP.");
        }
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => 0, "message" => "A database error occurred."]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => 0, "message" => "An error occurred."]);
}
?>
