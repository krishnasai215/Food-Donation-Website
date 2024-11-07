<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection (change credentials as per your setup)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login_page"; // Your database name

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $amount = $_POST['amount'];

    // Validate form inputs (you can add more validation)
    if (!empty($name) && !empty($email) && !empty($amount)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO donations (name, email, amount) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $email, $amount); 

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Thank you for your donation!'); window.location.href = 'donate.html';</script>";
        } else {
            echo "<script>alert('Donation failed. Please try again.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }

    // Close connection
    $conn->close();
}
?>
