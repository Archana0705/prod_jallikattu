<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $params = json_decode(file_get_contents('php://input'), true);

    if (empty($params['EVENT_DATE']) || empty($params['NAME_OF_ORGANIZATION']) || empty($params['EVENT_TYPE'])) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }

    $params['REQUEST_status'] = 'E'; 
    try {
        $checkSql = "SELECT event_id FROM TNEA_JALLIKATTU_EVENT_REGISTERATION_T WHERE event_id = :EVENT_ID";
        $checkStmt = $jk_write_db->prepare($checkSql);
        $checkStmt->bindValue(':EVENT_ID', $params['EVENT_ID'], PDO::PARAM_STR);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            http_response_code(409); 
            echo json_encode(["success" => 0, "message" => "Event ID already exists."]);
            die();
        }

        $jk_write_db->beginTransaction();
	        $sql = "INSERT INTO TNEA_JALLIKATTU_EVENT_REGISTERATION_T (
                    EVENT_ID, EVENT_DATE, NAME_OF_ORGANIZATION, COMMITEE_REGISTER_NUMBER, NAME_OF_PRESIDENT, 
                    CONTACT_OF_PRESIDENT, MOBILE_OF_PRESIDENT, NAME_OF_VILLAGE, NAME_OF_TALUK, 
                    NAME_OF_DISTRICT, PINCODE, REQUEST_LETTER, ATTACH_MIMETYPE, ATTACH_FILENAME, 
                    ASSURANCE_BOND, ASSURANCE_BOND_ATTACH_MIMETYPE, ASSURANCE_BOND_ATTACH_FILENAME, 
                    INSURANCE_COPY, INSURANCE_COPY_ATTACH_MIMETYPE, INSURANCE_COPY_ATTACH_FILENAME, 
                    LAYOUT_SKETCH, LAYOUT_SKETCH_ATTACH_MIMETYPE, LAYOUT_SKETCH_ATTACH_FILENAME, 
                    OTHER_DOCS, OTHER_DOCS_ATTACH_MIMETYPE, OTHER_DOCS_ATTACH_FILENAME, 
                    PREVIOUS_EVENT, PREVIOUS_EVENT_ATTACH_MIMETYPE, PREVIOUS_EVENT_ATTACH_FILENAME, 
                    PANCHAYAT_UNION, PANCHAYAT_UNION_ATTACH_MIMETYPE, PANCHAYAT_UNION_ATTACH_FILENAME, 
                    FIR, FIR_ATTACH_MIMETYPE, FIR_ATTACH_FILENAME, UPLOAD_PHOTO, 
                    UPLOAD_PHOTO_ATTACH_MIMETYPE, UPLOAD_PHOTO_ATTACH_FILENAME, EVENT_TYPE, 
                    REQUEST_status, PLACE_OF_EVENT, CREATED_BY
                ) VALUES (
                    :EVENT_ID, :EVENT_DATE, :NAME_OF_ORGANIZATION, :COMMITEE_REGISTER_NUMBER, :NAME_OF_PRESIDENT, 
                    :CONTACT_OF_PRESIDENT, :MOBILE_OF_PRESIDENT, :NAME_OF_VILLAGE, :NAME_OF_TALUK, 
                    :NAME_OF_DISTRICT, :PINCODE, :REQUEST_LETTER, :ATTACH_MIMETYPE, :ATTACH_FILENAME, 
                    :ASSURANCE_BOND, :ASSURANCE_BOND_ATTACH_MIMETYPE, :ASSURANCE_BOND_ATTACH_FILENAME, 
                    :INSURANCE_COPY, :INSURANCE_COPY_ATTACH_MIMETYPE, :INSURANCE_COPY_ATTACH_FILENAME, 
                    :LAYOUT_SKETCH, :LAYOUT_SKETCH_ATTACH_MIMETYPE, :LAYOUT_SKETCH_ATTACH_FILENAME, 
                    :OTHER_DOCS, :OTHER_DOCS_ATTACH_MIMETYPE, :OTHER_DOCS_ATTACH_FILENAME, 
                    :PREVIOUS_EVENT, :PREVIOUS_EVENT_ATTACH_MIMETYPE, :PREVIOUS_EVENT_ATTACH_FILENAME, 
                    :PANCHAYAT_UNION, :PANCHAYAT_UNION_ATTACH_MIMETYPE, :PANCHAYAT_UNION_ATTACH_FILENAME, 
                    :FIR, :FIR_ATTACH_MIMETYPE, :FIR_ATTACH_FILENAME, :UPLOAD_PHOTO, 
                    :UPLOAD_PHOTO_ATTACH_MIMETYPE, :UPLOAD_PHOTO_ATTACH_FILENAME, :EVENT_TYPE, 
                    :REQUEST_status, :PLACE_OF_EVENT, :CREATED_BY
                );";
        $stmt = $jk_write_db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        if ($stmt->execute()) {
            $jk_write_db->commit(); 
            http_response_code(200);
            echo json_encode(["success" => 1, "message" => "Data successfully inserted."]);
        } else {
            $jk_write_db->rollBack();
            http_response_code(500);
            echo json_encode(["success" => 0, "message" => "Error inserting data."]);
        }
    } catch (PDOException $e) {
        $jk_write_db->rollBack();
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["success" => 0, "message" => "A database error occurred."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
?>
