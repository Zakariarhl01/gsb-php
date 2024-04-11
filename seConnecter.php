<!DOCTYPE html>

<?php session_start(); 
?>
<html>
    <?php require_once 'includes/head.php';?>
    
    <body>
       <div class="container-lg">
        
       <?php
            //require_once 'includes/navBar.php';
            
            $formAction = "seConnecter.php";
            require_once 'includes/form_login.php';
        ?>
        <br>
        <?php
            require_once 'includes/gestion-erreur.php';
            require_once 'includes/footer.php'
        ?>  
        
       </div>
        
    </body>
</html>
