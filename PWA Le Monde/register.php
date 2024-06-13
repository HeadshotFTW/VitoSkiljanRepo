<?php
session_start();

$dbc = mysqli_connect("localhost", "root", "", "lemonde");

if (!$dbc) {
    die("Error: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['korisnicko_ime'];
    $lozinka = $_POST['lozinka'];
    $admin = isset($_POST['admin']) ? 1 : 0;

    $hashed_password = password_hash($lozinka, PASSWORD_BCRYPT);

    $query = "INSERT INTO Korisnik (korisnicko_ime, lozinka, admin) VALUES ('$username', '$hashed_password', '$admin')";

    if (mysqli_query($dbc, $query)) {
        $_SESSION['username'] = $username;
        $_SESSION['admin'] = $admin;
        mysqli_close($dbc);
        
        header("Location: index.php");
        exit();
    } else {
        echo "Greška: " . mysqli_error($dbc);
    }
    mysqli_close($dbc);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unos</title>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    <script src="login-val.js"></script>
</head>
<body>
    <header>
        <div class="login-button">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
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
            <h2>Register</h2>
            <form action="" method="post" name="register">
                <label for="korisnicko_ime">Korisničko ime:</label>
                <input type="text" id="korisnicko_ime" name="korisnicko_ime" required>
                <br><br>
                <label for="lozinka">Lozinka:</label>
                <input type="password" id="lozinka" name="lozinka" required>
                <br><br>
                <label for="lozinka2">Lozinka:</label>
                <input type="password" id="lozinka2" name="lozinka2" required>
                <br><br>
                <label for="admin">Admin:</label>
                <input type="checkbox" id="admin" name="admin">
                <br><br>
                <button type="submit">Registriraj se</button>
            </form>
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
