<?php
require_once './config.php';

function slugify($text) {
    $text = strtolower($text); // Minuscule
    $text = preg_replace('/[^a-z0-9]+/', '-', $text); // Remplace les caractères spéciaux par "-"
    $text = trim($text, '-'); // Supprime les "-" en début/fin
    return $text;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Livramis</title>
    <link rel="stylesheet" href="./res/css/index.css">
    <link rel="stylesheet" href="./home.css">
    <link rel="icon" type="image/png" href="./res/img/logo/square_logo.png">
</head>
<!-- Inclusion du fichier HTML header -->
<?php
include("./res/html/header.html");
?>
<body>
<?php

if (isset($_GET["categorie"]))  {
    $sql_request = $bdd->prepare("SELECT * FROM `books` WHERE categorie = :categorie");
    $sql_request->bindValue(":categorie", $_GET["categorie"]);
} else {
    $sql_request = $bdd->prepare("SELECT * FROM `books`");
}

$sql_request->execute();
$books = $sql_request->fetchAll(PDO::FETCH_ASSOC);


?>
<div class="books">
    <?php
        if (empty($books)) {
            echo "Aucun livre trouvé :/";
        }
        foreach($books as $book) {
            $book_link = "/book/" . slugify($book["title"]) . "-" . $book["id"];
            $sql_request = $bdd->prepare("SELECT * FROM `users` WHERE id = :id");
            $sql_request->bindValue(":id", $book["user_id"]);
            $sql_request->execute();

            $user = $sql_request->fetch(PDO::FETCH_ASSOC);

            $sql_request = $bdd->prepare("SELECT username FROM `users` WHERE id = :id");
            $sql_request->bindValue(':id', $book["user_id"]);
            $sql_request->execute();
            
            $username = $sql_request->fetch(PDO::FETCH_ASSOC);

            $proprietaire =  $username["username"];
                                    
            ?>
            <div class="book">
                <p class="book-url"><?php echo $book_link; ?></p>
                <div class="book-conteneur">
                    <a href="<?php echo $book_link; ?>">
                        <img class="couverture" src="<?php echo $book["have_picture"] ? "./books/".$book["id"]."/couverture.jpg" : "./books/default/couverture.jpg"; ?>" alt="">
                    </a>
                    <div>
                        <a class="invisible" href="<?php echo $book_link; ?>"><h2 class="title"><?php echo $book["title"]; ?></h2></a>
                        <h3 class="author"><?php echo $book["author"]; ?></h3>
                        <hr>
                        <div class="infos">
                                <h3 class="info">Catégorie : <?php echo $book["categorie"] ?></h3>
                                <h3  class="info">Disponibilité : <?php echo $book["state"] ?></h3>
                                
                                    <h3  class="info">Propriétaire : <a class="no-opened" href="/user/<?php echo $proprietaire; ?>"><?php echo $proprietaire; ?></a>
                                    </h3>
                                
                                <h2>Synopsis :</h2>
                                <div><?php echo $book["synopsis"] ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    ?>
</div>
<script src="./home.js" defer></script>
</body>
</html>
