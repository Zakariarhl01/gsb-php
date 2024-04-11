<?php
//classes outils DAO et de tri de tableau
require_once 'Cdao.php'; 
require_once 'Ctri.php';
require_once 'Cemploye.php';

/* ************ Classe métier Ccomptable et Classe de contrôle Ccomptables **************** */

class Ccomptable extends Cemploye
{

    public $idRegion;

    function __construct($sid, $slogin, $smdp, $snom, $sprenom, $sville, $sidRegion)
    {
        parent::__construct($sid, $slogin, $smdp, $snom, $sprenom, $sville);
        $this->idRegion = $sidRegion;

    }

}

class Ccomptables 
{
    //encapsulation des attributs de la classe en private
    private $ocollComptableById;
    private $ocollComptableByLogin;
    
    private $ocollComptable;
    private $tabVilleComptable;

    //private static $instance = null;

    public function __construct()
    {
       try{
                 $dao = new Cdao();             
                             

                             $query = 'SELECT * from employe';

                             $lesComptables =  $dao->getTabDataFromSql($query);


                            foreach ($lesComptables as $unComptable) {
                                
                                $ocomptable = new Ccomptable($unComptable['idEmploye'],$unComptable['login'],$unComptable['mdp'],$unComptable['nom'],$unComptable['prenom'],$unComptable['ville'], $unComptable['idRegion']);
                                $this->ocollComptableById[$unComptable['idEmploye']] = $ocomptable;
                                $this->ocollComptableByLogin[$ocomptable->login] = $ocomptable;
                                $this->ocollComptable[] = $ocomptable;
                            }
                            
                            foreach($this->ocollComptable as $ocomptable)
                            {
                                $this->tabVilleComptable[] = $ocomptable->ville;
                                
                            }
                            $this->tabVilleComptable = array_unique($this->tabVilleComptable);
                            sort($this->tabVilleComptable);

                            unset($dao);

                            unset($dao); 

                      }
                  catch(PDOException $e) {
                         $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
                         die($msg);
                        }
    }
    
    public function verifierInfosConnexion($slogin, $smdp)
    {
        $ocomptable = @$this->ocollComptableByLogin["$slogin"];
        if ($ocomptable == NULL) {
            return null;
        }
        if ($ocomptable->mdp == $smdp) {
            return $ocomptable;
        }
        return null;
    }
    
    function getVilleComptable()
    {
        return $this->tabVilleComptable;
    }
    
    function getTabComptablesParNomEtVille($sdebutFin, $spartieNom, $sville)
    {
        $tabComptablesByVilleNom = null;
        
        foreach ($this->ocollComptable as $ocomptable) {
            
            if ((strtolower($ocomptable->ville) == strtolower($sville)) || $sville == 'toutes') {
                if ($spartieNom != '*') {
                    if ($sdebutFin == "debut") {
                        $nomExtrait = substr($ocomptable->nom, 0, strlen($spartieNom));

                        if (strtolower($nomExtrait) == strtolower($spartieNom)) {
                            $tabComptablesByVilleNom[] = $ocomptable;
                        }
                    }
                    if ($sdebutFin == "fin") {
                        $nomExtrait = substr($ocomptable->nom, -strlen($spartieNom), strlen($spartieNom));

                        if (strtolower($nomExtrait) == strtolower($spartieNom)) {
                            $tabComptablesByVilleNom[] = $ocomptable;
                        }
                    } 

                    if ($sdebutFin == "nimporte") {
                        $i = 0;
                        $tab = str_split($ocomptable->nom);
                        foreach ($tab as $caract) {
                            $nomExtrait = substr($ocomptable->nom, $i, strlen($spartieNom));

                            if (strtolower($nomExtrait) == strtolower($spartieNom)) {
                                $tabComptablesByVilleNom[] = $ocomptable;
                                break;
                            } 

                            $i++;
                        }
                    }
                } else {
                    $tabComptablesByVilleNom[] = $ocomptable;
                }
            }
        }
        
        return $tabComptablesByVilleNom;
    }

    public function getComptableById($sid)
    {
        return $this->ocollComptableById[$sid];
    }
    
    public function getComptableByLogin($slogin)
    {
        return $this->ocollComptableByLogin[$slogin];
    }

    public function getCollComptableByLogin()
    {
        return $this->ocollComptableByLogin;
    }

    function getComptablesTrie()
    {
        $otrie = new Ctri();
        $ocollComptablesTrie = $otrie->TriTableau($this->ocollComptable, "nom");
        return $ocollComptablesTrie;
    }
}
