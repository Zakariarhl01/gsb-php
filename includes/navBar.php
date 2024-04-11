
<?php 
        require_once 'includes/head.php';        
        require_once './mesClasses/Cvisiteurs.php'; 
        require_once './mesClasses/Ccomptables.php'; 
        require_once './mesClasses/CdirecteurRegionales.php'; 
        require_once './mesClasses/Cemploye.php';
    ?>
    <!-- <nav class="navbar navbar-default">

        <div class="navbar-header">
          <a class="navbar-brand" href="#"> GSB Company</a>
        </div>
        <ul class="nav navbar-nav">
          <li> <a><img  src="img/gsb1.png" class="img-rounded" alt="LOGO GSB" width="45" height="20"/></a> </li>
          <li class="<?=$_SERVER['PHP_SELF']==='/accueil.php'?'active':''?>"><a href="accueil.php">Home</a></li>
          <li class="<?=$_SERVER['PHP_SELF']==='/saisirFicheFrais.php'?'active':''?>"><a href="saisirFicheFrais.php">Saisie des frais Visiteurs Médicaux</a></li>
          <li class="<?=$_SERVER['PHP_SELF']==='/validationFicheFrais.php'?'active':''?>"><a href="validationFicheFrais.php">Validation fiches de Frais</a></li
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="#"><span class="glyphicon glyphicon-user"></span> Bienvenue <?=isset($_SESSION['visiteur'])?unserialize($_SESSION['visiteur'])->prenom:'sur notre site';?></a></li>
          <li class="<?=$_SERVER['PHP_SELF']==='/seConnecter.php'?'active':''?>"><a href="seConnecter.php"><span class='<?=isset($_SESSION['visiteur'])?'glyphicon glyphicon-log-out':'glyphicon glyphicon-log-in';?>'></span><?=isset($_SESSION['visiteur'])?' Déconnexion':' Login';?> </a></li>
        </ul>

    </nav> -->


<nav class="navbar navbar-expand-sm bg-light navbar-light">
  <div class="container">
    <ul class="navbar-nav">
      <li class="nav-item">  
        <li> <a class="nav-link"><img  src="img/gsb1.png" class="img-rounded" alt="LOGO GSB" width="45" height="20"/></a> </li>
      </li>
      <li class="nav-item">
        <a class="nav-link <?=$_SERVER['PHP_SELF']==='/accueil.php'?'active':''?> " href="accueil.php">Accueil</a>
      </li>
      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Visiteurs médicaux
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item <?=!isset($_SESSION['visiteur'])?'disabled':'';?>" href="saisirFicheFrais.php">Saisie des fiche de frais</a></li>
            <li><a class="dropdown-item <?=!isset($_SESSION['visiteur'])?'disabled':'';?>" href="liste_visiteur.php">Liste des visiteurs médicaux</a></li>
            <li><a class="dropdown-item <?=!isset($_SESSION['visiteur'])?'disabled':'';?>" href="liste_medicament.php">Liste médicaments à présenter pour le mois</a></li>
          </ul>
        </li>
      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Comptabilité
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item <?=!isset($_SESSION['comptable'])?'disabled':'';?>" href="#">Validation des fiches de frais</a></li>
            <li><a class="dropdown-item <?=!isset($_SESSION['comptable'])?'disabled':'';?>" href="liste_visiteur.php">Liste des visiteurs médicaux</a></li>
          </ul>
      </li>
      
      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Direction régionale
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item <?=!isset($_SESSION['directeur_regional'])?'disabled':'';?>" href="selection_medicament.php">Attribution des produits à présenter</a></li>
            <li><a class="dropdown-item <?=!isset($_SESSION['directeur_regional'])?'disabled':'';?>" href="liste_visiteurs_par_region.php">Liste des visiteurs médicaux</a></li>
          </ul>
      </li>
      
    </ul>
    <ul class="navbar-nav ms-auto">
    <li class="nav-item">
        <a class="nav-link">Bienvenue <?=isset($_SESSION['employe'])?unserialize($_SESSION['employe'])->prenom:'inconnu(e) sur notre site';?></a>
    </li>
    <li class="nav-item <?=$_SERVER['PHP_SELF']==='/seConnecter.php' && !isset($_SESSION['employe'])?'active':''?>">
        <a class="nav-link" href="seConnecter.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="<?=isset($_SESSION['employe'])?'bi bi-door-closed-fill':'bi bi-door-closed-fill';?>" viewBox="0 0 16 16">
                <path d="<?=isset($_SESSION['employe'])?'M12 1a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2a1 1 0 0 1 1-1h8zm-2 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2z':'M1.5 15a.5.5 0 0 0 0 1h13a.5.5 0 0 0 0-1H13V2.5A1.5 1.5 0 0 0 11.5 1H11V.5a.5.5 0 0 0-.57-.495l-7 1A.5.5 0 0 0 3 1.5V15H1.5zM11 2h.5a.5.5 0 0 1 .5.5V15h-1V2zm-2.5 8c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1z'?>"/>
            </svg>
            <?=isset($_SESSION['employe'])?' Déconnexion':' Login';?> 
        </a>
    </li>      
</ul>


    
  </div>
</nav>