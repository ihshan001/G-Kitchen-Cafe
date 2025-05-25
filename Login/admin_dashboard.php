<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Dashboard</title>
    
    <!--icon link-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <div class="wrapper">
        <h1>Admin Dashboard</h1>
        <div class="admin-panel">
            <button onclick="toggleForm()" class="btn btn-toggle">
                <i class="fas fa-user-edit"></i> Member Form
            </button>
            <button onclick="toggleTable()" class="btn btn-toggle">
                <i class="fas fa-users"></i> Member List
            </button>
            <button onclick="toggleReservationsTable()" class="btn btn-toggle">
                <i class="fas fa-calendar-alt"></i> Reservations
            </button>
            <button onclick="toggleComments()" class="btn btn-toggle">
                <i class="fas fa-comments"></i> Comments
            </button>
            <button onclick="logout()" class="btn btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button> <!-- Logout Button -->

            <div id="memberForm" style="display: none;">
                <form action="admin_dashboard.php" method="post" class="admin-form">
                    <h2>Add New Member</h2>
                    <input type="text" name="member_id" placeholder="ID" required>
                    <input type="text" name="member_name" placeholder="Member Name" required>
                    <input type="email" name="member_email" placeholder="Email" required>
                    <input type="password" name="member_pass" placeholder="Password" required>
                    <button type="submit" class="btn">Add Member</button>
                </form>
            </div>

            <!-- Members Table -->
            <section id="membersTable" style="display: none;">
                <h2>Members List</h2>
                <form id="deleteMembersForm" action="admin_dashboard.php" method="post">
                    <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "admin_dashboard";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Insert member details into the database
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['member_id'])) {
                        $member_id = $_POST['member_id'];
                        $member_name = $_POST['member_name'];
                        $member_email = $_POST['member_email'];
                        $member_pass = $_POST['member_pass'];

                        $sql = "INSERT INTO members (member_id, member_name, member_email, member_pass)
                                VALUES ('$member_id', '$member_name', '$member_email', '$member_pass')";

                        if ($conn->query($sql) === TRUE) {
                            echo "<p>New member added successfully</p>";
                        } else {
                            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
                        }
                    }

                    // Delete selected members from the database
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_members'])) {
                        $delete_ids = $_POST['delete_members'];
                        if (!empty($delete_ids)) {
                            $ids_to_delete = implode(',', array_map('intval', $delete_ids));
                            $sql = "DELETE FROM members WHERE member_id IN ($ids_to_delete)";
                            if ($conn->query($sql) === TRUE) {
                                echo "<p>Selected members deleted successfully</p>";
                            } else {
                                echo "<p>Error deleting members: " . $conn->error . "</p>";
                            }
                        }
                    }

                    // Fetch members from the database
                    $sql = "SELECT member_id, member_name, member_email, member_pass FROM members";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<table>
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td><input type='checkbox' name='delete_members[]' value='" . $row["member_id"] . "'></td>
                                    <td>" . $row["member_id"]. "</td>
                                    <td>" . $row["member_name"]. "</td>
                                    <td>" . $row["member_email"]. "</td>
                                    <td>" . $row["member_pass"]. "</td>
                                  </tr>";
                        }
                        echo "</tbody>
                              </table>";
                    } else {
                        echo "<p>No members found</p>";
                    }

                    $conn->close();
                    ?>
                    <button type="submit" class="btn btnDel" onclick="return confirm('Are you sure you want to delete selected members?')">Delete Member</button>
                </form>
            </section>

            <!-- Reservations Table -->
            <section id="reservationsTable" style="display: none;">
                <h2>Reservations List</h2>
                <?php
                // Database connection
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "cafe";

                $conn = new mysqli($servername, $username, $password, $dbname);

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch reservations from the database
                $sql = "SELECT full_name, email, number_of_persons, reservation_time, reservation_date, phone FROM reservations";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    echo "<table>
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Number of Persons</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                    <th>Phone</th>
                                </tr>
                            </thead>
                            <tbody>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["full_name"]. "</td>
                                <td>" . $row["email"]. "</td>
                                <td>" . $row["number_of_persons"]. "</td>
                                <td>" . $row["reservation_time"]. "</td>
                                <td>" . $row["reservation_date"]. "</td>
                                <td>" . $row["phone"]. "</td>
                              </tr>";
                    }
                    echo "</tbody>
                          </table>";
                } else {
                    echo "<p>No reservations found</p>";
                }

                $conn->close();
                ?>
            </section>

            <!-- Comments Section -->
            <section id="CommentTable" style="display: none;">
                <h2>Customer Reviews</h2>
                <form id="deleteCommentsForm" action="admin_dashboard.php" method="post">
                    <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "cafe"; // Replace with your actual database name

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Delete selected comments from the database
                    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_comments'])) {
                        $delete_ids = $_POST['delete_comments'];
                        if (!empty($delete_ids)) {
                            $ids_to_delete = implode(',', array_map('intval', $delete_ids));
                            $sql = "DELETE FROM comments WHERE id IN ($ids_to_delete)";
                            if ($conn->query($sql) === TRUE) {
                                echo "<p>Selected comments deleted successfully</p>";
                            } else {
                                echo "<p>Error deleting comments: " . $conn->error . "</p>";
                            }
                        }
                    }

                    // Fetch comments from the database
                    $sql = "SELECT id, name, email, rating, comment FROM comments";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<table>
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Rating</th>
                                        <th>Comment</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td><input type='checkbox' name='delete_comments[]' value='" . $row["id"] . "'></td>
                                    <td>" . $row["id"]. "</td>
                                    <td>" . $row["name"]. "</td>
                                    <td>" . $row["email"]. "</td>
                                    <td>" . $row["rating"]. "</td>
                                    <td>" . $row["comment"]. "</td>
                                  </tr>";
                        }
                        echo "</tbody>
                              </table>";
                    } else {
                        echo "<p>No comments found</p>";
                    }

                    $conn->close();
                    ?>
                    <button type="submit" class="btn btnDel" onclick="return confirm('Are you sure you want to delete selected comments?')">Delete Comment</button>
                </form>
            </section>
        </div>
    </div>

    <script>
        function toggleForm() {
            var form = document.getElementById('memberForm');
            form.style.display = form.style.display === 'none' ? 'block' : 'none';
        }

        function toggleTable() {
            var table = document.getElementById('membersTable');
            table.style.display = table.style.display === 'none' ? 'block' : 'none';
        }

        function toggleReservationsTable() {
            var table = document.getElementById('reservationsTable');
            table.style.display = table.style.display === 'none' ? 'block' : 'none';
        }

        function toggleComments() {
            var table = document.getElementById('CommentTable');
            table.style.display = table.style.display === 'none' ? 'block' : 'none';
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = '/OurWeb/index.html'; // Redirect to the main page
            }
        }

        // Function to reply to a review
        function replyToReview(button) {
            var reviewElement = button.closest('.review');
            var replyForm = reviewElement.querySelector('.reply-form');
            replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
        }

        // Function to delete a review
        function deleteReview(button) {
            var reviewElement = button.closest('.review');
            reviewElement.remove();
            console.log('Review deleted:', reviewElement);
        }

        document.querySelectorAll('.reply-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                replyToReview(this);
            });
        });

        document.querySelectorAll('.delete-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                deleteReview(this);
            });
        });
    </script>
</body>
</html>
