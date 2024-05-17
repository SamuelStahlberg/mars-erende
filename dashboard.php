<?php
session_start();

// Kontrollera om användaren är inloggad som admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin.php"); // Omdirigera till admin inloggningssidan om användaren inte är inloggad
    exit();
}

// Databasanslutning
require_once "dbconn.php";

// Funktion för att stänga en ticket
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['closeticket'])) {
    $ticket_id = mysqli_real_escape_string($conn, $_POST['ticket_id']);

    // Uppdatera ticketens status till "closed" i databasen
    $sql_update = "UPDATE tickets SET status = 'closed' WHERE ticket_id = '$ticket_id'";
    if (mysqli_query($conn, $sql_update)) {
        // Flyttar ticketen från öppna tickets till arkiverade tickets
        header("Refresh:0");
    } else {
        $error = "Det uppstod ett fel. Försök igen senare.";
    }
}

// Hämtar öppna tickets från databasen
$sql_open = "SELECT * FROM tickets WHERE status = 'open'";
$result_open = mysqli_query($conn, $sql_open);
$open_tickets = mysqli_fetch_all($result_open, MYSQLI_ASSOC);

// Hämtar arkiverade tickets från databasen
$sql_archived = "SELECT * FROM tickets WHERE status = 'closed'";
$result_archived = mysqli_query($conn, $sql_archived);
$archived_tickets = mysqli_fetch_all($result_archived, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
<h1>Välkommen, Admin</h1>

<form action="start.php" method="get">
    <button type="submit" class="hem">⌂</button>
</form>

    <div class="container">

        <!-- Öppna Tickets -->
        <div class="otickets">
            <h2>Öppna Tickets</h2>
            <?php foreach ($open_tickets as $ticket) : ?>
                <div class="ticket">
                    <h3><?php echo $ticket['subject']; ?></h3>
                    <p><strong>Prioritet:</strong> <?php echo ucfirst($ticket['priority']); ?></p>
                    <!-- knapp för att visa beskrivning -->
                    <div class="knappar">
                        <button class="detalj" onclick="toggleTicket(<?php echo $ticket['ticket_id']; ?>)">Visa detaljer↓</button>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="ticket_id" value="<?php echo $ticket['ticket_id']; ?>">
                                <button type="submit" name="closeticket">Stäng Ticket</button>
                            </form>
                        </div>
                    <!-- Ticketens beskrivning visas när knappen trycks -->
                    <div id="ticket-details-<?php echo $ticket['ticket_id']; ?>" style="display: none;">
                        <p><strong>Beskrivning:</strong> <?php echo $ticket['description']; ?></p>
                        <p><strong>Skapad:</strong> <?php echo ucfirst($ticket['created_at']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Arkiverade Tickets -->
        
        <div class="atickets">
            <h2>Arkiverade Tickets</h2>
            <?php foreach ($archived_tickets as $ticket) : ?>
                <div class="ticket">
                    <h3><?php echo $ticket['subject']; ?></h3>
                    <p><strong>Prioritet:</strong> <?php echo ucfirst($ticket['priority']); ?></p>
                    <p><strong>Status:</strong> <?php echo ucfirst($ticket['status']); ?></p>
                    <p><strong>Beskrivning:</strong> <?php echo $ticket['description']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function toggleTicket(ticketId) {
            var ticketDetails = document.getElementById('ticket-details-' + ticketId);
            if (ticketDetails.style.display === 'none') {
                ticketDetails.style.display = 'block';
            } else {
                ticketDetails.style.display = 'none';
            }
        }
    </script>
</body>
</html>
