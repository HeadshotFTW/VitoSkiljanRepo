<?php
session_start();

$dbc = mysqli_connect("localhost", "root", "", "lemonde");

if (!$dbc) {
    die("Error: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete_id'])) {
        $delete_id = $_POST['delete_id'];
        $delete_query = "DELETE FROM clanak WHERE idClanak = ?";
        $stmt = mysqli_prepare($dbc, $delete_query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $delete_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            echo "<script>alert('Članak uspješno obrisan!'); window.location = 'unos.php';</script>";
            exit();
        } else {
            echo "Error: " . mysqli_error($dbc);
        }
    } else {
        $naslov = $_POST['title'];
        $summary = $_POST['summary'];
        $content = $_POST['content'];
        $category = $_POST['category'];
        $display = isset($_POST['display']) ? 1 : 0;
        $author = $_SESSION['korisnicko_ime'];
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $image_name = $_FILES['image']['name'];
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
            $unique_image_name = uniqid() . '.' . $image_extension;
            $target_dir = "uploads/";
            $target_file = $target_dir . $unique_image_name;

            if (move_uploaded_file($image_tmp_name, $target_file)) {
            } else {
                echo "Error kod uploada. ";
                exit;
            }
        } else {
            echo "Nema slike";
            exit;
        }

        $sql = "INSERT INTO clanak (naslov, summary, content, category, image, display, author) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($dbc, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssis", $naslov, $summary, $content, $category, $unique_image_name, $display, $author);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            
            echo "<script>alert('Članak uspješno dodan!'); window.location = 'index.php';</script>";
            exit();
        } else {
            echo "Error statement: " . mysqli_error($dbc);
        }
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
        <h1>Le Monde</h1>
        <div class="line"></div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#politique">Politique</a></li>
                <li><a href="index.php#sport">Sport</a></li>
                <?php
                if ($_SESSION['logged'] == True) {
                    echo '<li><a href="unos.php">Administracija</a></li>'; 
                }
                ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="background">
            <h2>Unos nove vijesti ili proizvoda</h2>
            <div class="content-wrapper">
                <form action="" method="POST" enctype="multipart/form-data">
                    <label for="title">Naslov vijesti:</label><br>
                    <input type="text" id="title" name="title" required>
                    <br><br>
                    <label for="summary">Kratki sažetak:</label><br>
                    <textarea id="summary" name="summary" rows="4" required></textarea>
                    <br><br>
                    <label for="content">Tekst vijesti:</label><br>
                    <textarea id="content" name="content" rows="10" required></textarea>
                    <br><br>
                    <label for="category">Kategorija:</label><br>
                    <select id="category" name="category" required>
                        <option value="politique">Politique</option>
                        <option value="sport">Sport</option>
                        <option value="administracija">Administracija</option>
                    </select>
                    <br><br>
                    <label for="image">Odaberite sliku:</label><br>
                    <input type="file" id="image" name="image" accept="image/*" required>
                    <br><br>
                    <label for="display">Prikazati na stranici:</label><br>
                    <input type="checkbox" id="display" name="display">
                    <br><br>
                    <button type="submit">Pošalji</button>
                </form>
                <aside>
                    <?php
                    $dbc = mysqli_connect("localhost", "root", "", "lemonde");

                    if (!$dbc) {
                        die("Error: " . mysqli_connect_error());
                    }

                    $query = "SELECT idClanak, naslov, category, date, author FROM clanak";

                    $result = mysqli_query($dbc, $query);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div>";
                            echo "Naslov: " . $row['naslov'] . "<br>";
                            echo "Kategorija: " . $row['category'] . "<br>";
                            echo "Datum: " . $row['date'] . "<br>";
                            echo "Autor: " . $row['author'] . "<br>";
                            echo "<form action='' method='POST' style='display:inline;'>";
                            echo "<input type='hidden' name='delete_id' value='" . $row['idClanak'] . "'>";
                            echo "<button type='submit'>Obriši</button>";
                            echo "</form>";
                            echo "<hr>";
                            echo "</div>";
                        }
                    } else {
                        echo "Error: " . mysqli_error($dbc);
                    }
                    ?>
                </aside>
            </div>
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
