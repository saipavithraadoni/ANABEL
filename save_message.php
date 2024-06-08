<?php
// save_message.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "anabel";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Save message
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["message"])) {
    $stmt = $conn->prepare("INSERT INTO messages (message) VALUES (?)");
    $stmt->bind_param("s", $_POST["message"]);
    $stmt->execute();

    echo "Message sent successfully!";
    $stmt->close();
} else {
    echo "No message received.";
}

$conn->close();
?>
