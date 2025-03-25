<!-- EHR Patient Chart Interface with Sidebar and Patient Details -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EHR Patient Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
        }
        .sidebar h2 {
            text-align: center;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px;
            cursor: pointer;
        }
        .sidebar ul li:hover {
            background: #34495e;
        }
        .container {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .chart-container {
            position: relative;
            width: 100%;
            height: 400px;
        }
        .patient-details {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .patient-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-right: 20px;
        }
        .patient-info {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <ul>
            <li>Dashboard</li>
            <li>Prescriptions</li>
            <li>Appointments</li>
            <li>Medical History</li>
            <li>Billing</li>
            <li>Settings</li>
        </ul>
    </div>
    
    <div class="container">
        <div class="patient-details">
            <img src="https://via.placeholder.com/100" alt="Patient" class="patient-image">
            <div class="patient-info">
                <strong>Name:</strong> John Doe <br>
                <strong>Age:</strong> 32 <br>
                <strong>Gender:</strong> Male <br>
                <strong>Blood Type:</strong> O+
            </div>
        </div>
        
        <h2>Patient Health Overview</h2>
        <canvas id="ehrPieChart"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('ehrPieChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Blood Pressure', 'Heart Rate', 'Cholesterol', 'Sugar Levels', 'BMI'],
                datasets: [{
                    label: 'Patient Health Stats',
                    data: [25, 20, 15, 30, 10],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</body>
</html>
