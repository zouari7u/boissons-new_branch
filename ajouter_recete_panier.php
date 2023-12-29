<?php
   
    session_start();

   try {
        $pgDB = new PDO('mysql:host=127.0.0.1;dbname=Cocktails', 'root', '');
    } catch (Exception $e) {
        exit($e->getMessage());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Vérifier si les données ont été envoyées
        if (isset($_POST['titreRecette']) && isset($_POST['id'])) {
            // Récupérer le titre de la recette et l'identifiant de l'utilisateur
            $titreRecette = $_POST['titreRecette'];
            $idUtilisateur = $_POST['id'];
    
               // Requête SQL pour insérer la nouvelle recette dans la table
               $sql = "INSERT INTO Panier (loginP, nomCocktailP, dateAjout) VALUES (:id, :nomCocktailP, :dateAjout)";
    
               $stmt = $pgDB->prepare("$sql");
                $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_STR);
               $stmt->bindParam(':login', $login, PDO::PARAM_STR);
               $stmt->bindParam(':nomCocktailP', $titre, PDO::PARAM_STR);
               $stmt->bindParam(':dateAjout', $dateAjout, PDO::PARAM_STR);
               $stmt->execute();

        }

    }

?>