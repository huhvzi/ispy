<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alpha Waitlist Signup</title>
    <style>
        /* CSS styles remain the same */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"],
        input[type="email"],
        input[type="number"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .success-message {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .error-msg {
            background-color: #ff0000;
            color: #000000;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
      }
    </style>
</head>
<body>
    <div class="container">
        <h2>Join Alpha Waitlist</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Join Waitlist">
        </form>

        <?php
        // Function to get the client IP address
        function getClientIP() {
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
                $ipaddress = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if(getenv('HTTP_X_FORWARDED'))
                $ipaddress = getenv('HTTP_X_FORWARDED');
            else if(getenv('HTTP_FORWARDED_FOR'))
                $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if(getenv('HTTP_FORWARDED'))
                $ipaddress = getenv('HTTP_FORWARDED');
            else if(getenv('REMOTE_ADDR'))
                $ipaddress = getenv('REMOTE_ADDR');
            else
                $ipaddress = 'UNKNOWN';
            return $ipaddress;
        }

        // Check if form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve client IP address
            $ip = getClientIP();

            // Check if IP address has a recent submission
            $filename = "cooldowns.txt";
            $cooldowns = file_exists($filename) ? unserialize(file_get_contents($filename)) : [];

            if (isset($cooldowns[$ip]) && time() - $cooldowns[$ip] < 300) { // 300 seconds = 5 minutes
                echo "<p class='error-msg'>Cooldown.</p>";
            } else {
                // Retrieve form data
                $username = $_POST['username'];
                $email = $_POST['email'];
                $age = $_POST['age'];
                $password = $_POST['password'];

                // Open file for writing
                $file = fopen("wl.txt", "a");

                // Write data to file
                fwrite($file, "Username: " . $username . "\n");
                fwrite($file, "Email: " . $email . "\n");
                fwrite($file, "Age: " . $age . "\n");
                fwrite($file, "Password: " . $password . "\n");
                fwrite($file, "--------------------------\n");

                // Close the file
                fclose($file);

                // Update cooldown for the IP address
                $cooldowns[$ip] = time();
                file_put_contents($filename, serialize($cooldowns));

                // Display success message
                echo "<p class='success-message'>Successfully joined the waitlist! Please wait for an email from our staff!</p>";
            }
        }
        ?>
    </div>
</body>
</html>
