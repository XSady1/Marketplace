<?php
// Start the session at the very top of the file
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Example user data from session
$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 20px;
        }
        h1 {
            color: #333;
        }
        p {
            color: #666;
            font-size: 16px;
        }
        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
        }
        .logout:hover {
            background-color: #c82333;
        }
        .toggle-switch {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
        }
        .toggle-switch label {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .toggle-switch input[type="checkbox"] {
            display: none;
        }
        .toggle-slider {
            position: relative;
            width: 50px;
            height: 24px;
            background-color: #ccc;
            border-radius: 50px;
            transition: background-color 0.3s;
            margin-right: 10px;
        }
        .toggle-slider:before {
            content: "";
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            transition: transform 0.3s;
        }
        input[type="checkbox"]:checked + .toggle-slider {
            background-color: #007bff;
        }
        input[type="checkbox"]:checked + .toggle-slider:before {
            transform: translateX(26px);
        }
        .toggle-label {
            font-size: 14px;
            color: #333;
        }
        .night-mode .toggle-label {
            color: #ddd;
        }
        .night-mode {
            background-color: #333;
            color: #ddd;
        }
        .night-mode .container {
            background: #444;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .night-mode input[type="text"], .night-mode input[type="password"], .night-mode input[type="email"] {
            border-color: #666;
            background: #555;
            color: #ddd;
        }
        .night-mode button {
            background-color: #0056b3;
        }
        .night-mode button:hover {
            background-color: #004494;
        }
        .night-mode .logout {
            background-color: #c82333;
        }
        .night-mode .logout:hover {
            background-color: #bd2130;
        }
    </style>
</head>
<body>
    <div class="toggle-switch">
        <label>
            <input type="checkbox" id="nightModeSwitch">
            <span class="toggle-slider"></span>
            <span class="toggle-label">Night Mode</span>
        </label>
    </div>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($user_name); ?>!</h1>
        <p>This is your home page. You are successfully logged in.</p>
        <a href="logout.php" class="logout">Log Out</a>
    </div>

    <script>
        function toggleNightMode() {
            document.body.classList.toggle('night-mode');
            if (document.body.classList.contains('night-mode')) {
                localStorage.setItem('nightMode', 'enabled');
            } else {
                localStorage.setItem('nightMode', 'disabled');
            }
        }

        // Initialize night mode based on localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const nightModeSwitch = document.getElementById('nightModeSwitch');
            if (localStorage.getItem('nightMode') === 'enabled') {
                document.body.classList.add('night-mode');
                nightModeSwitch.checked = true;
            }
            nightModeSwitch.addEventListener('change', toggleNightMode);
        });
    </script>
</body>
</html>
