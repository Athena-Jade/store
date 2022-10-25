<?php // ce fichier permet de faire des importations de variables pour les inclure dans d'autres fichiers

// pour accèder à la racine du projet
//print_r($_SERVER); exit();
define("SRC", dirname(__FILE__));
define("ROOT", dirname(SRC));
define("SP", DIRECTORY_SEPARATOR);

define("CONFIG", ROOT.SP."config"); 

define("VIEWS", ROOT.SP."views"); 
define("MODEL", ROOT.SP."model");


define("BASE_URL", dirname(dirname($_SERVER['SCRIPT_NAME']))); 

define("TVA", 20);

//import du nmodel
require CONFIG.SP."config.php";
require MODEL.SP."DataLayer.class.php";

$model = new DataLayer();
//exit();

//$var = $data->createCustomers('louchou','choupi@gmail.com','mauricette2022');

//authentifier l'user
//$authen = $data->authentifier('choupi@gmail.com', 'mauricette2022');
//print_r($authen); exit();

// les fonctions appelées par le controller


//Récupérer les categories pour le dropdown de la navbar
$category = $model->getCategory();
//print_r($category); exit();

//$data = $model->getCustomer(13);
//print_r($data); exit();



//mise à jour infos customer
//$data->updateInfosCustomer(array('id'=>13, 'sexe'=>1, 'pseudo'=>'jean', 'lastname'=>'vincent', 'firstname'=>'DUPONT', 'email'=>'jean@gmail.com'));





//recuperer les categories de mes produits
//$category = $data->getCategory();
//print_r($category); exit();



//recuperer produits de ma bdd
//$products = $data->getProduct(2);
//print_r($products); exit();
//var_dump($products); exit();


















// les fonctions appelée par le controller
require "functions.php";














?>