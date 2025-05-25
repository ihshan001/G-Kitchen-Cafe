<?php 
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $uname = $_POST['uname'];
    $pass = $_POST['pass'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'cafe');
    if ($conn->connect_error) {
        die('Connection Failed : ' . $conn->connect_error);
    }

    // Prepare and execute SQL query to check username and password
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $uname, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a row is returned
    if ($result->num_rows > 0) {
        // Redirect to admin/admin.php
        echo("Login Successful");
        exit(); // Ensure no further execution in case of redirect
    } else {
        echo "Incorrect username or password";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
