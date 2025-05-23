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

// Check profil_pelamar table structure
$result = $conn->query("DESCRIBE profil_pelamar");
echo "Table structure for 'profil_pelamar':\n";
while ($row = $result->fetch_assoc()) {
    echo "- {$row['Field']} ({$row['Type']})\n";
}

// Close connection
$conn->close();
