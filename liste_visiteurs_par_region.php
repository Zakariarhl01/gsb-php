<?php
session_start(); // Ajout de session_start

require_once 'includes/head.php'; 
require_once 'mesClasses/CdirecteurRegionales.php';
require_once 'includes/functions.php';
require_once 'mesClasses/Cmedicaments.php';

// Assurez-vous que la session contient un objet directeur régional
$odirecteurregional = isset($_SESSION['directeur_regional']) ? unserialize($_SESSION['directeur_regional']) : null;

if ($odirecteurregional == NULL) {
    header('location:seConnecter.php');
    exit();
}

$directeursRegionaux = new CdirecteurRegionales(); 
$oMed = new Cmedicaments ();

$id = $odirecteurregional->getId();

$idRegion = $odirecteurregional->getIdRegion(); // Utilisez la méthode publique pour obtenir la valeur

$listeVisiteurs = $directeursRegionaux->getListeVisiteursRegion($idRegion);

// Obtenez les 6 prochains mois
$sixProchainsMois = getSixProchainsMois();

// Instanciez la classe CdirecteurRegionales
$libelleRegion = $odirecteurregional->getLibelleRegion($id)[0]['Libelle'];

if(isset($_POST['medicaments'])){
    $_SESSION['med'] = $_POST['medicaments'];
}
if(isset($_POST['selectionner']) && isset($_POST['mois']) && isset($_SESSION['med'])){
    foreach($_POST['selectionner'] as $idUnVisiteur){
            $oMed->setMedicamentsForVisitor($idUnVisiteur,$_POST['mois'],$_SESSION['med']);
    }
}
?>

<body>
    <div class="container">
        <header title="listevisiteursregions"></header>
        <?php require_once 'includes/navBar.php'; 

        // Affichez la liste des visiteurs pour le directeur régional en cours
        echo "<h2>Liste des visiteurs pour la région $libelleRegion</h2>";
        
        if (empty($listeVisiteurs)) {
            echo "<p>Aucun visiteur pour cette région.</p>";
        } else {
            echo '<form method="post" action="">'; // Ajoutez le formulaire autour du tableau
            echo '<table class="table table-condensed">
            <thead title="entetetabvisiteur">
                <tr>
                    <th>ID</th>
                    <th>LOGIN</th>
                    <th>NOM</th>
                    <th>PRENOM</th>
                    <th>VILLE</th>
                    <th>Sélectionner</th>
                </tr>
            </thead>
            <tbody>';    
            $i = 0; // Ajoutez cette ligne avant la boucle foreach

            foreach ($listeVisiteurs as $visiteur) {
                ?>
                <tr class="<?=$i%2===0?'':'ligneTabVisitColor'?>">
                    <td><?=$visiteur['idEmploye']?></td>
                    <td><?=$visiteur['login']?></td>
                    <td><?=$visiteur['nom']?></td>
                    <td><?=$visiteur['prenom']?></td>
                    <td><?=$visiteur['ville']?></td>
                    <td>
                        <input type="checkbox" name="selectionner[]" value="<?=$visiteur['idEmploye']?>">
                    </td>
                </tr>
                <?php
                $i++;
            }                       
          
            echo "</tbody>";
            echo "</table>";
            echo '<a href="recapitulatif_affectation_produit.php"><button type="button" class="btn btn-primary">Voir et/ou supprimer les affectations</button></a>';
            // Tableau des 6 prochains mois
            echo "<h2>Période d'affectation des produits</h2>";
            echo '<table class="table table-condensed">
            <thead title="entetetabvisiteur">
                <tr>
                    <th>Mois</th>
                    <th>Sélectionner</th>
                </tr>
            </thead>
            <tbody>';    
            foreach ($sixProchainsMois as $mois) {
                ?>
                <tr>
                    <td><?= $mois['mois'] . "/" . $mois['annee'] ?></td>
                    <td>
                        <input type="checkbox" name="mois[]" value="<?= $mois['mois'] . "" . $mois['annee'] ?>">
                    </td>
                </tr>
                <?php
            }                       
            echo "</tbody>";
            echo "</table>";
            echo '<button type="submit" class="btn btn-primary">Attribuer</button>'; // Ajoutez le bouton de soumission du formulaire
            echo '</form>'; // Fermez le formulaire après le tableau

        }
        ?>
    </div>
</body>
