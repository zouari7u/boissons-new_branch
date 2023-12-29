<div id="sidebar">
            <div class="inner">

                <!-- Search -->
                    <section id="search" class="alt">
                        <form method="post" action="#">
                            <input type="text" name="query" id="query" placeholder="Search" />
                        </form>
                    </section>

                <!-- Menu -->
                    <nav id="menu">
                        <header class="major">
                            <h2>Menu</h2>
                        </header>
                        <ul>
                            <li><a href="index.php">Page d'accueil</a></li>
                            <li>
                                <span class="opener"> Nos Recetes</span>
                                <ul>
                                    <li><a href="recetes.php">voir toutes les recetes</a></li>
                                    <?php
                                        include 'creationBdd.php';

                                        $sql = new PDO('mysql:host=127.0.0.1;dbname=Cocktails', 'root', '');
                                        $stmt = $sql->prepare("SELECT * FROM Aliment WHERE pereAliment = 'Aliment'");
                                        $stmt->execute();
                                        $menu = $stmt->fetchAll();

                                        foreach ($menu as $menuItem) {
                                            $stmt = $sql->prepare("SELECT * FROM Aliment WHERE pereAliment = ?");
                                            $stmt->execute([$menuItem['nomAliment']]);
                                            $subMenu = $stmt->fetchAll();
                                            if (!empty($subMenu)) {
                                                echo "<span class='opener'>" .$menuItem['nomAliment']."</span> <ul>";
                                                echo "<li><a href='recetes.php?aliment=" . $menuItem['nomAliment'] . "'>+ voir toutes les recetes</a></li>";
                                                foreach ($subMenu as $subMenuItem) {
                                                    $stmt = $sql->prepare("SELECT * FROM Aliment WHERE pereAliment = ?"); //Sous menu 1
                                                    $stmt->execute([$subMenuItem['nomAliment']]);
                                                    $sub2Menu = $stmt->fetchAll();
                                                    echo "<li><a href='recetes.php?aliment=" . $subMenuItem['nomAliment'] . "'>+ " . $subMenuItem['nomAliment'] . "</a></li>";
                                                    
                                                }

                                                echo "</ul> ";
                                            }else{
                                                echo "<li><a href='recetes.php?aliment=" . $menuItem['nomAliment'] . "'>" . $menuItem['nomAliment'] . "</a></li>";
                                            }   
                                                
                
                                        }
                                    ?>
                                </ul>
                            </li>
                            <li><a href="panier.php">Panier</a></li>
                            <li><a href="inscription.php">Inscription</a></li>

                            
                        </ul>
                    </nav>

                

                <!-- Footer -->
                    <footer id="footer">
                        <p class="copyright">&copy; Untitled. All rights reserved. Demo Images: <a href="https://unsplash.com">Unsplash</a>. Design: <a href="https://html5up.net">HTML5 UP</a>.</p>
                    </footer>

            </div>
        </div>