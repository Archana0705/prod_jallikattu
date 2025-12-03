<?php
require_once('../../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $params = json_decode(file_get_contents('php://input'), true);

        if (empty($params['p_EVENT_ID']) || empty($params['user_id'])) {
            http_response_code(400);
            echo json_encode(["success" => 0, "message" => "Missing required fields"]);
            exit;
        }

        $R_status = 'SN';
        $fwdTo = 'Magesterial huzur sharishthadhar MHS';
        $sql1 = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T
        SET EVENT_DATE = :p_EVENT_DATE,
            NAME_OF_ORGANIZATION = :p_NAME_OF_ORGANIZATION,
            COMMITEE_REGISTER_NUMBER = :p_COMMITEE_REGISTER_NUMBER,
            NAME_OF_PRESIDENT = :p_NAME_OF_PRESIDENT,
            CONTACT_OF_PRESIDENT = :p_CONTACT_OF_PRESIDENT,
            MOBILE_OF_PRESIDENT = :p_MOBILE_OF_PRESIDENT,
            NAME_OF_VILLAGE = :p_NAME_OF_VILLAGE,
            NAME_OF_TALUK = :p_NAME_OF_TALUK,
            NAME_OF_DISTRICT = :p_NAME_OF_DISTRICT,
            PINCODE = :p_PINCODE,
            REQUEST_LETTER = :p_REQUEST_LETTER,
            ATTACH_MIMETYPE = :p_ATTACH_MIMETYPE,
            ATTACH_FILENAME = :p_ATTACH_FILENAME,
            ASSURANCE_BOND = :p_ASSURANCE_BOND,
            ASSURANCE_BOND_ATTACH_MIMETYPE = :p_ASSURANCE_BOND_ATTACH_MIMETYPE,
            ASSURANCE_BOND_ATTACH_FILENAME = :p_ASSURANCE_BOND_ATTACH_FILENAME,
            INSURANCE_COPY = :p_INSURANCE_COPY,
            INSURANCE_COPY_ATTACH_MIMETYPE = :p_INSURANCE_COPY_ATTACH_MIMETYPE,
            INSURANCE_COPY_ATTACH_FILENAME = :p_INSURANCE_COPY_ATTACH_FILENAME,
            LAYOUT_SKETCH = :p_LAYOUT_SKETCH,
            LAYOUT_SKETCH_ATTACH_MIMETYPE = :p_LAYOUT_SKETCH_ATTACH_MIMETYPE,
            LAYOUT_SKETCH_ATTACH_FILENAME = :p_LAYOUT_SKETCH_ATTACH_FILENAME,
            OTHER_DOCS = :p_OTHER_DOCS,
            OTHER_DOCS_ATTACH_MIMETYPE = :p_OTHER_DOCS_ATTACH_MIMETYPE,
            OTHER_DOCS_ATTACH_FILENAME = :p_OTHER_DOCS_ATTACH_FILENAME,
            PREVIOUS_EVENT = :p_PREVIOUS_EVENT,
            PREVIOUS_EVENT_ATTACH_MIMETYPE = :p_PREVIOUS_EVENT_ATTACH_MIMETYPE,
            PREVIOUS_EVENT_ATTACH_FILENAME = :p_PREVIOUS_EVENT_ATTACH_FILENAME,
            PANCHAYAT_UNION = :p_PANCHAYAT_UNION,
            PANCHAYAT_UNION_ATTACH_MIMETYPE = :p_PANCHAYAT_UNION_ATTACH_MIMETYPE,
            PANCHAYAT_UNION_ATTACH_FILENAME = :p_PANCHAYAT_UNION_ATTACH_FILENAME,
            FIR = :p_FIR,
            FIR_ATTACH_MIMETYPE = :p_FIR_ATTACH_MIMETYPE,
            FIR_ATTACH_FILENAME = :p_FIR_ATTACH_FILENAME,
            UPLOAD_PHOTO = :p_UPLOAD_PHOTO,
            UPLOAD_PHOTO_ATTACH_MIMETYPE = :p_UPLOAD_PHOTO_ATTACH_MIMETYPE,
            UPLOAD_PHOTO_ATTACH_FILENAME = :p_UPLOAD_PHOTO_ATTACH_FILENAME,
            EVENT_TYPE = :p_EVENT_TYPE,
            REQUEST_STATUS = :REQUEST_STATUS,
            PLACE_OF_EVENT = :p_PLACE_OF_EVENT,
            FORWARD_TO = :forward
        WHERE EVENT_ID = :p_EVENT_ID;";

        $stmt1 = $jk_write_db->prepare($sql1);

        foreach ($params as $key => $value) {
            if (strpos($sql1, ":$key") !== false) {
                $stmt1->bindValue(":$key", $value);
            }
        }

        if ($stmt1->execute()) {
            $sql2 = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T 
                     SET Request_status = :Request_status, FORWARD_TO = :forward 
                     WHERE EVENT_ID = :P108_EVENT_ID";
            $stmt2 = $jk_write_db->prepare($sql2);
            $stmt2->bindValue(':Request_status', $R_status);
            $stmt2->bindValue(':forward', $fwdTo);
            $stmt2->bindValue(':P108_EVENT_ID', $params['p_EVENT_ID']);

            if ($stmt2->execute()) {
                http_response_code(200);
                echo json_encode(["success" => 1, "message" => "Data successfully updated."]);
            } else {
                http_response_code(500);
                echo json_encode(["success" => 0, "message" => "Error updating second query."]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["success" => 0, "message" => "Error updating first query."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => 0, "message" => "A database error occurred."]);
    }
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
?>
