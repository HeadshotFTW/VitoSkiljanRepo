<?php
session_start();

if (!isset($_SESSION['logged'])) {
    $_SESSION['logged'] = false;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$dbc = mysqli_connect("localhost", "root", "", "lemonde");

if (!$dbc) {
    die("Error: " . mysqli_connect_error());
}

$categories = ['politique', 'sport', 'administracija']; 

$articles = [];

foreach ($categories as $category) {
    $sql = "SELECT * FROM clanak WHERE category = '$category' ORDER BY Date DESC";
    $result = mysqli_query($dbc, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $articles[$category][] = $row;
        }
    }
}

mysqli_close($dbc);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Monde</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php

        if ($_SESSION['logged'] == True) {
            echo '
                <form action="" method="post" >
                <label">'.$_SESSION['korisnicko_ime'].'</label>
                <button type="submit" name="logout">Log-out</button>
                </form><br><br>
                ';
        } else {
            echo '<div class="login-button">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            </div>';
        }

        ?>
        <h1>Le Monde</h1>
        
        <div class="line"></div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#politique">Politique</a></li>
                <li><a href="index.php#sport">Sport</a></li>
                <?php
                if ($_SESSION['logged'] == True) {
            echo '
                <li><a href="unos.php">Administracija</a></li>
                '; 
            }
            ?>
            </ul>

            
        </nav>
    </header>

    <main>
        <div class="background">
        <?php
            foreach ($categories as $category) {
                if (isset($articles[$category]) && !empty($articles[$category])) {
                    echo '
                        <section class="category" id="'.$category.'">
                            <h2>' . ucfirst($category) . '</h2>
                            <div class="articles">';
                    
                    foreach ($articles[$category] as $article) {
                        echo '
                            <article>
                                <img src="uploads/' . $article['image'] . '" alt="' . $article['naslov'] . ' Image">
                                <h3><a href=clanak.php?idClanak="'. $article['idClanak'] .'">' . $article['naslov'] . '</a></h3>
                                <p>' . $article['summary'] . '</p>
                        
                            </article>';
                    }

                    echo '
                            </div>
                        </section>';
                }
            }
            ?>
        </div>
            
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; 2024 Le Monde. All rights reserved.</p>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#politique">Politique</a></li>
                    <li><a href="index.php#sport">Sport</a></li>
                    <?php
                    if ($_SESSION['logged'] == True) {
                echo '
                    <li><a href="unos.php">Administracija</a></li>
                    '; 
                }
                ?>
                </ul>
            </nav>
        </div>
    </footer>
</body>
</html>
