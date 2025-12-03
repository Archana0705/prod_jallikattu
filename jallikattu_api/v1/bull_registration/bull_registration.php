<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);
    $params['R_STATUS'] = 'Y';
    $requiredFields = ["p_BULL_AGE","p_BULL_DENTITION","p_BULLS_HORN_LENGTH","p_INTER_CORNUAL_LENGTH","p_BULL_LENGTH","p_BULLS_COLOR","p_BULLS_IDENTIFICATION_MARKS","p_VETERINARY_DISPENSARY","p_FWD","p_BULLS_MEDICAL_CERTIFICATE","p_BULLS_MEDICAL_CERTIFICATE_MIME_TYPE","p_BULLS_MEDICAL_CERTIFICATE_FILENAME","p_BULLS_OWNER","p_BULLS_OWNER_MIME_TYPE","p_BULLS_OWNER_FILENAME","p_ASSISTANT_NAME","p_ASSISTANT_MOBILE","p_CREATED_BY","Current_Timestamp","p_HOST_DISTRICT","p_HOST_VILLAGE","p_OWNERS_NAME","p_OWNERS_MAIL","p_OWNERS_MOBILE","p_OWNERS_AADHAR_NUMBER","p_AADHAR_FILE","p_AADHAR_FILENAME","p_AADHAR_FILE_MIMETYPE","p_STREET_NO","p_REVENUE_VILLAGE","p_TALUK","p_DISTRICT","p_PINCODE","p_BULL_LEFT_HORN_LENGTH","p_EVENT_ID","p_PLACE_OF_EVENT"];
    // ✅ Required field validation
    foreach ($requiredFields as $param) {
        if (!isset($params[$param]) || empty(trim($params[$param]))) {
            http_response_code(400);
            echo json_encode(["success" => 0, "message" => "Missing or empty required parameter: $param"]);
            exit;
        }
    }

    // ✅ Allowed MIME types and file extensions
    $allowedMimeTypes = ['image/png', 'image/jpeg', 'application/pdf'];
    $allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf'];

    $fileMimeFields = [
        'p_BULLS_MEDICAL_CERTIFICATE_MIME_TYPE',
        'p_BULLS_OWNER_MIME_TYPE',
        'p_AADHAR_FILE_MIMETYPE'
    ];

    $filenameFields = [
        'p_BULLS_MEDICAL_CERTIFICATE_FILENAME',
        'p_BULLS_OWNER_FILENAME',
        'p_AADHAR_FILENAME'
    ];

    // ✅ Validate MIME types
    foreach ($fileMimeFields as $mimeField) {
        if (!isset($params[$mimeField]) || !in_array($params[$mimeField], $allowedMimeTypes)) {
            http_response_code(400);
            echo json_encode(["success" => 0, "message" => "Invalid MIME type for $mimeField. Only PNG, JPG, JPEG, or PDF allowed."]);
            exit;
        }
    }

    // ✅ Validate file extensions
    foreach ($filenameFields as $field) {
        if (isset($params[$field])) {
            $ext = strtolower(pathinfo($params[$field], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExtensions)) {
                http_response_code(400);
                echo json_encode(["success" => 0, "message" => "Invalid file extension for $field. Only PNG, JPG, JPEG, or PDF allowed."]);
                exit;
            }
        }
    }

    // ✅ OPTIONAL: Match MIME type to extension
    $mimeExtensionMap = [
        'image/png' => ['png'],
        'image/jpeg' => ['jpg', 'jpeg'],
        'application/pdf' => ['pdf']
    ];

    foreach ($filenameFields as $index => $field) {
        $ext = strtolower(pathinfo($params[$field], PATHINFO_EXTENSION));
        $mimeField = $fileMimeFields[$index];
        $mime = $params[$mimeField] ?? '';

        if (!isset($mimeExtensionMap[$mime]) || !in_array($ext, $mimeExtensionMap[$mime])) {
            http_response_code(400);
            echo json_encode(["success" => 0, "message" => "File extension does not match MIME type for $field."]);
            exit;
        }
    }

    // ✅ Proceed to DB insert
    try {
        $sql = "INSERT INTO TNEA_JALLIKATTU_BULL_REGISTERATION_T(BULLS_BREED,BULL_AGE,BULL_DENTITION,BULLS_HORN_LENGTH,INTER_CORNUAL_LENGTH,BULL_LENGTH,BULLS_COLOR,BULLS_IDENTIFICATION_MARKS,VETERINARY_DISPENSARY,FWD,BULLS_MEDICAL_CERTIFICATE,BULLS_MEDICAL_CERTIFICATE_MIME_TYPE,BULLS_MEDICAL_CERTIFICATE_FILENAME,BULLS_OWNER,BULLS_OWNER_MIME_TYPE,BULLS_OWNER_FILENAME,ASSISTANT_NAME,ASSISTANT_MOBILE,CREATED_BY,CREATED_DATE,HOST_DISTRICT,HOST_VILLAGE,OWNERS_NAME,OWNERS_MAIL,OWNERS_MOBILE,OWNERS_AADHAR_NUMBER,AADHAR_FILE,AADHAR_FILENAME,AADHAR_FILE_MIMETYPE,STREET_NO,REVENUE_VILLAGE,TALUK,DISTRICT,PINCODE,BULL_LEFT_HORN_LENGTH,EVENT_ID,PLACE_OF_EVENT) Values(:p_BULLS_BREED,:p_BULL_AGE,:p_BULL_DENTITION,:p_BULLS_HORN_LENGTH,:p_INTER_CORNUAL_LENGTH,:p_BULL_LENGTH,:p_BULLS_COLOR,:p_BULLS_IDENTIFICATION_MARKS,:p_VETERINARY_DISPENSARY,:p_FWD,:p_BULLS_MEDICAL_CERTIFICATE,:p_BULLS_MEDICAL_CERTIFICATE_MIME_TYPE,:p_BULLS_MEDICAL_CERTIFICATE_FILENAME,:p_BULLS_OWNER,:p_BULLS_OWNER_MIME_TYPE,:p_BULLS_OWNER_FILENAME,:p_ASSISTANT_NAME,:p_ASSISTANT_MOBILE,:p_CREATED_BY,Current_Timestamp,:p_HOST_DISTRICT,:p_HOST_VILLAGE,:p_OWNERS_NAME,:p_OWNERS_MAIL,:p_OWNERS_MOBILE,:p_OWNERS_AADHAR_NUMBER,:p_AADHAR_FILE,:p_AADHAR_FILENAME,:p_AADHAR_FILE_MIMETYPE,:p_STREET_NO,:p_REVENUE_VILLAGE,:p_TALUK,:p_DISTRICT,:p_PINCODE,:p_BULL_LEFT_HORN_LENGTH,:p_EVENT_ID,:p_PLACE_OF_EVENT);";

        $stmt = $jk_write_db->prepare($sql);
        foreach ($params as $key => $value) {
            if (strpos($sql, ":$key") !== false) {
                $stmt->bindValue(":$key", $value);
            }
        }
        if ($stmt->execute()) {
            $lastInsertedId = $jk_write_db->lastInsertId();
            http_response_code(200);
            echo json_encode(["success" => 1, "message" => "Data successfully inserted.", "bull_id" => $lastInsertedId]);
        } else {
            http_response_code(500);
            echo json_encode(["success" => 0, "message" => "Error inserting data."]);
        }
    }catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(["success" => 0, "message" => "Query Execution failed."]);
}
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
?>
