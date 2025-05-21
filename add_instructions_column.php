<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sirek_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the column already exists
$result = $conn->query("SHOW COLUMNS FROM assessments LIKE 'instructions'");
if ($result->num_rows == 0) {
    // Column doesn't exist, add it
    $sql = "ALTER TABLE `assessments` ADD COLUMN `instructions` TEXT AFTER `description`";

    if ($conn->query($sql) === TRUE) {
        echo "Column 'instructions' successfully added to the assessments table";
    } else {
        echo "Error adding column: " . $conn->error;
    }
} else {
    echo "Column 'instructions' already exists in the assessments table";
}

// Add is_active column if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM assessments LIKE 'is_active'");
if ($result->num_rows == 0) {
    // Column doesn't exist, add it
    $sql = "ALTER TABLE `assessments` ADD COLUMN `is_active` BOOLEAN DEFAULT TRUE AFTER `passing_score`";

    if ($conn->query($sql) === TRUE) {
        echo "<br>Column 'is_active' successfully added to the assessments table";
    } else {
        echo "<br>Error adding column: " . $conn->error;
    }
} else {
    echo "<br>Column 'is_active' already exists in the assessments table";
}

// Add max_attempts column if it doesn't exist
$result = $conn->query("SHOW COLUMNS FROM assessments LIKE 'max_attempts'");
if ($result->num_rows == 0) {
    // Column doesn't exist, add it
    $sql = "ALTER TABLE `assessments` ADD COLUMN `max_attempts` INT DEFAULT 1 AFTER `is_active`";

    if ($conn->query($sql) === TRUE) {
        echo "<br>Column 'max_attempts' successfully added to the assessments table";
    } else {
        echo "<br>Error adding column: " . $conn->error;
    }
} else {
    echo "<br>Column 'max_attempts' already exists in the assessments table";
}

$conn->close();
?>
