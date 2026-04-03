<?php
$db_host = "sql207.infinityfree.com"; 
$db_user = "if0_41446731";           
$db_pass = "idtgUIH1HT";  
$db_name = "if0_41446731_florentica"; 

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
$sql = "SELECT * FROM sensor_readings ORDER BY created_at DESC LIMIT 15";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Florentica Cloud</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <meta http-equiv="refresh" content="30">
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">🌿 Florentica Logística IoT</h2>
        <div class="table-responsive">
            <table class="table table-dark table-striped">
                <thead><tr><th>ID</th><th>Temp</th><th>Hum</th><th>Fecha</th></tr></thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['temperature'] ?>°C</td>
                        <td><?= $row['humidity'] ?>%</td>
                        <td><?= $row['created_at'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>