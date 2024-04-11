<?php
require_once 'Cdao.php';

class Cmedicament
{
    public $idMed;
    public $designationMed;
    public $niveauDanger;
    public $descriptifMed;
    public $dateSortie;
    public $cheminImageMed;

    function __construct($sidMed, $sdesignationMed, $sniveauDanger, $sdescriptifMed, $sdateSortie, $scheninImageMed) 
    {
        $this->idMed = $sidMed;
        $this->designationMed = $sdesignationMed;
        $this->niveauDanger = $sniveauDanger;
        $this->descriptifMed = $sdescriptifMed;
        $this->dateSortie = $sdateSortie;
        $this->cheminImageMed = $scheninImageMed;
    }
}

class Cmedicaments 
{
    private $ocollMedicamentById;
    private $ocollMedicament;  

    public function __construct()
    {
        try{
            $dao = new Cdao();
            $query = 'SELECT * FROM medicament';

            $lesMedicaments = $dao->getTabDataFromSql($query);

            foreach ($lesMedicaments as $unMedicament) {
                $medicament = new Cmedicament(
                    $unMedicament['idMed'],
                    $unMedicament['designationMed'],
                    $unMedicament['niveauDanger'],
                    $unMedicament['descriptifMed'],
                    $unMedicament['dateSortie'],
                    $unMedicament['cheminimageMed']
                );

                $this->ocollMedicament[] = $medicament;
            }
        } catch (PDOException $e) {
            $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            die($msg);
        }
    }

    public function getMedicament()
    {
        return $this->ocollMedicament;
    }

    public function getMedicamentById($sid)
    {
        return $this->ocollMedicamentById[$sid];
    }

    function getMedicamentsTrie()
    {
        $otrie = new Ctri();
        $ocollMedicamentsTrie = $otrie->TriMedicamentsParNom($this->ocollMedicament);
        return $ocollMedicamentsTrie;
    }
    public function getMedicamentByNiveauDanger($niveauDanger)
    {
        $resultats = array();

        // Vérifie si le niveau de danger est dans la plage de 1 à 3
        if ($niveauDanger >= 1 && $niveauDanger <= 3) {
            foreach ($this->ocollMedicament as $medicament) {
                if ($medicament->niveauDanger == $niveauDanger) {
                    $resultats[] = $medicament;
                }
            }
        }

        return $resultats;
    }

    public function getMedicamentByDateSortie($dateSortie)
    {
        $resultats = array();

        foreach ($this->ocollMedicament as $medicament) {
            // Comparaison des dates au format Y-m-d
            if (date("Y-m-d", strtotime($medicament->dateSortie)) == $dateSortie) {
                $resultats[] = $medicament;
            }
        }

        return $resultats;
    }

    public function getMedicamentByNom($nomSearch)
    {
        $resultats = array();

        foreach ($this->ocollMedicament as $medicament) {
            // Recherche insensible à la casse
            if (stripos($medicament->designationMed, $nomSearch) !== false) {
                $resultats[] = $medicament;
            }
        }

        return $resultats;
    }

    public function getMedicamentsForVisitor($visitorId, $moisSuivant)
{
    $resultats = array();
    
    // Créez une instance de Cdao
    $dao = new Cdao(); // Assurez-vous que cette ligne est correcte
    
    // Préparez la requête SQL en utilisant des paramètres nommés pour visitorId et moisSuivant
    $query = 'SELECT m.*, p.anneeMois
              FROM medicament m 
              INNER JOIN presenter p ON m.idMed = p.idMed 
              INNER JOIN visiteur v ON p.idVisiteur = v.idVisiteur
              WHERE v.idVisiteur = :visitorId
              AND p.anneeMois = :moisSuivant'; // Filtrer par mois suivant
    
    // Préparez les paramètres pour la requête
    $params = array(
        ':visitorId' => $visitorId,
        ':moisSuivant' => str_replace('-','',$moisSuivant)
    );
    // Utilisez la méthode préparée dans Cdao pour exécuter la requête
    $medicaments = $dao->getTabDataFromSql($query, $params);
    // Parcourez les résultats et créez des objets Cmedicament
    foreach ($medicaments as $medicament) {
        $resultats[] = new Cmedicament(
            $medicament['idMed'],
            $medicament['designationMed'],
            $medicament['niveauDanger'],
            $medicament['descriptifMed'],
            $medicament['dateSortie'],
            $medicament['cheminimageMed']
        );
    }

    // Retournez les résultats
    return $resultats;
}

    
    
    
    public function setMedicamentsForVisitor($selectedMois, $visitorId, $selectedMed)
    {
        $dao = new Cdao();
        $query = '  INSERT INTO presenter (anneeMois, idMed, idVisiteur) 
                    SELECT ?, ?, ? 
                    FROM dual WHERE NOT EXISTS ( 
                    SELECT 1 
                    FROM presenter 
                    WHERE SUBSTRING(anneeMois,1,4) = ? 
                    AND SUBSTRING(anneeMois,5,2) = ? 
                    AND idMed = ? 
                    AND idVisiteur = ? )';

        foreach($selectedMed as $idMed){
            foreach($selectedMois as $anneeMois){
                $resultats = $dao->insertion($query,[$anneeMois,$idMed,$visitorId,substr($anneeMois,0,4),substr($anneeMois,4,2),$idMed,$visitorId]);
            }
        }

        return $resultats;
    }
}


