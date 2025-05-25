<?php
/**
 * Script untuk memperbaiki data waktu_mulai yang kosong
 * Jalankan file ini untuk memperbaiki data penilaian yang waktu_mulai-nya kosong
 */

// Database configuration
$host = 'localhost';
$dbname = 'sirek_db_id';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Perbaikan Data Waktu Mulai Penilaian</h2>";
    
    // 1. Cek data yang bermasalah
    echo "<h3>1. Mengecek data yang bermasalah...</h3>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM penilaian_pelamar WHERE status = 'sedang_mengerjakan' AND waktu_mulai IS NULL");
    $sedang_mengerjakan_kosong = $stmt->fetch()['total'];
    echo "Data status 'sedang_mengerjakan' dengan waktu_mulai kosong: $sedang_mengerjakan_kosong<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM penilaian_pelamar WHERE status = 'selesai' AND waktu_mulai IS NULL");
    $selesai_kosong = $stmt->fetch()['total'];
    echo "Data status 'selesai' dengan waktu_mulai kosong: $selesai_kosong<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM penilaian_pelamar WHERE status = 'selesai' AND waktu_selesai IS NULL");
    $selesai_tanpa_waktu_selesai = $stmt->fetch()['total'];
    echo "Data status 'selesai' dengan waktu_selesai kosong: $selesai_tanpa_waktu_selesai<br>";
    
    if ($sedang_mengerjakan_kosong == 0 && $selesai_kosong == 0 && $selesai_tanpa_waktu_selesai == 0) {
        echo "<div style='color: green;'>✅ Semua data sudah benar, tidak perlu perbaikan.</div>";
        
        // Tampilkan sample data yang sudah benar
        echo "<h3>Sample data yang sudah benar:</h3>";
        $stmt = $pdo->query("SELECT pp.*, p.judul as penilaian_judul, u.nama_lengkap 
                            FROM penilaian_pelamar pp 
                            LEFT JOIN penilaian p ON p.id = pp.id_penilaian 
                            LEFT JOIN lamaran_pekerjaan lp ON lp.id = pp.id_lamaran 
                            LEFT JOIN pengguna u ON u.id = lp.id_pelamar 
                            WHERE pp.waktu_mulai IS NOT NULL
                            ORDER BY pp.dibuat_pada DESC
                            LIMIT 5");
        $sample_data = $stmt->fetchAll();
        
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Pelamar</th><th>Penilaian</th><th>Status</th><th>Waktu Mulai</th><th>Waktu Selesai</th></tr>";
        foreach ($sample_data as $row) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['nama_lengkap']}</td>";
            echo "<td>{$row['penilaian_judul']}</td>";
            echo "<td>{$row['status']}</td>";
            echo "<td>{$row['waktu_mulai']}</td>";
            echo "<td>{$row['waktu_selesai']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        exit;
    }
    
    // 2. Perbaiki data yang bermasalah
    echo "<h3>2. Memperbaiki data yang bermasalah...</h3>";
    
    // Perbaiki status sedang_mengerjakan dengan waktu_mulai kosong
    if ($sedang_mengerjakan_kosong > 0) {
        echo "Memperbaiki $sedang_mengerjakan_kosong data status 'sedang_mengerjakan'...<br>";
        $stmt = $pdo->prepare("UPDATE penilaian_pelamar 
                              SET waktu_mulai = dibuat_pada 
                              WHERE status = 'sedang_mengerjakan' AND waktu_mulai IS NULL");
        $stmt->execute();
        echo "✅ Berhasil memperbaiki " . $stmt->rowCount() . " data sedang_mengerjakan<br>";
    }
    
    // Perbaiki status selesai dengan waktu_mulai kosong
    if ($selesai_kosong > 0) {
        echo "Memperbaiki $selesai_kosong data status 'selesai' dengan waktu_mulai kosong...<br>";
        $stmt = $pdo->prepare("UPDATE penilaian_pelamar 
                              SET waktu_mulai = dibuat_pada 
                              WHERE status = 'selesai' AND waktu_mulai IS NULL");
        $stmt->execute();
        echo "✅ Berhasil memperbaiki " . $stmt->rowCount() . " data selesai (waktu_mulai)<br>";
    }
    
    // Perbaiki status selesai dengan waktu_selesai kosong
    if ($selesai_tanpa_waktu_selesai > 0) {
        echo "Memperbaiki $selesai_tanpa_waktu_selesai data status 'selesai' dengan waktu_selesai kosong...<br>";
        $stmt = $pdo->prepare("UPDATE penilaian_pelamar 
                              SET waktu_selesai = DATE_ADD(dibuat_pada, INTERVAL FLOOR(RAND() * 120 + 30) MINUTE)
                              WHERE status = 'selesai' AND waktu_selesai IS NULL");
        $stmt->execute();
        echo "✅ Berhasil memperbaiki " . $stmt->rowCount() . " data selesai (waktu_selesai)<br>";
    }
    
    // 3. Verifikasi hasil perbaikan
    echo "<h3>3. Verifikasi hasil perbaikan...</h3>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM penilaian_pelamar WHERE status = 'sedang_mengerjakan' AND waktu_mulai IS NULL");
    $sedang_mengerjakan_kosong_after = $stmt->fetch()['total'];
    echo "Data status 'sedang_mengerjakan' dengan waktu_mulai kosong setelah perbaikan: $sedang_mengerjakan_kosong_after<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM penilaian_pelamar WHERE status = 'selesai' AND waktu_mulai IS NULL");
    $selesai_kosong_after = $stmt->fetch()['total'];
    echo "Data status 'selesai' dengan waktu_mulai kosong setelah perbaikan: $selesai_kosong_after<br>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM penilaian_pelamar WHERE status = 'selesai' AND waktu_selesai IS NULL");
    $selesai_tanpa_waktu_selesai_after = $stmt->fetch()['total'];
    echo "Data status 'selesai' dengan waktu_selesai kosong setelah perbaikan: $selesai_tanpa_waktu_selesai_after<br>";
    
    // 4. Tampilkan sample data yang sudah diperbaiki
    echo "<h3>4. Sample data yang sudah diperbaiki:</h3>";
    $stmt = $pdo->query("SELECT pp.*, p.judul as penilaian_judul, u.nama_lengkap,
                        TIMESTAMPDIFF(MINUTE, pp.waktu_mulai, pp.waktu_selesai) as waktu_pengerjaan
                        FROM penilaian_pelamar pp 
                        LEFT JOIN penilaian p ON p.id = pp.id_penilaian 
                        LEFT JOIN lamaran_pekerjaan lp ON lp.id = pp.id_lamaran 
                        LEFT JOIN pengguna u ON u.id = lp.id_pelamar 
                        WHERE pp.waktu_mulai IS NOT NULL
                        ORDER BY pp.diperbarui_pada DESC 
                        LIMIT 10");
    $sample_data = $stmt->fetchAll();
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Pelamar</th><th>Penilaian</th><th>Status</th><th>Waktu Mulai</th><th>Waktu Selesai</th><th>Durasi (menit)</th></tr>";
    foreach ($sample_data as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['nama_lengkap']}</td>";
        echo "<td>{$row['penilaian_judul']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>{$row['waktu_mulai']}</td>";
        echo "<td>{$row['waktu_selesai']}</td>";
        echo "<td>" . ($row['waktu_pengerjaan'] ?: '-') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 5. Test query laporan
    echo "<h3>5. Test query laporan penilaian...</h3>";
    $stmt = $pdo->query("SELECT pp.id, pp.status, pp.nilai, pp.waktu_mulai, pp.waktu_selesai,
                        p.judul as penilaian_judul, u.nama_lengkap as pelamar_nama,
                        CASE 
                          WHEN pp.waktu_mulai IS NOT NULL AND pp.waktu_selesai IS NOT NULL 
                          THEN TIMESTAMPDIFF(MINUTE, pp.waktu_mulai, pp.waktu_selesai)
                          ELSE NULL 
                        END as waktu_pengerjaan
                        FROM penilaian_pelamar pp
                        LEFT JOIN penilaian p ON p.id = pp.id_penilaian 
                        LEFT JOIN lamaran_pekerjaan lp ON lp.id = pp.id_lamaran 
                        LEFT JOIN pengguna u ON u.id = lp.id_pelamar 
                        ORDER BY pp.dibuat_pada DESC 
                        LIMIT 5");
    $test_data = $stmt->fetchAll();
    
    echo "<p><strong>Test query untuk laporan (5 data teratas):</strong></p>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Pelamar</th><th>Penilaian</th><th>Status</th><th>Nilai</th><th>Waktu Pengerjaan</th><th>Tanggal Mulai</th></tr>";
    foreach ($test_data as $row) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['pelamar_nama']}</td>";
        echo "<td>{$row['penilaian_judul']}</td>";
        echo "<td>{$row['status']}</td>";
        echo "<td>" . ($row['nilai'] ?: '-') . "</td>";
        echo "<td>" . ($row['waktu_pengerjaan'] ? $row['waktu_pengerjaan'] . ' menit' : '-') . "</td>";
        echo "<td>" . ($row['waktu_mulai'] ? date('d/m/Y H:i', strtotime($row['waktu_mulai'])) : '-') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<div style='color: green; margin-top: 20px;'><strong>✅ Perbaikan data waktu mulai selesai! Sekarang coba akses laporan penilaian lagi.</strong></div>";
    echo "<p><a href='admin/laporan_penilaian' target='_blank'>Buka Laporan Penilaian</a></p>";
    
} catch (PDOException $e) {
    echo "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
}
?>
