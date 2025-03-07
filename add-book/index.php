<?php
require_once './../config.php';

if (!isset($_SESSION['id'])) {
    header('Location: /login/?redirect=/add-book');
    exit;
}

function cleanQuillContent($html) {
    // Liste des balises autorisées
    $allowedTags = "<p><span><strong><em><u><s><blockquote><a><div><br><pre><ol><ul><li>";

    // Nettoyer avec strip_tags
    $safeContent = strip_tags($html, $allowedTags);

    // Supprimer les attributs dangereux (ex: onclick, onerror, etc.)
    $safeContent = preg_replace('/on\w+=".*?"/i', '', $safeContent);

    // Nettoyer les liens pour éviter le JavaScript injecté
    $safeContent = preg_replace('/href="javascript:.*?"/i', 'href="#"', $safeContent);

    return $safeContent;
}


// Vérifier si le formulaire a été soumis et si un fichier a été téléchargé
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $author = $_POST["author"];

    echo $_FILES['image']['size'];

    $resume = cleanQuillContent($_POST["resume"]);

    $sql_request = $bdd->prepare("INSERT INTO books (title, synopsis, state, author, user_id, have_picture, categorie) VALUES (:title, :resume, :state, :author, :user_id, :have_picture, :categorie)");

    $sql_request->bindValue(':title', $_POST["title"]);
    $sql_request->bindValue(':resume', $resume);
    $sql_request->bindValue(':state', "Disponible");
    $sql_request->bindValue(':author', $author);
    $sql_request->bindValue(':user_id', $_SESSION["id"]);
    $sql_request->bindValue(':categorie', $_POST["categorie"]);


    if ($_FILES['image']['size']==0) { // Pas d'image
        $sql_request->bindValue(':have_picture', 0); //echo 'Pas d\'image';
    } else { // Il y a une image
        $sql_request->bindValue(':have_picture', 1);
        //echo'image';
    }

    $sql_request->execute();

    $book_id = $bdd->lastInsertId();

    $sql_request = $bdd->prepare("INSERT INTO `user_to_books` (id_user, id_book) VALUES (:userID, :bookID)");
    $sql_request->bindValue(':userID', $_SESSION["id"]);
    $sql_request->bindValue(':bookID', $book_id);

    $sql_request->execute();

    $sql_request = $bdd->prepare('SELECT id FROM `authors` WHERE name = :name');
    $sql_request->bindValue(':name', $author);

    $sql_request->execute();

    $result = $sql_request->fetch(PDO::FETCH_ASSOC);

    if ($result === false) {
        $sql_request = $bdd->prepare("INSERT INTO `authors` (name) VALUES (:name)");
        $sql_request->bindValue(':name', $author);
        $sql_request->execute();
    
        $author_id = $bdd->lastInsertId();

    } else {
        $author_id = $result['id'];
    }

    $sql_request = $bdd->prepare("INSERT INTO `author_to_books` (id_author, id_book) VALUES (:authorID, :bookID)");
    $sql_request->bindValue(':authorID', $author_id);
    $sql_request->bindValue(':bookID', $book_id);

    $sql_request->execute();

    if (!($_FILES['image']['size']==0)) { // Il y a une image
        echo "Image il y a";
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_name = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    
        // Vérification du type MIME
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);
    
        if (!in_array($mime_type, $allowed_types)) {
            echo "Erreur : Le fichier n'est pas une image valide.";
            exit;
        }
    
        // Charger l'image avec GD
        switch ($mime_type) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($file_tmp);
                break;
            case 'image/png':
                $image = imagecreatefrompng($file_tmp);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($file_tmp);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($file_tmp);
                break;
            default:
                echo "Format non supporté.";
                exit;
        }
    
        if (!$image) {
            echo "Erreur : Impossible de traiter l'image.";
            exit;
        }

        if (!file_exists('./../books/' . $book_id)) {mkdir('./../books/' . $book_id);}
    
        $output_file = "./../books/" . $book_id . "/couverture.jpg";
        imagejpeg($image, $output_file, 100); // Qualité 100%
    
        // Libérer la mémoire
        imagedestroy($image);
    }


    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre - Livramis</title>
    <link rel="stylesheet" href="./../res/css/index.css">
    <link rel="icon" type="image/png" href="./../res/img/logo/square_logo.png">
</head>
<!-- Inclusion du fichier HTML header -->
<?php
include("./../res/html/header.html");
?>
<body>
<?php
include("./add-book.html");
?>
</body>
</html>
