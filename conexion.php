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

        // Vérification si l'utilisateur existe déjà
        $stmt = $pgDB->prepare("SELECT * FROM Utilisateur WHERE login = :login");
        $stmt->bindParam(':login', $id, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {

            if($password == $user['mdp']){

                //echo "<p>Bienvenue, $id.</p>";

                session_start();

                //echo "<a href='infosUser.php'>Voir mes informations</a>";
                $_SESSION['loggedin'] = true;
                $_SESSION["id"] = $id;
                header("Location: infosUser.php");
                exit();

            } else {

                echo "<p>Identifiant ou mot de passe incorrect</p>";
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
                <h2>Connexion</h2>

                
                    <form id="loginPage" method="post" action="#">
                        <fieldset>
                            <label for="id" id="labelId">Identifiant</label>
                            <input type="text" id="id" name="id" required> <br>

                            <label for="password" id="labelPwd">Mot de passe</label>
                            <input type="password" id="password" name="password" required> <br>

                            <input type="submit" id="connexion" name="connexion" value="Se connecter">
                        </fieldset>
                    </form>


                    <div class="labelConnexion">
                            <a href="connexion.php" class="labelConnexion">Vous avez déja un compte ? Cliquez ici pour vous connecter.</a>
                    </div>
                    <?php 
      
                        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){

                            echo "<a href='infosUser.php'>Voir mes informations</a>";
                        }

                        ?>
                    
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