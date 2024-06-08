<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $userType = $_POST["userType"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Perform user authentication (replace with your authentication logic)
    if ($userType === "patient" && $username === "123" && $password === "123") {
        // Redirect to the patient dashboard
        header("Location: http://localhost/AnAbel/pwd_dashboard.php");
        exit();
    } elseif ($userType === "carer" && $username === "789" && $password === "789") {
        // Redirect to the carer dashboard
        header("Location: http://localhost/AnAbel/carer_dashboard.php");
        exit();
    } else {
        // Invalid login, display an error message or redirect to an error page
        echo "Invalid login credentials. Please try again.";
    }
}
?>
