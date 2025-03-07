<?php
require_once './../config.php';

session_unset();
session_destroy();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Déconnection - Livramis</title>
    <link rel="stylesheet" href="/res/css/simple_page.css">
    <link rel="stylesheet" href="/res/css/index.css">
    <link rel="icon" type="image/png" href="./res/img/logo/square_logo.png">
</head>
<!-- Inclusion du fichier HTML header -->
<?php
include("./../res/html/header.html");
?>
<body>
    <div class="center">
        <h1>Déconnection effective !</h1>
        <a href="/" class="button">Acceuil</a>
        <a href="/login" class="button">Connection</a>
    </div>
</body>
</html>