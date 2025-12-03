<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $params = json_decode(file_get_contents('php://input'), true);

    if (empty($params['EVENT_DATE']) || empty($params['NAME_OF_ORGANIZATION']) || empty($params['EVENT_TYPE'])) {
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Missing required fields"]);
        die();
    }
    $R_staus = 'SN';
    $fwdTo = 'Magesterial huzur sharishthadhar MHS';
    $sql = "UPDATE TNEA_JALLIKATTU_EVENT_REGISTERATION_T SET EVENT_DATE = :EVENT_DATE, NAME_OF_ORGANIZATION = :NAME_OF_ORGANIZATION, COMMITEE_REGISTER_NUMBER = :COMMITEE_REGISTER_NUMBER, NAME_OF_PRESIDENT = :NAME_OF_PRESIDENT, CONTACT_OF_PRESIDENT = :CONTACT_OF_PRESIDENT, MOBILE_OF_PRESIDENT = :MOBILE_OF_PRESIDENT, NAME_OF_VILLAGE = :NAME_OF_VILLAGE, NAME_OF_TALUK = :NAME_OF_TALUK, NAME_OF_DISTRICT = :NAME_OF_DISTRICT, PINCODE = :PINCODE, REQUEST_LETTER = :REQUEST_LETTER, ATTACH_MIMETYPE = :ATTACH_MIMETYPE, ATTACH_FILENAME = :ATTACH_FILENAME, ASSURANCE_BOND = :ASSURANCE_BOND, ASSURANCE_BOND_ATTACH_MIMETYPE = :ASSURANCE_BOND_ATTACH_MIMETYPE, ASSURANCE_BOND_ATTACH_FILENAME = :ASSURANCE_BOND_ATTACH_FILENAME, INSURANCE_COPY = :INSURANCE_COPY, INSURANCE_COPY_ATTACH_MIMETYPE = :INSURANCE_COPY_ATTACH_MIMETYPE, INSURANCE_COPY_ATTACH_FILENAME = :INSURANCE_COPY_ATTACH_FILENAME, LAYOUT_SKETCH = :LAYOUT_SKETCH, LAYOUT_SKETCH_ATTACH_MIMETYPE = :LAYOUT_SKETCH_ATTACH_MIMETYPE, LAYOUT_SKETCH_ATTACH_FILENAME = :LAYOUT_SKETCH_ATTACH_FILENAME, OTHER_DOCS = :OTHER_DOCS, OTHER_DOCS_ATTACH_MIMETYPE = :OTHER_DOCS_ATTACH_MIMETYPE, OTHER_DOCS_ATTACH_FILENAME = :OTHER_DOCS_ATTACH_FILENAME, PREVIOUS_EVENT = :PREVIOUS_EVENT, PREVIOUS_EVENT_ATTACH_MIMETYPE = :PREVIOUS_EVENT_ATTACH_MIMETYPE, PREVIOUS_EVENT_ATTACH_FILENAME = :PREVIOUS_EVENT_ATTACH_FILENAME, PANCHAYAT_UNION = :PANCHAYAT_UNION,   PANCHAYAT_UNION_ATTACH_MIMETYPE = :PANCHAYAT_UNION_ATTACH_MIMETYPE, PANCHAYAT_UNION_ATTACH_FILENAME = :PANCHAYAT_UNION_ATTACH_FILENAME, FIR = :FIR, FIR_ATTACH_MIMETYPE = :FIR_ATTACH_MIMETYPE, FIR_ATTACH_FILENAME = :FIR_ATTACH_FILENAME, UPLOAD_PHOTO = :UPLOAD_PHOTO, UPLOAD_PHOTO_ATTACH_MIMETYPE = :UPLOAD_PHOTO_ATTACH_MIMETYPE, UPLOAD_PHOTO_ATTACH_FILENAME = :UPLOAD_PHOTO_ATTACH_FILENAME, Mhs_comments = :Mhs_comments, EVENT_TYPE = :EVENT_TYPE, place_of_event = :place_of_event, Request_Status = :Request_Status, Forward_to = :Forward_to WHERE EVENT_ID = :P84_EVENT_ID;";

    $stmt = $jk_write_db->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }
    $stmt->bindParam(':Request_Status',$R_staus);
    $stmt->bindParam(':Forward_to',$fwdTo);
    http_response_code($stmt->execute() ? 200 : 500);
    $data = [
        "success" => $stmt->execute() ? 1 : 0,
        "message" => $stmt->execute() ? "Data Updated successfully." : "Error inserting data."
    ];
    echo json_encode($data);
    die();

} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
?>
