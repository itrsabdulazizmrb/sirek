<?php
/**
 * Script untuk menjalankan update SQL untuk sistem CAT
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sirek_db_id';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Menjalankan Update Database untuk Sistem CAT</h2>";
    echo "<hr>";
    
    // 1. Tambah kolom acak_soal ke tabel penilaian
    echo "<h3>1. Menambahkan kolom 'acak_soal' ke tabel penilaian</h3>";
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM penilaian LIKE 'acak_soal'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `penilaian` ADD COLUMN `acak_soal` TINYINT(1) NULL DEFAULT 0 COMMENT 'Aktifkan pengacakan urutan soal untuk setiap peserta'");
            echo "✅ Kolom 'acak_soal' berhasil ditambahkan<br>";
        } else {
            echo "ℹ️ Kolom 'acak_soal' sudah ada<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
    
    // 2. Tambah kolom mode_cat ke tabel penilaian
    echo "<h3>2. Menambahkan kolom 'mode_cat' ke tabel penilaian</h3>";
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM penilaian LIKE 'mode_cat'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `penilaian` ADD COLUMN `mode_cat` TINYINT(1) NULL DEFAULT 0 COMMENT 'Aktifkan mode CAT (Computer Adaptive Test) interface'");
            echo "✅ Kolom 'mode_cat' berhasil ditambahkan<br>";
        } else {
            echo "ℹ️ Kolom 'mode_cat' sudah ada<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
    
    // 3. Tambah kolom ditandai_ragu ke tabel jawaban_pelamar
    echo "<h3>3. Menambahkan kolom 'ditandai_ragu' ke tabel jawaban_pelamar</h3>";
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM jawaban_pelamar LIKE 'ditandai_ragu'");
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `jawaban_pelamar` ADD COLUMN `ditandai_ragu` TINYINT(1) NULL DEFAULT 0 COMMENT 'Tandai soal sebagai ragu-ragu untuk review'");
            echo "✅ Kolom 'ditandai_ragu' berhasil ditambahkan<br>";
        } else {
            echo "ℹ️ Kolom 'ditandai_ragu' sudah ada<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
    
    // 4. Buat tabel urutan_soal_pelamar
    echo "<h3>4. Membuat tabel 'urutan_soal_pelamar'</h3>";
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE 'urutan_soal_pelamar'");
        if ($stmt->rowCount() == 0) {
            $sql = "CREATE TABLE `urutan_soal_pelamar` (
                `id` int NOT NULL AUTO_INCREMENT,
                `id_penilaian_pelamar` int NOT NULL COMMENT 'ID dari tabel penilaian_pelamar',
                `id_soal` int NOT NULL COMMENT 'ID dari tabel soal',
                `urutan` int NOT NULL COMMENT 'Urutan soal untuk peserta ini (1, 2, 3, dst)',
                `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                INDEX `idx_penilaian_pelamar` (`id_penilaian_pelamar`),
                INDEX `idx_soal` (`id_soal`),
                INDEX `idx_urutan` (`id_penilaian_pelamar`, `urutan`),
                UNIQUE KEY `unique_pelamar_soal` (`id_penilaian_pelamar`, `id_soal`)
            ) ENGINE = InnoDB CHARACTER SET = latin1 COLLATE = latin1_swedish_ci 
            COMMENT = 'Menyimpan urutan soal yang diacak untuk setiap peserta dalam mode CAT'";
            
            $pdo->exec($sql);
            echo "✅ Tabel 'urutan_soal_pelamar' berhasil dibuat<br>";
        } else {
            echo "ℹ️ Tabel 'urutan_soal_pelamar' sudah ada<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
    
    // 5. Tambah foreign key constraints
    echo "<h3>5. Menambahkan foreign key constraints</h3>";
    try {
        // Cek apakah foreign key sudah ada
        $stmt = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                            WHERE TABLE_SCHEMA = 'sirek_db_id' 
                            AND TABLE_NAME = 'urutan_soal_pelamar' 
                            AND CONSTRAINT_NAME = 'fk_urutan_soal_penilaian_pelamar'");
        
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `urutan_soal_pelamar` 
                       ADD CONSTRAINT `fk_urutan_soal_penilaian_pelamar` 
                       FOREIGN KEY (`id_penilaian_pelamar`) 
                       REFERENCES `penilaian_pelamar` (`id`) 
                       ON DELETE CASCADE ON UPDATE RESTRICT");
            echo "✅ Foreign key 'fk_urutan_soal_penilaian_pelamar' berhasil ditambahkan<br>";
        } else {
            echo "ℹ️ Foreign key 'fk_urutan_soal_penilaian_pelamar' sudah ada<br>";
        }
        
        $stmt = $pdo->query("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                            WHERE TABLE_SCHEMA = 'sirek_db_id' 
                            AND TABLE_NAME = 'urutan_soal_pelamar' 
                            AND CONSTRAINT_NAME = 'fk_urutan_soal_soal'");
        
        if ($stmt->rowCount() == 0) {
            $pdo->exec("ALTER TABLE `urutan_soal_pelamar` 
                       ADD CONSTRAINT `fk_urutan_soal_soal` 
                       FOREIGN KEY (`id_soal`) 
                       REFERENCES `soal` (`id`) 
                       ON DELETE CASCADE ON UPDATE RESTRICT");
            echo "✅ Foreign key 'fk_urutan_soal_soal' berhasil ditambahkan<br>";
        } else {
            echo "ℹ️ Foreign key 'fk_urutan_soal_soal' sudah ada<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
    
    // 6. Tambah index untuk performa
    echo "<h3>6. Menambahkan index untuk performa</h3>";
    try {
        // Index untuk tabel penilaian
        $indexes = [
            'idx_penilaian_mode_cat' => 'CREATE INDEX `idx_penilaian_mode_cat` ON `penilaian` (`mode_cat`)',
            'idx_penilaian_acak_soal' => 'CREATE INDEX `idx_penilaian_acak_soal` ON `penilaian` (`acak_soal`)',
            'idx_jawaban_ditandai_ragu' => 'CREATE INDEX `idx_jawaban_ditandai_ragu` ON `jawaban_pelamar` (`ditandai_ragu`)',
            'idx_jawaban_penilaian_soal' => 'CREATE INDEX `idx_jawaban_penilaian_soal` ON `jawaban_pelamar` (`id_penilaian_pelamar`, `id_soal`)'
        ];
        
        foreach ($indexes as $name => $sql) {
            try {
                $pdo->exec($sql);
                echo "✅ Index '$name' berhasil ditambahkan<br>";
            } catch (PDOException $e) {
                if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
                    echo "ℹ️ Index '$name' sudah ada<br>";
                } else {
                    echo "❌ Error membuat index '$name': " . $e->getMessage() . "<br>";
                }
            }
        }
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
    
    // 7. Insert data sample
    echo "<h3>7. Menambahkan data sample</h3>";
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM jenis_penilaian WHERE nama LIKE '%CAT%'");
        $stmt->execute();
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            $pdo->exec("INSERT INTO `jenis_penilaian` (`nama`, `deskripsi`, `dibuat_pada`) VALUES
                       ('CAT - Computer Adaptive Test', 'Penilaian menggunakan sistem CAT dengan interface modern dan fitur keamanan tinggi', NOW())");
            echo "✅ Data sample jenis penilaian CAT berhasil ditambahkan<br>";
        } else {
            echo "ℹ️ Data sample jenis penilaian CAT sudah ada<br>";
        }
    } catch (PDOException $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Update Database Selesai!</h3>";
    echo "<p>Sistem CAT sudah siap digunakan. Silakan test dengan menjalankan <code>test_cat_system.php</code></p>";
    
} catch (PDOException $e) {
    echo "❌ Error koneksi database: " . $e->getMessage();
    echo "<br><br>Pastikan:";
    echo "<ul>";
    echo "<li>Database server berjalan</li>";
    echo "<li>Kredensial database benar</li>";
    echo "<li>Database 'sirek_db_id' sudah dibuat</li>";
    echo "</ul>";
}
?>
