<?php
require_once 'includes/head.php';
require_once './mesClasses/Cdao.php';
require_once './mesClasses/Cmedicaments.php';

session_start();

// Assurez-vous que la session contient un objet visiteur
$odirecteurregional = isset($_SESSION['directeur_regional']) ? unserialize($_SESSION['directeur_regional']) : null;

if ($odirecteurregional == NULL) {
    header('location:seConnecter.php');
    exit();
}

// Instanciez la classe Cdao
$dao = new Cdao();
// Instanciez la classe Cmedicaments pour récupérer la liste de médicaments
$omedicaments = new Cmedicaments();

// Si le formulaire a été soumis, appliquez les filtres
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Filtrage par niveau de danger
    if (isset($_POST['danger']) && !empty($_POST['danger'])) {
        $niveauDanger = $_POST['danger'];
        $omedicamentsListe = $omedicaments->getMedicamentByNiveauDanger($niveauDanger);
    }

    // Filtrage par date de sortie
    if (isset($_POST['date']) && !empty($_POST['date'])) {
        $dateSortie = $_POST['date'];
        $omedicamentsListe = $omedicaments->getMedicamentByDateSortie($dateSortie);
    }

    // Recherche par nom
    if (isset($_POST['search']) && !empty($_POST['search'])) {
        $nomSearch = $_POST['search'];
        $omedicamentsListe = $omedicaments->getMedicamentByNom($nomSearch);
    }
} else {
    // Si le formulaire n'a pas été soumis, récupérez la liste complète des médicaments
    $omedicamentsListe = $omedicaments->getMedicament();
}
?>

<!DOCTYPE html>
<html lang="fr">
    <body>
        <div class="container">
            <header title="listemedicament"></header>
            <?php require_once 'includes/navBar.php'; ?>
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
            // Affichez le formulaire de sélection des médicaments seulement si aucun médicament n'a été sélectionné
            if (!isset($medicamentsSelectionnes)) {
                ?>
                <form method="POST" action="liste_visiteurs_par_region.php">
                    <?php
                    foreach ($omedicamentsListe as $omedicament) {
                        ?>
                        <div class="medicament-card">
                            <input type="checkbox" name="medicaments[]" value="<?=$omedicament->idMed?>">
                            <img src="<?=$omedicament->cheminImageMed?>" alt="<?=$omedicament->designationMed?> Image">
                            <h3><?=$omedicament->designationMed?></h3>
                            <p><strong>Niveau de danger:</strong> <?=$omedicament->niveauDanger?></p>
                            <p><strong>Descriptif:</strong> <?=$omedicament->descriptifMed?></p>
                            <p><strong>Date de sortie:</strong> <?=$omedicament->dateSortie?></p>
                        </div>
                        <?php
                    }
                    ?>
                    <button type="submit">Attribuer aux visiteurs</button>
                </form>
                <?php
            } else {
                echo "<p>Des médicaments ont déjà été sélectionnés.</p>";
            }
            ?>
        </div>
    </body>
</html>
