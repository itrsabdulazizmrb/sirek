<?php
/**
 * Script untuk membuat data test assessment untuk user pelamar_test
 */

// Konfigurasi database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'sirek_db_id';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Membuat Data Test Assessment</h2>";
    echo "<hr>";
    
    // 1. Cek user pelamar_test
    echo "<h3>1. Cek User Pelamar Test</h3>";
    $stmt = $pdo->prepare("SELECT * FROM pengguna WHERE nama_pengguna = 'pelamar_test'");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "<p>❌ User pelamar_test tidak ditemukan. Jalankan create_test_user.php terlebih dahulu.</p>";
        exit;
    }
    
    $user_id = $user['id'];
    echo "<p>✅ User pelamar_test ditemukan dengan ID: $user_id</p>";
    
    // 2. Cek atau buat lowongan test
    echo "<h3>2. Cek/Buat Lowongan Test</h3>";
    $stmt = $pdo->query("SELECT * FROM lowongan_pekerjaan WHERE status = 'aktif' LIMIT 1");
    $job = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$job) {
        echo "<p>Membuat lowongan test...</p>";
        $stmt = $pdo->prepare("INSERT INTO lowongan_pekerjaan (judul, deskripsi, persyaratan, gaji_min, gaji_max, lokasi, tipe_pekerjaan, status, batas_lamaran, dibuat_oleh, dibuat_pada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            'Programmer Test',
            'Lowongan test untuk sistem CAT',
            'Minimal S1 Informatika',
            5000000,
            8000000,
            'Jakarta',
            'full_time',
            'aktif',
            date('Y-m-d', strtotime('+30 days')),
            1 // admin user
        ]);
        $job_id = $pdo->lastInsertId();
        echo "<p>✅ Lowongan test berhasil dibuat dengan ID: $job_id</p>";
    } else {
        $job_id = $job['id'];
        echo "<p>✅ Lowongan ditemukan dengan ID: $job_id</p>";
    }
    
    // 3. Cek atau buat lamaran
    echo "<h3>3. Cek/Buat Lamaran Test</h3>";
    $stmt = $pdo->prepare("SELECT * FROM lamaran_pekerjaan WHERE id_pelamar = ? AND id_pekerjaan = ?");
    $stmt->execute([$user_id, $job_id]);
    $application = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$application) {
        echo "<p>Membuat lamaran test...</p>";
        $stmt = $pdo->prepare("INSERT INTO lamaran_pekerjaan (id_pekerjaan, id_pelamar, surat_lamaran, cv, status, tanggal_lamaran) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            $job_id,
            $user_id,
            'Surat lamaran test untuk sistem CAT',
            'cv_test.pdf',
            'seleksi'
        ]);
        $application_id = $pdo->lastInsertId();
        echo "<p>✅ Lamaran test berhasil dibuat dengan ID: $application_id</p>";
    } else {
        $application_id = $application['id'];
        echo "<p>✅ Lamaran ditemukan dengan ID: $application_id</p>";
    }
    
    // 4. Cek penilaian CAT
    echo "<h3>4. Cek Penilaian CAT</h3>";
    $stmt = $pdo->prepare("SELECT * FROM penilaian WHERE mode_cat = 1 LIMIT 1");
    $stmt->execute();
    $assessment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$assessment) {
        echo "<p>❌ Tidak ada penilaian dengan mode CAT. Membuat penilaian CAT test...</p>";
        
        // Ambil jenis penilaian CAT
        $stmt = $pdo->query("SELECT id FROM jenis_penilaian WHERE nama LIKE '%CAT%' LIMIT 1");
        $assessment_type = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$assessment_type) {
            // Buat jenis penilaian CAT
            $stmt = $pdo->prepare("INSERT INTO jenis_penilaian (nama, deskripsi, dibuat_pada) VALUES (?, ?, NOW())");
            $stmt->execute(['CAT Test', 'Computer Adaptive Test untuk testing']);
            $assessment_type_id = $pdo->lastInsertId();
        } else {
            $assessment_type_id = $assessment_type['id'];
        }
        
        $stmt = $pdo->prepare("INSERT INTO penilaian (judul, id_jenis, deskripsi, petunjuk, batas_waktu, nilai_lulus, aktif, maksimal_percobaan, acak_soal, mode_cat, dibuat_oleh, dibuat_pada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->execute([
            'CAT Test Assessment',
            $assessment_type_id,
            'Penilaian test untuk sistem CAT',
            'Ikuti petunjuk dengan seksama',
            60, // 60 menit
            70, // nilai lulus 70
            1,  // aktif
            1,  // maksimal 1 percobaan
            1,  // acak soal
            1,  // mode CAT
            1   // dibuat oleh admin
        ]);
        $assessment_id = $pdo->lastInsertId();
        echo "<p>✅ Penilaian CAT test berhasil dibuat dengan ID: $assessment_id</p>";
        
        // Buat beberapa soal test
        echo "<p>Membuat soal test...</p>";
        for ($i = 1; $i <= 5; $i++) {
            $stmt = $pdo->prepare("INSERT INTO soal (id_penilaian, teks_soal, jenis_soal, poin, dibuat_pada) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([
                $assessment_id,
                "Soal test CAT nomor $i. Pilih jawaban yang benar.",
                'pilihan_ganda',
                10
            ]);
            $question_id = $pdo->lastInsertId();
            
            // Buat pilihan jawaban
            $options = [
                ['Pilihan A untuk soal ' . $i, 0],
                ['Pilihan B untuk soal ' . $i, 1], // jawaban benar
                ['Pilihan C untuk soal ' . $i, 0],
                ['Pilihan D untuk soal ' . $i, 0]
            ];
            
            foreach ($options as $option) {
                $stmt = $pdo->prepare("INSERT INTO pilihan_soal (id_soal, teks_pilihan, benar, dibuat_pada) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$question_id, $option[0], $option[1]]);
            }
        }
        echo "<p>✅ 5 soal test berhasil dibuat</p>";
    } else {
        $assessment_id = $assessment['id'];
        echo "<p>✅ Penilaian CAT ditemukan dengan ID: $assessment_id</p>";
    }
    
    // 5. Cek atau buat penilaian_pelamar
    echo "<h3>5. Cek/Buat Penilaian Pelamar</h3>";
    $stmt = $pdo->prepare("SELECT * FROM penilaian_pelamar WHERE id_lamaran = ? AND id_penilaian = ?");
    $stmt->execute([$application_id, $assessment_id]);
    $applicant_assessment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$applicant_assessment) {
        echo "<p>Membuat penilaian_pelamar...</p>";
        $stmt = $pdo->prepare("INSERT INTO penilaian_pelamar (id_lamaran, id_penilaian, status, ditugaskan_pada, ditugaskan_oleh, dibuat_pada) VALUES (?, ?, ?, NOW(), ?, NOW())");
        $stmt->execute([
            $application_id,
            $assessment_id,
            'belum_mulai',
            1 // ditugaskan oleh admin
        ]);
        $applicant_assessment_id = $pdo->lastInsertId();
        echo "<p>✅ Penilaian pelamar berhasil dibuat dengan ID: $applicant_assessment_id</p>";
    } else {
        $applicant_assessment_id = $applicant_assessment['id'];
        echo "<p>✅ Penilaian pelamar ditemukan dengan ID: $applicant_assessment_id</p>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Data Test Assessment Siap!</h3>";
    
    echo "<h3>URL Test yang Dapat Digunakan:</h3>";
    echo "<ul>";
    echo "<li><strong>Login:</strong> <a href='http://localhost/sirek/auth' target='_blank'>http://localhost/sirek/auth</a></li>";
    echo "<li><strong>Username:</strong> pelamar_test</li>";
    echo "<li><strong>Password:</strong> password</li>";
    echo "<li><strong>URL CAT:</strong> <a href='http://localhost/sirek/pelamar/cat-penilaian/$assessment_id/$application_id' target='_blank'>http://localhost/sirek/pelamar/cat-penilaian/$assessment_id/$application_id</a></li>";
    echo "</ul>";
    
    echo "<h3>Data Summary:</h3>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Item</th><th>ID</th><th>Status</th></tr>";
    echo "<tr><td>User Pelamar</td><td>$user_id</td><td>✅</td></tr>";
    echo "<tr><td>Lowongan</td><td>$job_id</td><td>✅</td></tr>";
    echo "<tr><td>Lamaran</td><td>$application_id</td><td>✅</td></tr>";
    echo "<tr><td>Penilaian CAT</td><td>$assessment_id</td><td>✅</td></tr>";
    echo "<tr><td>Penilaian Pelamar</td><td>$applicant_assessment_id</td><td>✅</td></tr>";
    echo "</table>";
    
    echo "<h3>Langkah Test:</h3>";
    echo "<ol>";
    echo "<li>Login dengan username: <strong>pelamar_test</strong> dan password: <strong>password</strong></li>";
    echo "<li>Akses URL CAT di atas</li>";
    echo "<li>Test semua fitur CAT (navigasi, tandai ragu, dll)</li>";
    echo "<li>Cek apakah pengacakan soal berfungsi</li>";
    echo "</ol>";
    
} catch (PDOException $e) {
    echo "❌ Error database: " . $e->getMessage();
}
?>
