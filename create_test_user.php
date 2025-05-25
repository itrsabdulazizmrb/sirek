<?php
/**
 * Script untuk membuat user pelamar test
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sirek_db_id';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Membuat User Pelamar Test</h2>";
    echo "<hr>";
    
    // Cek apakah user pelamar test sudah ada
    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE nama_pengguna = 'pelamar_test' OR email = 'pelamar@test.com'");
    $stmt->execute();
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($existing_user) {
        echo "<h3>✅ User Pelamar Test Sudah Ada</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Value</th></tr>";
        foreach ($existing_user as $key => $value) {
            if ($key != 'password') {
                echo "<tr><td>$key</td><td>$value</td></tr>";
            }
        }
        echo "</table>";
        
        echo "<h3>Informasi Login:</h3>";
        echo "<ul>";
        echo "<li><strong>Username:</strong> {$existing_user['nama_pengguna']}</li>";
        echo "<li><strong>Email:</strong> {$existing_user['email']}</li>";
        echo "<li><strong>Password:</strong> password</li>";
        echo "<li><strong>Role:</strong> {$existing_user['role']}</li>";
        echo "</ul>";
    } else {
        echo "<h3>Membuat User Pelamar Test Baru</h3>";
        
        // Hash password
        $hashed_password = password_hash('password', PASSWORD_DEFAULT);
        
        // Insert user pelamar test
        $stmt = $pdo->prepare("INSERT INTO pengguna (nama_pengguna, email, password, role, nama_lengkap, status, dibuat_pada) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $result = $stmt->execute([
            'pelamar_test',
            'pelamar@test.com',
            $hashed_password,
            'pelamar',
            'Pelamar Test',
            'aktif'
        ]);
        
        if ($result) {
            $user_id = $pdo->lastInsertId();
            echo "<p>✅ User pelamar test berhasil dibuat dengan ID: $user_id</p>";
            
            echo "<h3>Informasi Login:</h3>";
            echo "<ul>";
            echo "<li><strong>Username:</strong> pelamar_test</li>";
            echo "<li><strong>Email:</strong> pelamar@test.com</li>";
            echo "<li><strong>Password:</strong> password</li>";
            echo "<li><strong>Role:</strong> pelamar</li>";
            echo "</ul>";
            
            // Buat profil pelamar basic
            try {
                $stmt = $pdo->prepare("INSERT INTO profil_pelamar (id_pengguna, tanggal_lahir, jenis_kelamin, pendidikan, pengalaman, keahlian, dibuat_pada) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([
                    $user_id,
                    '1990-01-01',
                    'laki-laki',
                    'S1',
                    '2 tahun',
                    'Programming, Web Development'
                ]);
                echo "<p>✅ Profil pelamar basic berhasil dibuat</p>";
            } catch (PDOException $e) {
                echo "<p>ℹ️ Profil pelamar tidak dibuat (mungkin tabel tidak ada): " . $e->getMessage() . "</p>";
            }
        } else {
            echo "<p>❌ Gagal membuat user pelamar test</p>";
        }
    }
    
    // Cek apakah ada user admin
    echo "<h3>User Admin yang Tersedia:</h3>";
    $stmt = $pdo->query("SELECT id, nama_pengguna, email, role FROM pengguna WHERE role = 'admin'");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($admins) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th></tr>";
        foreach ($admins as $admin) {
            echo "<tr><td>{$admin['id']}</td><td>{$admin['nama_pengguna']}</td><td>{$admin['email']}</td><td>{$admin['role']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p>❌ Tidak ada user admin. Membuat user admin...</p>";
        
        $hashed_password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO pengguna (nama_pengguna, email, password, role, nama_lengkap, status, dibuat_pada) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $result = $stmt->execute([
            'admin',
            'admin@sirek.com',
            $hashed_password,
            'admin',
            'Administrator',
            'aktif'
        ]);
        
        if ($result) {
            echo "<p>✅ User admin berhasil dibuat</p>";
            echo "<p><strong>Username:</strong> admin</p>";
            echo "<p><strong>Password:</strong> admin123</p>";
        }
    }
    
    echo "<hr>";
    echo "<h3>Langkah Selanjutnya:</h3>";
    echo "<ol>";
    echo "<li><a href='http://localhost/sirek/auth' target='_blank'>Login sebagai pelamar_test</a></li>";
    echo "<li>Setelah login, akses <a href='http://localhost/sirek/pelamar/cat-penilaian/5/13' target='_blank'>URL CAT</a></li>";
    echo "<li>Jika masih error, cek <a href='http://localhost/sirek/check_session.php' target='_blank'>session status</a></li>";
    echo "</ol>";
    
    echo "<h3>Troubleshooting:</h3>";
    echo "<ul>";
    echo "<li>Pastikan Apache dan MySQL berjalan</li>";
    echo "<li>Pastikan database sirek_db_id sudah ada</li>";
    echo "<li>Pastikan semua tabel sudah dibuat</li>";
    echo "<li>Cek error log Apache jika masih ada masalah</li>";
    echo "</ul>";
    
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
