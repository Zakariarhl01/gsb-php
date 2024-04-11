<?php
$oLigneFHFs = new CligneFraisFHFs();


if(isset($_GET['idLFHF']))
{
    try {
        
        $oLigneFHFs->deleteFHF($_GET['idLFHF']);
        /*envoi un en-tête en appelant l'url et en demandant un rafraichissement de la page à 0 seconde
		L'url est à adapter en fonction de la votre */
        echo '<meta http-equiv="refresh" content="0'
        . ';http://localhost/TP3_SAISIE_FHF_FF_LC/saisirFicheFrais.php">'; // 0 est le temps de rafraichissement
        
        //header('location:saisirFicheFrais.php'); 
        
    } catch (Exception $ex) {
        
        $errorMsg = "La ligne n° ".$_GET['idLFHF']." "." n'a pas été correctement correctement supprimée.";
        
    }   
    
}
?>
    <h3 class="frais"><p class="">Récapitulatif des frais hors forfait du mois :<?= ' '.moisEnFrancais(date('F')).' ';?><span class="glyphicon glyphicon-align-justify"></p></h4>
<div class="container"> 
  <table class="table table-striped table-hover">
    <thead >
       <tr class="afficheFHF">
        <th>Libellé</th>
        <th>Date</th>
        <th>Montant</th>
        <th>Action</th>  <!-- supprimer ligne -->
      </tr>
    </thead>
    <tbody>
        <?php
        
        $mois = getAnneeMois();
        $ovisiteur = unserialize($_SESSION['visiteur']);
        // var_dump($_SESSION['visiteur']);
        $ocollLigneFHFsByVisiteur = $oLigneFHFs->getFHFByIdVisiteurMois($ovisiteur->id, $mois);
        // var_dump($ocollLigneFHFsByVisiteur);
        if($ocollLigneFHFsByVisiteur != null)
        {
            // var_dump($ocollLigneFHFsByVisiteur);
            foreach($ocollLigneFHFsByVisiteur as $LigneFHF) {
                ?>
                <tr>
                    <td><?=$LigneFHF['libelle']?></td>
                    <td><?=$LigneFHF['date']?></td>
                    <td <?=($LigneFHF['montant'] >= 100)?"class='text-danger'":"";?>><?=$LigneFHF['montant']?></td>
                    <td>
                        <a href="saisirFicheFrais.php?idLFHF=<?=$LigneFHF['idLigneFraisForfait']?>" class="btn btn-danger" id="btnSuppLigneFHF" role="button">Supprimer</a>
                    </td>
                </tr>
                <?php
            }  
        }
        else
        {
            if(!isset($errorMsg)) // sinon d'autre message prioritaire
            {
                $errorMsg = "Pas de frais hors forfait enregistrés ";
            }
        }
        ?>

        

    </tbody>
  </table>
</div>
        
