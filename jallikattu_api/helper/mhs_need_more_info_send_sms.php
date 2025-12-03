<?php
function sendSMS($mobile_number)
{
    $message_content = "Your jallikattu application sent back to you: $mobile_number - Tamil Nadu e-Governance Agency.";
    $entityid = 1301157259712022912;
    $templateid = 1007224235798722296;
    $endpoint = 'https://tmegov.onex-aura.com/api/sms';
    $params = [
        'key' => 'bGqBNbIp',
        'to' => $mobile_number,
        'from' => 'TNGOVT',
        'body' => $message_content,
        'entityid' => $entityid,
        'templateid' => $templateid
    ];

    $url = $endpoint . '?' . http_build_query($params);
    error_log("API Request URL: " . $url);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);

    $result = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    error_log("API Response: " . $result);
    error_log("HTTP Code: " . $http_code);

    if ($http_code !== 200) {
        error_log("HTTP Error: Code " . $http_code);
        return false;
    }

    $data = json_decode($result, true); // Decode response as associative array

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON Decode Error: " . json_last_error_msg());
        return false;
    }

    if (isset($data['status']) && $data['status'] === 100) {
        return true;
    }

    error_log("SMS API Error: " . print_r($data, true));
    return false;
}
