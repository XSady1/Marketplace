<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session
session_start();

// Include database connection
require_once('db.php');
require_once('index.php');

// Initialize an array for error messages
$errors = [];

// Get form data
$login = isset($_POST['login']) ? trim($_POST['login']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$repeatpassword = isset($_POST['repeatpassword']) ? trim($_POST['repeatpassword']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

// Check if all fields are filled
if (empty($login) || empty($password) || empty($repeatpassword) || empty($email)) {
    $errors[] = 'All fields must be filled.';
}

// Check if passwords match
if ($password !== $repeatpassword) {
    $errors[] = 'Passwords do not match.';
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
}

// Check if login or email already exists
if (empty($errors)) {
    $sql = "SELECT id FROM registeredUsers WHERE login = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param("ss", $login, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = 'Login or email already exists.';
    }

    $stmt->close();
}

// If there are errors, return them to the front-end
if (!empty($errors)) {
    echo '<script type="text/javascript">',
         'showError("'.implode('\\n', $errors).'");',
         '</script>';
    exit();
}

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into the database
$sql = "INSERT INTO registeredUsers (login, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}
$stmt->bind_param("sss", $login, $hashedPassword, $email);

if ($stmt->execute()) {
    // Redirect or show a success message
    echo '<script type="text/javascript">',
         'alert("Registration successful!");',
         'window.location.href = "index.php";', // Redirect to the login page
         '</script>';
} else {
    // Database insertion failed
    echo '<script type="text/javascript">',
         'showError("Registration failed. Please try again.");',
         '</script>';
}

$stmt->close();
$conn->close();
?>
