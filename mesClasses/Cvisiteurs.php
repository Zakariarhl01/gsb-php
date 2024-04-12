<?php
//classes outils DAO et de tri de tableau
require_once 'Cdao.php'; 
require_once 'Ctri.php';
require_once 'Cemploye.php';

/* ************ Classe métier Cvisiteur et Classe de contrôle Cvisiteurs **************** */


class Cvisiteur extends Cemploye
{

    
    function __construct($sid,$slogin,$smdp,$snom,$sprenom,$sville, $sidRegion)
    {
        parent::__construct($sid,$slogin,$smdp,$snom,$sprenom,$sville, $sidRegion);

        /*$this->id = $sid;
        $this->login = $slogin;
        $this->mdp = $smdp;
        $this->nom = $snom;
        $this->prenom = $sprenom;
        $this->connecte = false;   // le visiteur est par défaut non connecté*/
    }

    
}


class Cvisiteurs 
{
    //encapsulation des attributs de la classe en private
    private $ocollVisiteurById;
    private $ocollVisiteurByLogin;
    
    private $ocollVisiteur;
    private $tabVilleVisiteur;

    //private static $instance = null;

    public function __construct()
    {
                  try {                        

                             $dao = new Cdao();
                             
                             

                             $query = 'SELECT * from employe';

                             $lesVisiteurs =  $dao->getTabDataFromSql($query);


                            foreach ($lesVisiteurs as $unVisiteur) {
                                
                                $ovisiteur = new Cvisiteur($unVisiteur['idEmploye'],$unVisiteur['login'],$unVisiteur['mdp'],$unVisiteur['nom'],$unVisiteur['prenom'],$unVisiteur['ville'],$unVisiteur['idRegion']);
                                $this->ocollVisiteurById[$unVisiteur['idEmploye']] = $ovisiteur;
                                $this->ocollVisiteurByLogin[$ovisiteur->login] = $ovisiteur;
                                $this->ocollVisiteur[] = $ovisiteur;
                            }
                            
                            foreach($this->ocollVisiteur as $ovisiteur)
                            {
                                $this->tabVilleVisiteur[] = $ovisiteur->ville;
                                
                            }
                            $this->tabVilleVisiteur = array_unique($this->tabVilleVisiteur);
                            sort($this->tabVilleVisiteur);

                            unset($dao);

                            unset($dao); 

                      }
                  catch(PDOException $e) {
                         $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
                         die($msg);
                        }
   

    }
    
    
    public function verifierInfosConnexion($unLogin, $unMdp) {  //Vérifier le login d'un visiteur est de la responsabilité de la classe de contrôle Cvisiteurs
        
        //@ permet de ne pas afficher le E_NOTICE (WARNING) sur la page en cas de non existence de la clef
        //Ce qui explique aussi que ce n'est pas une exception et donc pas de possibilité de gérer par TRY..CATCH
        $ovisiteur = @$this->ocollVisiteurByLogin["$unLogin"];
        if($ovisiteur == NULL)
        {   
            return null;
        }
        if($ovisiteur->mdp == $unMdp){
            return $ovisiteur;       
        }
        return null;
     
    }
    
    function getVilleVisiteur()
    {
        
        return $this->tabVilleVisiteur;
        
    }
    
    function getTabVisiteursParNomEtVille($sdebutFin,$spartieNom,$sville)
    {
        $tabVisiteursByVilleNom = null ;
        
        
        foreach ($this->ocollVisiteur as $ovisiteur) {
            
            if((strtolower($ovisiteur->ville) == strtolower($sville)) || $sville == 'toutes')
            {
                if($spartieNom != '*')
                {
                    if($sdebutFin == "debut")
                    {
                        $nomExtrait = substr($ovisiteur->nom,0,strlen($spartieNom));

                        if(strtolower($nomExtrait) == strtolower($spartieNom))
                        {
                            $tabVisiteursByVilleNom[] = $ovisiteur;
                        }                                      
                    }
                    if($sdebutFin == "fin")
                    {

                        $nomExtrait = substr($ovisiteur->nom,-strlen($spartieNom),strlen($spartieNom));

                       if(strtolower($nomExtrait) == strtolower($spartieNom))
                        {
                            $tabVisiteursByVilleNom[] = $ovisiteur;
                        }

                    } 

                    if($sdebutFin == "nimporte")
                    {
                        $i = 0;
                        $tab = str_split($ovisiteur->nom);
                        foreach ($tab as $caract) 
                        {
                            $nomExtrait = substr($ovisiteur->nom,$i,strlen($spartieNom));

                            if(strtolower($nomExtrait) == strtolower($spartieNom))
                            {
                                $tabVisiteursByVilleNom[] = $ovisiteur;
                                break;
                            } 

                            $i++;
                        }


                    }
                }else{$tabVisiteursByVilleNom[] = $ovisiteur;}
                
            }
            
        }
        
       
        
        return $tabVisiteursByVilleNom;
    } 

    public function getVisiteurById($sid)
    {
        
        return $this->ocollVisiteurById[$sid];
    }
    
    public function getVisiteurByLogin($login)
    {
        
        return $this->ocollVisiteurByLogin[$login];
    }

    public function getCollVisiteurByLogin()
    {

        return $this->ocollVisiteurByLogin;
    }
    function getVisiteursTrie()
    {
        $otrie = new Ctri();
        $ocollVisiteursTrie = $otrie->TriTableau($this->ocollVisiteur,"nom");
        return $ocollVisiteursTrie;
    }

}


