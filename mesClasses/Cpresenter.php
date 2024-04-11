<?php
require_once 'Cdao.php';
 class Cpresenter{

    public $idPres;
    public $anneeMois;
    public $idMed;
    public $idVisiteur;

    function __construct($sidPres, $sanneeMois, $sidMed, $sidVisiteur){
        $this->idPres = $sidPres;
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
                    $unePresentation['idPres'],
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
    public function deletePresentation($idPresentation) {
        try {
            $dao = new Cdao();
            $query = 'DELETE FROM presenter WHERE idPres = :idPres';

            $lepdo = $dao->getObjetPDO();
            $sth = $lepdo->prepare($query);
            $sth->bindValue(':idPres', $idPresentation);
            $sth->execute();
            unset($lepdo);

            // Supprimer la présentation de la collection en mémoire si nécessaire
            foreach ($this->ocollePresenter as $key => $presentation) {
                if ($presentation->idPres == $idPresentation) {
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