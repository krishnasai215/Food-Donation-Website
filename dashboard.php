<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: loginsignup.html");
    exit();
}

// Database connection
$host = "localhost";
$user = "root";
$password = "";  // Your MySQL password
$dbname = "login_page";  // Your database name

$conn = mysqli_connect($host, $user, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch user donation history from the "dashboard" table
$user_id = $_SESSION['user_id'];
$sql = "SELECT amount, donation_date FROM dashboard WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch user name
$user_name = $_SESSION['user_name'];

// Close the statement and connection after use
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-black">
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 w-full flex justify-between items-center p-4 bg-gray-950 bg-opacity-50 z-20">
        <span class="text-white text-5xl font-bold p-5">Welcome, <?php echo $user_name; ?>!</span>
        <a href="loginsignup.html" class="text-white rounded-lg border border-white px-4 py-2 hover:bg-white hover:text-black transition-colors">Logout</a>
    </nav>

    <main class="container mx-auto mt-16 p-4">
        <h1 class="text-3xl font-bold mb-6">Your Donation History</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-2 px-4 text-left">Amount</th>
                        <th class="py-2 px-4 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="py-2 px-4"><?php echo "$" . $row['amount']; ?></td>
                            <td class="py-2 px-4"><?php echo date("F j, Y", strtotime($row['donation_date'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-lg text-white">You have not made any donations yet.</p>
        <?php endif; ?>
    </main>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
