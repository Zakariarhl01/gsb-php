<?php

class Cdao  //classe outil
{

    public $test = 'ceci est un test';

  
   //  function filtrerChainePourBD($str) {
   //      if ( ! get_magic_quotes_gpc() ) { 
   //          // si la directive de configuration magic_quotes_gpc est activée dans php.ini,
   //          // toute chaîne reçue par get, post ou cookie est déjà échappée 
   //          // par conséquent, il ne faut pas échapper la chaîne une seconde fois                              
   //          $str = mysql_real_escape_string($str);
   //      }
   //      return $str;
   //  }

    public function getObjetPDO() //mettre le DSN en paramètre formel
    {

        //$strConnection = 'mysql:host=172.29.0.118;dbname=gsb'; // DSN
        $strConnection = 'mysql:host=localhost;dbname=gsb'; // DSN
        $arrExtraParam= array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"); // demande format utf-8
        //$pdo = new PDO($strConnection, 'remote_user', 'dxGm8FqWbHL/s0Dv', $arrExtraParam); // Instancie la connexion si base distante remote_user remotepass
        $pdo = new PDO($strConnection, 'root', '', $arrExtraParam);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// Demande la gestion d'exception car par défaut PDO ne la propose pas

        return $pdo;
    }

    public function getTabObjetFromSql($squery) // $stype sera le type d'objet que l'on désire
    {
       $lepdo = $this->getObjetPDO();

       $sth = $lepdo->prepare($squery); //prepare echappe entre autre proprement la requete
       $sth->execute();

       $result = $sth->fetchAll(); // fetchall met toutes les données dans un tableau d'objets du type en paramètre
       unset($lepdo);
       return $result; //$result est un tableau d'objet du type en parametre comportant tous les objets du type (All de fetchAll)

    }

    public function getTabDataFromSql($squery, $params = array()) 
    {
        $lepdo = $this->getObjetPDO();
    
        $sth = $lepdo->prepare($squery);
        $sth->execute($params); // Lier les paramètres à la requête
    
        $result = $sth->fetchAll();
        
        return $result;
    }
    
    public function insertion($squery,$array = null) //fera une insertion dans la base en fonction de la requete recue en parametre
    {
             $lepdo = $this->getObjetPDO();
   
             $sth = $lepdo->prepare($squery);
             $sth->execute($array);
             
             unset($lepdo);            
       
    }
    
    public function update($squery) //fera une mise à jour dans la base en fonction de la requete recue en parametre
    {
             $lepdo = $this->getObjetPDO();
   
             $sth = $lepdo->prepare($squery);
             $sth->execute();
             unset($lepdo);            
       
    }

    public function delete($squery)
    {
       $lepdo = $this->getObjetPDO();
   
       $sth = $lepdo->prepare($squery);
       $sth->execute();
       unset($lepdo); 
        
    }
    
}