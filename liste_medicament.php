<?php
session_start(); // Ajout de session_start

require_once 'includes/head.php'; 
require_once 'mesClasses/Cmedicaments.php'; 
require_once 'mesClasses/Cpresenter.php'; 
require_once './mesClasses/Cvisiteurs.php';

// Récupérer les informations sur les médicaments à présenter pour le mois en cours
$presenters = new Cpresenters();
$medicaments = new Cmedicaments();

// Obtenir le mois suivant au format "mois-année"
$moisSuivant = date('m-Y', strtotime('+1 month'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des médicaments</title>
</head>
<body>
    <div class="container">
        <header title="listemedicament"></header>
        <?php require_once 'includes/navBar.php'; ?>
        <h1>Liste des médicaments pour le <?= $moisSuivant ?></h1>
        <div title="filtrage">  
            <form method="POST" action="">
                <label for="danger">Filtrer par niveau de danger :</label>
                <select name="danger" id="danger">
                    <option value="">Tous</option>
                    <option value="1">Faible</option>
                    <option value="2">Moyen</option>
                    <option value="3">Élevé</option>
                </select>
                <button type="submit">Filtrer</button>
            </form>

            <form method="POST" action="">
                <label for="date">Filtrer par date de sortie :</label>
                <input type="date" name="date" id="date">
                <button type="submit">Filtrer</button>
            </form>

            <form method="POST" action="">
                <label for="search">Rechercher par nom :</label>
                <input type="text" name="search" id="search">
                <button type="submit">Rechercher</button>
            </form>
        </div>  

        <?php
     // Vérifier si l'ID du visiteur connecté est défini dans la session
if (isset($_SESSION['visiteur'])) {
    // Désérialiser la chaîne pour accéder aux propriétés de l'objet
    $visiteur = unserialize($_SESSION['visiteur']);
    $idVisiteur = $visiteur->id;
    
    // Exécuter la requête en passant l'ID du visiteur et le mois suivant
    $omedicaments = $medicaments->getMedicamentsForVisitor($idVisiteur, $moisSuivant);

    // Afficher les informations pour chaque médicament
    foreach ($omedicaments as $medicament) { ?>
        <div class="medicament-card">
            <img src="<?= $medicament->cheminImageMed ?>" alt="<?= $medicament->designationMed ?> Image">
            <h3><?= $medicament->designationMed ?></h3>
            <p><strong>Niveau de danger:</strong> <?= $medicament->niveauDanger ?></p>
            <p><strong>Descriptif:</strong> <?= $medicament->descriptifMed ?></p>
            <p><strong>Date de sortie:</strong> <?= $medicament->dateSortie ?></p>
        </div>
    <?php }
} else {
    echo 'ID du visiteur non défini.';
}
?>
    </div>
</body>
</html>
