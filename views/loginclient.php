<?php
require_once "user.php";
require_once "../db/Database.php";
$database = new Database("users");
$database->createTable(USERS_COLUMNS);
session_name("client");
session_start();
if (isset($_SESSION["email"])) {
    $userExists = $database->query(["email"], "email = '" . $_SESSION["email"] . "'", null,
        null, null, "1");
    if ($userExists != null) {
        header("Location: newlogin.php");
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
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/loginclient.css">
</head>
<body id="loginclient">


<div class="container">
    <section id="formHolder">

        <div class="row">

            <!-- Brand Box -->
            <div class="col-sm-6 brand">
                <a href="#" class="logo">Mmvrooming<span>.</span></a>

                <div class="heading">
                    <h2>MMV</h2>
                    <p>les Vacances Club</p>
                </div>

                <div class="success-msg">
                    <p>Great! You are one of our members now</p>
                    <a href="#" class="profile">Your Profile</a>
                </div>
            </div>


            <!-- Form Box -->
            <div class="col-sm-6 form">

                <!-- Login Form -->
                <div class="login form-peice switched">
                    <form class="login-form" action="#" method="post">
                        <div class="form-group">
                            <label for="loginemail">Email Adderss</label>
                            <input type="email" name="loginemail" id="loginemail" required>
                        </div>

                        <div class="form-group">
                            <label for="loginPassword">Password</label>
                            <input type="password" name="loginPassword" id="loginPassword" required>
                        </div>

                        <div class="CTA">
                            <input id="login" type="submit" value="Login">
                            <a href="#" class="switch">I'm New</a>
                        </div>
                    </form>
                </div><!-- End Login Form -->


                <!-- Signup Form -->
                <div class="signup form-peice">
                    <form class="signup-form" action="#" method="post">

                        <div class="form-group">
                            <label for="name">Nnuméro de réservation</label>
                            <input type="text" name="username" id="name" class="name">
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Adderss</label>
                            <input type="email" name="emailAdress" id="email" class="email">
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number - <small>Optional</small></label>
                            <input type="text" name="phone" id="phone">
                        </div>

                        <div class="form-group">
                            <label for="password">Mot de passe</label>
                            <input type="password" name="password" id="password" class="pass">
                            <span class="error"></span>
                        </div>

                        <div class="form-group">
                            <label for="passwordCon">Confirmez le mot de passe</label>
                            <input type="password" name="passwordCon" id="passwordCon" class="passConfirm">
                            <span class="error"></span>
                        </div>

                        <div class="CTA">
                            <input type="submit" value="Signup Now" id="submit">
                            <a href="#" class="switch">I have an account</a>
                        </div>
                    </form>
                </div><!-- End Signup Form -->
            </div>
        </div>

    </section>


    <footer>
        <p>
            ©&nbsp;2022, Tous les droits sont réservés. <a href="http:/mmv.fr" target="_blank">MMV</a>
        </p>
    </footer>

</div>


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="../js/loginclient.js"></script>
<script src="../js/Http.js"></script>
</body>
</html>