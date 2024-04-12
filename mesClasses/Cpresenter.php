<?php
require_once 'Cdao.php';
 class Cpresenter{

    public $anneeMois;
    public $idMed;
    public $idVisiteur;

    function __construct( $sanneeMois, $sidMed, $sidVisiteur){
        $this->anneeMois  = $sanneeMois;
        $this->idMed  = $sidMed;
        $this->idVisiteur  = $sidVisiteur;
    }
 }

 class Cpresenters {
    public $ocollePresenter;
    public function __construct(){

        try{
            $dao = new Cdao();
            $query = 'SELECT * FROM presenter';

            $lesPresentation = $dao->getTabDataFromSql($query);

            foreach ($lesPresentation as $unePresentation) {
                $opresenter = new Cpresenter(
                    $unePresentation['anneeMois'],
                    $unePresentation['idMed'],
                    $unePresentation['idVisiteur']
                );
                $this->ocollePresenter[] = $opresenter;

            }
        } catch (PDOException $e) {
            $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            die($msg);
        }
    }
    public function getPresentation()
    {
        return $this->ocollePresenter;
    }
    public function deletePresentation($anneMois, $idMed, $idVisiteur) {
        try {
            $dao = new Cdao();
            $query = 'DELETE FROM presenter WHERE anneeMois = :anneeMois and idMed = :idMed and idVisiteur = :idVisiteur';

            $lepdo = $dao->getObjetPDO();
            $sth = $lepdo->prepare($query);
            $sth->bindValue(':anneeMois', $anneMois);
            $sth->bindValue(':idMed', $idMed);
            $sth->bindValue(':idVisiteur', $idVisiteur);
            $sth->execute();
            unset($lepdo);

            // Supprimer la présentation de la collection en mémoire si nécessaire
            foreach ($this->ocollePresenter as $key => $presentation) {
                if ($presentation->anneeMois == $anneMois && $presentation->idMed == $idMed && $presentation->idVisiteur == $idVisiteur) {
                    unset($this->ocollePresenter[$key]);
                    break; // Sortir de la boucle une fois que la présentation est supprimée
                }
            }
        } catch (PDOException $e) {
            $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
            die($msg);
        }
    }
 }