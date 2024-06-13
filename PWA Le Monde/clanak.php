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

if (isset($_GET['idClanak']) && !empty($_GET['idClanak'])) {
    $idClanak = $_GET['idClanak'];
} else {
    echo 'Error 404: Page not Found';
}

$sql = "SELECT naslov, summary, content, image, Date, author FROM clanak WHERE idClanak = "."$idClanak";

$result = mysqli_query($dbc, $sql);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $title = $row['naslov'];
        $summary = $row['summary'];
        $content = $row['content'];
        $image = $row['image'];
        $author = $row['author'];
        $published_at = $row['Date'];
    } else {
        echo "No article found with idClanak = $idClanak";
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($dbc);
}

mysqli_close($dbc);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - Le Monde</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php
        if ($_SESSION['logged']) {
            echo '<form action="" method="post">';
            echo '<label>' . htmlspecialchars($_SESSION['korisnicko_ime']) . '</label>';
            echo '<button type="submit" name="logout">Log-out</button>';
            echo '</form>';
        } else {
            echo '<div class="login-button">';
            echo '<a href="login.php">Login</a>';
            echo '<a href="register.php">Register</a>';
            echo '</div>';
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
                if ($_SESSION['logged']) {
                    echo '<li><a href="unos.php">Administracija</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>
    <main>
        <div class="background">
            <article class="clanak">
                <h2><?php echo $title; ?></h2>
                <p class="author">Author: <?php echo $author; ?></p>
                <p class="published">Published on: <?php echo $published_at; ?></p>
                <h3 class="summary"><?php echo $summary; ?></h3>
                <img src="uploads/<?php echo $image; ?>" alt="Article Image">
                <div class="content">
                    <?php echo $content; ?>
                </div>
                
            </article>
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

