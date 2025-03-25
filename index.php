<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Drug Verification and Management System ensuring drug authenticity and safety.">
    <meta name="keywords" content="drug verification, pharmacy, medicine, counterfeit drugs">
    
    <!-- Page Title -->
    <title>Drug Verification and Management System</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png" type="image/x-icon">

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/animate-3.7.0.css">
    <link rel="stylesheet" href="assets/css/font-awesome-4.7.0.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap-4.1.3.min.css">
    <link rel="stylesheet" href="assets/css/owl-carousel.min.css">
    <link rel="stylesheet" href="assets/css/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="assets/css/linearicons.css">
    <link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/spinners.css">
	<link rel="stylesheet" href="assets/css/icons.min.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>
<body>
    <!-- Preloader Starts -->
    <div class="preloader" id="loader">
        <div class="spinner"></div>
        <img src="assets/images/drug.gif" alt="Loading" class="drug-image">
    </div>
    <!-- Preloader End -->

    <!-- Header Area Starts -->
    <header class="header-area">
        <div id="header">
            <div class="container">
                <div class="row align-items-center justify-content-between d-flex">
                    <div id="logo">
                        <a href="index.php">
                            <img src="assets/images/dvms-dark.png" alt="DVMS Logo" height= "22">
                        </a>
                    </div>
                    <nav id="nav-menu-container">
                        <ul class="nav-menu">
                            <li class="menu-active"><a href="index.php">Home</a></li>
                            <li><a href="backend/doc/index.php">Employee's Login</a></li>
                            <li><a href="backend/admin/index.php">Administrator Login</a></li>
                            <li><a href="backend/doc/his_doc_drug_verification.php">Verify Drug</a></li>
							<li><a href="backend/User/user_login.php">Patient login</a></li>
                        </ul>
                    </nav><!-- #nav-menu-container -->
                </div>
            </div>
        </div>
    </header>
    <!-- Header Area End -->

    <!-- Banner Area Starts  -->
    <section class="banner-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h4 data-aos="fade-up">Caring for better life</h4>
                    <h1 data-aos="fade-up" data-aos-delay="200">Leading the way in medical excellence</h1>
                    <p data-aos="fade-up" data-aos-delay="400">Welcome to the Drug Verification and Management System (DVMS), your trusted platform<br>
                        for ensuring the safety, authenticity, and effective management of pharmaceutical products.</p>
                    
                    <h1 data-aos="fade-up" data-aos-delay="600">Verify a Drug</h1>
                    <form action="backend/doc/his_doc_drug_verification.php" method="GET" data-aos="fade-up" data-aos-delay="800">
                        <input type="text" name="drug_code" placeholder="Enter Drug Code" required>
                        <button type="submit">Verify</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
	
	<!-- Footer Section -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Contact Info -->
            <div class="footer-section contact">
                <h3>Contact Us</h3>
                <p>Email: support@dvms.com</p>
                <p>Phone: +1 (123) 456-7890</p>
                <p>Address: 123 DVMS Street, Health City</p>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-section links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Verify a Drug</a></li>
                    <li><a href="#">Administrator Login</a></li>
                    <li><a href="#">Employee’s Login</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Support</a></li>
                    <li><a href="#">Blog</a></li>
                </ul>
            </div>
            
            <!-- Social Media -->
            <div class="footer-section social">
                <h3>Follow Us</h3>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
            
            <!-- Newsletter Subscription -->
            <div class="footer-section newsletter">
                <h3>Subscribe for Updates</h3>
                <form>
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 DVMS. All Rights Reserved.</p>
    </div>
</footer>

    <!-- Javascript -->
    <script src="assets/js/vendor/jquery-2.2.4.min.js"></script>
    <script src="assets/js/vendor/bootstrap-4.1.3.min.js"></script>
    <script src="assets/js/vendor/wow.min.js"></script>
    <script src="assets/js/vendor/owl-carousel.min.js"></script>
    <script src="assets/js/vendor/jquery.datetimepicker.full.min.js"></script>
    <script src="assets/js/vendor/jquery.nice-select.min.js"></script>
    <script src="assets/js/vendor/superfish.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        $(document).ready(function() {
            AOS.init();
        });
    </script>
</body>
</html>
