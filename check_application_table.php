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

// Check lamaran_pekerjaan table structure
$result = $conn->query("DESCRIBE lamaran_pekerjaan");
echo "Table structure for 'lamaran_pekerjaan':\n";
while ($row = $result->fetch_assoc()) {
    echo "- {$row['Field']} ({$row['Type']})\n";
}

// Close connection
$conn->close();
