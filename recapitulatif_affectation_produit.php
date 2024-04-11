<?php
session_start();
require_once './mesClasses/Cdao.php';
require_once './mesClasses/Cpresenter.php';
require_once './mesClasses/Cvisiteurs.php';
require_once './mesClasses/CdirecteurRegionales.php';

$odirecteurregional = isset($_SESSION['directeur_regional']) ? unserialize($_SESSION['directeur_regional']) : null;

if ($odirecteurregional == NULL) {
    header('location:seConnecter.php');
    exit();
}


// Instanciation des objets
$presenters = new Cpresenters();
$visiteurs = new Cvisiteurs();
$directeursRegionaux = new CdirecteurRegionales(); 



// Vérifier si l'ID de la présentation est présent dans l'URL
if (isset($_GET['idPres'])) {
try {
    // Supprimer la présentation avec l'ID spécifié
    $presenters->deletePresentation($_GET['idPres']);
    // Redirection vers la même page après la suppression
    header('Location: recapitulatif_affectation_produit.php');
    exit(); // Terminer le script après la redirection
} catch (Exception $ex) {
    $errorMsg = "La présentation n° " . $_GET['idPres'] . " n'a pas été correctement supprimée.";
}
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Présentations de médicaments</title>
</head>
<body>
    <header title="listevisiteursregions"></header>
    <?php require_once './includes/navBar.php'; ?>
        <h1>Liste des présentations de médicaments</h1>
   
 

    <div class="container">
        <table class="visiteurs-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Région</th>
                    <th>Nom du médicament</th>
                    <th>Période</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($presenters->getPresentation() as $presentation){ ?>
                <tr>
                    <td><?php echo $visiteurs->getVisiteurById($presentation->idVisiteur)->nom; ?></td>
                    <td><?php echo $visiteurs->getVisiteurById($presentation->idVisiteur)->prenom; ?></td>
                    <td><?php echo $visiteurs->getVisiteurById($presentation->idVisiteur)->idRegion; ?></td>
                    <td><?php echo $presentation->idMed; ?></td>
                    <td><?php echo $presentation->anneeMois; ?></td>
                    <td><a class="btn" href="recapitulatif_affectation_produit.php?idPres=<?php echo $presentation->idPres; ?>">Supprimer</a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
