<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sirek_db_id';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get table structure
$table = 'lamaran_pekerjaan';
$sql = "DESCRIBE $table";
$result = $conn->query($sql);

if ($result) {
    echo "Structure of table '$table':\n";
    echo "-----------------------------\n";
    echo "Field | Type | Null | Key | Default | Extra\n";
    echo "-----------------------------\n";

    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " | " . $row['Type'] . " | " . $row['Null'] . " | " . $row['Key'] . " | " . $row['Default'] . " | " . $row['Extra'] . "\n";
    }
} else {
    echo "Error: " . $conn->error;
}

// Close connection
$conn->close();
?>
