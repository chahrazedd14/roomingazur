<?php


require_once "../db/Database.php";
require_once "vendor/PHPMailer/src/PHPMailer.php";
require_once "vendor/PHPMailer/src/Exception.php";
require_once "vendor/PHPMailer/src/SMTP.php";
require_once "AzureCommunication.php";

use PHPMailer\PHPMailer\PHPMailer;

error_reporting(E_ERROR | E_PARSE);

if (isset($_POST["type"]) && $_POST["type"] == "get") {
    getRow();
} elseif (isset($_POST["type"]) && $_POST["type"] == "update") {
    updateRow();
} elseif (isset($_POST["type"]) && $_POST["type"] == "roomId") {
    getUniqueRoomId();
} elseif (isset($_POST["type"]) && $_POST["type"] == "getStatus") {
    getStatus();
} elseif (isset($_POST["type"]) && $_POST["type"] == "confirm") {
    sendConfirm();
}

function sendConfirm()
{
    session_name("client");
    session_start();
    $result = [
        "status" => "fail"
    ];
    if (isset($_SESSION["email"]) && isset($_SESSION["booking_id"])) {
        $email = $_SESSION["email"];
        $database = new Database("customer");
        $query = $database->query(null, "email = '" . $email . "' AND booking_id = '".$_SESSION["booking_id"]."'", null,
            null, "id desc", "1");
        if ($query != null) {
            $file = $query[0]["file"];
           /* $phpmailer = new PHPMailer();
            $phpmailer->isSMTP();
            $phpmailer->CharSet  = "UTF-8"; 
            $phpmailer->Host = 'ssl0.ovh.net';
            $phpmailer->SMTPAuth = true;
            $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $phpmailer->Port = 587;
            $phpmailer->Username = 'emails@chahrazed-durand.com';
            $phpmailer->Password = 'Sherashera2626';
            $phpmailer->From = 'c.durand@mmv.fr';
            $phpmailer->FromName = 'MMV Rooming';
            $phpmailer->Subject = "MMV Rooming";
            $phpmailer->isHTML(true);
            $phpmailer->addAddress("c.durand@mmv.fr");
            $phpmailer->Body = "Client " . $email . " a remplir la romming";
            $phpmailer->addStringAttachment($file, "Booking " . $query[0]["booking_id"] . "-V" . ($query[0]["modified"] + 1) . ".csv");
            */
            $azureCommunication = new AzureCommunication();
            if ($azureCommunication->sendMail("c.durand@mmv.fr", "MMV Rooming",
                "Client " . $email . " a remplir la romming", $file,
                "Booking " . $query[0]["booking_id"] . "-V" . ($query[0]["modified"] + 1) . ".csv", "txt")) {
                $result["status"] = "success";
                $colVal = [
                    "confirm" => "1",
                    "modified" => intval($query[0]["modified"]) + 1
                ];
                $database->update($colVal, "id = '" . $query[0]["id"] . "'", null, "1");
            }
        }
    }
    echo json_encode($result);
}

function updateRow()
{
    session_name("client");
    session_start();
    $result = [
        "status" => "fail",
        "message" => "Something went wrong"
    ];
    if (isset($_SESSION["email"]) && isset($_SESSION["booking_id"])) {
        $email = $_SESSION["email"];
        $bookingId = $_SESSION["booking_id"];
        $database = new Database("customer");
        $query = $database->query(null, "email = '" . $email . "' AND booking_id = '".$bookingId."'",
            null, null, "id desc", "1");
        if ($query != null) {
            $file = $query[0]["file"];
            $rows = csvToArray($file);
            $keyDone = false;
            $keys = [];
            $id = 0;
            $same = false;
            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (!$keyDone) {
                    $keyDone = true;
                    $keys = $row;
                } else {
                    if ($id == $_POST["id"]) {
                        if ($row[6] == $_POST["firstname"] && $row[7] == $_POST["lastname"] &&
                            $row[8] == $_POST["birthday"] && $row[22] == $_POST["observation-client"]){
                            $same = true;
                        }
                        $row[6] = $_POST["firstname"];
                        $row[7] = $_POST["lastname"];
                        $row[8] = $_POST["birthday"];
                        $row[22] = $_POST["observation-client"];
                        $rows[$i] = $row;
                    }
                    $id++;
                }
            }

            $newFile = "";
            foreach ($rows as $row) {
                $newFile .= implode(";", $row) . "\n";
            }

            $columns = [
                "file" => $newFile
            ];
            $update = $database->update($columns, "email = '" . $email . "' AND booking_id = '".$bookingId."'",
                null, "1");
            if ($update || $same) {
                $result["status"] = "success";
                $result["message"] = "enregistrer avec succes";
            }
        }
    }

    echo json_encode($result);
}

function getRow()
{
    session_name("client");
    session_start();
    $result = [
        "status" => "fail",
        "message" => ""
    ];
    if (isset($_SESSION["email"])) {
        $email = $_SESSION["email"];
        $database = new Database("customer");
        $query = $database->query(null, "email = '" . $email . "'", null,
            null, "id desc", "1");
        if ($query != null) {
            $result["status"] = "success";
            $file = $query[0]["file"];
            $rows = csvToArray($file);
            $keyDone = false;
            $keys = [];
            $customData = [];
            $id = 0;
            foreach ($rows as $row) {
                if (!$keyDone) {
                    $keyDone = true;
                    $keys = $row;
                } else {
                    $r = [];
                    $r["id"] = $id++;
                    $r["no"] = $row[0];
                    $r["room_num"] = $row[3];
                    $r["type"] = $row[4];
                    $r["firstname"] = $row[6];
                    $r["lastname"] = $row[7];
                    $r["birthday"] = $row[8];
                    $r["arrive_date"] = $row[1];
                    $r["observation_client"] = $row[22];

                    $customData[] = $r;
                }
            }

            $result["recordsTotal"] = count($rows);
            $result["recordsFiltered"] = count($rows);

            if (isset($_POST["order"])) {

                $column = null;
                if ($_POST["order"][0]["column"] == 0) {
                    $column = "no";
                } elseif ($_POST["order"][0]["column"] == 1) {
                    $column = "type";
                } elseif ($_POST["order"][0]["column"] == 2) {
                    $column = "firstname";
                } elseif ($_POST["order"][0]["column"] == 3) {
                    $column = "lastname";
                } elseif ($_POST["order"][0]["column"] == 4) {
                    $column = "birthday";
                }

                $col = array_column($customData, $column);
                array_multisort($col,
                    $_POST["order"][0]["dir"] == "asc" ? SORT_ASC : SORT_DESC, $customData);

            }
            if ($_POST["room_num"] != "") {
                $customData = array_filter($customData, function ($var) {
                    return $var["room_num"] == $_POST["room_num"];
                });
            }
            if ($_POST["date"] != "") {
                $customData = array_filter($customData, function ($var) {
                    $newDate = date("d/m/Y", strtotime($_POST["date"]));
                    return $var["arrive_date"] == $_POST["date"] || $var["arrive_date"] == $newDate;
                });
            }
            if (!function_exists('str_contains')) {
                function str_contains(string $haystack, string $needle)
                {
                    if ('' === $needle || false !== strpos($haystack, $needle)) {
                        return 0;
                    } else {
                        return -1;
                    }
                }
            }

            if ($_POST["query"] != "") {
                $customData = array_filter($customData, function ($var) {
                    return str_contains($var["type"], $_POST["query"]) == 0;
                });
            }

            $customData = array_slice($customData, $_POST["start"], $_POST["length"]);
            $result["data"] = $customData;
        }
    }
    echo json_encode($result);
}


function getStatus()
{
    session_name("client");
    session_start();
    $result = [
        "status" => "fail",
        "message" => ""
    ];
    if (isset($_SESSION["email"]) && isset($_SESSION["booking_id"])) {
        $email = $_SESSION["email"];
        $database = new Database("customer");
        $query = $database->query(null, "email = '" . $email . "' AND booking_id = '".$_SESSION["booking_id"]."'", null,
            null, "id desc", "1");
        if ($query != null) {
            $result["status"] = "success";
            $file = $query[0]["file"];
            $rows = csvToArray($file);
            $keyDone = false;
            $keys = [];
            $id = 0;
            $confirm = true;
            $ids = [];
            foreach ($rows as $row) {
                if (!$keyDone) {
                    $keyDone = true;
                    $keys = $row;
                } else {
                    $r = [];
                    $r["id"] = $id++;
                    $r["no"] = $row[0];
                    $r["type"] = $row[4];
                    $r["firstname"] = $row[6];
                    $r["lastname"] = $row[7];
                    $r["birthday"] = $row[8];
                    $r["arrive_date"] = $row[1];
                    $r["observation_client"] = $row[22];
                    if (empty($r["firstname"]) || empty($r["lastname"]) || empty($r["birthday"]) ||
                        empty($r["arrive_date"])) {
                        $ids[$row[0]][] = false;
                    }else{
                        $ids[$row[0]][] = true;
                    }
                }
            }
            foreach ($ids as $key => $value){
                if (!in_array(true, $value)){
                    $confirm = false;
                    break;
                }
            }
            $result["confirm"] = $confirm;
        }
    }
    echo json_encode($result);
}


function getUniqueRoomId()
{
    session_name("client");
    session_start();
    $result = [
        "status" => "fail",
        "message" => ""
    ];
    if (isset($_SESSION["email"])) {
        $email = $_SESSION["email"];
        $database = new Database("customer");
        $query = $database->query(null, "email = '" . $email . "'", null,
            null, "id desc", "1");
        if ($query != null) {
            $result["status"] = "success";
            $file = $query[0]["file"];
            $rows = csvToArray($file);
            $keyDone = false;
            $keys = [];
            $ids = [];
            $id = 0;
            foreach ($rows as $row) {
                if (!$keyDone) {
                    $keyDone = true;
                    $keys = $row;
                } else {
                    $ids[] = $row[3];
                }
            }

            $result["data"] = array_unique($ids);
        }
    }
    echo json_encode($result);
}

function csvToArray($content)
{
    $lines = explode("\n", $content);
    $data = [];
    foreach ($lines as $line) {
        if (empty($line)) {
            continue;
        }
        $values = preg_split("/[,;]/", $line);
        $data[] = $values;
    }

    return $data;
}
