<?php
require_once('../../../helper/header.php');
header("Access-Control-Allow-Methods: GET");
require_once('../../../helper/db/jk_read.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $app_employee_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;   
    if($app_employee_id === null){
        http_response_code(401);
        $data = array("success" => 0, "message" => "User does not exist");
        echo json_encode($data);
        die();
    }
    $sql = "SELECT e.EVENT_ID,e.EVENT_DATE,e.NAME_OF_ORGANIZATION,e.COMMITEE_REGISTER_NUMBER,e.NAME_OF_president,e.CONTACT_OF_PRESIDENT,e.MOBILE_OF_PRESIDENT,e.NAME_OF_VILLAGE,e.NAME_OF_TALUK,e.NAME_OF_DISTRICT,e.PINCODE,e.REQUEST_LETTER,e.ATTACH_MIMETYPE,e.ATTACH_FILENAME,e.ATTACH_LAST_UPDATE,e.ATTACH_CHARSET,e.ASSURANCE_BOND,e.ASSURANCE_BOND_ATTACH_MIMETYPE,e.ASSURANCE_BOND_ATTACH_FILENAME,e.INSURANCE_COPY,e.INSURANCE_COPY_ATTACH_MIMETYPE,e.INSURANCE_COPY_ATTACH_FILENAME,e.LAYOUT_SKETCH,e.LAYOUT_SKETCH_ATTACH_MIMETYPE,e.LAYOUT_SKETCH_ATTACH_FILENAME,e.OTHER_DOCS,e.OTHER_DOCS_ATTACH_MIMETYPE,e.OTHER_DOCS_ATTACH_FILENAME,e.PREVIOUS_EVENT,e.PREVIOUS_EVENT_ATTACH_MIMETYPE,e.PREVIOUS_EVENT_ATTACH_FILENAME,e.PANCHAYAT_UNION,e.PANCHAYAT_UNION_ATTACH_MIMETYPE,e.PANCHAYAT_UNION_ATTACH_FILENAME,e.FIR,e.FIR_ATTACH_MIMETYPE,e.FIR_ATTACH_FILENAME,e.UPLOAD_PHOTO,e.UPLOAD_PHOTO_ATTACH_MIMETYPE,e.UPLOAD_PHOTO_ATTACH_FILENAME,e.STATUS,e.CREATED_BY,e.CREATED_DATE,e.UPDATED_BY,e.UPDATED_DATE,e.EVENT_TYPE,e.REQUEST_STATUS,e.FORWARD_TO,e.MHS_COMMENTS,e.REGISTERING_DATE_FOR_THE_EVENT,e.DC_COMMENTS,e.DAH_VS_COMMENTS,e.ACS_PS_COMMENTS,e.PLACE_OF_EVENT,e.PROCEED_COMMENTS from TNEA_JALLIKATTU_EVENT_REGISTERATION_T e;";
    $stmt = $jk_read_db->prepare($sql);
    if ($stmt->execute()) {
        $data_result_count = $stmt->rowCount();
        if ($data_result_count > 0) {
            $data_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data_result = array_map(fn($item) => array_filter($item, fn($value) => gettype($value) !== 'resource'), $data_result);
            http_response_code(200);
            $data = array("success" => 1, "message" => "Data found", "data" => $data_result);
        } else {
            http_response_code(200);
            $data = array("success" => 2, "message" => "No data Found");
           
        }
        echo json_encode($data);
        die();
    }else {
        http_response_code(500);
        $data = array("success" => 3, "message" => "Problem in executing the query in db");
        echo json_encode($data);
        die();
    }
} else {
    http_response_code(405);
    $data = array("success" => 0, "message" => "Method Not Allowed");
    echo json_encode($data);
    die();
}
