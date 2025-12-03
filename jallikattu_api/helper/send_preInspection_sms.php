<?php
function sendSMS($mobile_number, $district, $eventPlace, $inspectionDate)
{
    $message_content = "Jallikattu /Manju virattu /vadamadu /erudu vidum vizha $eventPlace -G.O. issued - pre event inspection/re-inspection scheduled on $inspectionDate District Collector, $district -TNAHVS";
    $entityid = 1001730754604494181;
    $templateid = 1007126362085717149;
    $endpoint = 'https://tmegov.onex-aura.com/api/sms';
    $params = array('key' => 'r8o9j9JV', 'to' => $mobile_number, 'from' => 'TNAHVS', 'body' => $message_content, 'entityid' => $entityid, 'templateid' => $templateid);
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
    $data = json_decode($result);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("JSON Decode Error: " . json_last_error_msg());
        return false; 
    }
    if (isset($data->status) && $data->status == 100) {
        return true;
    } else {
        return false; 
    }
    if (isset($data->status)) {
        if ($data->status == 100) {
            return true;
        } else {
            error_log("SMS API Error: " . print_r($data, true)); // Log the error in case of failure
            return false; 
        }
    } else {
        error_log("Missing status field in API response");
        return false;
    }
}
function generateOTP($n)
{
    $generator = "1357902468";
    $result = "";
    for ($i = 1; $i <= $n; $i++) {
        $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }
    return $result;
}
sendSMS('8925331130', 'Madurai', 'alanganallur', '10/10/2024');