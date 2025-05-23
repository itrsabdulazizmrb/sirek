<?php
// Database configuration
define('BASEPATH', true);
include 'application/config/database.php';

// Connect to database
$conn = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check job deadlines
$result = $conn->query("SELECT id, judul, batas_waktu FROM lowongan_pekerjaan");
echo "Job deadlines:\n";
$today = date('Y-m-d');
$expired_count = 0;
$valid_count = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $deadline = $row['batas_waktu'];
        $status = ($deadline >= $today) ? "Valid" : "Expired";
        
        if ($status == "Expired") {
            $expired_count++;
        } else {
            $valid_count++;
        }
        
        echo "- ID: {$row['id']}, Judul: {$row['judul']}, Batas Waktu: {$deadline}, Status: {$status}\n";
    }
    
    echo "\nSummary:\n";
    echo "- Valid deadlines: {$valid_count}\n";
    echo "- Expired deadlines: {$expired_count}\n";
} else {
    echo "No jobs found.\n";
}

// Update expired deadlines to future date
if ($expired_count > 0) {
    $future_date = date('Y-m-d', strtotime('+30 days'));
    $sql = "UPDATE lowongan_pekerjaan SET batas_waktu = '{$future_date}' WHERE batas_waktu < '{$today}'";
    
    if ($conn->query($sql) === TRUE) {
        echo "\nSuccessfully updated {$expired_count} expired deadlines to {$future_date}.\n";
    } else {
        echo "\nError updating deadlines: " . $conn->error . "\n";
    }
}

// Close connection
$conn->close();
