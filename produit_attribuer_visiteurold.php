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
if (isset($_GET['anneeMois']) && isset($_GET['idMed']) && isset($_GET['idVisiteur'])) {
    try {
        // Supprimer la présentation avec l'ID spécifié
        $anneeMois = $_GET['anneeMois'];
        $idMed = $_GET['idMed'];
        $idVisiteur = $_GET['idVisiteur'];
        $oPresenters->deletePresentation($anneeMois, $idMed, $idVisiteur);
        // Redirection vers la même page après la suppression
        header('Location: produit_attribuer_visiteur.php');
        exit(); // Terminer le script après la redirection
    } catch (Exception $ex) {
        $errorMsg = "La présentation du " . $_GET['anneeMois'] ." pour le médicament ". $_GET['idMed'] . " du visiteur " . $_GET['idVisiteur'] . " n'a pas été correctement supprimée.";
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
            <td><?= $presentation->anneeMois ?></td>
            <td><?= $presentation->idMed ?></td>
            <td><?= $presentation->idVisiteur ?></td>
            <td>
                <!-- Utiliser un lien avec un bouton de suppression -->
                <a href="produit_attribuer_visiteur.php?anneeMois=<?php echo $presentation->anneeMois; ?>&idMed=<?php echo $presentation->idMed; ?>&idVisiteur=<?php echo $presentation->idVisiteur; ?>">Supprimer</a>
            </td>
        </tr>
        <?php
    }
    ?>
</tbody>
</table>
