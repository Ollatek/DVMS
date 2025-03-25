<?php
session_start();
include('assets/inc/config.php');

// Ensure user is logged in
if (!isset($_SESSION['pat_number'])) {
    header("Location: user_dashboard.php");
    exit();
}

$pat_number = $_SESSION['pat_number'];

// Fetch patient details
$stmt = $mysqli->prepare("SELECT * FROM his_patients WHERE pat_number = ?");
$stmt->bind_param("s", $pat_number);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    header("Location: user_login.php");
    exit();
}

// Set profile picture (use default if none is set)
$profile_pic = !empty($patient['profile_picture']) ? 'uploads/profile_pics/' . $patient['profile_picture'] : 'assets/images/users/default.png';

// Handle Profile Picture Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_picture"])) {
    $target_dir = "uploads/profile_pics/";
    $file_name = basename($_FILES["profile_picture"]["name"]);
    $target_file = $target_dir . $file_name;

    // Move the uploaded file
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $update_stmt = $mysqli->prepare("UPDATE his_patients SET profile_picture = ? WHERE pat_number = ?");
        $update_stmt->bind_param("ss", $file_name, $pat_number);
        $update_stmt->execute();
        header("Location: user_dashboard.php"); // Refresh after upload
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Background Styling */
        body {
            background: url('assets/images/dg_bg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white; /* Ensure text is visible on the background */
        }

        /* Make sure the profile card stands out */
        .profile-card, .card {
            background: rgba(0, 0, 0, 0.7); /* Semi-transparent background */
            color: white; /* White text for readability */
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }

        .profile-card img {
            border: 3px solid white;
        }

        .btn-primary {
            background-color: #28a745; /* Green button */
            border-color: #218838;
        }

        .btn-primary:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="profile-card text-center p-3">
                    <img src="<?php echo $profile_pic; ?>" class="rounded-circle img-thumbnail" alt="profile-image">
                    <h5 class="mt-3"><?php echo $patient['pat_fname'] . ' ' . $patient['pat_lname']; ?></h5>
                    <p><strong>Mobile:</strong> <?php echo $patient['pat_phone']; ?></p>
                    <p><strong>Address:</strong> <?php echo $patient['pat_addr']; ?></p>
                    <p><strong>Date of Birth:</strong> <?php echo $patient['pat_dob']; ?></p>
                    <p><strong>Age:</strong> <?php echo $patient['pat_age']; ?> Years</p>
                    <p><strong>Ailment:</strong> <?php echo $patient['pat_ailment']; ?></p>
                    
                    <!-- Profile Picture Upload Form -->
                    <form action="" method="post" enctype="multipart/form-data">
                        <input type="file" name="profile_picture" class="form-control mt-2" required>
                        <button type="submit" class="btn btn-primary mt-2">Upload Profile Picture</button>
                    </form>

                    <a href="user_logout.php" class="btn btn-danger mt-3">Logout</a>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#prescription">Prescription</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#vitals">Vitals</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#lab">Lab Records</a>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body tab-content">
                        <div class="tab-pane fade show active" id="prescription">
                            <ul class="list-unstyled">
                                <?php
                                $stmt = $mysqli->prepare("SELECT * FROM his_prescriptions WHERE pres_pat_number = ?");
                                $stmt->bind_param("s", $pat_number);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    echo "<li><strong>" . date("Y-m-d", strtotime($row['pres_date'])) . "</strong>: " . $row['pres_ins'] . "</li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="tab-pane fade" id="vitals">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Temperature</th>
                                        <th>Pulse</th>
                                        <th>Respiration</th>
                                        <th>Blood Pressure</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $mysqli->prepare("SELECT * FROM his_vitals WHERE vit_pat_number = ?");
                                    $stmt->bind_param("s", $pat_number);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($row = $res->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['vit_bodytemp']}°C</td>
                                            <td>{$row['vit_heartpulse']} BPM</td>
                                            <td>{$row['vit_resprate']} bpm</td>
                                            <td>{$row['vit_bloodpress']} mmHg</td>
                                            <td>" . date("Y-m-d", strtotime($row['vit_daterec'])) . "</td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="lab"><a href="/DVMS-PHP/backend/User/generate_lab_report.php" class="btn btn-primary">Download Lab Report</a>

                            <ul class="list-unstyled">
                                <?php
                                $stmt = $mysqli->prepare("SELECT * FROM his_laboratory WHERE lab_pat_number = ?");
                                $stmt->bind_param("s", $pat_number);
                                $stmt->execute();
                                $res = $stmt->get_result();
                                while ($row = $res->fetch_assoc()) {
                                    echo "<li><strong>" . date("Y-m-d", strtotime($row['lab_date_rec'])) . "</strong><br>";
                                    echo "Tests: " . $row['lab_pat_tests'] . "<br>";
                                    echo "Results: " . $row['lab_pat_results'] . "</li><hr>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
