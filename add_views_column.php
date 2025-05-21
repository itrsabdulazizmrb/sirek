<?php
// Bypass CodeIgniter's direct script access check
define('BASEPATH', '');

// Koneksi ke database
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'sirek_db';

$conn = new mysqli($hostname, $username, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah kolom views sudah ada
$result = $conn->query("SHOW COLUMNS FROM blog_posts LIKE 'views'");
if ($result->num_rows == 0) {
    // Kolom views belum ada, tambahkan
    $sql = "ALTER TABLE `blog_posts` ADD COLUMN `views` INT DEFAULT 0 AFTER `status`";

    if ($conn->query($sql) === TRUE) {
        echo "Kolom views berhasil ditambahkan ke tabel blog_posts";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Kolom views sudah ada di tabel blog_posts";
}

$conn->close();
?>
