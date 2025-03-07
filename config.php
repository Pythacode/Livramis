<?php
session_start();
//session_set_cookie_params(0);

// Mod de passe bdd : s*dwoI9XRnq8MIHC
// Définir un temps d'expiration de session
$timeout = 600; // 10 minutes
/*
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $timeout)) {
    session_unset();
    session_destroy();
    header("Location: /login"); // Rediriger vers la page de connexion
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // Met à jour l'activité
*/

// Connexion à la base de données (si nécessaire)
try {
    $bdd = new PDO("mysql:host=localhost;dbname=livramis;charset=utf8;", "livramis", "s*dwoI9XRnq8MIHC");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
};
function rrmdir($src) {
    $dir = opendir($src);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            $full = $src . '/' . $file;
            if ( is_dir($full) ) {
                rrmdir($full);
            }
            else {
                unlink($full);
            }
        }
    }
    closedir($dir);
    rmdir($src);
}

?>
