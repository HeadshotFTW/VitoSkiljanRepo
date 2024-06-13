<?php
session_start();

$dbc = mysqli_connect("localhost", "root", "", "lemonde");

if (!$dbc) {
    die("Error: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $korisnicko_ime = $_POST['korisnicko_ime'];
    $lozinka = $_POST['lozinka'];

    $sql = "SELECT * FROM korisnik WHERE korisnicko_ime = ?";
    $stmt = mysqli_prepare($dbc, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $korisnicko_ime);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user) {
            if (password_verify($lozinka, $user['lozinka'])) {
                $_SESSION['korisnicko_ime'] = $user['korisnicko_ime'];
                echo "Prijava je uspjela";
                $_SESSION['logged'] = True;
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['logged'] = False;
                echo "Pogrešno korisničko ime ili lozinka.";
            }
        } else {
            $_SESSION['logged'] = False;
            echo "Pogrešno korisničko ime ili lozinka.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error statementa: " . mysqli_error($dbc);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unos</title>
    <link rel="stylesheet" href="style.css">
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
                if (isset($_SESSION['logged']) && $_SESSION['logged'] == True) {
                    echo '<li><a href="unos.php">Administracija</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="background">
            <h2>Login</h2>
            <form method="post" name="login">
                <label for="korisnicko_ime">Korisničko ime:</label>
                <input type="text" id="korisnicko_ime" name="korisnicko_ime" required>
                <br><br>
                <label for="lozinka">Lozinka:</label>
                <input type="password" id="lozinka" name="lozinka" required>
                <br><br>
                <button type="submit">Ulogiraj se!</button>
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
