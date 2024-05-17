<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Databasanslutning
    require_once "dbconn.php";

    // Säkerhet
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hämta användarinfo från databasen
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Jämför det angivna lösenordet med det krypterade lösenordet i databasen
        if (password_verify($password, $row['password'])) {
            $_SESSION['customer_logged_in'] = true;
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username']; // Lägg till användarnamnet i sessionen
            header("Location: kundsida.php");
            exit();
        } else {
            $error = "Fel användarnamn eller lösenord!";
        }
    } else {
        $error = "Fel användarnamn eller lösenord!";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kundinloggning</title>
    <link rel="stylesheet" href="login.css"> <!-- Länk till CSS-filen för styling -->
</head>
<body>

<form action="start.php" method="get">
    <button type="submit" class="hem">⌂</button>
</form>

    <div class="container">
        <h2>Logga in som kund</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" class="inputfält" id="username" name="username" placeholder="Användarnamn" required><br>
            <input type="password" class="inputfält" id="password" name="password" placeholder="Lösenord" required><br><br>
            <input type="submit" class="button" value="Logga in">
        </form>
        <p>Har du inget konto? <a href="signup.php">Registrera dig här</a></p>
        <?php if (isset($error)) { echo '<div class="error">' . $error . '</div>'; } ?>
    </div>
</body>
</html>
