<?php
session_start();

// Kontrollera om användaren är inloggad som kund
if (!isset($_SESSION['customer_logged_in']) || $_SESSION['customer_logged_in'] !== true) {
    header("Location: login.php"); // Omdirigera till inloggningssidan om användaren inte är inloggad
    exit();
}

// Databasanslutning
require_once "dbconn.php";

// formulär för att skapa en ticket
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape användarinput för säkerhet
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);

    $user_id = $_SESSION['user_id'];

    // Lägger till ticket i databasen
    $sql = "INSERT INTO tickets (user_id, subject, description, priority) VALUES ('$user_id', '$subject', '$description', '$priority')";
    if (mysqli_query($conn, $sql)) {
        $success = "Ticket skapad framgångsrikt!";
    } else {
        $error = "Det uppstod ett fel. Försök igen senare.";
    }
}

// Hämtar användarens tickets från databasen
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tickets WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$tickets = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> kundsida</title>
    <link rel="stylesheet" href="kundsida.css">
</head>
<body>
<form action="start.php" method="get">
    <button type="submit" class="hem">⌂</button>
</form>
    <div class="container">
        <h1>Välkommen, <?php echo $_SESSION['username']; ?></h1> 
        <div class="sida">

            <div class="skapaticket"> <!--  skapa en ny ticket -->
                <h2>Skapa ny ticket</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="subject">Ämne:</label><br>
                    <input type="text" id="subject" name="subject" required><br>
                    <label for="description">Beskrivning:</label><br>
                    <textarea id="description" name="description" required></textarea><br>
                    <label for="priority">Prioritet:</label><br>
                    <select id="priority" name="priority">
                        <option value="low">Låg</option>
                        <option value="medium">Medium</option>
                        <option value="high">Hög</option>
                    </select><br><br>
                    <input type="submit" class="button" value="Skicka">
                </form>
            </div>

            <div class="tickets"> <!-- Visa befintliga tickets -->
                <h2> <?php echo $_SESSION['username']; ?>'s skapade tickets</h2>
                <?php foreach ($tickets as $ticket) : ?>
                    <div class="ticket">
                        <p><strong>Ämne:</strong><?php echo $ticket['subject']; ?></p>
                        <p><strong>Beskrivning:</strong><?php echo $ticket['description']; ?></p>
                        <p><strong>Prioritet:</strong> <?php echo ucfirst($ticket['priority']); ?></p>
                        <p><strong>Status:</strong> <?php echo ucfirst($ticket['status']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</body>
</html>
