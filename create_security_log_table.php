<?php
/**
 * Script untuk membuat tabel log security violations
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sirek_db_id';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Membuat Tabel Log Security Violations</h2>";
    echo "<hr>";
    
    // Cek apakah tabel sudah ada
    $stmt = $pdo->query("SHOW TABLES LIKE 'log_security_violations'");
    if ($stmt->rowCount() > 0) {
        echo "<p>ℹ️ Tabel 'log_security_violations' sudah ada</p>";
    } else {
        echo "<p>Membuat tabel 'log_security_violations'...</p>";
        
        $sql = "CREATE TABLE `log_security_violations` (
            `id` int NOT NULL AUTO_INCREMENT,
            `id_penilaian_pelamar` int NOT NULL COMMENT 'ID dari tabel penilaian_pelamar',
            `id_pelamar` int NOT NULL COMMENT 'ID dari tabel pengguna (pelamar)',
            `violation_type` varchar(255) NOT NULL COMMENT 'Jenis pelanggaran keamanan',
            `timestamp` timestamp NOT NULL COMMENT 'Waktu pelanggaran terjadi',
            `ip_address` varchar(45) NULL COMMENT 'IP address pelamar',
            `user_agent` text NULL COMMENT 'User agent browser',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            INDEX `idx_penilaian_pelamar` (`id_penilaian_pelamar`),
            INDEX `idx_pelamar` (`id_pelamar`),
            INDEX `idx_violation_type` (`violation_type`),
            INDEX `idx_timestamp` (`timestamp`),
            CONSTRAINT `fk_security_log_penilaian_pelamar` 
                FOREIGN KEY (`id_penilaian_pelamar`) 
                REFERENCES `penilaian_pelamar` (`id`) 
                ON DELETE CASCADE ON UPDATE RESTRICT,
            CONSTRAINT `fk_security_log_pelamar` 
                FOREIGN KEY (`id_pelamar`) 
                REFERENCES `pengguna` (`id`) 
                ON DELETE CASCADE ON UPDATE RESTRICT
        ) ENGINE = InnoDB 
          CHARACTER SET = latin1 
          COLLATE = latin1_swedish_ci 
          COMMENT = 'Log pelanggaran keamanan selama ujian CAT'";
        
        $pdo->exec($sql);
        echo "<p>✅ Tabel 'log_security_violations' berhasil dibuat</p>";
    }
    
    // Buat direktori logs jika belum ada
    $logs_dir = 'application/logs';
    if (!is_dir($logs_dir)) {
        if (mkdir($logs_dir, 0755, true)) {
            echo "<p>✅ Direktori logs berhasil dibuat</p>";
        } else {
            echo "<p>❌ Gagal membuat direktori logs</p>";
        }
    } else {
        echo "<p>ℹ️ Direktori logs sudah ada</p>";
    }
    
    // Test insert sample data
    echo "<h3>Test Insert Sample Data</h3>";
    try {
        $stmt = $pdo->prepare("INSERT INTO log_security_violations (id_penilaian_pelamar, id_pelamar, violation_type, timestamp, ip_address, user_agent) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            12, // id_penilaian_pelamar dari data test
            4,  // id_pelamar dari pelamar_test
            'Test violation - system check',
            date('Y-m-d H:i:s'),
            '127.0.0.1',
            'Test User Agent'
        ]);
        
        $log_id = $pdo->lastInsertId();
        echo "<p>✅ Sample data berhasil diinsert dengan ID: $log_id</p>";
        
        // Hapus sample data
        $stmt = $pdo->prepare("DELETE FROM log_security_violations WHERE id = ?");
        $stmt->execute([$log_id]);
        echo "<p>✅ Sample data berhasil dihapus (test selesai)</p>";
        
    } catch (PDOException $e) {
        echo "<p>❌ Error test insert: " . $e->getMessage() . "</p>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Setup Security Logging Selesai!</h3>";
    
    echo "<h3>Fitur Security Logging:</h3>";
    echo "<ul>";
    echo "<li>✅ Tabel database untuk menyimpan log pelanggaran</li>";
    echo "<li>✅ Direktori logs untuk backup file log</li>";
    echo "<li>✅ Automatic fallback ke file log jika database error</li>";
    echo "<li>✅ Tracking IP address dan user agent</li>";
    echo "<li>✅ Foreign key constraints untuk data integrity</li>";
    echo "</ul>";
    
    echo "<h3>Jenis Pelanggaran yang Dilog:</h3>";
    echo "<ul>";
    echo "<li>🚫 Alt+Tab (task switching)</li>";
    echo "<li>🚫 Windows key press</li>";
    echo "<li>🚫 F12, Ctrl+Shift+I (developer tools)</li>";
    echo "<li>🚫 Alt+F4 (close window)</li>";
    echo "<li>🚫 Ctrl+R, F5 (refresh)</li>";
    echo "<li>🚫 Print Screen (screenshot)</li>";
    echo "<li>🚫 Tab switching/window blur</li>";
    echo "<li>🚫 Mouse leaving exam area</li>";
    echo "<li>🚫 Fullscreen exit attempts</li>";
    echo "</ul>";
    
    echo "<h3>Cara Melihat Log:</h3>";
    echo "<p><strong>Database:</strong></p>";
    echo "<pre>SELECT * FROM log_security_violations ORDER BY created_at DESC;</pre>";
    
    echo "<p><strong>File Log:</strong></p>";
    echo "<pre>application/logs/security_violations_" . date('Y-m-d') . ".log</pre>";
    
    echo "<h3>Query Analisis Log:</h3>";
    echo "<pre>";
    echo "-- Pelanggaran per pelamar\n";
    echo "SELECT p.nama_lengkap, COUNT(*) as total_violations\n";
    echo "FROM log_security_violations lsv\n";
    echo "JOIN pengguna p ON p.id = lsv.id_pelamar\n";
    echo "GROUP BY lsv.id_pelamar\n";
    echo "ORDER BY total_violations DESC;\n\n";
    
    echo "-- Jenis pelanggaran terbanyak\n";
    echo "SELECT violation_type, COUNT(*) as count\n";
    echo "FROM log_security_violations\n";
    echo "GROUP BY violation_type\n";
    echo "ORDER BY count DESC;\n\n";
    
    echo "-- Pelanggaran per ujian\n";
    echo "SELECT pen.judul, COUNT(*) as violations\n";
    echo "FROM log_security_violations lsv\n";
    echo "JOIN penilaian_pelamar pp ON pp.id = lsv.id_penilaian_pelamar\n";
    echo "JOIN penilaian pen ON pen.id = pp.id_penilaian\n";
    echo "GROUP BY pp.id_penilaian\n";
    echo "ORDER BY violations DESC;";
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "❌ Error database: " . $e->getMessage();
}
?>
