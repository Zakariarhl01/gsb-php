<?php
require_once 'Cdao.php';

class CficheFrais
{
    public $idVisiteur;
    public $mois;
    public $nbJustificatifs;
    public $montantValide;
    public $dateModif;
    public $idEtat;
}

class CficheFraiss
{
    public $ocollFicheFrais;

    public function __construct()
    {
        $odao = new Cdao();
        $query = 'SELECT * FROM fichefrais';
        $tabObjetFF = $odao->getTabObjetFromSql($query, 'CficheFrais');
        $this->ocollFicheFrais = $tabObjetFF;
        
        // Libérer la mémoire
        unset($tabObjetFF, $odao);
    }

    public function verifFicheFrais($sidVisiteur)
    {
        // $oFicheFraisByIdVisiteur = null;
        // foreach ($this->ocollFicheFrais as $oficheFrais) {
        //     if ($oficheFrais->idVisiteur == $sidVisiteur && $oficheFrais->mois == getAnneeMois()) {
        //         $oFicheFraisByIdVisiteur = $oficheFrais;
        //     }
        // }
        // if ($oFicheFraisByIdVisiteur == null) {
        //     $this->insertFicheFrais($sidVisiteur);
        // }
    }

    public function insertFicheFrais($sidVisiteur)
    {
        $this->verifFicheFrais($sidVisiteur);

        $odao = new Cdao();
        $mois = getAnneeMois();
        $query = "INSERT INTO fichefrais (idVisiteur, mois) VALUES ('" . $sidVisiteur . "', '" . $mois . "')";
        $odao->insertion($query);

        // Libérer la mémoire
        unset($odao);
    }    
    
    public function getFicheFraisByIdVisiteurMois($sidVisiteur, $smois)
    {
        foreach ($this->ocollFicheFrais as $oficheFrais) {
            if ($oficheFrais->idVisiteur == $sidVisiteur && $oficheFrais->mois == $smois) {
                return $oficheFrais;
            }
        }
        
        return null;
    }
}
