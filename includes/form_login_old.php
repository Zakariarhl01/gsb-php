<?php session_start(); ?>
<div class="container"
<?php
            require_once 'includes/functions.php';         
            require_once 'mesClasses/Cvisiteurs.php';
            
            
            if (!empty($_POST['username']) && !empty($_POST['pwd']))
            {
                try{
                        $name = $_POST['username'];
                        $mdp = $_POST['pwd'];

                        $ovisiteurs = new Cvisiteurs();
                        $ovisiteur = $ovisiteurs->verifierInfosConnexion($name,$mdp);
                

                        if($ovisiteur != NULL)
                        {
                          $ovisiteurs = new Cvisiteurs();
                          //$ovisiteur = $ovisiteurs->verifierInfosConnexion($name,$mdp);
                          $ovisiteur = $ovisiteurs->getVisiteurByLogin($name);
                          $_SESSION['visiteur'] = serialize($ovisiteur);
                          header('location:saisirFicheFrais.php');  

                        }
                        else{
                            
                           $errorMsg = 'Login ou mot de passe incorrect !'; 
                        }

                        
                        
                    }catch(Exception $ex)
                    {
                        $errorMsg = $ex->message;
                    }
            }
            else {
                
                session_destroy();
                
            }
                
            
        ?>

             <br>
             <br>
              <form role="form" method="post" action="<?=$formAction?>" class="formLogin">
                <div class="form-group">
                  <label class="control-label">Username:</label>
                  <input type="text" class="form-control" name="username"  placeholder="Enter username" required>
                </div>
                <div class="form-group">
                  <label class="control-label">Password:</label>
                  <input type="password" class="form-control" name="pwd"  placeholder="Enter password" required>
                </div>
                <!--<div class="checkbox">
                  <label><input type="checkbox"> Remember me</label>
                </div>-->
                <button type="submit" class="btn btn-default">Submit</button>
              </form>

</div>
        
        