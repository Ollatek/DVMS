<?php
session_start();
include('assets/inc/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pat_number = mysqli_real_escape_string($conn, $_POST['patient_number']);

    // Check if patient exists
    $sql = "SELECT * FROM his_patients WHERE pat_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pat_number); // Use "s" for string
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $patient = $result->fetch_assoc();

        // Reset session to avoid data mix-up
        session_unset();
        session_destroy();
        session_start();

        // Store new patient session data
        $_SESSION['pat_id'] = $patient['pat_id'];
        $_SESSION['pat_number'] = $patient['pat_number'];

        // Redirect to dashboard
        header("Location: user_dashboard.php");
        exit();
    } else {
        // Redirect back to login with error
        header("Location: patient_login.php?error=invalid");
        exit();
    }
}
?>
