<?php
require_once "../db/Database.php";
require_once "vendor/PHPMailer/src/PHPMailer.php";
require_once "vendor/PHPMailer/src/Exception.php";
require_once "vendor/PHPMailer/src/SMTP.php";
require_once "AzureCommunication.php";

use PHPMailer\PHPMailer\PHPMailer;

/*$phpmailer = new PHPMailer();
$phpmailer->isSMTP();
$phpmailer->Host = 'ssl0.ovh.net';
$phpmailer->SMTPAuth = true;
$phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$phpmailer->Port = 587;
$phpmailer->Username = 'rooming@chahrazed-durand.com';
$phpmailer->Password = 'Sherashera2626';
$phpmailer->From = 'roomnig@chahrazed-durand.com';
$phpmailer->FromName = 'MMV Rooming';
$phpmailer->Subject = "Verification code";
$phpmailer->isHTML(true);*/

$azureCommunication = new AzureCommunication();

if (isset($_POST["type"]) && $_POST["type"] == "login") {
    $result = [
        "status" => "fail"
    ];
    $database = new Database("customer");
    if (isset($_POST["email"]) && isset($_POST["code"])) {
        $selection = "email = '" . $_POST["email"] . "' AND booking_id = '" . $_POST["code"] . "'";
        $matched = $database->query(null, $selection, null, null, null, "1");
        if ($matched) {
            $verificationDatabase = new Database("verification");
            $columns = [
                "id int NOT NULL PRIMARY KEY AUTO_INCREMENT",
                "email varchar(255) NOT NULL",
                "booking_id int not null",
                "code varchar(255) NOT NULL",
                "confirm tinyint NOT NULL DEFAULT 0",
                "date bigint NOT NULL"
            ];
            $verificationDatabase->createTable($columns);
            $verificationDatabase->delete("email = '" . $_POST["email"] . "'", null, null, null);
            $digits = 4;
            $code = str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);

//            $phpmailer->addAddress($_POST["email"]);
            $content = file_get_contents("emailpassword.html");
            $content = str_replace("{code}", $code, $content);
            $content = str_replace("{booking_id}", $_POST["code"], $content);
            $uniqueLink = "https://sherazad.codeur.online/rooming/views/admin?u=" . $matched[0]["unique_id"];
            $content = str_replace("{unique_link}", $uniqueLink, $content);
//            $phpmailer->Body = $content;
            if ($azureCommunication->sendMail($_POST["email"], "Verification code", $content)) {
                $insert = $verificationDatabase->insert(
                    [
                        "email" => $_POST["email"],
                        "code" => $code,
                        "booking_id" => $_POST["code"], //booking id
                        "date" => time(),
                    ]
                );
                $result["status"] = "success";
            }
        }
    }
    echo json_encode($result);
} elseif (isset($_POST["type"]) && $_POST["type"] == "verify" && isset($_POST["code"]) &&
    isset($_POST["email"])) {
    $database = new Database("verification");
    $rows = $database->query(null, "email = '" . $_POST["email"] .
        "' AND code = '" . $_POST["code"] . "' AND booking_id = '" . $_POST["booking_id"] . "'", null, null, null, "1");
    $result = [
        "status" => "fail"
    ];
    if ($rows != null) {
        if (time() - intval($rows[0]["date"]) < 60 * 10) {
            $result["status"] = "success";
            session_name("client");
            session_start();
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["booking_id"] = $_POST["booking_id"];
            $colVal = [
                "confirm" => 1,
                "booking_id" => $_POST["booking_id"]
            ];
            $database->update($colVal, "email = '" . $_POST["email"] . "' AND booking_id = '" . $_POST["booking_id"] . "'", null, "1");
        } else {
            $database->delete("email = '" . $_POST["email"] .
                "' AND code = '" . $_POST["code"] . "' AND booking_id = '" . $_POST["booking_id"] . "'", null, null, "1");
        }
    }
    echo json_encode($result);
}

