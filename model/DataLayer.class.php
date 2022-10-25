<?php
// ce fichier permet d'interagir avec la bdd

class DataLayer
{
    private $connexion; //connexion avec ma bdd jstore

    function __construct()
    { // créer un constructeur
        try {
            // $this->connexion = new PDO("mysql:host=".HOST.",dbname=".DB_NAME, DB_USER, DB_PASSWORD);
            $this->connexion = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            //  echo "connexion bdd ok";
        } catch (PDOException $th) {
            echo $th->getMessage();
        }
    }



    /**
     * fonction pour créer un customer en base de données
     * @param pseudo le pseudo du customer
     * @param email l'email du customer
     * @param password le mot de passe du customer
     * @return TRUE sien cas de création avec succès du customer, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function createCustomers($pseudo, $email, $password)
    {
        $sql = "INSERT INTO customers (pseudo,email,password) VALUES (:pseudo,:email,:password)";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                ':pseudo' => $pseudo,
                ':email' => $email,
                ':password' => sha1($password)
            ));
            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }


    /**
     * fonction qui permet d'authentifier un customer
     * @param email l'email du customer
     * @param password le mot de passe du customer
     * @return ARRAY tableau contenant les infos du customer si authentification réussie
     * @return FALSE si authentification échouée
     * @return NULL s'il y a une exception déclenchée 
     */
    function authentifier($email, $password)
    {
        $sql = "SELECT * FROM customers WHERE email = :email";
        try {
            $result = $this->connexion->prepare($sql);
            $result->execute(array(':email' => $email));
            $data = $result->fetch(PDO::FETCH_ASSOC);
            if ($data && ($data['password'] == sha1($password))) {
                unset($data['password']); // unset retire le mot de passe qui est visible à l'écran
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }




    // fonction permettant aux users de passer commande

    /**
     * 
     * @param idCustomers l'identifiant du customers
     * @param idPoduct l'identifiant du produt de la commande
     * @param quantity La quantité du produit commandé
     * @param price Le prix de la commande
     * @return TRUE si en cas de commande réalisée avec succès, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    function createOrders($idCustomers, $idPoduct, $quantity, $price)
    {
        $sql = "INSERT INTO `orders`(`id_customers`, `id_product`, `quantity`, `price`) VALUES 
        (:id_customers,:id_product,:quantity,:price)";
        try {
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array(
                ':id_customers' => $idCustomers,
                ':id_product' => $idPoduct,
                ':quantity' => $quantity,
                ':price' => $price
            ));
            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }


    /**
     * fonction qui met à jour les information d'un utilisateur
     * en base de données
     * @param newInfos Tableau de associatif "nom_champ_a_mettre_a_jour" => "nouvelle_valeur"
     * @return TRUE si en cas de succès de la mise à jour, FALSE sinon
     * @return NULL s'il y a une exception déclenchée 
     */
    //mettre à jour infos des customers
    function updateInfosCustomer($newInfos)
    {
        $sql = "UPDATE `customers` SET ";
        $id = $newInfos["id"];
        unset($newInfos["id"]);
        try {
            foreach ($newInfos as $key => $value) {
                $value = addslashes($value);
                $sql .= " $key = '$value' ,";
            }
            $sql = substr($sql, 0, -1); // retire la dernière virgule

            $sql .= " WHERE id = :id ";
          //  print_r($sql); exit();
            $result = $this->connexion->prepare($sql);
            $var = $result->execute(array('id' =>$id));
             
            if ($var) {
                return TRUE;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }



    //permet de récupérer toutes les categories de ma bdd
    function getCategory()
    {
        $sql = "SELECT * FROM category";

        //preparer la requête
        try {
            $result = $this->connexion->prepare($sql);

            //executer la requête
            $var = $result->execute();

            //verifier si on a obtenu des valeurs
            $data = $result->fetchAll(PDO::FETCH_ASSOC);

            //verifier si le tableau existe
            if ($data) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }


    
    /**
     * fonction qui sert à récupérer les catégories de produits au sein de la base de données
     * @param id id de l'utilisateur
     * @return array tableau contenant les infos de l'utilisateur, en cas de succès, FALSE sinon
     * @return FALSE dans le cas d'echec
     * @return NULL s'il y a une exception déclenchée 
     */

    function getCustomer($id)
    {
        $sql = "SELECT * FROM customers WHERE id = ?";

        //preparer la requête
        try {
            $result = $this->connexion->prepare($sql);

            //executer la requête
            $var = $result->execute(array($id));

            //verifier si on a obtenu des valeurs
            $data = $result->fetchAll(PDO::FETCH_ASSOC);

            //verifier si le tableau existe
            if ($data) {
                unset ($data[0]['password']); 
                return $data[0];
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }
    }





    //recuperer un ou des produits
    function getProduct($limit=NULL,$category = NULL,$id=NULL){
        $sql = "SELECT * FROM product ";

        //preparer la requête
        try {            
            if (!is_null($id)) {
                $sql .= 'WHERE  id = '.$id;
            }

            if (!is_null($category)) {
                $sql .= 'WHERE  category = '.$category;
            }
            if (!is_null($limit)) {
                $sql .= ' LIMIT '.$limit;
            }

           // print_r($sql); exit();
           // var_dump($sql); exit();

            $result = $this->connexion->prepare($sql);

            //executer la requête
            $var = $result->execute();

            //verifier si on a obtenu des valeurs
            $data = $result->fetchAll(PDO::FETCH_ASSOC);

            //verifier si le tableau existe
            if ($data) {
                return $data;
            } else {
                return FALSE;
            }
        } catch (PDOException $th) {
            return NULL;
        }

    }


}
