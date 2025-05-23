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

// Check if lowongan_pekerjaan table exists
$result = $conn->query("SHOW TABLES LIKE 'lowongan_pekerjaan'");
if ($result->num_rows == 0) {
    echo "Table lowongan_pekerjaan does not exist!\n";
} else {
    echo "Table lowongan_pekerjaan exists.\n";

    // Get table structure
    $result = $conn->query("DESCRIBE lowongan_pekerjaan");
    echo "\nTable structure:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']} ({$row['Type']})\n";
    }

    // Count all jobs
    $result = $conn->query("SELECT COUNT(*) as total FROM lowongan_pekerjaan");
    $row = $result->fetch_assoc();
    echo "\nTotal jobs: {$row['total']}\n";

    // Count active jobs
    $result = $conn->query("SELECT COUNT(*) as total FROM lowongan_pekerjaan WHERE status = 'aktif'");
    $row = $result->fetch_assoc();
    echo "Active jobs: {$row['total']}\n";

    // List all jobs
    $result = $conn->query("SELECT id, judul, status FROM lowongan_pekerjaan");
    echo "\nAll jobs:\n";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "- ID: {$row['id']}, Judul: {$row['judul']}, Status: {$row['status']}\n";
        }
    } else {
        echo "No jobs found.\n";
    }
}

// Close connection
$conn->close();
