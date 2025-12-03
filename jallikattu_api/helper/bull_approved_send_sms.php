<?php


function sendSMS($mobile_number, $eventPlace, $eventDate)
{
    $message_content = "இந்த $eventDate ல்  $eventPlace ல் நடக்கும் ஜல்லிக்கட்டு/ வடமாடு /மஞ்சு விரட்டு /எருது விடும் விழா நிகழ்ச்சியில் பங்கேற்க தங்க‌ள் காளை அனுமதிக்கப்படுகிறது-TNAHVS";
    $entityid = 1001730754604494181;
    $templateid = 1007029880775419396;
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