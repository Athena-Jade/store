<?php  
    // initialiser une session afin de récupérer ce que le client à mit dans son panier
    session_start();

   require "include.php";

   verifParams();

   $url = trim($_SERVER['PATH_INFO'],'/');
   $url = explode('/',$url);
   $route = array("accueil", "contact", "produit","category", "details", "panier", "supprimer", "actionInscription", "deconnexion", "profil", "actionConnexion", "updateProfil", "updateAction", "validationCommande");
   //print_r($url);

   $action = $url[0];
   
   // controller
   if(!in_array($action,$route)){
       $title = "Page Error";
       $content = "URL introuvable !";
   }else{
       //echo 'Bienvenu sur la page '.$action;
       $function = "display".ucwords($action);
       $title = "Page ".$action;
       $content = $function();
   }
   require VIEWS.SP."templates".SP."default.php";


  
  ?>