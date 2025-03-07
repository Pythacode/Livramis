<?php
require_once './../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = htmlspecialchars($_POST["username"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $email = htmlspecialchars($_POST["email"]);
    $firstname = htmlspecialchars($_POST["first-name"]);
    $lastname = htmlspecialchars($_POST["last-name"]);

    $stmt = $bdd->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    // Vérifie si le nom d'utilisateur existe déjà
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        echo '<script type="text/javascript">
                document.addEventListener("DOMContentLoaded", function() {
                    document.getElementById("error").textContent = "Le nom d\'utilisateur n\'est pas disponible"
                });
              </script>';
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Inscription - Livramis</title>
            <link rel="stylesheet" href="/res/css/index.css">
            <link rel="icon" type="image/png" href="/res/img/logo/square_logo.png">
        </head>
        <body>
            <?php include "./../res/html/header.html"; ?>
            <?php include "signin.html"; ?>
        </body>
        </html>
        <?php

        exit;  // Interrompre l'exécution du script PHP
    
    }

    $sql_request = $bdd->prepare("INSERT INTO users (username, password, first_name, last_name, email, is_admin, biographie) VALUES (:username, :password, :first_name, :last_name, :email, :is_admin, :biographie)");

    $sql_request->bindValue(':username', $username);
    $sql_request->bindValue(':password', $password);
    $sql_request->bindValue(':first_name', $firstname);
    $sql_request->bindValue(':last_name', $lastname);
    $sql_request->bindValue(':email', $email);
    $sql_request->bindValue(':is_admin', 0);
    $sql_request->bindValue(':biographie', "");

    $sql_request->execute();

    // Connection

    $get_user = $bdd->prepare("SELECT * FROM `users` WHERE username = :username");
    $get_user->bindValue(':username', $username);

    $get_user->execute();

    $result = $get_user->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $_SESSION["username"] = $result["username"];
        $_SESSION["id"] = $result["id"];
        $_SESSION["first_name"] = $result["first_name"];
        $_SESSION["last_name"] = $result["last_name"];
        $_SESSION["email"] = $result["email"];
        $_SESSION["is_admin"] = boolval($result["is_admin"]);
        $_SESSION["biographie"] = $result["biographie"];

        header("Location: /");
        exit();
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Inscription - Livramis</title>
        <link rel="stylesheet" href="/res/css/index.css">
        <link rel="icon" type="image/png" href="/res/img/logo/square_logo.png">
    </head>
    <body>
        <?php include "./../res/html/header.html"; ?>
        <?php include "signin.html"; ?>
    </body>
    </html>
    <?php
}

?>