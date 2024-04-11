<?php
$errorMsg = NULL;
$successMsg = NULL;

if (isset($_POST['btnFHF'])) {
    if (isset($_POST["libelle"]) && isset($_POST["montant"])) {
        $libelle = trim($_POST["libelle"]);
        $montant = floatval($_POST["montant"]);

        // Validate inputs
        if (empty($libelle) || $montant <= 0) {
            $errorMsg = "Veuillez fournir des données valides.";
        } else {
            // Perform database operation
            $oLigneFHFs = new CligneFraisFHFs;
            try {
                $oLigneFHFs->insertFHF($libelle, $montant);
                $successMsg = "Enregistrement réussi !";
            } catch (Exception $ex) {
                $errorMsg = "Erreur lors de l'insertion dans la base. Prévenir l'administrateur.";
                // Log the actual exception for debugging: $ex->getMessage();
            }
        }
    } else {
        $errorMsg = "Veuillez fournir des données valides.";
    }
}
?>

<div class="container">
    <h3 class="frais">
        <p>Saisie des frais hors forfait 
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-currency-euro" viewBox="0 0 16 16">
                <path d="M4 9.42h1.063C5.4 12.323 7.317 14 10.34 14c.622 0 1.167-.068 1.659-.185v-1.3c-.484.119-1.045.17-1.659.17-2.1 0-3.455-1.198-3.775-3.264h4.017v-.928H6.497v-.936c0-.11 0-.219.008-.329h4.078v-.927H6.618c.388-1.898 1.719-2.985 3.723-2.985.614 0 1.175.05 1.659.177V2.194A6.617 6.617 0 0 0 10.341 2c-2.928 0-4.82 1.569-5.244 4.3H4v.928h1.01v1.265H4v.928z"/>
            </svg>
        </p>
    </h3>
    <br>
    <form id="formFHF" class="form-horizontal" role="form" method="post" action="<?=$formAction?>">
        <div class="offset-md-1 mb-3 row">
            <label class="col-sm-2 col-form-label" for="libelle">Libellé:</label>
            <div class="col-sm-10">
                <textarea type="text" class="form-control" name="libelle" placeholder="Entrer libelle frais" required autofocus></textarea>
            </div>
        </div>
        <div class="offset-md-1 mb-3 row">
            <label class="col-sm-2 col-form-label" for="montant">Montant:</label>
            <div class="col-sm-10">
                <input class="form-control" name="montant" placeholder="Entrer montant frais" required="required" type="number" min="0" step="0.01">
            </div>
        </div>
        <div class="offset-md-3 mb-12">
            <button type="submit" name="btnFHF" class="btn-lg">Enregistrer</button>
        </div>
    </form>

    <?php
    if ($errorMsg) {
        echo '<div class="alert alert-danger" role="alert">' . $errorMsg . '</div>';
    } elseif ($successMsg) {
        echo '<div class="alert alert-success" role="alert">' . $successMsg . '</div>';
    }
    ?>
</div>
