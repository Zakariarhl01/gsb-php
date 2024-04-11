<?php

// Classes outils DAO et de tri de tableau
require_once 'Cdao.php';
require_once 'Ctri.php';

class Cregion {

    public $idRegion;
    public $libelle;
    public $idDirecteurRegional;

    public function __construct($idRegion, $libelle, $idDirecteurRegional)
    {
        $this->idRegion = $idRegion;
        $this->libelle = $libelle;
        $this->idDirecteurRegional = $idDirecteurRegional;
    }

    // Ajoutez les getters nécessaires si vous n'en avez pas déjà
    
}

class Cregions{

    public $ocollRegionById;
    public $ocollRegionByLibelle;
    public $ocollRegionByIdDirecteurRegional;

    public $ocollRegion;

    public function __construct()
    {
        try {
               $dao = new Cdao();
                             
                             
        $query = 'SELECT * from region';

        $lesRegions =  $dao->getTabDataFromSql($query);

        foreach ($lesRegions as $uneRegion) {
            
            $oregion = new Cregion(
                $uneRegion['idRegion'],
                $uneRegion['Libelle'],
                $uneRegion['idDirecteurRegional']
            );
            $this->ocollRegionById[$uneRegion['idRegion']] = $oregion;
            $this->ocollRegionByLibelle[$uneRegion['Libelle']] = $oregion;
            $this->ocollRegionByIdDirecteurRegional[$uneRegion['idDirecteurRegional']] = $oregion;
            $this->ocollRegion[] = $oregion;
        }
        
            unset($dao);
        }
        catch(PDOException $e) {
            $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            die($msg);
            }
     
    }
    
    public function getLibelleById($idRegion)
    {
        if (isset($this->ocollRegionById[$idRegion])) {
            return $this->ocollRegionById[$idRegion]->libelle;
        }
        return null; 
    }
   
    public function getIdRegion($libelle)
    {
        foreach ($this->ocollRegionByLibelle as $idRegion => $region) {
            if (strtolower($region->getLibelle()) == strtolower($libelle)) {
                return $idRegion;
            }
        }

        return null; 

    }
    
    public function getLibelle()
    {
        return  $this->ocollRegionByLibelle;
    }


    
}
?>