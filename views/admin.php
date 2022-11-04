<?php
require_once "../db/Database.php";
session_name("client");
session_start();
if (isset($_GET["u"])){
    $database = new Database("customer");
    $rows = $database->query(null, "unique_id = '" . $_GET["u"] . "'", null, null,
        null, "1");

    if ($rows){
        $row = $rows[0];
        $verification = new Database("verification");
        $exists = $verification->query(null, "email = '".$row["email"]."' AND booking_id = ".$row["booking_id"]." AND confirm = 1", null,
        null,null, "1");
        if ($exists){
            $_SESSION["email"] = $row["email"];
            $_SESSION["booking_id"] = $row["booking_id"];
        }
    }
}


if (isset($_SESSION["email"]) && isset($_SESSION["booking_id"])) {
    $database = new Database("customer");
    $exists = $database->query(null, "email = '" . $_SESSION["email"] . "' AND booking_id = ".$_SESSION["booking_id"],
        null, null, null, "1");
    if ($exists == null) {
        header("Location: newlogin.php");
        exit();
    }
}else{
    header("Location: newlogin.php");
    exit();
}

if (isset($_GET["logout"])){
    unset($_SESSION["email"]);
    unset($_SESSION["booking_id"]);
    session_destroy();
    header("Location: newlogin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rooming Managment</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.css" />

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/datatables.min.js"></script>

    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css">

    <script language="JavaScript" src="https://code.jquery.com/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script language="JavaScript" src="https://cdn.datatables.net/1.10.4/js/jquery.dataTables.min.js"
        type="text/javascript"></script>
    <script language="JavaScript"
        src="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js"
        type="text/javascript"></script>

    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css">
        <link rel="icon" type="image/x-icon" href="../medias/favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/tableclient.css">
</head>

<body>
    <div class="container-fluid">
        <div class="container">
            <div style="display: flex;justify-content: space-between;align-items: center;">

                <h2><a id="logo" href="#"><img class="logo-mmv"
                            src="https://share.mmv.fr/_data/i/upload/2022/09/06/20220906092007-b7b29418-me.png"
                            alt="logo-mmv"></a></h2>

                <div class="d-flex mobile-icon-wrapper">
                    <div class="d-flex align-items-center pr-2"> <a href="tel:+33(0)492124321" class=""> <img
                                class="mobile-icon"
                                src=""
                                alt=""> </a> </div>
                    <div class="middle-header">
                        <div class="col d-flex pl-0" style="display: flex;align-items: center;">
                            <div class="customtop d-flex">
                            <img src="../medias/casquenoir.png" alt="" width="50" height="50">
                                <div class="customtop-icons"><a href="tel:+33(0)492124321"
                                        class="blanctext top-telf"></a></div>
                                <div class="customtop-text"><a href="tel:+33(0)492124321" class="blanctext"><span
                                            class="top-telf">04 92 12 43 21</span><br>Lundi-Vendredi : 9h-19h<br>Samedi
                                        : 9h-18h</a></div>
                            </div>
                            
                            <div class="disconnect">
                         
                                <a class="lougout" href="?logout=1" style="color: #ff622c !important;font-weight: 700 !important;padding-left: 26px">Déconnexion</a>
                            </div>
                        </div>
                    </div>
                    <div class="profile-wrapper " data-fstrz-fragment-id="fstrz-scss-1"
                        data-fstrz-fragment-selector=".profile-wrapper"> </div>
                </div>


            </div>


        </div>
    </div>

    <div id="content-container" class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-4">
                        <h2> <b>Rooming Management</b></h2>
                    </div>
                    <div class="col-sm-8">
                        <a href="#" class="btn btn-primary" onclick="window.location.reload()"><i
                                class="material-icons">&#xE863;</i></a>
                        <button id="confirm" class="btn btn-primary disabled mr-10">Validation liste de donnée</button>
                    </div>
                </div>
            </div>
            <div class="table-filter">
                <div class="row">

                    <div class="col-sm-3">

                    </div>
                    <div class="col-sm-12">

                        <button class="btn btn-filter" data-toggle="collapse" data-target="#filter_option" aria-expanded="false"
                            aria-controls="filter_option">
                            <i class="fa fa-filter"></i> Filter
                        </button>

                        <div id="filter_option" class="collapse">
                            <button type="button" onclick="filterTable()" class="btn btn-primary"><i
                                    class="fa fa-search"></i></button>

                            <div class="filter-group">
                                <label>Ordre de chambre </label>
                                <select id="room_num_f" class="form-control">
                                    <option value="">Sélectionner..</option>
                                </select>

                            </div>
                            <div class="filter-group">
                                <label>Date</label>
                                <input id="date_f" type="date" class="form-control">
                            </div>
                            <div class="filter-group">
                                <label>Type d'hébergement</label>
                                <input id="query_f" type="text" class="form-control">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <table id="datatable" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th> Ordre </th>
                        <th> Date d'arrivée </th>
                        <th>Type d'hébergement </th>
                        <th>Nom de l'occupant</th>
                        <th>Prénom de l'occupant</th>
                        <th>Date de naissance</th>
                        <th>Observation</th>
                        <th>Editer</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr id="addMenu" class="collapse" style="background: #d5d2d2;">
                        <td>
                            <select id="room_id" style="width:100px;" class="form-control">
                                <option value="">Select..</option>
                            </select>
                        </td>
                        <td><input id="room_type" type="text" placeholder="Type" class="form-control"></td>
                        <td><input id="firstname" type="text" placeholder="First Name" class="form-control"></td>
                        <td><input id="lastname" type="text" placeholder="Last Name" class="form-control"></td>
                        <td><input id="date" type="date" class="form-control" lang="fr-CA" placeholder="jj/mm/aaaa"></td>
                        <td><button class="btn btn-primary" onclick="lastPage()">Add</button></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>


    <script type="text/javascript" src="../js/Http.js"></script>
    <script type="text/javascript" src="../js/tableclient.js"></script>
</body>

</html>