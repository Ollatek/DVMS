<?php
session_start();
include('assets/inc/config.php');
require 'assets/vendor/autoload.php'; // Include Composer's autoloader for PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['send_otp'])) {
    $email = $_POST['email'];
    if (empty($email)) {
        $err = "Please enter your email address.";
    } else {
        $otp = rand(100000, 999999); // Generate a random 6-digit OTP

        // Save OTP and email in session to validate later
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $email;

        // Send OTP to the user's email using PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Server settings for Mercury Mail Server
            $mail->isSMTP(); // Set PHPMailer to use SMTP
            $mail->Host = 'localhost'; // Mercury Mail Server is likely running on the same machine
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'your_email@yourdomain.com'; // Your email address on Mercury server
            $mail->Password = 'your_email_password'; // Your Mercury email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use STARTTLS encryption
            $mail->Port = 25; // Use port 587 for TLS encryption

            // Recipients
            $mail->setFrom('no-reply@hospital.com', 'Hospital Management System');
            $mail->addAddress($email); // Recipient's email address

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Password Reset OTP';
            $mail->Body = "<p>Your OTP code is: <strong>$otp</strong></p>";

            $mail->send();
            $success = "OTP sent to your email. Please check your inbox.";
        } catch (Exception $e) {
            $err = "Failed to send OTP. Mailer Error: " . $mail->ErrorInfo;
            // Clear OTP session on failure
            session_unset();
        }
    }
}

if (isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];

    if ($_SESSION['otp'] == $entered_otp && $_SESSION['otp_email'] == $_POST['email']) {
        $_SESSION['otp_verified'] = true;
        $success = "OTP verified. You can now reset your password.";
    } else {
        $err = "Invalid OTP. Please try again.";
        // Clear OTP session on error
        session_unset();
    }
}

if (isset($_POST['reset_pwd'])) {
    if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified']) {
        $email = $_POST['email'];
        $new_password = sha1(md5($_POST['new_password'])); // Encrypt the new password

        // Update the password in the database
        $query = "UPDATE his_pwdresets SET pwd = ?, status = 'Reset' WHERE email = ?";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('ss', $new_password, $email);
        $stmt->execute();

        if ($stmt) {
            $success = "Password successfully reset. You can now log in.";
            session_unset(); // Clear OTP session data after reset
        } else {
            $err = "Failed to reset password. Please try again.";
        }
    } else {
        $err = "OTP verification required before resetting password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Password Reset - Drug Verification and Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/icons.min.css">
    <link rel="stylesheet" href="assets/css/app.min.css">
    <script src="assets/js/swal.js"></script>

    <?php if (isset($success)) { ?>
        <script>
            setTimeout(function () {
                swal("Success", "<?php echo $success; ?>", "success");
            }, 100);
        </script>
    <?php } ?>

    <?php if (isset($err)) { ?>
        <script>
            setTimeout(function () {
                swal("Error", "<?php echo $err; ?>", "error");
            }, 100);
        </script>
    <?php } ?>
</head>

<body class="authentication-bg authentication-bg-pattern">
<div class="account-pages mt-5 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-pattern">
                    <div class="card-body p-4">
                        <div class="text-center w-75 m-auto">
                            <a href="index.php">
                                <img src="assets/images/dvms-dark.png" alt="" height="32">
                            </a>
                            <p class="text-muted mb-4 mt-3">Follow the steps below to reset your password.</p>
                        </div>

                        <?php if (!isset($_SESSION['otp'])) { ?>
                            <!-- Send OTP Form -->
                            <form method="post">
                                <div class="form-group mb-3">
                                    <label for="email">Email address</label>
                                    <input class="form-control" name="email" type="email" id="email" required placeholder="Enter your email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                                </div>
                                <div class="form-group mb-0 text-center">
                                    <button name="send_otp" class="btn btn-primary btn-block" type="submit">Send OTP</button>
                                </div>
                            </form>
                        <?php } elseif (!isset($_SESSION['otp_verified']) && isset($_SESSION['otp_email'])) { ?>
                            <!-- Verify OTP Form -->
                            <form method="post">
                                <div class="form-group mb-3">
                                    <label for="otp">Enter OTP</label>
                                    <input class="form-control" name="otp" type="text" id="otp" required placeholder="Enter the OTP sent to your email">
                                </div>
                                <input type="hidden" name="email" value="<?php echo $_SESSION['otp_email']; ?>">
                                <div class="form-group mb-0 text-center">
                                    <button name="verify_otp" class="btn btn-primary btn-block" type="submit">Verify OTP</button>
                                </div>
                            </form>
                        <?php } elseif (isset($_SESSION['otp_verified'])) { ?>
                            <!-- Reset Password Form -->
                            <form method="post">
                                <div class="form-group mb-3">
                                    <label for="new_password">New Password</label>
                                    <input class="form-control" name="new_password" type="password" id="new_password" required placeholder="Enter your new password">
                                </div>
                                <input type="hidden" name="email" value="<?php echo $_SESSION['otp_email']; ?>">
                                <div class="form-group mb-0 text-center">
                                    <button name="reset_pwd" class="btn btn-primary btn-block" type="submit">Reset Password</button>
                                </div>
                            </form>
                        <?php } ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 text-center">
                        <p>Back to <a href="index.php" class="text-white ml-1"><b>Log in</b></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="assets/js/vendor.min.js"></script>
<script src="assets/js/app.min.js"></script>
</body>
</html>
