<html>
    <?php 
        require_once 'includes/head.php';        
        require_once './mesClasses/Cvisiteurs.php'; 
        
        session_start();
    ?>
    <body>
        <?php
        $ovisiteur = unserialize($_SESSION['visiteur']);
        if ($ovisiteur == NULL) {
            header('location:seConnecter.php');
        }
        $ovisiteurs = new Cvisiteurs();
        $ocollTrie = $ovisiteurs->getVisiteursTrie();
        
        // Mémorisation du nombre total de visiteurs pour utilisation à la ligne 79
        $_SESSION['nbTotalVisiteur'] = count($ocollTrie);
        
         // Conservation de la valeur choisie après un postBack grâce aux variables de session
        if (isset($_POST['debutFin']) && isset($_POST['ville']) && isset($_POST['partieNom'])) {
            $_SESSION['debutFin'] = $_POST['debutFin'];
            $_SESSION['ville'] = $_POST['ville'];
            $_SESSION['partieNom'] = $_POST['partieNom'];
        }
        
        $tabVilles = $ovisiteurs->getVilleVisiteur();
        
        ?>
        
        <div class="container">
            <header title="listevisiteur"></header>
            <?php require_once 'includes/navBar.php'; ?>
            
            <div title="filtrage">  
                <form class="" method="POST" action="">
                    <div class="row">
                        <div class="col-lg-2">
                            <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg></span>
                            &nbsp; 
                            <label class="form-label" for="ville">Choisir la localité :</label>
                            &nbsp;
                        </div>
                        <div class="col-lg-5">
                            <select name="ville">
                                <?php
                                    // Conservation de la valeur choisie après un postBack grâce aux variables de session
                                    echo "<option value='toutes'>Toutes villes</option>";
                                    // isset obligatoire car au premier chargement le tableau associatif ($_SESSION['ville]) n'est pas défini
                                    if (isset($_SESSION['ville'])) {
                                        foreach ($tabVilles as $ville) {
                                            echo "<option value='".$ville;
                                            if ($_SESSION['ville'] == trim($ville)) {
                                                echo "' selected >";
                                            } else {
                                                echo "' >";
                                            }
                                            echo $ville." </option>";
                                        }
                                    } else {
                                        foreach ($tabVilles as $ville) {
                                            echo "<option value='".$ville."' >".$ville."</option>";
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-lg-3">
                            <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                            </svg></span>
                            &nbsp;               
                            <label class="col-form-label" for="partieNom"><span></span>Saisir tout ou partie du nom :</label>
                            &nbsp;
                        </div>
                        <div class="col-lg-3">
                            <!-- Conservation de la valeur choisie après un postBack grâce aux variables de session -->
                            <input class="form-control" type="text" name="partieNom" value="<?=isset($_SESSION['partieNom'])?$_SESSION['partieNom']:''?>" required>
                        </div>
                        <div class="col-lg-3">
                            <!-- Conservation de la valeur choisie après un postBack grâce aux variables de session -->
                            <?php if (isset($_SESSION['debutFin'])): ?>
                                <div class="radio">
                                    <INPUT type="radio" name="debutFin" value="debut" <?=($_SESSION['debutFin'] == 'debut') ? 'checked required' : ''?>> Début &nbsp;
                                    <INPUT type="radio" name="debutFin" value="fin" <?=($_SESSION['debutFin'] == 'fin') ? 'checked required' : ''?>> Fin &nbsp;
                                    <INPUT type="radio" name="debutFin" value="nimporte" <?=($_SESSION['debutFin'] == 'nimporte') ? 'checked required' : ''?>> Dans la chaine &nbsp;
                                </div>
                            <?php else: ?>
                                <div class="radio">
                                    <INPUT type="radio" name="debutFin" value="debut" checked required> Début &nbsp;
                                    <INPUT type="radio" name="debutFin" value="fin" required> Fin &nbsp;
                                    <INPUT type="radio" name="debutFin" value="nimporte" required> Dans La Chaine &nbsp;
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-2">
                            <button type="submit" class="btnFiltrage">Filtrer</button>
                        </div>
                    </div>           
                </form> 
            </div>  
            
            <?php
            if (isset($_POST['debutFin']) && isset($_POST['ville']) && isset($_POST['partieNom'])) {
                $ovisiteurs = new Cvisiteurs();
                $tabVisiteurs = $ovisiteurs->getTabVisiteursParNomEtVille($_POST['debutFin'],$_POST['partieNom'], $_POST['ville']);
                $otrie = new Ctri();
                
                if ($tabVisiteurs != null) {
                    // Dans le if car le tableau ne doit pas être nul pour le tri
                    $tabVisiteurs = $otrie->TriTableau($tabVisiteurs, 'nom');
                    
                    // Remet l'en-tête du tableau comme au début si le nombre de visiteurs est le nombre total
                    // sinon, l'en-tête précise que le tableau est filtré avec le nombre de visiteurs dans le titre
                    $titleCount = (count($tabVisiteurs) == $_SESSION['nbTotalVisiteur']) ? '' : 'filtrés par nom et par ville';
                    echo "<h1><p title='tabvisiteur'>liste des visiteurs médicaux (".count($tabVisiteurs).") $titleCount</p></h1>";
                    
                    echo '<table class="table table-condensed">
                            <thead title="entetetabvisiteur">
                                <tr>
                                    <th>ID</th>
                                    <th>LOGIN</th>
                                    <th>NOM</th>
                                    <th>PRENOM</th>
                                    <th>VILLE</th>
                                </tr>
                            </thead>
                            <tbody>';

                    $i = 0;
                    foreach ($tabVisiteurs as $ovisiteur) {
                        ?>
                        <tr class="<?=$i%2===0?'':'ligneTabVisitColor'?>">
                            <td><?=$ovisiteur->id?></td>
                            <td><?=$ovisiteur->login?></td>
                            <td><?=$ovisiteur->nom?></td>
                            <td><?=$ovisiteur->prenom?></td>
                            <td><?=$ovisiteur->ville?></td>
                        </tr>
                        <?php
                        $i++;
                    }

                    echo "</tbody>";
                    echo "</table>";
                }
                if ($tabVisiteurs == null) {
                    $errorMsg = "Il n'y a pas de visiteur répondant aux critères.";

                    if (isset($errorMsg)) {
                        echo "<br><br><div class='alert alert-danger'>".$errorMsg."</div>";
                    }
                }
            } else {
                echo "<h1><p title='tabvisiteur'>liste des visiteurs médicaux (".count($ocollTrie).")</p></h1>";
                echo '<table class="table table-condensed">
                        <thead title="entetetabvisiteur">
                            <tr>
                                <th>ID</th>
                                <th>LOGIN</th>
                                <th>NOM</th>
                                <th>PRENOM</th>
                                <th>VILLE</th>
                            </tr>
                        </thead>
                        <tbody>';

                $i = 0;
                foreach ($ocollTrie as $ovisiteur) {
                    if ($i % 2 == 1) {
                        ?>
                        <tr class="ligneTabVisitColor">
                            <td><?=$ovisiteur->id?></td>
                            <td><?=$ovisiteur->login?></td>
                            <td><?=$ovisiteur->nom?></td>
                            <td><?=$ovisiteur->prenom?></td>
                            <td><?=$ovisiteur->ville?></td>
                        </tr>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td><?=$ovisiteur->id?></td>
                            <td><?=$ovisiteur->login?></td>
                            <td><?=$ovisiteur->nom?></td>
                            <td><?=$ovisiteur->prenom?></td>
                            <td><?=$ovisiteur->ville?></td>
                        </tr>
                        <?php
                    }
                    $i++;
                }

                echo "</tbody>";
                echo "</table>";
            }
            ?>
        </div>
    </body>
</html>
