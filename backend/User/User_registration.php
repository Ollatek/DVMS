<?php
session_start();
include ('assets/inc/config.php'); // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Check if email already exists
    $check_query = "SELECT * FROM users WHERE ad_email = ?";
    $stmt = $mysqli->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already registered. Try another!');</script>";
    } else {
        // Insert new user
        $query = "INSERT INTO users (ad_fname, ad_lname, ad_email, ad_pwd) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssss", $fname, $lname, $email, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful. You can now log in.'); window.location='user_login.php';</script>";
        } else {
            echo "<script>alert('Error in registration. Try again.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../assets/css/style.css"> <!-- Ensure this path is correct -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- FontAwesome for icons -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('assets/images/bg-pattern.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            display: flex;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 800px;
            width: 100%;
        }
        .form-box {
            flex: 1;
            padding: 20px;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .checkbox-group {
            display: inline-block;
            align-items: center;
            margin-bottom: 15px;
			margin-top: 10px;
        }
        .checkbox-group input {
            margin-right: 10px;
        }
        .illustration {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .illustration img {
            max-width: 100%;
			height: 105%;
			margin-left: 25px;
			border-radius:5px;
        }
		.checkbox-group a {
			text-decoration: none;
		}
    </style>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h2>Sign Up</h2>
            <form action="user_registration.php" method="POST">
                <div class="input-group">
                    <label for="first_name"><i class="fas fa-user"></i> First Name</label>
                    <input type="text" id="fname" name="first_name" required>
                </div>
                <div class="input-group">
                    <label for="last_name"><i class="fas fa-user"></i> Last Name</label>
                    <input type="text" id="lname" name="last_name" required>
                </div>
                <div class="input-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to all statements in <a href="#">Terms of service</a></label><br>
					<label for ="signup"> Do you have an account? <a href ="user_login.php">Sign in</a></label>
                </div>
                <button type="submit" class="btn">Register</button>
            </form>
        </div>
        <div class="illustration">
            <img src="assets/images/dg_bg.jpg" alt="Sign Up Illustration">
        </div>
    </div>
</body>
</html>


