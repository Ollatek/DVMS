<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Login | DVMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        /* Full-page background */
        body {
            font-family: 'Poppins', sans-serif;
            background: url('assets/images/medical-bg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            color: black;
        }

        /* Title Styling */
        .login-title {
            font-size: 36px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        /* Login Container */
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        /* Form Inputs */
        .form-control {
            background: transparent;
            border: none;
            border-bottom: 2px solid rgba(255, 255, 255, 0.5);
            color: #fff;
            font-size: 16px;
            text-align: center;
            transition: all 0.3s ease-in-out;
        }
        .form-control:focus {
            border-bottom: 2px solid #ffffff;
            outline: none;
            box-shadow: none;
        }
        ::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(45deg, #00c9ff, #92fe9d);
            border: none;
            padding: 12px;
            font-size: 18px;
            border-radius: 30px;
            width: 100%;
            font-weight: 600;
            transition: all 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #0095c2, #60d394);
            transform: scale(1.05);
            box-shadow: 0px 4px 10px rgba(0, 201, 255, 0.3);
        }

        /* Loader for Button */
        .loader {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid white;
            border-top: 3px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        /* Footer */
        .footer-text {
            margin-top: 10px;
            color: black;
            font-size: 14px;
        }

        /* Animations */
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- Title for Patient Login -->
    <h1 class="login-title">Patient Login</h1>

    <!-- Login Form Container -->
    <div class="login-container">
        <h2>Login</h2>
        <form id="loginForm" action="patient_auth.php" method="post">
            <div class="mb-3">
                <input type="text" name="patient_number" class="form-control text-center" placeholder="Enter Patient Number" required>
            </div>
            <button type="submit" class="btn btn-primary" id="loginButton">
                <span class="btn-text">Login</span>
                <div class="loader"></div>
            </button>
        </form>
        <p class="footer-text">Drug Verification & Management System</p>
    </div>

    <!-- Script to Show Loader on Login -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            let loginButton = document.getElementById('loginButton');
            loginButton.disabled = true;
            loginButton.querySelector('.btn-text').style.display = 'none';
            loginButton.querySelector('.loader').style.display = 'block';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
