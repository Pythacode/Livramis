<?php
require_once './../config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = htmlspecialchars($_POST["username"]);
    $password = $_POST["password"];
    
    $sql_request = $bdd->prepare("SELECT * FROM `users` WHERE username = :username");
    $sql_request->bindValue(':username', $username);

    $sql_request->execute();

    $user = $sql_request->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["username"] = $user["username"];
            $_SESSION["id"] = $user["id"];
            $_SESSION["first_name"] = $user["first_name"];
            $_SESSION["last_name"] = $user["last_name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["is_admin"] = boolval($user["is_admin"]);
            $_SESSION["biographie"] = $user["biographie"];

            $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : '/';
            
            if (str_starts_with($redirect, "/")) {
                header("Location: " . $redirect);
            } else {
                header("Location: /");
            }
            
            exit();

        } else  {
            echo '<script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("error").textContent = "Le mot de passe n\'est pas correct"
            });
          </script>';
        }
    } else  {
        echo '<script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("error").textContent = "Aucun utilisateur trouvé"
        });
      </script>';
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Connection Livramis</title>
        <link rel="stylesheet" href="/res/css/index.css">
        <link rel="icon" type="image/png" href="/res/img/logo/square_logo.png">
    </head>
    <body>
        <?php include "./../res/html/header.html"; ?>
        <?php include "login.html"; ?>
    </body>
    </html>
    <?php

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Connection Livramis</title>
        <link rel="stylesheet" href="/res/css/index.css">
        <link rel="icon" type="image/png" href="/res/img/logo/square_logo.png">
    </head>
    <body>
        <?php include "./../res/html/header.html"; ?>
        <?php include "login.html"; ?>
    </body>
    </html>
    <?php
}

?>