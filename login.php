<?php
// Start the session
session_start();

// Include database connection (update with your actual database connection script)
require 'db.php';

// Initialize variables for error messages
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $loginOrEmail = filter_input(INPUT_POST, 'loginOrEmail', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    if (!empty($loginOrEmail) && !empty($password)) {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, login, password FROM registeredUsers WHERE login = ? OR email = ?");

        // Check if preparation was successful
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ss", $loginOrEmail, $loginOrEmail);
        $stmt->execute();
        $stmt->store_result();

        // Check if the user exists
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $username, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $username;

                // Redirect to the home page
                header("Location: home.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with that username or email.";
        }

        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            transition: background-color 0.3s, color 0.3s;
        }
        .container {
            width: 400px;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            transition: background-color 0.3s, color 0.3s;
        }
        h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            margin: 10px 0;
        }
        .form-toggle {
            text-align: center;
            margin-top: 10px;
        }
        .night-mode {
            background-color: #2e2e2e;
            color: #f4f4f4;
        }
        .night-mode .container {
            background-color: #333;
            color: #f4f4f4;
        }
        .toggle-container {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
        .toggle-container label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .toggle-container input[type="checkbox"] {
            display: none;
        }
        .toggle-slider {
            width: 34px;
            height: 20px;
            background-color: #ccc;
            border-radius: 50px;
            position: relative;
            transition: background-color 0.3s;
        }
        .toggle-slider:before {
            content: "";
            width: 16px;
            height: 16px;
            background-color: white;
            border-radius: 50%;
            position: absolute;
            top: 2px;
            left: 2px;
            transition: transform 0.3s;
        }
        .toggle-container input[type="checkbox"]:checked + .toggle-slider {
            background-color: #007bff;
        }
        .toggle-container input[type="checkbox"]:checked + .toggle-slider:before {
            transform: translateX(14px);
        }
    </style>
</head>
<body>
    <!-- Night mode toggle -->
    <div class="toggle-container">
        <label>
            <input type="checkbox" id="nightModeToggle">
            <span class="toggle-slider"></span>
        </label>
    </div>

    <div class="container">
        <h2>Login</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="text" placeholder="Username or Email" name="loginOrEmail" required>
            </div>
            <div class="form-group">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="form-toggle">
            <a href="register.php">Don't have an account? Register</a>
        </div>
    </div>

    <script>
        // Load night mode preference from localStorage
        document.addEventListener('DOMContentLoaded', function () {
            const nightModeToggle = document.getElementById('nightModeToggle');
            const isNightMode = localStorage.getItem('nightMode') === 'true';

            // Apply night mode based on stored preference
            if (isNightMode) {
                document.body.classList.add('night-mode');
                nightModeToggle.checked = true;
            }

            // Toggle night mode
            nightModeToggle.addEventListener('change', function () {
                if (nightModeToggle.checked) {
                    document.body.classList.add('night-mode');
                    localStorage.setItem('nightMode', 'true');
                } else {
                    document.body.classList.remove('night-mode');
                    localStorage.setItem('nightMode', 'false');
                }
            });
        });
    </script>
</body>
</html>