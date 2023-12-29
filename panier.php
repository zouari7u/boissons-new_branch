<?php 

session_start();
?>

<?php

    if(isset($_POST['supprimer_panier'])){


        session_start(); // Démarrer la session sur chaque page où vous utilisez $_SESSION

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
            
            $dateAjout = date("Y-m-d H:i:s");

            //echo "<script>console.log('slt');</script>\n";

            try {
                //echo "<script>console.log('cc');</script>\n";
                $pgDB = new PDO('mysql:host=127.0.0.1;dbname=Cocktails', 'root', '');
            } catch (Exception $e) {
                exit($e->getMessage());
            }
            
            //Récupération des données depuis le formulaire
            $titre = $_POST['titreRecette'];
            $id = $_SESSION['id'];
            $dateAjout = date("Y-m-d H:i:s");

            echo "<script>console.log('$titre');</script>\n";

            // Vérification si le cocktail existe dans la table 'Cocktail'
            $checkCocktail = $pgDB->prepare("SELECT nomCocktail FROM Cocktail WHERE nomCocktail = :nomCocktail");
            $checkCocktail->bindParam(':nomCocktail', $titre, PDO::PARAM_STR);
            $checkCocktail->execute();
            $cocktailExists = $checkCocktail->fetch(PDO::FETCH_ASSOC);

            $checkId = $pgDB->prepare("SELECT login FROM Utilisateur WHERE login = :login");
            $checkId->bindParam(':login', $id, PDO::PARAM_STR);
            $checkId->execute();
            $idExists = $checkId->fetch(PDO::FETCH_ASSOC);

            if ($cocktailExists && $idExists) {

                echo "<script>console.log('existe');</script>\n";
                // Le cocktail existe dans la table 'Cocktail', on peut l'insérer dans la table 'Panier'
                $sql = "DELETE FROM Panier WHERE loginP = :loginP AND nomCocktailP = :nomCocktailP";
                $stmt = $pgDB->prepare($sql);
                $stmt->bindParam(':loginP', $_SESSION['id'], PDO::PARAM_STR);
                $stmt->bindParam(':nomCocktailP', $titre, PDO::PARAM_STR);

                $stmt->execute();
                
            }
        
        }

    }
?>


<!DOCTYPE html>
<html>
<head>
    <title>Mon Panier</title>  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>
<body class="is-preload">

    <div id="wrapper">
        <div id="main">
            <div class="inner">

                <!-- Header -->
                <header id="header">
                    <a href="index.php" class="logo"><strong>Boissons</strong> by Nabaji & Zouari</a>
                
                </header>
                <?php
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                        try {
                            $pgDB = new PDO('mysql:host=127.0.0.1;dbname=Cocktails', 'root', '');
                        } catch (Exception $e) {
                            exit($e->getMessage());
                        }
                    
                        $nom_utilisateur = $_SESSION['id'];
                    
                        // Requête SQL pour récupérer les informations des recettes dans le panier de l'utilisateur spécifique
                        $sql = "SELECT Panier.loginP, Cocktail.nomCocktail, Panier.dateAjout
                                FROM Panier
                                INNER JOIN Cocktail ON Panier.nomCocktailP = Cocktail.nomCocktail
                                WHERE Panier.loginP = :loginP";
                        
                        try {
                            $stmt = $pgDB->prepare($sql);
                            $stmt->bindParam(':loginP', $nom_utilisateur, PDO::PARAM_STR);
                            $stmt->execute();
                        
                            // Récupération des résultats
                            $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                            echo "<ul>";
                    
                            // Affichage des informations sur chaque recette dans le panier de l'utilisateur
                            foreach ($resultats as $resultat) {
                                echo "<li>" . $resultat['nomCocktail'];
                                echo "<form action='' method='post'>";
                                echo "<input type='hidden' id='titreRecette' name='titreRecette' value=\"" . $resultat['nomCocktail'] . "\" />";
                                echo "<input type='submit' name='supprimer_panier' class='button big' value='Supprimer du panier'/>";
                                echo "</form>";
                                echo "</li>";
                            }
                    
                            echo "</ul>";
                    
                        } catch (PDOException $e) {
                            exit("Erreur lors de l'exécution de la requête: " . $e->getMessage());
                        }
                    
                    }
                ?>
               
            </div>
        </div>
        <?php include 'sidebar.php'; ?>
    </div>
    
            <script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

</body>
    

</html>