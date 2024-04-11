<html>
    <?php 
        require_once 'includes/head.php';        
        require_once './mesClasses/Cpresenter.php'; 
        
        session_start();
    ?>
<?php
// Créer une instance de la classe Cpresenters
$oPresenters = new Cpresenters();

// Vérifier si l'ID de la présentation est présent dans l'URL
if (isset($_GET['idPres'])) {
try {
    // Supprimer la présentation avec l'ID spécifié
    $oPresenters->deletePresentation($_GET['idPres']);
    // Redirection vers la même page après la suppression
    header('Location: produit_attribuer_visiteur.php');
    exit(); // Terminer le script après la redirection
} catch (Exception $ex) {
    $errorMsg = "La présentation n° " . $_GET['idPres'] . " n'a pas été correctement supprimée.";
}
}
?>

<body>
    <div class="container">
        <header title="recapitulatifdesaffectations"></header>
        <?php require_once 'includes/navBar.php'; ?>
<h3>Récapitulatif des affectations de produits aux visiteurs pour les différentes périodes</h3>
<table class="table table-striped">
<thead>
    <tr>
        <th>ID</th>
        <th>Année et mois</th>
        <th>ID Médecin</th>
        <th>ID Visiteur</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
    // Récupérer les présentations à partir de la classe Cpresenters
    $presentations = $oPresenters->getPresentation();
    
    // Afficher chaque présentation dans une ligne de tableau avec un bouton "Supprimer"
    foreach ($presentations as $presentation) {
        ?>
        <tr>
            <td><?= $presentation->idPres ?></td>
            <td><?= $presentation->anneeMois ?></td>
            <td><?= $presentation->idMed ?></td>
            <td><?= $presentation->idVisiteur ?></td>
            <td>
                <!-- Utiliser un lien avec un bouton de suppression -->
                <a href="produit_attribuer_visiteur.php?idPres=<?= $presentation->idPres ?>" class="btn btn-danger" role="button">Supprimer</a>
            </td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>
