<?php
require_once './mesClasses/Cvisiteurs.php';  
require_once './mesClasses/Ccomptables.php';  
require_once './mesClasses/CdirecteurRegionales.php';   


$_SESSION['visiteur'] = null;
$_SESSION['comptable'] = null;
$_SESSION['directeur_regional'] = null;
$_SESSION['employe'] = null;
$_SESSION['med'] = null;

if(isset($_POST['username']) && isset($_POST['pwd'])) {

    $lesVisiteurs = new Cvisiteurs();
    $lesComptables = new Ccomptables();
    $lesDirecteursRegionaux = new CdirecteurRegionales();
    
    $mdp = filter_input(INPUT_POST, 'pwd', FILTER_SANITIZE_STRING);
    $ovisiteur = $lesVisiteurs->verifierInfosConnexion($_POST['username'], $mdp);
    $ocomptable = $lesComptables->verifierInfosConnexion($_POST['username'], $mdp);
    $odirecteurRegional = $lesDirecteursRegionaux->verifierInfosConnexion($_POST['username'], $mdp);
    


    $tableau = [
        $ovisiteur->id,
        $ocomptable->id,
        $odirecteurRegional->id
    ];
    
    for($i = 0; $i < 3; $i++){
        $secondLetter = substr($tableau[$i], 1, 1);
    
        if($secondLetter === 'c') {
            $userType = 'comptable';
        } elseif($secondLetter === 'd') {
            $userType = 'directeur_regional';
        } else {
            $userType = 'visiteur';
        }
    }
    var_dump($userType);
    if($userType == 'visiteur'){
        $_SESSION['visiteur'] = serialize($ovisiteur);
        $_SESSION['employe'] = $_SESSION['visiteur'];
        header('Location: saisirFicheFrais.php');
    }elseif($userType == 'directeur_regional'){
        $_SESSION['directeur_regional'] = serialize($odirecteurRegional);
        $_SESSION['employe'] = $_SESSION['directeur_regional'];
        header('Location: selection_medicament.php');
    }elseif($userType == 'comptable'){
        $_SESSION['comptable'] = serialize($ocomptable);
        $_SESSION['employe'] = $_SESSION['comptable'];
        header('Location: pageComptable.php');
    }else {
        $errorMsg = "Login/Mot de passe incorrect";
    }

}
?>

<header title="formlogin">
    <h2 title="cnx">Connexion lab GSB</h2>
    <!--<img class=img-responsive src="../img/med1.jpg">-->
</header>

<?php
require_once 'navBar.php';
?>

<form action="" method="POST">
  <div class="col-lg-4 offset-md-4 mb-3 mt-3">
    <label for="username" class="form-label">Login:</label>
    <input type="text" class="form-control" id="username" placeholder="Saisir votre login" name="username" required="required">
  </div>
  <div class=" col-lg-4 offset-md-4 mb-3">
    <label for="pwd" class="form-label">Mot de passe:</label>
    <input type="password" class="form-control" id="pwd" placeholder="Saisir votre mot de passe" name="pwd" required="required">
  </div>
  <div class="col-lg-8 offset-md-4 form-check mb-3">
    <label class="form-check-label">
      <input class="form-check-input" type="checkbox" name="remember"> Se souvenir de moi
    </label>
  </div>
  <button type="submit" class="offset-md-4 btn-lg">Se connecter</button>
</form>