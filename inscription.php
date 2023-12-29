<!DOCTYPE html>
<html>
<head>
    <title>Boisson</title>  
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <style>

            body{

                  display:block;
                  align-items:center;
                  justify-content: center;
                  background-size: cover; 
                  background-position: center; 
                  background-repeat: no-repeat;
            }

            form{
                  border: none;
                  padding: 0;
               
            }

            #loginPage{

                  height: auto;
                  width: 60%;
                  margin: auto;
                  margin-top: 100px;
                  align-items:center;
                  border-radius: 10px;
                  
            }

            #loginPage legend {

                  text-align: center;
                  font-weight: bold;
                  font-size: 26px;
            }

            input:focus {
                  outline:  none;
            }

            #connexion {

                
                  align-items:center;
                  display:block;
                  margin:auto;

                  margin-bottom:10px;
            }

            label {

                  margin-left: 20px;
                  margin-bottom: 15px;
            }

            input {

                  margin-left: 20px;
                  margin-bottom: 20px;
                  margin-right: 20px;
            }

            .sexe-labels {    
                  
            }

            .sexe-labels label {
                  font-size: 16px;  
            }

             /* Style pour le champ de formulaire en focus */
            input:focus {
                  border: 1px solid black;
            }

            .labelConnexion {

                  display:flex;
                  margin:auto;
                  margin-top:30px;
            }

            p {

                  text-align:center;
                  margin-top:30px;
            }

      </style>
</head>
<?php
try {
    $pgDB = new PDO('mysql:host=127.0.0.1;dbname=Cocktails', 'root', '');
} catch (Exception $e) {
    exit($e->getMessage());
}

if (isset($_POST['connexion'])) {
    $id = $_POST['id'];
    $password = $_POST['password'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $sexe = isset($_POST['sexe']) ? $_POST['sexe'] : '';
    $numTel = isset($_POST['numTel']) ? $_POST['numTel'] : '';
    $birthdate = $_POST['birthDate'];
    $mail = $_POST['mail'];
    $adresse = $_POST['adresse'];
    $codePostal = $_POST['codePostal'];
    $ville = $_POST['ville'];

    // Vérification si l'utilisateur existe déjà
    $stmt = $pgDB->prepare("SELECT * FROM Utilisateur WHERE login = :login");
    $stmt->bindParam(':login', $id, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {


    } else {
        // Insérer un nouvel utilisateur
        $stmt = $pgDB->prepare("INSERT INTO Utilisateur (login, mdp, nom, prenom, sexe, numTel, dateNaissance, mail, adresse, codePostal, ville) 
                                VALUES (:login, :mdp, :nom, :prenom, :sexe, :numTel, :birthdate, :mail, :adresse, :codePostal, :ville)");

        $stmt->bindParam(':login', $id, PDO::PARAM_STR);
        $stmt->bindParam(':mdp', $password, PDO::PARAM_STR);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':sexe', $sexe, PDO::PARAM_STR);
        $stmt->bindParam(':numTel', $numTel, PDO::PARAM_STR);
        $stmt->bindParam(':birthdate', $birthdate, PDO::PARAM_STR);
        $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindParam(':codePostal', $codePostal, PDO::PARAM_STR);
        $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "<p>Inscription effectuée avec succès.</p>";
            header("Location: connexion.php"); // Redirection vers login.php après inscription
            exit;
        } else {
            echo "<p>Erreur lors de l'inscription</p>" . $stmt->errorInfo()[2];
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

                <section>
                <h2>Inscription</h2>

                
                    <form id="loginPage" method="post" action="#">
                        <fieldset>
                            <label for="id" class="label" id="labelId">Identifiant*</label>
                            <input type="text" id="id" name="id" required><br>
                            

                            <label for="password" class="label" id="labelPwd">Mot de passe*</label>
                            <input type="password" id="password" name="password" required><br>

                            <label for="nom" class="label" id="labelNom">Nom</label>
                            <input type="text" id="nom" name="nom">

                            <label for="prenom" class="label">Prénom</label>
                            <input type="text" id="prenom" name="prenom"><br>

                            <div class="sexe-labels">
                                <label for="sexe" class="label">Homme</label>
                                <input type="radio" id="sexe" name="sexe" value="h">

                                <label for="sexe" class="label">Femme</label>
                                <input type="radio" id="sexe" name="sexe" value="f">

                                <label for="sexe" class="label">Autre</label>
                                <input type="radio" id="sexe" name="sexe" value="a">
                            </div><br>

                            <label for="numTel" class="label">Numéro de téléphone :</label>
                            <input type="tel" id="numTel" name="numTel" pattern="[0-9]{10}" placeholder="Entrez votre numéro de téléphone"><br>

                            <label for="birthDate" class="label" id="labelBd">Date de naissance</label>
                            <input type="date" id="birthDate" name="birthDate"><br>

                            <label for="mail" class="label" id="labelMail">E-Mail</label>
                            <input type="email" id="mail" name="mail"><br>

                            <label for="adresse" class="label">Adresse</label>
                            <input type="text" id="adresse" name="adresse">

                            <label for="codePostal" class="label" id="labelCodePostal">Code Postal</label>
                            <input type="text" id="codePostal" name="codePostal">

                            <label for="ville" class="label" id="labelVille">Ville</label>
                            <input type="text" id="ville" name="ville"><br>

                            <input type="submit" id="connexion" name="inscription" class="button large" value="S'inscrire">
                        </fieldset>
                    </form>


                    <div class="labelConnexion">
                            <a href="connexion.php" class="labelConnexion">Vous avez déja un compte ? Cliquez ici pour vous connecter.</a>
                    </div>

                    
                </section>
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