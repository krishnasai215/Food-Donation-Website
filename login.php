<?php
// Start session
session_start();

// Database credentials
$host = "localhost";
$user = "root";
$password = "";  // Your MySQL password
$dbname = "login_page";  // Your database name

// Establish database connection
$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepared statement to avoid SQL injection
    $sql = "SELECT id, name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();
        $stored_password = $user['password']; // Plain-text password from DB

        // Compare passwords directly (not hashed)
        if ($password === $stored_password) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Password is incorrect
            echo "<script>alert('Invalid password. Please try again.'); window.location.href = 'loginsignup.html';</script>";
        }
    } else {
        // No user found with this email
        echo "<script>alert('No user found with this email. Please sign up first.'); window.location.href = 'signup.html';</script>";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>