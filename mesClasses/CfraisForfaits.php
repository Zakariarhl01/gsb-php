<?php
require_once 'Cdao.php';

class CfraisForfait
{
    public $id; // Exemple : ETP
    public $libelle; // ex : Frais Etape
    public $montant; // ex : 110 pour 110 euros prix forfaitaire d'une étape pour un visiteur médical
    
    public function __construct($sid, $slibelle, $smontant)
    {
        $this->id = $sid;
        $this->libelle = $slibelle;
        $this->montant = $smontant;
    }
}


class CfraisForfaits
{
    private $ocollFraisForfait;
    private $ocollFFById;
    
    public function __construct() 
    {
        $odao = new Cdao();
        
        $query = 'SELECT * FROM fraisforfait';
        
        $tabObjetFraisForfait = $odao->getTabObjetFromSql($query, 'CfraisForfait');
        
        $this->ocollFFById = array();
        $this->ocollFraisForfait = array();
        
        foreach ($tabObjetFraisForfait as $ofraisForfaitArray) {
            $ofraisForfait = new CfraisForfait($ofraisForfaitArray['idFraisForfait'], $ofraisForfaitArray['libelle'], $ofraisForfaitArray['montant']);
            $this->ocollFFById[$ofraisForfait->id] = $ofraisForfait;
            $this->ocollFraisForfait[] = $ofraisForfait;
        }
        
        
        unset($odao);
    }
    
    public function getCollFraisForfait()
    {
        return $this->ocollFraisForfait;
    }
    
    public function getCollFraisForfaitById()
    {
        return $this->ocollFFById;
    }
}
