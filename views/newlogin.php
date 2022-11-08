<?php
require_once "../db/Database.php";
session_name("client");
session_start();

if (isset($_SESSION["email"]) && isset($_SESSION["booking_id"])) {
    $database = new Database("customer");
    $exists = $database->query(null, "email = '" . $_SESSION["email"] . "' AND booking_id = ".$_SESSION["booking_id"],
        null, null, null, "1");
    if ($exists != null) {
        header("Location: admin");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="../css/loginclient.css">
    <link rel="icon" type="image/x-icon" href="../medias/favicon.ico">
    <title>MMV-Romming Apps</title>
</head>



<div class="container-fluid bg-dark">

    <div class="container py-2">
        <div style="display: flex;justify-content: space-between;align-items: center;">

            <h2><a id="logo" href="#"><img class="logo-mmv" src="../medias/Logo-new.png"
                        alt="logo-mmv" width="160"></a></h2>

            <div class="d-flex mobile-icon-wrapper">
                <div class="d-flex align-items-center pr-2"> <a href="tel:+33(0)492124321" class=""> <img
                            class="mobile-icon" src="" alt=""> </a> </div>
                <div class="middle-header">
                    <div class="col d-flex pl-0">
                        <div class="customtop d-flex">
                        <img src="../medias/mobile.png" alt="" width="50" height="50">
                            <div class="customtop-icons"><a href="tel:+33(0)492124321" class="blanctext top-telf"></a>
                            </div>
                            <div class="customtop-text"><a href="tel:+33(0)492124321" class="blanctext"><span
                                        class="top-telf font-weight-bold">04 92 12 43 21</span><br>9h à 13h et de 14h à 18h  <br> du lundi au vendredi</a></div>
                        </div>
                    </div>
                </div>
                <div class="profile-wrapper " data-fstrz-fragment-id="fstrz-scss-1"
                    data-fstrz-fragment-selector=".profile-wrapper"> </div>
            </div>


        </div>


    </div>

</div>

<div class="container-fluid pt-5">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="img-elan" style="">
                    <img class="img-elan" src="../medias/CARRÉ post elan de nouveauté.jpg" alt="" width="100%">
                </div>
            </div>

            <div class="form col-6">
                <form action="/" method="POST">
                    <div class="back"><span><i class="fa fa-arrow-circle-left" aria-hidden="true"></i></span></div>

                    <h2 class="style-etab">MMV Rooming </h2>
                    <h3>Entrez vos identifiants</h3>
                    <div class="inputs">
                        <div class="email">
                            <input id="email" class="first" type="text" placeholder="sophie@example.com" required/>
                            <button class="next">Suivant <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z" />
                                </svg></button>
                        </div>
                        <div class="password">
                            <input id="password" class="second" type="password" placeholder="Numéro de réservation" />
                            <button class="login">Connexion</button>
                        </div>
                    </div>
                </form>
            </div>


            <div class="col-6 bg-verrification">

                <form class="verification">
                    <section class="verification__wrap">
                        <header class="verification__header">
                            <h2 class="verification__title">Verification Code</h2>
                            <p id="verification-description" class="verification__description">
                            Vous avez reçu sur votre e-mail  <span> {{email}}</span> un code à 4 chiffres. <br>
Merci de le renseigner ci-dessous

                            </p>
                        </header>

                        <section class="verification__fields">
                            <fieldset class="verification__field">
                                <legend>
                                    <!-- HINT : write something here for more accesability  -->
                                </legend>

                                <input type="text" class="verification__input verification__input--1"
                                    id="verification-input-1" placeholder="-" maxlength="1" />
                                <input type="text" class="verification__input verification__input--2"
                                    id="verification-input-2" placeholder="-" maxlength="1" />
                                <input type="text" class="verification__input verification__input--3"
                                    id="verification-input-3" placeholder="-" maxlength="1" />
                                <input type="text" class="verification__input verification__input--4"
                                    id="verification-input-4" placeholder="-" maxlength="1" />
                            </fieldset>
                        </section>

                        <section class="verification__verify">
                            <p>
                                <button type="button" class="verification__verify_btn">Connexion</button>
                            </p>
                        </section>

                        <section class="verification__timeout">
                            <p  id="verification-counter-text">
                            attendez svp<strong class="verification__counter">00 : 00</strong> secondes pour envoyer un nouveau code
                            </p>
                            <button id="verification-send-new" type="button" class="verification__send_new">obtenir un nouveau code</button>
                        </section>

                    </section>
                </form>

            </div>
        </div>
    </div>


    <p class="warning"></p>


    <button type="button" class="theme d-none"> LIGHT</button>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="../js/loginclient.js"></script>
    <script src="../js/Http.js"></script>
    </body>

</html>