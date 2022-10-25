<?php

// securiser le site
function verifParams(){
  if(isset($_POST) && sizeof($_POST)>0){
    foreach ($_POST as $key => $value) {
      $data = trim($value);
      $data = stripslashes($data);
      $data = strip_tags($data);
      $data = htmlspecialchars($data);
      $_POST[$key] = $data;
    }
    //print_r($_POST);exit();
  }
  
}



function displayAccueil()
{
  $result = '<h1> Bienvenu sur la page d\'Accueil</h1>';
  $result .= '<div class="bg-white shadow-sm rounded p-6">
  <form class="" action="actionInscription" method="post">
    <div class="mb-4">
      <h2 class="h4">INSCRIPTION</h2>
    </div>

    <!-- Input -->
    <div class=" mb-3">
      <div class="input-group input-group form">
        <input type="text" name="pseudo" class="form-control " value="Supermoi" required="" placeholder="Entrer votre Pseudo" aria-label="Entrer votre Pseudo">
      </div>
    </div>
    <!-- End Input -->

    <!-- Input -->
    <div class=" mb-3">
      <div class="input-group input-group form">
        <input type="email" class="form-control " name="email" value="nicolas@gmail.com" required="" placeholder="Entrez votre adresse email" aria-label="Entrez votre adresse email">
      </div>
    </div>
    <!-- End Input -->

    <!-- Input -->
    <div class=" mb-3">
      <div class="input-group input-group form">
        <input type="password" class="form-control " name="password" value="mauricette2022" required="" placeholder="Entrez votre mot de passe" aria-label="Entrez votre mot de passe">
      </div>
    </div>
    <!-- End Input -->

    <button type="submit" class="btn btn-block btn-primary">S\'inscrire</button>
  </form>
</div>';
  return $result;
}


function displayActionInscription()
{
  global $model;
  //recuperer les infos que l'user a envoyé
  //print_r($_POST); exit();

  $pseudo = $_POST["pseudo"];
  $email = $_POST["email"];
  $password = $_POST["password"];

  //inscrire l'user
  $data = $model->createCustomers($pseudo, $email, $password);
  if ($data) { // inscription réussie
    $data_customer = $model->authentifier($email, $password);

    if ($data_customer) {
      $_SESSION["customer"] = $data_customer;
      return '<p class="btn btn-success btn-block">Inscription réussie ' . $pseudo . ',vous êtes bien connecté </p>' . displayProduit();
    }
  } else { //inscription échouée
    return '<p class="btn btn-danger btn-block">Inscription échouée </p>' . displayProduit();
  }
}


function displayActionConnexion()
{
  //print_r($_POST); exit();
  global $model;
  $email = $_POST["email"];
  $password = $_POST["password"];

  //authentifier l'user
  $data_customer = $model->authentifier($email, $password);

  if ($data_customer) {
    $_SESSION["customer"] = $data_customer;
    return '<p class="btn btn-success btn-block">Authentification réussie, vous êtes bien connecté </p>' . displayProduit();
  } else { //authentification échouée
    return '<p class="btn btn-danger btn-block">Authentification échouée </p>' . displayProduit();
  }
}


function displayDeconnexion()
{
  unset($_SESSION["customer"]);

  return '<p class="btn btn-success btn-block">Vous êtes déconnecté' . displayProduit();
}





// formulaire de contact
function displayContact()
{
}



function displayProduit()
{

  global $model;
  global $model;

  $dataProduct = $model->getProduct();

  $result = '<h1> Bienvenu sur la page Produits</h1>';
  foreach ($dataProduct as $key => $value) {
    $result .= '<div class="card" style="width: 18rem; display:inline-block;">
    <img src="' . BASE_URL . SP . "images" . SP . "produit" . SP . $value["image"] . '" class="card-img-top" alt="...">
    <div class="card-body">
      <h5 class="card-title">' . $value["name"] . '</h5>
      <a href="' . BASE_URL . SP . "details" . SP . $value["id"] . '" class="btn btn-primary">Détails</a>
      <a href="' . BASE_URL . SP . "panier" . SP . $value["id"] . '" class="btn btn-success">Acheter</a>
    </div>
  </div>';
  }



  return $result;
}


function displayCategory()
{
  global $model;
  global $url;
  global $category;
  // securiser le site . si tentative malveillante d'une personne qui connait en informatique
  if (isset($url[1]) && is_numeric($url[1]) && $url[1] > 0 && $url[1] <= sizeof($category)) {
    $result = '<h1> Produit de la catégorie ' . $category[$url[1] - 1]["name"] . '</h1>';
    $dataProduct = $model->getProduct(null, $url[1]);

    foreach ($dataProduct as $key => $value) {
      $result .= '<div class="card" style="width: 18rem; display:inline-block;">
        <img src="' . BASE_URL . SP . "images" . SP . "produit" . SP . $value["image"] . '" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">' . $value["name"] . '</h5>
          <a href="' . BASE_URL . SP . "details" . SP . $value["id"] . '" class="btn btn-primary">Détails</a>
          <a href="' . BASE_URL . SP . "panier" . SP . $value["id"] . '" class="btn btn-success">Acheter</a>
         
        </div>
      </div>';
    }
  } else {
    $result = '<h1> URL incorrecte !</h1>';
  }


  return $result;
}



function displayDetails()
{
  global $model;
  global $url;
  global $category;
  $result = '<h1> Bienvenu sur la page de détails produits</h1>';
  $dataProduct = $model->getProduct(null, null, $url[1]);
  //print_r($dataProduct);exit();
  $result .= '
    <div class="row details">
      <div class="col-md-5 col-12">
      <img src="' . BASE_URL . SP . "images" . SP . "produit" . SP . $dataProduct[0]["image"] . '" class="card-img-top" alt="...">

      </div>
      <div class="col-md-7 col-12">
        <h2>' . $dataProduct[0]["name"] . '</h2>
        <p>' . $dataProduct[0]["description"] . '</p>
        <p> Categorie : ' . $category[$dataProduct[0]["category"] - 1]["name"] . '</p>
        <a href="' . BASE_URL . SP . "panier" . SP . $dataProduct[0]["id"] . '" class="btn btn-block btn-success">Ajouter au panier </a>
        <a href="' . BASE_URL . SP . "produit" . '" class="btn btn-block btn-primary">Retour Page Produits </a>

      </div>

    </div>
  ';


  return $result;
}



function displayPanier()
{
  global $model; // pour accèder à ma bdd
  global $url; //accèder aux infos du id produit que l'user veut acheter

  if (isset($url[1])) {

    $idProduct = $url[1]; // récup id du produit
    $dataProduct = $model->getProduct(null, null, $url[1]); //récuper l'id du produit

    $_SESSION["panier"][] = $dataProduct[0]; // stocker ces infos de manière persistante
    //print_r($_SESSION);exit();
  }

  if (!isset($_SESSION["panier"]) || sizeof($_SESSION["panier"]) == 0) {
    return '<h1>Votre panier est vide</h1>' . displayProduit();
  }

  $result = '<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Nom</th>
      <th scope="col">Description</th>
      <th scope="col">Image</th>
      <th scope="col">Prix</th>
      <th scope="col">Quantité</th>
      <th scope="col">Action</th>
    </tr>
  </thead>

  <tbody>
  ';

  $total_price = 0;

  //Afficher à l'écran le contenu du panier de l'user
  foreach ($_SESSION["panier"] as $key => $value) {
    $total_price += $value["price"];

    $result .= '<tr>

      <th scope="row">' . $value["id"] . '</th>
      <td>' . $value["name"] . '</td>
      <td>' . $value["description"] . '</td>  
      <td><img src="' . BASE_URL . SP . "images" . SP . "produit" . SP . $value["image"] . '" alt="..."/></td>
      <td>' . $value["price"] . '€</td>
      <td>1</td>
    
      <td><a href="' . BASE_URL . SP . "supprimer" . SP . $key . '" class="btn btn-primary">Supprimer</a></td>
    
    </tr>';
  }


  $total_tva = $total_price * TVA / 100;
  $total_ttc =  $total_tva + $total_price;
  $result .= '<tr><td></td><td></td><td></td><td>Prix total (HT)</td><td>' . number_format($total_price, 2) . '€</td><td></td></tr>
              <tr><td></td><td></td><td></td><td>Tva (' . TVA . '%)</td><td>' . number_format($total_tva, 2) . '€</td><td></td></tr>
              <tr><td></td><td></td><td></td><td>Total (TTC)</td><td>' . number_format($total_ttc, 2) . '€</td><td></td></tr>';
  $result .= '</tbody>
   
  </table>';

  $result .= '<a href="'.BASE_URL.SP."validationCommande".'" class="btn btn-success btn-block">Valider votre commande</a>';
  return $result;
}



function displaySupprimer()
{
  //print_r($_SESSION["panier"]);
  global $url;


  //voir si le paramètre est donné
  if (isset($url[1]) && is_numeric($url[1])) {
    $param = $url[1];

    //enlever le produit du panier
    unset($_SESSION["panier"][$param]);
    header("Location: " . BASE_URL . SP . "panier");
  }
}

//profil de l'user
function displayProfil()
{
  if (isset($_SESSION['customer']["sexe"])) {
    if ($_SESSION['customer']["sexe"] == 1) {
      $_SESSION['customer']["sexe"] = "masculin";
    } else {
      $_SESSION['customer']["sexe"] = "féminin";
    }
  }

  $result = '
  <ul class="list-group">
    <li class="list-group-item active" aria-current="true">Bienvenue sur votre profil ' . $_SESSION['customer']["pseudo"] . '</li>
    <li class="list-group-item"> sexe:' . $_SESSION['customer']["sexe"] . ' </li>
    <li class="list-group-item"> pseudo :' . $_SESSION['customer']["pseudo"] . '</li>
    <li class="list-group-item"> nom : ' . $_SESSION['customer']["firstname"] . '</li>
    <li class="list-group-item"> prénom : ' . $_SESSION['customer']["lastname"] . '</li>
    <li class="list-group-item"> email : ' . $_SESSION['customer']["email"] . '</li>

    <li class="list-group-item"> description : ' . $_SESSION['customer']["desccription"] . '</li>
    <li class="list-group-item"> adresse livraison : ' . $_SESSION['customer']["adresse_livraison"] . '</li>
    <li class="list-group-item"> adresse facturation : ' . $_SESSION['customer']["adresse_facturation"] . '</li>

  </ul>

  <div class="mt-2">
    <a href="' . BASE_URL . SP . "updateProfil" . '" class="btn btn-success">Mettre à jour mes données</a>
  </div>
  ';

  return $result;
}


function displayUpdateProfil()
{
  $result = '
  <form action="updateAction" method="post">
      <div class="form-row">
        <div class="input-group mb-3">
        <div class="input-group-prepend">
          <label class="input-group-text" for="inputGroupSelect01">Sexe</label>
        </div>
        
        <select name="sexe" class="custom-select" id="inputGroupSelect01">
          <option selected>Choose...</option>
          <option value="1">Masculin</option>
          <option value="2">Féminin</option>
        </select>
    </div>
    
    <div class="form-group col-md-3">
      <label for="inputEmail4">Nom </label>
      <input type="text" name="firstname" value="'.$_SESSION['customer']["firstname"].'" class="form-control" id="inputEmail4">
    </div>
   
    <div class="form-group col-md-3">
      <label for="inputPassword4">Prénom </label>
      <input type="text" name="lastname" value="'.$_SESSION['customer']["lastname"].'" class="form-control" id="inputPassword4">
    </div>
   
    <div class="form-group col-md-3">
      <label for="inputPassword4">Email </label>
      <input type="text" name="email" value="'.$_SESSION['customer']["email"].'" class="form-control" id="inputPassword4">
    </div>
   
    <div class="form-group col-md-3">
      <label for="inputPassword4">Téléphone </label>
      <input type="text" name="tel" value="'.$_SESSION['customer']["tel"].'" class="form-control" id="inputPassword4">
    </div>
  </div>
 
  <div class="form-group">
    <label for="inputAddress">Description</label>
    <input type="text" name="description" value="'.$_SESSION['customer']["description"].'" class="form-control" id="inputAddress" placeholder="Merci de taper une courte descricption de vous !">
  </div>
 
  <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputPassword4">Adresse de Facturation </label>
        <input type="text" value="'.$_SESSION['customer']["adresse_facturation"].'" name="adresse_facturation" class="form-control" id="inputPassword4">
      </div>
      <div class="form-group col-md-6">
        <label for="inputPassword4">Adresse de Livraison </label>
        <input type="text" name="adresse_livraison" value="'.$_SESSION['customer']["adresse_livraison"].'" class="form-control" id="inputPassword4">
      </div>
  </div>

  <button type="submit" class="btn btn-success">Mettre à jour</button>
</form>
 

  
  
  ';


  return $result;
}



// récuperer adresse livraison et facturation
function displayUpdateAction(){
  global $model;

  $_POST["id"] = $_SESSION["customer"]["id"];
 //print_r($_POST);exit();
 
  $r = $model->updateInfosCustomer($_POST);
 
  if ($r) {
    $_SESSION["customer"] = $model->getCustomer($_SESSION["customer"]["id"]);
    $result = '<p class="btn btn-success btn-block"> mise à jour réussie </p>';
  }else{
    $result = '<p class="btn btn-danger btn-block"> mise à jour échouée </p>';
  }

  

  return $result.displayProfil();
}



function displayValidationCommande(){
  global $model;
  if(isset($_SESSION["customer"])){ // l'utilisateur est connecté
    $dataCustomer = $_SESSION["customer"];
   // print_r($_SESSION["panier"]);exit();
    foreach ($_SESSION["panier"] as $key => $value) {
      $r = $model->createOrders($dataCustomer["id"],$value["id"],1,$value["price"]);
      if($r){
        return "Validation de Commande échouée";
      }
    }
    unset($_SESSION["panier"]);
    $result = "Validation de Commande Réussie. Vous pouvez passer réucupérer en magasin dans 30min";


  }else{// l'utilisateur n'est pas connecté
    $result = '<p class="btn btn-danger btn-block">Connectez-vous pour pouvoir valider votre commande</p>';
    $result .= displayAccueil();
  }
  return $result;


}




?>