<?php
require_once('../../helper/header.php');
header("Access-Control-Allow-Methods: POST");
require_once('../../helper/db/jk_write.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Log the raw POST data for debugging
    $rawData = file_get_contents('php://input');
    error_log("Raw POST Data: " . $rawData); // Logs the data for debugging

    // Decode the incoming JSON data
    $params = json_decode($rawData, true);
    
    if ($params === null) {
        error_log("JSON Decode Failed: " . json_last_error_msg()); // Log JSON decode errors
        http_response_code(400);
        echo json_encode(["success" => 0, "message" => "Invalid JSON data."]);
        exit;
    }

    // Add default values for 'p_STATUS' and 'R_STATUS'
    $params['p_STATUS'] = 'Y';
    $params['R_STATUS'] = 'Y';

    // Define the required fields
    $requiredFields = ["p_EVENT_TYPE", "p_EVENT_DATE", "p_PLACE_OF_EVENT", "p_DISTRICT", "p_CREATED_BY", "p_SELECTED_BULL", "p_mobile"];
    
    // Check if all required fields are present in the request data
    foreach ($requiredFields as $param) {
        if (!isset($params[$param]) || empty(trim($params[$param]))) {
            // Log and throw an exception if any parameter is missing
            error_log("Missing parameter: " . $param);
            throw new Exception("Missing or empty required parameter: $param");
        }
    }

    try {
        // Prepare the SQL query to insert the data
        $sql = "INSERT INTO TNEA_JALLIKATTU_BULL_EVENT_T(EVENT_TYPE, EVENT_DATE, PLACE_OF_EVENT, DISTRICT, CREATED_BY, SELECTED_BULL, mobile) 
                VALUES(:p_EVENT_TYPE, :p_EVENT_DATE, :p_PLACE_OF_EVENT, :p_DISTRICT, :p_CREATED_BY, :p_SELECTED_BULL, :p_mobile)";

        // Prepare the statement
        $stmt = $jk_write_db->prepare($sql);
        
        // Bind the parameters dynamically
        foreach ($params as $key => $value) {
            if (strpos($sql, ":$key") !== false) {
                $stmt->bindValue(":$key", $value);
            }
        }

        // Execute the statement
        if ($stmt->execute()) {
            // Success response
            http_response_code(200);
            echo json_encode(["success" => 1, "message" => "Data successfully inserted."]);
        } else {
            // Failure response
            http_response_code(500);
            echo json_encode(["success" => 0, "message" => "Error inserting data."]);
        }
    } catch (PDOException $e) {
        // Log and send an error response
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["success" => 0, "message" => "Query Execution failed."]);
    }
} else {
    // Method not allowed if not a POST request
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
?>
