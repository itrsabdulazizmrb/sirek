<?php
/**
 * Script untuk generate data dummy penilaian
 * Jalankan file ini untuk membuat data dummy jika tabel kosong
 */

// Database configuration
$host = 'localhost';
$dbname = 'sirek_db_id';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Generate Data Dummy Penilaian</h2>";
    
    // 1. Cek data yang sudah ada
    echo "<h3>1. Mengecek data yang sudah ada...</h3>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM penilaian_pelamar");
    $existing_data = $stmt->fetch()['total'];
    echo "Data penilaian_pelamar yang sudah ada: $existing_data<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM penilaian");
    $total_penilaian = $stmt->fetch()['total'];
    echo "Total penilaian: $total_penilaian<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM lamaran_pekerjaan");
    $total_lamaran = $stmt->fetch()['total'];
    echo "Total lamaran: $total_lamaran<br>";
    
    if ($existing_data > 0) {
        echo "<div style='color: orange;'>Data penilaian_pelamar sudah ada ($existing_data records). Tidak perlu generate data dummy.</div>";
        
        // Tampilkan sample data yang ada
        echo "<h3>Sample data yang sudah ada:</h3>";
        $stmt = $pdo->query("SELECT pp.*, p.judul as penilaian_judul, u.nama_lengkap 
                            FROM penilaian_pelamar pp 
                            LEFT JOIN penilaian p ON p.id = pp.id_penilaian 
                            LEFT JOIN lamaran_pekerjaan lp ON lp.id = pp.id_lamaran 
                            LEFT JOIN pengguna u ON u.id = lp.id_pelamar 
                            LIMIT 5");
        $sample_data = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Pelamar</th><th>Penilaian</th><th>Status</th><th>Nilai</th><th>Waktu Mulai</th></tr>";
        foreach ($sample_data as $row) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['nama_lengkap']}</td>";
            echo "<td>{$row['penilaian_judul']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$row['nilai']}</td>";
            echo "<td>{$row['waktu_mulai']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        exit;
    }
    
    if ($total_penilaian == 0 || $total_lamaran == 0) {
        echo "<div style='color: red;'>Error: Tidak ada data penilaian atau lamaran. Pastikan data master sudah ada terlebih dahulu.</div>";
        exit;
    }
    
    // 2. Generate data dummy jika tidak ada
    echo "<h3>2. Membuat data dummy penilaian_pelamar...</h3>";
    
    // Ambil data penilaian dan lamaran yang ada
    $stmt = $pdo->query("SELECT id FROM penilaian LIMIT 10");
    $penilaian_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $stmt = $pdo->query("SELECT id FROM lamaran_pekerjaan LIMIT 20");
    $lamaran_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($penilaian_ids) || empty($lamaran_ids)) {
        echo "<div style='color: red;'>Error: Tidak ada data penilaian atau lamaran untuk membuat dummy data.</div>";
        exit;
    }
    
    $status_options = ['belum_mulai', 'sedang_mengerjakan', 'selesai'];
    $dummy_count = 0;
    
    // Generate 50 data dummy
    for ($i = 0; $i < 50; $i++) {
        $id_penilaian = $penilaian_ids[array_rand($penilaian_ids)];
        $id_lamaran = $lamaran_ids[array_rand($lamaran_ids)];
        $status = $status_options[array_rand($status_options)];
        
        // Generate nilai dan waktu berdasarkan status
        $nilai = null;
        $waktu_mulai = null;
        $waktu_selesai = null;
        
        if ($status == 'sedang_mengerjakan' || $status == 'selesai') {
            $waktu_mulai = date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days -' . rand(1, 12) . ' hours'));
        }
        
        if ($status == 'selesai') {
            $nilai = rand(40, 100); // Nilai antara 40-100
            $waktu_selesai = date('Y-m-d H:i:s', strtotime($waktu_mulai . ' +' . rand(30, 180) . ' minutes'));
        }
        
        $dibuat_pada = date('Y-m-d H:i:s', strtotime('-' . rand(1, 60) . ' days'));
        
        try {
            $stmt = $pdo->prepare("INSERT INTO penilaian_pelamar 
                                  (id_lamaran, id_penilaian, status, nilai, waktu_mulai, waktu_selesai, dibuat_pada, diperbarui_pada) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$id_lamaran, $id_penilaian, $status, $nilai, $waktu_mulai, $waktu_selesai, $dibuat_pada, $dibuat_pada]);
            $dummy_count++;
        } catch (PDOException $e) {
            // Skip jika ada duplicate atau error lain
            continue;
        }
    }
    
    echo "Berhasil membuat $dummy_count data dummy penilaian_pelamar<br>";
    
    // 3. Verifikasi data yang dibuat
    echo "<h3>3. Verifikasi data yang dibuat...</h3>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM penilaian_pelamar");
    $new_total = $stmt->fetch()['total'];
    echo "Total data penilaian_pelamar sekarang: $new_total<br>";
    
    $stmt = $pdo->query("SELECT status, COUNT(*) as jumlah FROM penilaian_pelamar GROUP BY status");
    $status_counts = $stmt->fetchAll();
    echo "Distribusi berdasarkan status:<br>";
    foreach ($status_counts as $row) {
        echo "- {$row['status']}: {$row['jumlah']}<br>";
    }
    
    // 4. Tampilkan sample data yang dibuat
    echo "<h3>4. Sample data yang dibuat:</h3>";
    $stmt = $pdo->query("SELECT pp.*, p.judul as penilaian_judul, u.nama_lengkap 
                        FROM penilaian_pelamar pp 
                        LEFT JOIN penilaian p ON p.id = pp.id_penilaian 
                        LEFT JOIN lamaran_pekerjaan lp ON lp.id = pp.id_lamaran 
                        LEFT JOIN pengguna u ON u.id = lp.id_pelamar 
                        ORDER BY pp.dibuat_pada DESC 
                        LIMIT 10");
    $sample_data = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Pelamar</th><th>Penilaian</th><th>Status</th><th>Nilai</th><th>Waktu Mulai</th><th>Dibuat</th></tr>";
    foreach ($sample_data as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['nama_lengkap']}</td>";
        echo "<td>{$row['penilaian_judul']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>{$row['nilai']}</td>";
        echo "<td>{$row['waktu_mulai']}</td>";
        echo "<td>{$row['dibuat_pada']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<div style='color: green; margin-top: 20px;'><strong>✅ Data dummy berhasil dibuat! Sekarang coba akses laporan penilaian lagi.</strong></div>";
    echo "<p><a href='admin/laporan_penilaian' target='_blank'>Buka Laporan Penilaian</a></p>";
    
} catch (PDOException $e) {
    echo "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
}
?>
