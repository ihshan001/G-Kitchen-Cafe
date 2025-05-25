<?php
$servername = "localhost";
$username = "root"; // Use your database username
$password = ""; // Use your database password
$dbname = "cafe";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $number_of_persons = $_POST['number_of_persons'];
    $reservation_time = $_POST['reservation_time'];
    $reservation_date = $_POST['reservation_date'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO reservations (full_name, email, number_of_persons, reservation_time, reservation_date, phone)
            VALUES ('$full_name', '$email', $number_of_persons, '$reservation_time', '$reservation_date', '$phone')";

    if ($conn->query($sql) === TRUE) {
        $message = "New reservation created successfully. A staff member will be connected shortly.";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation</title>
    <link rel="stylesheet" href="reservation.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
     <button id="backBtn"><i class="bx bx-arrow-back"></i></button>
    
    <div class="body">
        <div class="title">
            <p>Reservation</p>
        </div>
        <h1>Booking Form</h1><br>
        <p class="sub-title">
            Book your table now and have a great meal!
        </p>
        <?php
        if (!empty($message)) {
            echo "<p class='message'>$message</p>";
        }
        ?>
        <form method="post" action="">
            <div class="first">
                <div class="input">
                    <p>Your full name</p>
                    <input type="text" name="full_name" placeholder="Write your name here..." required>
                </div>
                <div class="input">
                    <p>Your email address?</p>
                    <input type="email" name="email" placeholder="Write your email here..." required>
                </div>     
            </div>
            <div class="mid">
                <div class="input">
                    <p>Number of persons</p>
                    <div class="input-i">
                        <input type="number" name="number_of_persons" placeholder="2 persons" required>
                        <i class='bx bxs-down-arrow'></i>
                    </div>
                </div>
                <div class="input">
                    <p>What time?</p>
                    <div class="input-i">
                        <input type="time" name="reservation_time" required>
                    </div>
                </div>
            </div>
            <div class="last">
                <div class="input">
                    <p>What is the date?</p>
                    <div class="input-i">
                        <input type="date" name="reservation_date" required>
                    </div>
                </div>
                <div class="input">
                    <p>Your phone number?</p>
                    <input type="tel" name="phone" placeholder="Write your number here..." required>
                </div>
            </div>
            <button class="btn" type="submit">BOOK NOW</button>
        </form>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const backButton = document.getElementById("backBtn");
            backButton.addEventListener("click", function() {
                window.location.href = "/OurWeb/index.html"; // Change URL as needed
            });
        });
    </script>
</body>   
</html>
