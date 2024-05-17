<?php
session_start();

// Kolla om formuläret har skickats med POST-metoden
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Databasanslutning
    require_once "dbconn.php";

    // Escapar användarinput för säkerhet
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Kryptera lösenordet
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Lägg till användaren i databasen
    $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['user_registered'] = true; // Användaren har registrerats
        header("Location: login.php"); // Omdirigera till inloggningssidan
        exit();
    } else {
        $error = "Det uppstod ett fel vid registreringen.";
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrering</title>
    <link rel="stylesheet" href="signup.css"> 
</head>
<body>
    
<form action="start.php" method="get">
    <button type="submit" class="hem">⌂</button>
</form>

    <div class="container">
        <h2>Registrera dig</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" class="inputfält" name="username" placeholder="Användarnamn" required><br>
            <input type="email" class="inputfält" name="email" placeholder="E-post" required><br>
            <input type="password" class="inputfält" name="password" placeholder="Lösenord" required><br><br>
            <input type="submit" class="button" value="Registrera"> 
        </form>
        <!-- Visar eventuellt felmeddelande -->
        <?php if (isset($error)) { echo $error; } ?>
    </div>
</body>
</html>
