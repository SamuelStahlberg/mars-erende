<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // databas connection
    require_once "dbconn.php";

    // extra säkerhet
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // fråga för att hämta info från databasen
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND role = 'admin'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Fel användarnamn eller lösenord!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<form action="start.php" method="get">
    <button type="submit" class="hem">⌂</button>
</form>
    <div class="container">
        <h2>Logga in som admin</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" class="inputfält" id="username" name="username" placeholder="Användarnamn" required><br>
            <input type="password" class="inputfält" id="password" name="password" placeholder="Lösenord" required><br><br>
            <input type="submit" class="button" value="Logga in">
        </form>
        <?php if (isset($error)) { echo $error; } ?>
    </div>
</body>
</html>
