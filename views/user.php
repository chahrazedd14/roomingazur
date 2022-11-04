<?php

require_once "../db/Database.php";
const USERS_COLUMNS = [
    "`id` int NOT NULL PRIMARY KEY AUTO_INCREMENT",
    "`booking_id` int NOT NULL",
    "`email` varchar(255) NOT NULL",
    "`phone` varchar(255) DEFAULT NULL",
    "`password` varchar(255) NOT NULL",
    "`date` bigint NOT NULL",
];

if (isset($_POST["type"]) && $_POST["type"] == "signup") {
    if (isset($_POST["bookingId"]) && isset($_POST["email"]) && isset($_POST["password"])) {
        $database = new Database("users");

        $database->createTable(USERS_COLUMNS);
        $userExists = $database->query(["email"], "email = '" . $_POST["email"] . "'", null,
            null, null, "1");
        $result = [
            "status" => "fail"
        ];
        if ($userExists == null) {
            $colVal = [
                "booking_id" => $_POST["bookingId"],
                "email" => $_POST["email"],
                "password" => $_POST["password"],
                "date" => time(),
            ];
            if (isset($_POST["phone"])) {
                $colVal["phone"] = $_POST["phone"];
            }
            if ($database->insert($colVal)) {
                $result["status"] = "success";
                session_name("client");
                session_start();
                $_SESSION["email"] = $_POST["email"];
            }
        }
        echo json_encode($result);
    }
} elseif (isset($_POST["type"]) && $_POST["type"] == "login") {
    $result = [
        "status" => "fail"
    ];
    $database = new Database("users");
    if (isset($_POST["email"]) && isset($_POST["password"])) {
        $matched = $database->query(null, "email = '" . $_POST["email"] .
            "' AND password = '" . $_POST["password"] . "'",
            null, null, null, "1");
        if ($matched != null) {
            $result["status"] = "success";
            session_name("client");
            session_start();
            $_SESSION["email"] = $_POST["email"];
        }
    }
    echo json_encode($result);
}
