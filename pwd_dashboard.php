<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="logo.png">
    <title>AnAbel - Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .grid-item:nth-child(1) { background-color: #ff9999; }
        .grid-item:nth-child(2) { background-color: #99ff99; }
        .grid-item:nth-child(3) { background-color: #ffcc99; }
        .grid-item:nth-child(4) { background-color: #99ffff; }
        .grid-item:nth-child(5) { background-color: #ff99ff; }
        .grid-item:nth-child(6) { background-color: #99ffcc; }
        .grid-item:nth-child(7) { background-color: #9999ff; }       
    </style>
    <script>
        function openChat() {
            document.getElementById("chatModal").style.display = "block";
        }

        function closeChat() {
            document.getElementById("chatModal").style.display = "none";
        }
    </script>
</head>
<body>
    <?php
        // Message handling PHP code
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mood'])) {
            $mood = $_POST['mood']; // Get the selected mood

            $conn = new mysqli("localhost","root", "", "anabel");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "INSERT INTO mood (mood) VALUES ('$mood')";
            if ($conn->query($sql) === TRUE) {
                // Mood inserted successfully
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();
        }
        
        
        $conn = new mysqli("localhost", "root", "", "anabel");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        $currentTime = date("H:i:s");
    
        // Fetch tasks that are upcoming later today
        $sql = "SELECT TaskName, StartTime, EndTime FROM DailyActivities WHERE StartTime > '$currentTime' ORDER BY StartTime";
        $result = $conn->query($sql);
    
        $upcomingTasks = [];
        if ($result !== false && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $upcomingTasks[] = $row;
            }
        }
        $conn->close();

    ?>
    <button class="chat-button" onclick="openChat()">
        <img src="chat-icon.png" alt="Chat" class="chat-icon">
    </button>
    <div id="chatModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeChat()">&times;</span>
            <iframe src="http://localhost:3000/patient" frameborder="0" style="width:100%; height:400px;"></iframe>
        </div>
    </div>
    <div class="container">
        <div class="header">
            <h2>Primary User Dashboard</h2>
        </div>
        <marquee direction="left" scrollamount="3" style="background-color: rgb(180, 75 , 75, 0.2); padding: 5px; border: 1px solid gray; font-family: 'Helvetica Neue', Arial, sans-serif; font-size: 12pt; font-weight: bold; border-radius: 4px; color: black; margin: 10px 0;">
            Drink and Log Water | Take Medication | Do Exercise | Games | Look back at some memories
        </marquee>
        <div class="interaction-area">
            <div class="message-input">
                <form method="post">
                    <!-- Replace the dropdown with buttons -->
                    <button type="submit" name="mood" value="sad">Sad</button>
                    <button type="submit" name="mood" value="happy">Happy</button>
                    <button type="submit" name="mood" value="neutral">Neutral</button>
                </form>
            </div>
            <div class="upcoming-events">
                <h3>Upcoming Events</h3>
                <div class="scroll-container">
                    <?php if (!empty($upcomingTasks)): ?>
                        <?php foreach ($upcomingTasks as $task): ?>
                            <p>
                                <strong><?php echo htmlspecialchars($task['TaskName']); ?></strong> - 
                                Today at 
                                <strong><?php echo htmlspecialchars($task['StartTime']); ?></strong> to 
                                <?php echo htmlspecialchars($task['EndTime']); ?>
                            </p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No upcoming events for today.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <nav>
            <div class="grid-container">
                <div class="grid-item"><a href="eating.php"><img src="eating-icon.png" alt="Eating"><span>Eating</span></a></div>
                <div class="grid-item"><a href="sleeping.html"><img src="sleeping-icon.png" alt="Sleeping"><span>Sleeping</span></a></div>
                <div class="grid-item"><a href="medication.html"><img src="medication-icon.png" alt="Medication"><span>Medication</span></a></div>
                <div class="grid-item"><a href="hydration.html"><img src="hydration-icon.png" alt="Hydration"></a><span>Hydration</span></div>
                <div class="grid-item"><a href="exercise.html"><img src="exercise-icon.png" alt="Physical Exercise"><span>Physical Exercise</span></a></div>
                <div class="grid-item"><a href="games.html"><img src="games.png" alt="Games"><span>Games</span></a></div>
                <div class="grid-item"><a href="memories.html"><img src="memories.png" alt="Memories"><span>Memories</span></a></div>
            </div>
        </nav>
        <div class="footer">
            <p>&copy; 2024 Anabel 2.0. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
