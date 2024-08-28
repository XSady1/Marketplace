<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <style>
        /* Basic styles for the entire page */
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
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color 0.3s;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
        #errorModal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            border-radius: 8px;
        }
        #errorModal .close {
            float: right;
            cursor: pointer;
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }
        #errorModal p {
            margin: 0;
        }
        .form-toggle {
            text-align: center;
            margin-top: 10px;
        }

        /* Night mode styles */
        body.night-mode {
            background-color: #333;
            color: #ddd;
        }
        body.night-mode .container {
            background: #444;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        body.night-mode input[type="text"], body.night-mode input[type="password"], body.night-mode input[type="email"] {
            border-color: #666;
            background: #555;
            color: #ddd;
        }
        body.night-mode button {
            background-color: #0056b3;
        }
        body.night-mode button:hover {
            background-color: #004494;
        }
        body.night-mode #errorModal {
            background-color: #555;
            border-color: #666;
        }

        /* Toggle Switch Styles */
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
            margin-left: 10px;
            font-size: 14px;
            color: #333;
        }
        body.night-mode .toggle-label {
            color: #ddd;
        }
    </style>
    <script>
        function showForm(formId) {
            document.getElementById('loginForm').style.display = formId === 'loginForm' ? 'block' : 'none';
            document.getElementById('registerForm').style.display = formId === 'registerForm' ? 'block' : 'none';
        }

        function showError(message) {
            document.getElementById('errorMessage').innerText = message;
            document.getElementById('errorModal').style.display = 'block';
        }

        function closeError() {
            document.getElementById('errorModal').style.display = 'none';
        }

        function toggleNightMode() {
            document.body.classList.toggle('night-mode');
        }

        // Initialize with the login form visible
        window.onload = function() {
            showForm('loginForm');
            // Check for night mode in localStorage
            if (localStorage.getItem('nightMode') === 'enabled') {
                document.body.classList.add('night-mode');
                document.getElementById('nightModeSwitch').checked = true;
            }
        }

        // Save night mode preference to localStorage
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('nightModeSwitch').addEventListener('change', function() {
                toggleNightMode();
                if (document.body.classList.contains('night-mode')) {
                    localStorage.setItem('nightMode', 'enabled');
                } else {
                    localStorage.setItem('nightMode', 'disabled');
                }
            });
        });
    </script>
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
        <!-- Login Form -->
        <div id="loginForm">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <div class="form-group">
                    <input type="text" placeholder="Login or Email" name="loginOrEmail" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="form-toggle">
                <a href="#" onclick="showForm('registerForm'); return false;">Don't have an account? Register</a>
            </div>
        </div>

        <!-- Register Form -->
        <div id="registerForm" style="display: none;">
            <h2>Register</h2>
            <form action="register.php" method="post">
                <div class="form-group">
                    <input type="text" placeholder="Login" name="login" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                <div class="form-group">
                    <input type="password" placeholder="Repeat Password" name="repeatpassword" required>
                </div>
                <div class="form-group">
                    <input type="email" placeholder="Email" name="email" required>
                </div>
                <button type="submit">Register</button>
            </form>
            <div class="form-toggle">
                <a href="#" onclick="showForm('loginForm'); return false;">Already have an account? Login</a>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div id="errorModal">
        <span class="close" onclick="closeError()">×</span>
        <p id="errorMessage"></p>
    </div>
</body>
</html>
