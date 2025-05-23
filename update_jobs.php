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

// Update all jobs to active status
$sql = "UPDATE lowongan_pekerjaan SET status = 'aktif' WHERE status IS NULL OR status = ''";
if ($conn->query($sql) === TRUE) {
    echo "Successfully updated " . $conn->affected_rows . " jobs to active status.\n";
} else {
    echo "Error updating jobs: " . $conn->error . "\n";
}

// Close connection
$conn->close();
