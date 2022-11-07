<?php


class AzureCommunication
{
    public $to = null;
    public $subject = "";
    public $content = null;
    private $key = "6wIFl4Y/rG6nboIJKCZGx3OaqQ6pVs9yrmqLelBf9OxOISdVAQIK9F9dOyhzeL3iMPfirQvswrI6T1qLgfcg3w==";

    public function sendMail($to, $subject, $content, $attachment = null, $attachmentName = null,
                             $attachmentType = "binary")
    {
        $from = "DoNotReply@6b6ba55a-4351-487d-ad25-a703e637b445.azurecomm.net";
        $endpoint = "https://mmvcommunications2.communication.azure.com/emails:send?api-version=2021-10-01-preview";
        $dt = new DateTime('UTC');
        $sentDate = $dt->format('D, d M Y H:i:s \G\M\T');

        $guid = "";
        if (function_exists('com_create_guid') === true) {
            $guid = trim(com_create_guid(), '{}');
        } else {
            $guid = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        }

        $body = array(
            "content" => [
                "subject" => $subject,
                "html" => $content
            ],
            "sender" => $from,
            "importance" => "normal",
            "recipients" => [
                "to" => [
                    [
                        "email" => $to,
                        "displayName" => "Shera"
                    ]
                ]
            ]
        );

        if ($attachment != null) {
            $body["attachments"] = [
                [
                    "name" => $attachmentName,
                    "attachmentType" => $attachmentType,
                    "contentBytesBase64" => base64_encode($attachment)
                ]
            ];
        }

        $hashedBodyStr = rtrim(base64_encode(hash("sha256", json_encode($body), true)), "=") . "=";

        $url = "/emails:send?api-version=2021-10-01-preview";
        $hostStr = "mmvcommunications2.communication.azure.com";

        $stringToSign = utf8_encode("POST\n" . $url . "\n" . $sentDate . ";" . $hostStr . ";" . $hashedBodyStr);
        $decodedKey = base64_decode($this->key);
        $hmac = hex2bin(hash_hmac("sha256", $stringToSign, $decodedKey));
        $signature = rtrim(base64_encode($hmac), "=") . "=";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Host: " . $hostStr,
            "Authorization: " . utf8_encode("HMAC-SHA256 SignedHeaders=date;host;x-ms-content-sha256&Signature=" . $signature),
            "Content-Type: application/json",
            "repeatability-first-sent: " . $sentDate,
            "Date: " . $sentDate,
            "repeatability-request-id: " . $guid,
            "x-ms-content-sha256: " . $hashedBodyStr
        ));

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo $server_output;
        if ($httpcode == 202) {
            return true;
        } else {
            return false;
        }
    }


    public function sendSms($to, $message)
    {

        $endpoint = "https://mmvcommunications2.communication.azure.com/sms?api-version=2021-03-07";

        $dt = new DateTime('UTC');
        $sentDate = $dt->format('D, d M Y H:i:s \G\M\T');

        $guid = "";
        if (function_exists('com_create_guid') === true) {
            $guid = trim(com_create_guid(), '{}');
        } else {
            $guid = sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
        }

        $body = array(
            "from" => "",
            "message" => $message,
            "smsRecipients" => [
                [
                    "repeatabilityFirstSent" => $sentDate,
                    "repeatabilityRequestId" => $guid,
                    "to" => $to
                ]
            ]
        );

        $hashedBodyStr = rtrim(base64_encode(hash("sha256", json_encode($body), true)), "=") . "=";

        $url = "/sms?api-version=2021-03-07";
        $hostStr = "mmvcommunications2.communication.azure.com";

        $stringToSign = utf8_encode("POST\n" . $url . "\n" . $sentDate . ";" . $hostStr . ";" . $hashedBodyStr);
        $decodedKey = base64_decode($this->key);
        $hmac = hex2bin(hash_hmac("sha256", $stringToSign, $decodedKey));
        $signature = rtrim(base64_encode($hmac), "=") . "=";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Host: " . $hostStr,
            "Authorization: " . utf8_encode("HMAC-SHA256 SignedHeaders=date;host;x-ms-content-sha256&Signature=" . $signature),
            "Content-Type: application/json",
            "repeatability-first-sent: " . $sentDate,
            "Date: " . $sentDate,
            "repeatability-request-id: " . $guid,
            "x-ms-content-sha256: " . $hashedBodyStr
        ));

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode == 202) {
            return true;
        } else {
            return false;
        }
    }

}

