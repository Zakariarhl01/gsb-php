<?php
require_once './mesClasses/Cdao.php';
// Liste des mots de passe à hasher
$query = "select * from employe";
$odao = new Cdao();

$tabEmploye = $odao->gettabDataFromSql($query);

// Fonction pour hasher un mot de passe en SHA-512
function hasherMotDePasse($smdp,$sid) {
    $odao = new Cdao();
    $salt = random_bytes(16); // Génère un sel aléatoire de 16 octets
    $salt = bin2hex($salt); // Met le hash en Hexadecimal
    $motDePasseHash = hash('sha512', $smdp . $salt); // Hash le mot de passe avec le sel   
    $query = "update employe set salt='".$salt."', hashMdp='".$motDePasseHash."' where id='".$sid."';";
    $odao->update($query);
    
}

// Tableau pour stocker les mots de passe hashés
$motsDePasseHashes = [];

// Hasher chaque mot de passe et stocker les résultats dans le tableau
foreach ($tabEmploye as $row) {
    hasherMotDePasse($row['mdp'],$row['id']);
}

?>
