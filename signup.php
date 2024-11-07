<?php
// Collect form data
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Database credentials
$host = "localhost";
$user = "root";
$pwd = "";
$db = "login_page";

// Establish database connection
$connection = mysqli_connect($host, $user, $pwd, $db);

// Check if the connection was successful
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Check if the email already exists
$email_check_query = $connection->prepare("SELECT * FROM users WHERE email = ?");
$email_check_query->bind_param("s", $email);
$email_check_query->execute();
$result = $email_check_query->get_result();

if ($result->num_rows > 0) {
    // Email already exists
    echo "<script>alert('Email already exists. Please try a different email.'); window.location.href = 'signup.html';</script>";
} else {
    // Prepare and execute the SQL query using prepared statements to avoid SQL injection
    $query = $connection->prepare("INSERT INTO users (email, password, name) VALUES (?, ?, ?)");
    $query->bind_param("sss", $email, $password, $name);

    if ($query->execute()) {
        // Show alert on successful registration
        echo "<script>alert('Signup successful!'); window.location.href = 'loginsignup.html';</script>";
    } else {
        // Show an alert and stay on the signup page in case of failure
        echo "<script>alert('Failed to register. Please try again.'); window.location.href = 'signup.html';</script>";
    }
}

// Close the database connection
$email_check_query->close();
$query->close();
$connection->close();
?>
