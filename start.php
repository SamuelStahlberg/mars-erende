<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adminlogin'])) {
        // Redirect till admin login page
        header("Location: admin.php");
        exit();
    } elseif (isset($_POST['kundlogin'])) {
        // Redirect to kund login page
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Start Page</title>
    <link rel="stylesheet" href="start.css">
</head>

<body>
    <div class="container">
        <h1>Välkommen</h1>
        <div class="knappar">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="submit" class="button admin" name="adminlogin" value="Logga in som admin">
                <input type="submit" class="button kund" name="kundlogin" value="Logga in som kund">
            </form>
        </div>
    </div>
</body>
</html>
