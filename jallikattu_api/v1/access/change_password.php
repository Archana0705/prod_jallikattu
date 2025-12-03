<?php
require_once('../../helper/header.php');
require_once('../../helper/db/jk_write.php');
require_once('../../helper/db/jk_read.php');
header("Access-Control-Allow-Methods: POST");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $employee_id = isset($_POST['employee_id']) ? trim($_POST['employee_id']) : null;
    $old_password = isset($_POST['old_password']) ? trim($_POST['old_password']) : null;
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : null;
    $errors = [];
    $requiredFields = [
            'employee_id' => 'Employee Id',
            'old_password' => 'Old Password',
            'new_password' => 'New Password',
        ];
        foreach ($requiredFields as $fieldKey => $fieldLabel) {
            if (empty($$fieldKey)) { 
                $errors[] = $fieldLabel;
            }
        }
        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                "success" => 0,
                "message" => "Missing fields: " . implode(', ', $errors)
            ]);
            die();
        }

        $verifySql = "SELECT password FROM TNEA_JALLIKATTU_APPROVER_LOGIN_T where approver_id = :employeeid UNION ALL SELECT password FROM TNEA_JALLIKATTU_REGISTER_LOGIN_T where register_id = :employeeid";
        $verifystmt = $jk_read_db->prepare($verifySql);
        $verifystmt->bindParam(':employeeid', $employee_id);
        if ($verifystmt->execute()) {
            $verifyData = $verifystmt->fetch(PDO::FETCH_ASSOC);
            if($verifyData && password_verify($old_password, $verifyData['password'])){
                $hashed_password_new = password_hash($new_password, PASSWORD_BCRYPT);
                $changeSql = "SELECT * FROM change_password(:p_employee_id, :p_old_password, :p_new_password)";
                $changePassword = $jk_write_db->prepare($changeSql);
                $changePassword->bindParam(':p_employee_id', $employee_id);
                $changePassword->bindParam(':p_old_password', $verifyData['password']);
                $changePassword->bindParam(':p_new_password', $hashed_password_new);
                if ($changePassword->execute()) {
                $result = $changePassword->fetch(PDO::FETCH_ASSOC);
        
                $responseMessage = isset($result['change_password']) && stripos($result['change_password'], 'successfully') !== false
                    ? "Password Changed successfully"
                    : "Password not Changed";
        
                echo json_encode([
                    "success" => $responseMessage === "Password Changed successfully" ? 1 : 0,
                    "message" => $responseMessage,
                       ]);
                } else {
                    $errorInfo = $changePassword->errorInfo();
                    http_response_code(400);
                    echo json_encode([
                        "success" => 0,
                        "message" => "Incorrect Old Password",
                        "error" => $errorInfo[2],
                        "debug" => [
                            "query_executed" => $changeSql,
                            "inputs" => [
                                "employee_id" => $employee_id,
                                "old_password" => $old_password,
                                "new_password" => $new_password
                            ]
                        ]
                    ]);
                }
            }else{
                http_response_code(400);
                echo json_encode([
                    "success" => 0,
                    "message" => "Incorrect Old Password"]);
            }
        } else {
            http_response_code(400);
            echo json_encode([
                "success" => 0,
                "message" => "Error executing query."]);
        }

       
} else {
    http_response_code(405);
    echo json_encode(["success" => 0, "message" => "Method Not Allowed"]);
}
$jk_write_db = null;
exit;
?>
