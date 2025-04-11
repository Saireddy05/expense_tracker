<?php
// Database connection settings
$servername = "localhost"; // Adjust as needed
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "expense_tracker"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']); // Correcting field 'emaail' to 'email'
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Secure password hashing

    if (empty($name) || empty($email) || empty($_POST['password'])) {
        echo "Please fill in all fields.";
        exit;
    }

    // Check for existing email
    $emailCheckQuery = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $emailCheckQuery->bind_param("s", $email);
    $emailCheckQuery->execute();
    $emailCheckQuery->store_result();

    if ($emailCheckQuery->num_rows > 0) {
        echo "This email is already registered.";
        exit;
    }

    // Insert new user into database
    $query = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $query->bind_param("sss", $name, $email, $password);

    if ($query->execute()) {
        echo "Registration successful!";
        header("Location: login.html"); // Redirect to login page
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
