<?php
session_start();
include('assets/inc/config.php');
if (isset($_POST['reset_pwd'])) {
    // Capture user email
    $email = $_POST['email'];
    
    // Generate a secure token and a temporary password
    $length_pwd = 10;
    $length_token = 30;
    $temp_pwd = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, $length_pwd);
    $_token = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, $length_token);
    
    // Hash the temporary password for security
    $hashed_pwd = password_hash($temp_pwd, PASSWORD_DEFAULT);
    
    // Set status to "Pending"
    $status = "Pending";
    
    // Prepare SQL query
    $query = "INSERT INTO his_pwdresets (email, token, status, pwd) VALUES(?, ?, ?, ?)";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('ssss', $email, $_token, $status, $hashed_pwd);
    
    if ($stmt->execute()) {
        // Send password reset email
        $subject = "Password Reset Instructions";
        $message = "Hello,\n\nYou requested a password reset. Use the temporary password below to log in and reset your password.\n\nTemporary Password: $temp_pwd\nReset Link: http://yourwebsite.com/reset_password.php?token=$_token\n\nIf you did not request this, please ignore this email.";
        $headers = "From: noreply@yourwebsite.com"; // Update with your email domain
        
        if (mail($email, $subject, $message, $headers)) {
            $success = "Check your inbox for password reset instructions.";
        } else {
            $err = "Failed to send reset email. Try again later.";
        }
    } else {
        $err = "Database error: Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Drug Verification and Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/icons.min.css">
    <link rel="stylesheet" href="assets/css/app.min.css">
    <style>
        body {
            background: url('assets/images/bg-pattern.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            animation: fadeIn 1s ease-in-out;
            transition: transform 0.3s ease-in-out;
        }
        .card:hover {
            transform: scale(1.02);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-primary {
            transition: background 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background: #553c9a;
        }
        input:focus {
            border-color: #553c9a !important;
            box-shadow: 0 0 5px rgba(85, 60, 154, 0.5) !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4">
                    <div class="card-body">
                        <h4 class="text-center">Reset Your Password</h4>
                        <p class="text-muted text-center">Enter your email to receive reset instructions.</p>
                        <form method="post">
                            <div class="form-group">
                                <label>Email address</label>
                                <input class="form-control" name="email" type="email" required placeholder="Enter your email">
                            </div>
                            <button name="reset_pwd" class="btn btn-primary btn-block" type="submit"> Reset Password </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
