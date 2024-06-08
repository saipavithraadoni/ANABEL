<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>AnAbel - Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .grid-item:nth-child(1) { background-color: #99cc99; }
        .grid-item:nth-child(2) { background-color: #ff9999; }
        .grid-item:nth-child(3) { background-color: #99ff99; }
        .grid-item:nth-child(4) { background-color: #9999ff; }
        .grid-item:nth-child(5) { background-color: #ffff99; }
        .grid-item:nth-child(6) { background-color: #ff99ff; }
        .grid-item:nth-child(7) { background-color: #99ffff; }
        .grid-item:nth-child(8) { background-color: #ffcc99; }
        .grid-item:nth-child(9) { background-color: #99ffcc; }
        .timestamp {
            color: #888;
            font-size: 0.8em;
            margin-left: 10px;
        }
        .content-area {
            display: flex;
            justify-content: space-between;
            
        }
        .messages-section, .alerts-section {
            width: 45%;
            border: 1px solid gray;
            background-color: rgba(229, 210, 225);
            border-radius: 5px;
            padding:10px;
        }
    </style>
    <script>
    function openChat() {
        window.open('http://localhost:3000/carer', '_blank');
    }
    </script>
    <button class="chat-button" onclick="openChat()">
            <img src="chat-icon.png" alt="Chat" class="chat-icon">
    </button>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Carer Dashboard</h2>
        </div>
        <div class="content-area">
            <div class="messages-section">
                <h2>Patient Messege</h2>
                <div id="mood-history">
                    <?php
                    $conn = new mysqli("localhost", "root", "", "anabel");
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $result = $conn->query("SELECT mood, timestamp FROM mood ORDER BY timestamp DESC");
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<p>" . htmlspecialchars($row["mood"]) . " <span class='timestamp'>at " . $row["timestamp"] . "</span></p>";
                        }
                    } else {
                        echo "No mood data available.";
                    }
                    $conn->close();
                    ?>
                </div>
            </div>

            <div class="alerts-section">
                <h2>Notifications:</h2>
                <div id="alerts">
                <?php
        $conn = new mysqli("localhost", "root", "", "anabel");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $query = "SELECT Date, Day, Breakfast_Status, Lunch_Status, Dinner_Status, Snack_Status FROM eating_schedule WHERE Breakfast_Status = -1 OR Lunch_Status = -1 OR Dinner_Status = -1 OR Snack_Status = -1";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if ($row["Breakfast_Status"] == -1) {
                    echo "<p style='color: red;'>Missed Breakfast on " ." <span class='timestamp'>at ". $row["Day"] . ", " . $row["Date"] . "</p>";
                }
                if ($row["Lunch_Status"] == -1) {
                    echo "<p style='color: red;'>Missed Lunch on " ." <span class='timestamp'>at ". $row["Day"] . ", " . $row["Date"] . "</p>";
                }
                if ($row["Dinner_Status"] == -1) {
                    echo "<p style='color: red;'>Missed Dinner on " ." <span class='timestamp'>at ". $row["Day"] . ", " . $row["Date"] . "</p>";
                }
                if ($row["Snack_Status"] == -1) {
                    echo "<p style='color: red;'>Missed Snack on " ." <span class='timestamp'>at ". $row["Day"] . ", " . $row["Date"] . "</p>";
                }
            }
        } else {
            echo "No missed meals.";
        }
        $conn->close();
        ?>
                </div>
            </div>
        </div>

        <nav>
        <div class="grid-container">
                <div class="grid-item"><a href="activities.php"><img src="logo.png" alt="Activities Monitoring"><span>Activities Monitoring</span></a></div>
                <div class="grid-item"><a href="eating.php"><img src="eating-icon.png" alt="Eating"><span>Eating</span></a></div>
                <div class="grid-item"><a href="sleeping.html"><img src="sleeping-icon.png" alt="Sleeping"><span>Sleeping</span></a></div>
                <div class="grid-item"><a href="elopement.html"><img src="elopement-icon.png" alt="Elopement"><span>Elopement</span></a></div>
                <div class="grid-item"><a href="wandering.html"><img src="wandering-icon.png" alt="Wandering"><span>Wandering</span></a></div>
                
            </div>
        </nav>

        <div class="footer">
            <p>&copy; 2024 Anabel 2.0. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

