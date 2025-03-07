<?php
require_once './../config.php';

$id = explode("-", $_GET["title"]);
$id = end($id);

$sql_request = $bdd->prepare("SELECT * FROM `books` WHERE id = :id");
$sql_request->bindValue(":id", $id);
$sql_request->execute();

$book = $sql_request->fetch(PDO::FETCH_ASSOC);

if ($book == false) {
    header('Location: /404.php');
}

$sql_request = $bdd->prepare("SELECT username FROM `users` WHERE id = :id");
$sql_request->bindValue(':id', $book["user_id"]);
$sql_request->execute();

$username = $sql_request->fetch(PDO::FETCH_ASSOC);

$proprietaire =  $username["username"];

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    
    if (isset($_SESSION['id'])) {
        if ($book["user_id"] == $_SESSION['id'] || $_SESSION["is_admin"] ) {
            $sql_request = $bdd->prepare("DELETE FROM `books` WHERE id = :id;");
            $sql_request->bindValue(":id", $id);
            $sql_request->execute();
            
            $sql_request = $bdd->prepare("DELETE FROM `author_to_books` WHERE  id_book = :id;");
            $sql_request->bindValue(":id", $id);
            $sql_request->execute();
            
            $sql_request = $bdd->prepare("DELETE FROM `user_to_books` WHERE id_book = :id;");
            $sql_request->bindValue(":id", $id);
            $sql_request->execute();

            if (file_exists("./../books/" . $id)) {
                rrmdir("./../books/" . $id);
            }

            http_response_code(200);

        } elseif (!($book["user_id"] == $_SESSION['id'])){
            http_response_code(403);
            echo '';
        }
    } else {
        http_response_code(401);
        echo '';
    }
    exit();
    
} elseif ($_SERVER['REQUEST_METHOD'] == 'GET') {

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/res/css/index.css">
        <link rel="stylesheet" href="/res/css/book.css">
        <script src="/res/js/book.js" defer></script>
        <title><?php echo $book["title"] ?> - Livramis</title>
    </head>
    <body>
        <?php include "./../res/html/header.html"; ?>
        <div class="book">
            <img class="couverture" src="<?php echo $book["have_picture"] ? "./../books/".$book["id"]."/couverture.jpg" : "./../books/default/couverture.jpg"; ?>" alt="Couverture de <?php echo $book["title"] ?>">
            
            <div  style="position : relative;width: 100%;">
                <h2 class="title"><?php echo $book["title"]; ?></h2>
                <h3 class="author"><?php echo $book["author"]; ?></h3>
                <div class="button-top-right">
                    <?php
                    if (isset($_SESSION['id'])) {
                        if ($book["user_id"] == $_SESSION['id'] || $_SESSION["is_admin"] ) {
                            echo '<a class="button" onclick="remove()">Suprimer</a>';
                        } if (!($book["user_id"] == $_SESSION['id'])) {
                            echo '<a class="button" onclick="reserve()">Reserver</a>';
                        }
                    } else {
                        echo '<a class="button" href="/login?redirect=/book/' . $_GET["title"] . '">Se connecter pour reserver</a>';
                    }
                        
                    ?>
                </div>
                <hr>
                <div class="infos">
                        <h3 class="info">Type : <?php echo $book["categorie"] ?></h3>
                        <h3  class="info">Disponibilité : <?php echo $book["state"] ?></h3>
                        <h3  class="info">Propriétaire : <a class="no-opened" href="/user/<?php echo $proprietaire; ?>"><?php echo $proprietaire; ?></a></h3>
                        <h2>Synopsis :</h2>
                        <div><?php echo $book["synopsis"] ?></div>
                </div>
            </div>
        </div>
        <div id="modal-overlay" class="modal-overlay" style="display: none;"></div>
        <div id="modal-fenetre-reserve" class="modal-fenetre" style="display: none;">
            <h1>Reservation en cours...</h1>
            <img class="loading" src="<?php echo $book["have_picture"] ? "./../books/".$book["id"]."/couverture.jpg" : "./../books/default/couverture.jpg"; ?>" alt="">
        </div>
        <div id="modal-fenetre-remove" class="modal-fenetre" style="display: none;">
            <h1>Suprimer un livre</h1>
            <h2 id="text" style="margin: 0;">Veut tu vraiment suprimer çe livre ?</h2>
            <h3 id="sub-text" style="display:none;"></h3>
            <div id="button-validate-delete" style="margin-top: 15px;">
                <a class="button" onclick="remove_confirmed()">Oui</a>
                <a class="button" onclick="closeModal('remove')">Non</a>
            </div>
            <img style="display:none;" id="loading-delete" class="loading" src="<?php echo $book["have_picture"] ? "./../books/".$book["id"]."/couverture.jpg" : "./../books/default/couverture.jpg"; ?>" alt="">
            <a href="/" class="button" style="display:none;" id="home-button">Retour à l'aceuil</a>
        </div>
    </body>
    </html>
<?php

}