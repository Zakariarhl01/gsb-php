<!DOCTYPE html>
 <?php session_start(); ?>
<html>
    <?php 
        require_once 'includes/head.php';
        require_once 'mesClasses/Ccomptables.php';
        require_once 'mesClasses/Cvisiteurs.php';
        require_once 'mesClasses/CligneFHFs.php';
        require_once 'mesClasses/CligneFFs.php';
        require_once 'mesClasses/CficheFrais.php';
        require_once 'mesClasses/CfraisForfaits.php'; //rajouté pour exo 
        require_once 'includes/functions.php';
        
        $ovisiteur = unserialize($_SESSION['visiteur']);
        if($ovisiteur == NULL)
        {
            header('location:seConnecter.php');
        }
        
        $oficheFrais = new CficheFraiss();
        //echo $ovisiteur->nom_employe;
        $oficheFrais->verifFicheFrais($ovisiteur->id); //verification de l'existence de la fiche de frais
        
        if(isset($_GET['idLFHF']) || isset($_POST['btnFHF']))
        {
            $_SESSION['successMSG_FF'] = NULL;
        }
        
    ?>
    
    <body>
        <div class='container'>
        <header title="saisirFF"></header>
         <?php 
            
            require_once 'includes/navBar.php';
             
        ?>
        
         
       <?php
       $formAction = $_SERVER['PHP_SELF'];
       require_once 'includes/form_FF.php';
       ?>
       <br>
       <?php
       require 'includes/gestion-erreur.php';
       
       require_once 'includes/form_FHF.php';
       require_once 'includes/afficheFHF.php';
        ?>
            
        <br>
        <br>
        <?php
        require 'includes/gestion-erreur.php';
         require 'includes/footer.php';
        ?>  
        </div>
    </body>
</html>
