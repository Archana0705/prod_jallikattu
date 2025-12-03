<?php 
require_once('../../helper/header.php');
require_once('../../helper/db/jk_read.php');
header("Access-Control-Allow-Methods: POST");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $decryptedData = isset($_POST['data']) ? decryptData($_POST['data']) : null;
    if (!isset($decryptedData)) {
        http_response_code(400);
        echo json_encode([
            "success" => 0,
            "message" => "Invalid input!",
        ]);
        exit;
    }
    $username = htmlspecialchars(trim($decryptedData['mobile_no']), ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars(trim($decryptedData['password']), ENT_QUOTES, 'UTF-8');
    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode([
            "success" => 0,
            "message" => "Missing required fields",
        ]);
        exit;
    }
    try {
        $loginSql = "SELECT * from user_login_fun(:p_username);";
        $loginStmt = $jk_read_db->prepare($loginSql);
        $loginStmt->bindParam(':p_username', $username, PDO::PARAM_INT); 
        if ($loginStmt->execute()) {
            $result = $loginStmt->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                $storedPassword = $result[0]['password'];
                if ($result[0] && $result[0]['current']) {
                    http_response_code(403);
                    echo json_encode([
                        "success" => 0,
                        "message" => "User already logged in"
                    ]);
                    exit;
                }
                if (password_verify($password, $storedPassword)){
                    $approverSql = "SELECT 1 FROM public.tnea_jallikattu_approver_login_t WHERE mobile_number = :mobile";
                    $approverStmt = $jk_read_db->prepare($approverSql);
                    $approverStmt->bindParam(':mobile', $username);
                    $approverStmt->execute();
                    $existsInApprover = $approverStmt->rowCount() > 0;

                    // 2. Check if it exists in the register table
                    $registerSql = "SELECT 1 FROM public.tnea_jallikattu_register_login_t WHERE mobile = :mobile";
                    $registerStmt = $jk_read_db->prepare($registerSql);
                    $registerStmt->bindParam(':mobile', $username);
                    $registerStmt->execute();
                    $existsInRegister = $registerStmt->rowCount() > 0;

                    // 3. Conditionally update tables
                    if ($existsInApprover) {
                        $updateApproverSql = "UPDATE public.tnea_jallikattu_approver_login_t
                                            SET is_logged_in = true
                                            WHERE mobile_number = :mobile";
                        $updateApproverStmt = $jk_read_db->prepare($updateApproverSql);
                        $updateApproverStmt->bindParam(':mobile', $username);
                        $updateApproverStmt->execute();
                    }

                    if ($existsInRegister) {
                        $updateRegisterSql = "UPDATE public.tnea_jallikattu_register_login_t
                                            SET is_logged_in = true
                                            WHERE mobile = :mobile";
                        $updateRegisterStmt = $jk_read_db->prepare($updateRegisterSql);
                        $updateRegisterStmt->bindParam(':mobile', $username);
                        $updateRegisterStmt->execute();
                    }
                    http_response_code(200);
                    echo json_encode([ 
                        "success" => 1,
                        "message" => "Valid user",
                        "data" => encrypt([
                        "user_id" => $result[0]['user_id'],
                        "mobile"  => $username,
                        "name"    => $result[0]['name'],
                        "role"    => $result[0]['role']
                        ])
                    ]);
                } else {
                    echo json_encode([
                        "success" => 0,
                        "message" => "Invalid user!",
                    ]);
                }
            } else {
                http_response_code(400);
                echo json_encode([ 
                    "success" => 0,
                    "message" => "User not found"   
                ]);
            }
        } else {
            http_response_code(500);
                echo json_encode([ 
                    "success" => 0,
                    "message" => "Failed to execute query"   
                ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([ 
            "success" => 0,
            "message" => "Internal server error"   
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([ 
        "success" => 0,
        "message" => "Method Not Allowed"   
    ]);
}
?>
