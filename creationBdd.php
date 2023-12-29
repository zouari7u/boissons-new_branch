<?php
    include 'Donnees.inc.php';

    /*conexion a mysql*/
    $mysqli=mysqli_connect('127.0.0.1', 'root', '') or die("Erreur de connexion");
    $base="Cocktails";

    $requete = "CREATE DATABASE IF NOT EXISTS $base";
    if ($mysqli->query($requete) === TRUE) {
        echo "<script>console.log('Base de données créée correctement');</script>\n";
    } else {
        echo "<script>console.log('Erreur lors de la création de la base de données : ' , $mysqli->error , '\n');</script>";
    }

    $mysqli->query("USE $base");


    $requete = "
        CREATE TABLE IF NOT EXISTS Aliment(
            nomAliment varchar(50) NOT NULL,
            pereAliment varchar(50) NOT NULL,
            PRIMARY KEY(nomAliment)
        );
    ";
    $stmt = $mysqli->prepare($requete);
    if(!$stmt){
        echo "<script>console.log('Préparation ratée : (', $mysqli->errno,') ',$mysqli->error,'\n');</script>";
    }
    $stmt->execute();

    $requete = "
        CREATE TABLE IF NOT EXISTS Cocktail (
            nomCocktail varchar(100) NOT NULL,
            preparationCocktail varchar(1000) NOT NULL,
            ingredients varchar(800) NOT NULL,
            PRIMARY KEY(nomCocktail)
        );
    ";
    $stmt = $mysqli->prepare($requete);
    if(!$stmt){
        echo "<script>console.log('Préparation ratée : (', $mysqli->errno,') ',$mysqli->error,'\n');</script>";
    }
    $stmt->execute();
   
    $requete = "
        CREATE TABLE IF NOT EXISTS Utilisateur(
            login varchar(50) NOT NULL,
            mdp varchar(50) NOT NULL,
            nom varchar(50) ,
            prenom varchar(50) ,
            sexe char(1) ,
            numTel varchar(10) ,
            dateNaissance varchar(50) ,
            mail varchar(50) ,
            adresse varchar(50) ,
            codePostal varchar(5),
            ville varchar(50) ,    
            PRIMARY KEY (login)
        );    
    ";
    $stmt = $mysqli->prepare($requete);
    if(!$stmt){
        echo "<script>console.log('Préparation ratée : (', $mysqli->errno,') ',$mysqli->error,'\n');</script>";
    }
    $stmt->execute();

    $requete = "
        CREATE TABLE IF NOT EXISTS Liaison(
            nomAlimentL varchar(50),
            nomCocktailL varchar(50),
            PRIMARY KEY (nomAlimentL, nomCocktailL),
            CONSTRAINT key_aliment FOREIGN KEY(nomAlimentL) REFERENCES Aliment(nomAliment),
            CONSTRAINT key_cocktailL FOREIGN KEY(nomCocktailL) REFERENCES Cocktail(nomCocktail)
        );
    ";
    $stmt = $mysqli->prepare($requete);
    if(!$stmt){
        echo "<script>console.log('Préparation ratée : (', $mysqli->errno,') ',$mysqli->error,'\n');</script>";
    }
    $stmt->execute();

    $requete = "
        CREATE TABLE IF NOT EXISTS Panier(
            loginP varchar(50) NOT NULL,
            nomCocktailP varchar(100) NOT NULL,
            dateAjout date NOT NULL,
            PRIMARY KEY(loginP, nomCocktailP),
            CONSTRAINT key_login FOREIGN KEY(loginP) REFERENCES Utilisateur(login),
            CONSTRAINT key_cocktail FOREIGN KEY(nomCocktailP) REFERENCES Cocktail(nomCocktail)
        );
    ";
    $stmt = $mysqli->prepare($requete);
    if(!$stmt){
        echo "<script>console.log('Préparation ratée : (', $mysqli->errno,') ',$mysqli->error,'\n');</script>";
    }
    $stmt->execute();


   
foreach ($Hierarchie as $nom => $alimentT){
    if (isset($alimentT['super-categorie'])){
        foreach ($alimentT['super-categorie'] as $cat => $pere){
            // Vérification de l'existence de l'aliment avant l'insertion
            $stmt_check = $mysqli->prepare("SELECT nomAliment FROM Aliment WHERE nomAliment = ?");
            $stmt_check->bind_param("s", $nom);
            $stmt_check->execute();
            $result = $stmt_check->get_result();

            if ($result->num_rows == 0) { // Si l'aliment n'existe pas encore, on l'insère
                $stmt_insert = $mysqli->prepare("INSERT INTO Aliment(nomAliment, pereAliment) values (?, ?)");
                $stmt_insert->bind_param("ss", $nom, $pere);
                $stmt_insert->execute();
                mysqli_stmt_close($stmt_insert);
            }

            mysqli_stmt_close($stmt_check);
        }
    }
}

  // Parcours des recettes
  foreach ($Recettes as $cocktail) {
    $nomCocktail = $cocktail['titre'];

    // Vérifier si le cocktail existe déjà dans la table 'Cocktail'
    $query = "SELECT COUNT(*) AS count FROM Cocktail WHERE nomCocktail = ?";
    $stmt_check = $mysqli->prepare($query);
    $stmt_check->bind_param("s", $nomCocktail);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        // Le cocktail n'existe pas, donc on peut l'insérer
        $stmt_insert_cocktail = $mysqli->prepare("INSERT INTO Cocktail(nomCocktail, preparationCocktail, ingredients) VALUES (?, ?, ?)");
        $stmt_insert_cocktail->bind_param("sss", $cocktail['titre'], $cocktail['preparation'], $cocktail['ingredients']);
        $stmt_insert_cocktail->execute();
        $stmt_insert_cocktail->close();
    }
}


    mysqli_close($mysqli);    



?>