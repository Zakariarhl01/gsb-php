<?php
//classe outil d'accès à la base 
require_once 'Cdao.php';
//petite bibliothèque de fonction
require_once 'includes/functions.php';

class CligneFraisFHF
{

    public $id;
    public $idVisiteur;
    public $mois;
    public $libelle;
    public $date;
    public $montant; // champ qui n'est pas dans la base mais uniquement au niveau objet


    // Penser à commenter le constructeur pour le cas n°3 du constructeur de Cvisiteurs
    /* function __construct($sid,$slogin,$smdp,$snom,$sprenom)
    {

        $this->id = $sid;
        $this->login = $slogin;
        $this->mdp = $smdp;
        $this->nom = $snom;
        $this->prenom = $sprenom;
        $this->connecte = false;   // le visiteur est par défaut non connecté
    } */
}


class CligneFraisFHFs //extends CI_Model
{
    public $ocollFHFById;
    //public $ocollVisiteurByLogin;

    //private static $instance = null;

    public function __construct()
    {
        //parent::__construct();
                  try {


                            
                             $dao = new Cdao();

                             $query = 'SELECT idLigneFraisForfait,idVisiteur,mois,libelle,date,montant from lignefraishorsforfait';

                             $lesObjetsFHF =  $dao->getTabObjetFromSql($query,'CligneFraisFHF'); //le deuxieme parametre est le type d'ojet qui sera créé

                             $this->ocollFHFById = array();
                             

                             foreach ($lesObjetsFHF as $oFHF) {

                               
                                $this->ocollFHFById[$oFHF['idLigneFraisForfait']] = $oFHF;
                                
                                
                            }
                            
                            // objet odao meurt de toutes façons à la fin du try puisque instancié dedans
                            unset($dao); 

                      

                      }
                  catch(PDOException $e) {
                         $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
                         die($msg);
                        }
   

    }
    
    public function deleteFHF($sid)
    {
        //Suppression de la base
        $odao = new Cdao();
        $query = 'DELETE FROM lignefraishorsforfait WHERE idLigneFraisForfait ='.$sid;
        $odao->delete($query);
        //suppression de l'objet du dictionnaire dont la clef est l'id du FHF : si objetde contrôle dans une variable de session
        unset($this->ocollFHFById[$sid]);
        unset($odao);
        
    }
    
    public function insertFHF($slibelle,$smontant)
    {
        $libelle = prepareChaineHtml($slibelle);
        $montant = prepareChaineHtml($smontant);
        $ovisiteur = unserialize($_SESSION['visiteur']);
        $idVisiteur = $ovisiteur->id;
        $dateSaisie = date('Y-m-d');
        $mois = getAnneeMois(); //chaine anneemois
           
        $odao = new Cdao();
        $query = "INSERT INTO lignefraishorsforfait(idVisiteur,mois,libelle,date,montant) VALUES (".
        "'".$idVisiteur."'".",'".$mois."',"."'".$libelle."'".","."'".$dateSaisie."'".",".$montant.")";   
        $odao->insertion($query);      
    }
    
    

    public function getFHFByIdVisiteurMois($idVisiteur, $mois)
    {
        $tabAretourner = array();
        $i = -1;
        
        foreach ($this->ocollFHFById as $oFHF) {    
            // Check if the object has the expected properties
            if (isset($oFHF['idVisiteur']) && isset($oFHF['mois']) && $oFHF['idVisiteur'] == $idVisiteur && $oFHF['mois'] == $mois) {
                $i++;
                $tabAretourner[$i] = $oFHF;
            }
        }
    
        return $tabAretourner;
    }
    


    /*public static function getInstance() {
 
         if(is_null(self::$instance)) 
         {
           self::$instance = new Cvisiteurs();  
         }
 
     return self::$instance;
   }*/
}

