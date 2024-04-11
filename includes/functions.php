<?php

function getDb()
{
    return new PDO("mysql:host=localhost;dbname=gsb_frais;charset=utf8", "root", "",
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        
}

function getAnneeMois()
{
    return date("Ym");
}

 function prepareChaineHtml($schaine)
 {
     //return trim(htmlspecialchars($schaine));
     //$text = htmlentities($chaine, ENT_NOQUOTES, "UTF-8");
     //$text = htmlspecialchars_decode($text, ENT_NOQUOTES, "UTF-8");
     $text = utf8_decode($schaine); //$schaine est en utf8 et va etre mis en iso
     return $text;
 }
 
 function moisEnFrancais($schaine)
 {
     $lesMois = array(
         
         'January'   => 'Janvier',
         'February'  => 'Février',
         'March'     => 'Mars',
         'April'     => 'Avril',
         'May'       => 'Mai',
         'June'      => 'Juin',
         'July'      => 'Juillet',
         'August'    => 'Août',
         'September' => 'Septembre',
         'October'   => 'Octobre',
         'November'  => 'Novembre',
         'December'  => 'Décembre',      
     );
     
     return $lesMois[$schaine];
 }

 function getSixProchainsMois()
 {
     // Initialisation de la liste des mois
     $sixProchainsMois = array();
 
     // Obtenez la date actuelle
     $dateActuelle = new DateTime();
 
     // Ajoutez les 6 prochains mois à la liste
     for ($i = 0; $i < 6; $i++) { 
         $dateActuelle->modify('+1 month'); // Ajoute un mois à la date actuelle
         // Obtenez l'année et le mois en chiffre
         $annee = $dateActuelle->format('Y');
         $mois = $dateActuelle->format('m');
         $sixProchainsMois[] = array('annee' => $annee, 'mois' => $mois);
         
     }
 
     // Retourne la liste des 6 prochains mois
     return $sixProchainsMois;
 }
 
 /*
 function filtre_int_get($item)
 {
     return $reponse = filter_var($item, FILTER_VALIDATE_INT); // retourne soit false ou la valeur entière
         
 }*/
    


?>

