<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);
    $params['R_STATUS'] = 'Y';
    $requiredFields = ["p_NAME","p_AGE","p_GENDER","p_MOBILE","p_ADDRESS","p_CREATED_BY", "p_DATE_OF_BIRTH","p_EMAIL_ADDRESS","p_BLOOD_GROUP","p_PHYSICAL_CERTIFICATE","p_ATTACH_MIMETYPE","p_ATTACH_FILENAME","p_AGE_PROOF","p_AGE_ATTACH_MIMETYPE","p_AGE_ATTACH_FILENAME","p_EMERGENCY_MOBILE"];
    // ✅ Required Field Validation
    foreach ($requiredFields as $param) {
        if (!isset($params[$param]) || empty(trim($params[$param]))) {
            http_response_code(400);
            echo json_encode(["success" => 0, "message" => "Missing or empty required parameter: $param"]);
            exit;
        }
    }

    // ✅ Allowed MIME types and extensions
    $allowedMimeTypes = ['image/png', 'image/jpeg', 'application/pdf'];
    $allowedExtensions = ['png', 'jpg', 'jpeg', 'pdf'];

    $fileMimeFields = [
        'p_ATTACH_MIMETYPE',
        'p_AGE_ATTACH_MIMETYPE'
    ];

    $filenameFields = [
        'p_ATTACH_FILENAME',
        'p_AGE_ATTACH_FILENAME'
    ];

    // ✅ MIME Type Validation
    foreach ($fileMimeFields as $mimeField) {
        if (!isset($params[$mimeField]) || !in_array($params[$mimeField], $allowedMimeTypes)) {
            http_response_code(400);
            echo json_encode(["success" => 0, "message" => "Invalid MIME type for $mimeField. Only PNG, JPG, JPEG, or PDF allowed."]);
            exit;
        }
    }

    // ✅ File Extension Validation
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

    // ✅ OPTIONAL: Cross-check MIME matches Extension
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

    // ✅ Proceed with DB Insert
    try {
        $sql = "INSERT INTO TNEA_JALLIKATTU_PARTICIPANTS_REGISTER_T(NAME,AGE,GENDER,MOBILE,ADDRESS,CREATED_BY,DATE_OF_BIRTH,EMAIL_ADDRESS,BLOOD_GROUP,PHYSICAL_CERTIFICATE,ATTACH_MIMETYPE,ATTACH_FILENAME,AGE_PROOF,AGE_ATTACH_MIMETYPE,AGE_ATTACH_FILENAME,EMERGENCY_MOBILE)VALUES (:p_NAME,:p_AGE,:p_GENDER,:p_MOBILE,:p_ADDRESS,:p_CREATED_BY,:p_DATE_OF_BIRTH,:p_EMAIL_ADDRESS,:p_BLOOD_GROUP,:p_PHYSICAL_CERTIFICATE,:p_ATTACH_MIMETYPE,:p_ATTACH_FILENAME,:p_AGE_PROOF,:p_AGE_ATTACH_MIMETYPE,:p_AGE_ATTACH_FILENAME,:p_EMERGENCY_MOBILE);";

        $stmt = $jk_write_db->prepare($sql);
        foreach ($params as $key => $value) {
            if (strpos($sql, ":$key") !== false) {
                $stmt->bindValue(":$key", $value);
            }
        }
        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["success" => 1, "message" => "Data successfully inserted."]);
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
