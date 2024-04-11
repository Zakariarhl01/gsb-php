<?php   
class CligneFF
{
    public $idVisiteur;
    public $mois;
    public $oFraisForfait;
    public $quantite;
    
    public function __construct($sidVisiteur,$smois,$soFraisForfait,$squantite)
    {
        $this->idVisiteur = $sidVisiteur;
        $this->mois = $smois;
        $this->oFraisForfait = $soFraisForfait;
        $this->quantite = $squantite;
              
    }
}
class CligneFFs
{
    public $oCollLigneFraisForfait;
    
    public function __construct()
    {
        try
        {
            $odao = new Cdao();
            $query = 'SELECT * FROM lignefraisforfait';
            //$tabLigneFrais = $odao->getTabObjetFromSql($query, 'Cligneff');
            $tabLigneFrais = $odao->getTabDataFromSql($query);
            $this->oCollLigneFraisForfait = array();
            $ofraisForfaits = new CfraisForfaits();
            $ocollFFById = $ofraisForfaits->getCollFraisForfaitById();
            foreach ($tabLigneFrais as $Lignefrais) {
                if (isset($Lignefrais['idFraisForfait']) && array_key_exists('idFraisForfait', $Lignefrais)) {
                    $ofraisForfait = $ocollFFById[$Lignefrais['idFraisForfait']];
                    $oLignefrais = new CligneFF($Lignefrais['idVisiteur'], $Lignefrais['mois'], $ofraisForfait, $Lignefrais['quantite']);
                    $this->oCollLigneFraisForfait[] = $oLignefrais;
                } else {
                    // Handle the case where the key 'idFraisForfait' is absent in the array
                }
            }
                unset($odao);
        }          
        catch (PDOException $e) 
        {
            $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
                         die($msg);
        }
    }
    
    public function verifExistLFFByIdVisiteurMois($sidVisiteur) //verifie l'existence des 4 lignes à 0
    {
        $mois = getAnneeMois();
        $lFFexist = false;
        foreach($this->oCollLigneFraisForfait as $oLigneFF)
        {
            if($oLigneFF->idVisiteur == $sidVisiteur && $oLigneFF->mois == $mois)
            {
                 $lFFexist = true; //Si une est rencontrée alors les autres le sont aussi donc break 
                 break;
            }
        }
        if(!$lFFexist)
        {
            $this->insertLigneFF();
        }
    }
    
    public function getLFFByIdVisiteurMois($sidVisiteur)
    {
        $ocollLFFbyIdVisiteurMois = array();
        $mois = getAnneeMois();
        foreach($this->oCollLigneFraisForfait as $oLigneFF)
        {
            if($oLigneFF->idVisiteur == $sidVisiteur && $oLigneFF->mois == $mois)
            {
                $ocollLFFbyIdVisiteurMois[] = $oLigneFF;
            }
        }
        
        return $ocollLFFbyIdVisiteurMois;
    }
    
    
    public function insertLigneFF()
    {
        $odao = new Cdao();
        $oVisiteur = unserialize($_SESSION['visiteur']);
        $mois = getAnneeMois();

        // Vérifier si la fiche de frais existe, sinon l'insérer
        $oficheFrais = new CficheFraiss();
        $oficheFrais->verifFicheFrais($oVisiteur->id);

        // Vérifier si une ligne avec la combinaison '202402-ETP' existe déjà
        $existingEntry = $odao->getTabDataFromSql("SELECT * FROM lignefraisforfait WHERE idVisiteur = '{$oVisiteur->id}' AND mois = '{$mois}' AND idFraisForfait = 'ETP'");

        if (empty($existingEntry)) {
            // Insérer les lignes de frais forfait seulement si elles n'existent pas déjà
            $query = "INSERT INTO lignefraisforfait (idVisiteur, mois, idFraisForfait, quantite) VALUES ";
            $query .= "('" . $oVisiteur->id . "', '" . $mois . "', 'ETP', '0'),";
            $query .= "('" . $oVisiteur->id . "', '" . $mois . "', 'KM', '0'),";
            $query .= "('" . $oVisiteur->id . "', '" . $mois . "', 'NUI', '0'),";
            $query .= "('" . $oVisiteur->id . "', '" . $mois . "', 'REP', '0')";

            $odao->insertion($query);
        }

        unset($odao);
    }
    
    
    public function updateLigneFF($squantite,$sidFF)
    {
        $odao = new Cdao();
        $oVisiteur = unserialize($_SESSION['visiteur']);
        $mois = getAnneeMois();
        
         $query = "update lignefraisforfait set quantite=".$squantite.
                            " where mois='".$mois."'"." and idVisiteur='".$oVisiteur->id."'"." and idFraisForfait='".$sidFF."'";
         $odao->update($query);
         unset($odao);
    }
}
