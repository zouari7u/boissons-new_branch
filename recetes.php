<!DOCTYPE html>
<html>
<head>
    <title>Nos recettes</title>  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
</head>
<?php

    if(isset($_POST['ajouter_panier'])){

        echo "<script>console.log('cc');</script>\n";

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
                $insertQuery = "INSERT INTO Panier (loginP, nomCocktailP, dateAjout) VALUES (:loginP, :nomCocktailP, :dateAjout)";
                $stmt = $pgDB->prepare($insertQuery);
                $stmt->bindParam(':loginP', $_SESSION['id'], PDO::PARAM_STR);
                $stmt->bindParam(':nomCocktailP', $titre, PDO::PARAM_STR);
                $stmt->bindParam(':dateAjout', $dateAjout, PDO::PARAM_STR);

                $stmt->execute();
                
            }
        
        }

    }
?>
<body class="is-preload">

    <div id="wrapper">
        <div id="main">
            <div class="inner">
                <!-- Header -->
                <header id="header">
                    <a href="index.php" class="logo"><strong>Boissons</strong> by Nabaji & Zouari</a>
                
                </header>
                <?php
                    $sql = new PDO('mysql:host=127.0.0.1;dbname=Cocktails', 'root', '');

                        $stmt = $sql->prepare("SELECT * FROM Cocktail c");
                        $stmt->execute();
                        $cocktails = $stmt->fetchAll();

                    //echo "<script>console.log('cocktails : ', $cocktails);</script>";
                    

                    echo "<h1>Recettes</h1>";
                    foreach ($cocktails as $cocktail) {
                        $titre = $cocktail['nomCocktail'];
                        echo '<section id="banner">';
                        echo '<div class="content">';
                        echo '<header>';
                        echo '<h1>' . $cocktail['nomCocktail'] . '</h1>';
                        echo '</header>';
                        echo '<p>' . $cocktail['ingredients'] . '</p>';
                        echo '<p>' . $cocktail['preparationCocktail'] . '</p>';
                        echo '<ul class="actions">';
                        echo "<form action='' method='post'>";
                        echo "<input type='hidden' id='titreRecette' name='titreRecette' value='$titre' />";
                        echo "<input type='submit' name='ajouter_panier' class='button big' value='Ajouter a mon panier'/>";
                        echo "</form>";
                        echo '</ul>';
                        echo '</div>';
                        echo '<span class="image object">';
    
                        // Generating the image source based on naming conventions
                        $imageName = str_replace(' ', '_', $cocktail['nomCocktail']) . '.jpg';
                        $imagePath = 'assets/images/icon.png';
                        if (file_exists('assets/images/' . $imageName)) {
                            $imagePath = 'assets/images/' . $imageName;
                        }
                        echo '<img src="' . $imagePath . '" alt="" />';
                        echo '</span>';
                        echo '</section>';
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