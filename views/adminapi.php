<?php
require_once "../db/Database.php";
require_once "vendor/PHPMailer/src/PHPMailer.php";
require_once "vendor/PHPMailer/src/Exception.php";
require_once "vendor/PHPMailer/src/SMTP.php";
require_once "data.php";
require_once "apiconfig.php";
require_once "AzureCommunication.php";



use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST["type"]) && $_POST["type"] == "add") {
    add();
}elseif (isset($_POST["type"]) && $_POST["type"] == "getEstablishment"){
    getEstablishment();
}elseif (isset($_POST["type"]) && $_POST["type"] == "delete"){
    delete();
}

function getEstablishment(){
    $database = new Database("establishment");
    $columns = [
        "id int not null primary key auto_increment",
        "name varchar(255) not null",
        "type varchar(255) not null",
        "logo text not null",
        "logo_summer text not null",
        "rating text not null"
    ];
    $database->createTable($columns);
    $rows = $database->query(["id","name"], null, null, null, "name DESC", null);
    $result = [
        "status" => "fail"
    ];
    if ($rows != null){
        $result["status"] = "success";
        $result["data"] = $rows;
    }
    echo json_encode($result);
}

function add()
{
    $clientUrl = DOMAIN."newlogin";
    if (isset($_POST["clientName"]) && isset($_POST["bookingId"]) && isset($_POST["date"]) &&
        isset($_POST["establishment"]) && isset($_POST["clientEmail"]) && isset($_FILES["files"])) {

        $database = new Database("customer");
        $columns = [
            "id int not null primary key auto_increment",
            "email varchar(255) not null",
            "name varchar(255) not null",
            "booking_id varchar(255) not null",
            "establishment varchar(255) not null",
            "etab_id int not null",
            "unique_id varchar(255) not null",
            "file BLOB NOT NULL",
            "confirm tinyint NOT NULL DEFAULT 0",
            "alert tinyint NOT NULL DEFAULT -1",
            "date varchar(255) not null"
        ];
        $database->createTable($columns);
        $clientName = $_POST["clientName"];
        $bookingId = $_POST["bookingId"];
        $date = $_POST["date"];
        $establishment = $_POST["establishment"];
        $etabId = $_POST["etab_id"];
        $clientEmail = $_POST["clientEmail"];
        $files = $_FILES["files"];

        /*$phpmailer = new PHPMailer();
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
        $phpmailer->addAddress($clientEmail);*/


        $content = file_get_contents("email.html");
        $content = str_replace("{booking_id}", $bookingId, $content);
        $content = str_replace("{{link}}", $clientUrl, $content);
        $uniqueId = uniqueId();
        $uniqueLink = DOMAIN."admin?u=".$uniqueId;
        $content = str_replace("{unique_link}", $uniqueLink, $content);
        $content = str_replace("{etab}", $establishment, $content);

        $fileText = file_get_contents($files["tmp_name"][0]);
        $rows = csvToArray($fileText);
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

        $month = date("n",strtotime($date));
        $etabDb = new Database("establishment");
        $etabRows = $etabDb->query(null, "id = '".$etabId."'", null, null, null, "1");
        if($etabRows != null){
            $content = str_replace("{etab_type}", $etabRows[0]["type"], $content);
            $content = str_replace("{rating}",  DOMAIN.$etabRows[0]["rating"], $content);
            if ($month == "7" || $month == "8"){
                $content = str_replace("{etab_logo}",  DOMAIN.$etabRows[0]["logo_summer"], $content);
            }else{
                $content = str_replace("{etab_logo}",  DOMAIN.$etabRows[0]["logo"], $content);
            }
        }

//        $phpmailer->Body = $content;

        $result = [
            "status" => "fail",
            "message" => "Something went wrong"
        ];

        $exists = $database->query(null, "email = '" . $clientEmail . "' AND booking_id = '" . $bookingId . "'",
            null, null, null, null);

        $azureCommunication = new AzureCommunication();


        if ($exists == null && $azureCommunication->sendMail($clientEmail, "MMV Rooming", $content)) {
            $colVal = [
                "email" => $clientEmail,
                "name" => $clientName,
                "booking_id" => $bookingId,
                "establishment" => $establishment,
                "etab_id" => $etabId,
                "file" => $fileText,
                "unique_id" => $uniqueId,
                "date" => $date,
            ];

            $diff = strtotime($date) - time();
            if ($diff <= ((60 * 60) * 24) * 5) {
                $colVal["alert"] = 0;
            }

            $database->insert($colVal);
            $result["status"] = "success";
            $result["message"] = "E-mail sent successfully";
        } else {
            if ($exists){
                $result["message"] = "Booking ID exists with that email";
            }else{
//                $result["message"] = $phpmailer->ErrorInfo;
                $result["message"] = "Something went wrong";
            }
        }
        echo json_encode($result);
    }
}


function uniqueId() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 10; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $database = new Database("customer");
    $exists = $database->query(null, "unique_id = '".$randomString."'", null, null,
        null, null);
    if ($exists) {
        return uniqueId();
    }
    return $randomString;
}


function delete(){
    $result = [
        "status" => "fail"
    ];
    $database = new Database("customer");
    if (isset($_POST["id"])){
        $delete = $database->delete("booking_id = '" . $_POST["id"] . "'", null, null, null);
        if ($delete){
            $result["status"] = "success";
        }
    }

    echo json_encode($result);
}