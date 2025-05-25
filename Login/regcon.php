<?php 
$uname = $_POST['uname'];
$email = $_POST['email'];
$pass = $_POST['pass']; // Corrected from '$_POST['$pass']'

// db connection
$conn = new mysqli('localhost', 'root', '', 'cafe');
if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
} else {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $uname, $email, $pass);
    $stmt->execute();
    echo "Registered";
    $stmt->close();
    $conn->close();
}
?>
