<?php
require_once __DIR__ . "/../db/Database.php";
require_once __DIR__ . "/data.php";
require_once __DIR__ . "/vendor/PHPMailer/src/PHPMailer.php";
require_once __DIR__ . "/vendor/PHPMailer/src/Exception.php";
require_once __DIR__ . "/vendor/PHPMailer/src/SMTP.php";
require_once __DIR__ . "/apiconfig.php";

use PHPMailer\PHPMailer\PHPMailer;

$database = new Database("customer");
$query = $database->query(null, "confirm = 0 AND alert != 0", null, null, null, null);
if ($query != null) {
    $phpmailer = new PHPMailer();
    $phpmailer->isSMTP();
    $phpmailer->Host = 'ssl0.ovh.net';
    $phpmailer->SMTPAuth = true;
    $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'rooming@chahrazed-durand.com';
    $phpmailer->Password = 'Sherashera2626';
    $phpmailer->From = 'rooming@chahrazed-durand.com';
    $phpmailer->FromName = 'MMV Rooming';
    $phpmailer->Subject = "MMV Rooming";
    $phpmailer->isHTML(true);
    $content = file_get_contents( __DIR__ ."/relancemail.html");

    foreach ($query as $row) {
        $diff = strtotime($row["date"]) - time();
        $colVal = [
            "alert" => -1
        ];
        $content = str_replace("{booking_id}", $row["booking_id"], $content);
        $content = str_replace("{etab}", $row["establishment"], $content);
        $uniqueLink = "https://sherazad.codeur.online/rooming/views/admin?u=".$query[0]["unique_id"];
        $content = str_replace("{unique_link}", $uniqueLink, $content);

        $rows = csvToArray($row["file"]);
        $keyDone = false;
        $keys = [];
        $id = 0;
        foreach ($rows as $r) {
            if (!$keyDone) {
                $keyDone = true;
                $keys = $row;
            } else {
                $content = str_replace("{start_date}", $r[1], $content);
                $content = str_replace("{end_date}", $r[2], $content);
                break;
            }
        }


        $etabDb = new Database("establishment");
        $etabRows = $etabDb->query(["type", "logo"], "id = '" . $row["etab_id"] . "'", null, null,
            null, "1");
           
        if ($etabRows != null){
            $content = str_replace("{etab_logo}",  DOMAIN.$etabRows[0]["logo"], $content);
            $content = str_replace("{etab_type}", $etabRows[0]["type"], $content);
        }
        
        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        $secondsInADay = 24 * $secondsInAnHour;

        // Extract days
        $days = floor($diff / $secondsInADay);
        // Extract hours
        $hourSeconds = $diff % $secondsInADay;
        $hours = floor($hourSeconds / $secondsInAnHour);
        // Extract minutes
        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        $timeText =  $days." days";
        $content = str_replace("{time}", $timeText, $content);
        $phpmailer->Body = $content;
        $phpmailer->addAddress($row["email"]);


        //Test
        $colVal["alert"] = 0;
        $database->update($colVal, "id = " . $row["id"], null, "1");
        $phpmailer->send();
        continue;
        //Test end

        if (intval($row["alert"]) == -1) {
            if ($diff <= ((60 * 60) * 24) * 5) {
                $colVal["alert"] = 0;
            } elseif ($diff <= ((60 * 60) * 24) * 10) {
                $colVal["alert"] = 1;
            } elseif ($diff <= ((60 * 60) * 24) * 20) {
                $colVal["alert"] = 2;
            }
            if ($colVal["alert"] != -1) {
                $database->update($colVal, "id = " . $row["id"], null, "1");
                $phpmailer->send();
            }
        } else {
            if ($diff <= ((60 * 60) * 24) * 5 && intval($row["alert"]) == 1) {
                $colVal["alert"] = 0;
            } elseif ($diff <= ((60 * 60) * 24) * 10 && intval($row["alert"]) == 2) {
                $colVal["alert"] = 1;
            }
            if ($colVal["alert"] != -1) {
                $database->update($colVal, "id = " . $row["id"], null, "1");
                if(!$phpmailer->send()){
                    echo "Failed";
                }
            }
        }

    }
}