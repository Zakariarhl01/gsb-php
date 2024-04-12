<?php
// classes outils DAO 
require_once 'Cdao.php';
require_once 'Ctri.php';
require_once 'Cemploye.php';
require_once 'Cregion.php';

/* ************ Classe métier CdirecteurRegionale et Classe de contrôle CdirecteurRegionales **************** */

class CdirecteurRegionale extends Cemploye
{

    function __construct($sid, $slogin, $smdp, $snom, $sprenom, $sville, $sidRegion)
    {
        parent::__construct($sid, $slogin, $smdp, $snom, $sprenom, $sville, $sidRegion);
      
    }
    public function getIdRegion()
    {
        return $this->idRegion;
    }
    public function getLibelleRegion($id)
    {
        $dao = new Cdao();
        $cnx = $dao->getObjetPDO();
        $query = "SELECT Libelle FROM `region` WHERE idDirecteurRegional = :idDirecteurRegional";
    
        $prepare = $cnx->prepare($query);
        $prepare->bindValue(':idDirecteurRegional', $id);
        $prepare->execute();
        $libelle = $prepare->fetchAll(PDO::FETCH_ASSOC);  // Utilisation de FETCH_ASSOC pour obtenir un tableau associatif
        return $libelle;
    }
   
}

class CdirecteurRegionales
{
    
    private $ocollDirecteurRegionaleById;
    private $ocollDirecteurRegionaleByLogin;
    private $ocollDirecteurRegionale;
    private $tabVilleDirecteurRegionale;

    public function __construct()
    {
        
        try {
            $dao = new Cdao();
            $query = 'SELECT * from employe';

            $lesDirecteurRegionales = $dao->getTabDataFromSql($query);

            foreach ($lesDirecteurRegionales as $unDirecteurRegionale) {

                $odirecteurregionale = new CdirecteurRegionale(
                    $unDirecteurRegionale['idEmploye'],
                    $unDirecteurRegionale['login'],
                    $unDirecteurRegionale['mdp'],
                    $unDirecteurRegionale['nom'],
                    $unDirecteurRegionale['prenom'],
                    $unDirecteurRegionale['ville'],
                    $unDirecteurRegionale['idRegion']
                );
                $this->ocollDirecteurRegionaleById[$unDirecteurRegionale['idEmploye']] = $odirecteurregionale;
                $this->ocollDirecteurRegionaleByLogin[$odirecteurregionale->login] = $odirecteurregionale;
                $this->ocollDirecteurRegionale[] = $odirecteurregionale;
            }

            foreach ($this->ocollDirecteurRegionale as $odirecteurregionale) {
                $this->tabVilleDirecteurRegionale[] = $odirecteurregionale->ville;
            }

            $this->tabVilleDirecteurRegionale = array_unique($this->tabVilleDirecteurRegionale);
            sort($this->tabVilleDirecteurRegionale);

            unset($dao);
        } catch (PDOException $e) {
            $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            die($msg);
        }
    }

   public function verifierInfosConnexion($slogin, $smdp)
    {
        
        $odirecteurregionale = @$this->ocollDirecteurRegionaleByLogin["$slogin"];
        if ($odirecteurregionale == NULL) {
            return null;
        }
        if ($odirecteurregionale->mdp == $smdp) {
            // Mettez à jour l'objet directeur régional avec la liste des visiteurs de sa région
            // $odirecteurregionale->listeVisiteursRegion = $odirecteurregionale->getListeVisiteursRegion();
            return $odirecteurregionale;
        }
        return null;
    }
    
    public function getListeVisiteursRegion($idRegion)
    {
        $dao = new Cdao();
        $cnx = $dao->getObjetPDO();
        $query = 'SELECT e.idEmploye, e.login, e.nom, e.prenom, e.ville, v.idRegion 
                  FROM visiteur v 
                  INNER JOIN employe e ON v.idVisiteur = e.idEmploye
                  WHERE v.idRegion = :idRegion';
    
        $prepare = $cnx->prepare($query);
        $prepare->bindValue(':idRegion', $idRegion);
        $prepare->execute();
    
        // Récupérer les résultats de la requête
        $resultats = $prepare->fetchAll(PDO::FETCH_ASSOC);
    
        // Retourner les résultats
        return $resultats;
    }
    
    public function getNom($directeurRegional)
    {
        return $directeurRegional->getNom();
    }

    function getVilleDirecteurRegional()
    {
        return $this->tabVilleDirecteurRegionale;
    }

    public function getDirecteurRegionaleById($sid)
    {
        return $this->ocollDirecteurRegionaleById[$sid];
    }

    public function getDirecteurRegionaleByLogin($slogin)
    {
        return $this->ocollDirecteurRegionaleByLogin[$slogin];
    }

    public function getCollDirecteurRegionaleByLogin()
    {
        return $this->ocollDirecteurRegionaleByLogin;
    }

}

