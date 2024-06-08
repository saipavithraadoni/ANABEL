<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "AnAbel");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get dates from the request, default to current week if not provided
$startDate = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d', strtotime('monday this week'));
$endDate = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d', strtotime('sunday this week'));

// Sanitize the inputs
$startDate = $conn->real_escape_string($startDate);
$endDate = $conn->real_escape_string($endDate);

// SQL query to select data within the given date range
$sql = "SELECT 
            DATE_ADD('$startDate', INTERVAL num DAY) AS Date, 
            DAYNAME(DATE_ADD('$startDate', INTERVAL num DAY)) AS Day,
            COALESCE(es.Breakfast_Status, 'Unavailable') AS Breakfast_Status,
            COALESCE(es.Lunch_Status, 'Unavailable') AS Lunch_Status,
            COALESCE(es.Dinner_Status, 'Unavailable') AS Dinner_Status,
            COALESCE(es.Snack_Status, 'Unavailable') AS Snack_Status
        FROM 
            (SELECT 0 AS num UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6) AS nums
        LEFT JOIN eating_schedule es ON DATE_ADD('$startDate', INTERVAL nums.num DAY) = es.Date
        WHERE DATE_ADD('$startDate', INTERVAL nums.num DAY) BETWEEN '$startDate' AND '$endDate'";


$result = $conn->query($sql);

$dataArray = array();

if ($result !== false && $result->num_rows > 0) {
    // Fetch data
    while($row = $result->fetch_assoc()) {
        $dataArray[] = $row;
    }
} else {
    echo "0 results";
}

$conn->close();

// Output the data as JSON
header('Content-Type: application/json');
echo json_encode($dataArray);
?>
