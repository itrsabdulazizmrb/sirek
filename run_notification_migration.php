<?php
/**
 * SIREK Notification System Migration Runner
 * 
 * This script creates the notification tables and sets up the notification system.
 * Run this file once to initialize the notification system in your SIREK database.
 * 
 * Usage: Access this file via browser: http://your-domain/run_notification_migration.php
 * Or run via command line: php run_notification_migration.php
 */

// Include CodeIgniter bootstrap
require_once 'index.php';

// Get CodeIgniter instance
$CI =& get_instance();

// Load database
$CI->load->database();

echo "<h2>SIREK Notification System Migration</h2>\n";
echo "<p>Initializing notification system...</p>\n";

try {
    // Check if tables already exist
    $tables_exist = $CI->db->table_exists('notifikasi') && $CI->db->table_exists('pengaturan_notifikasi');
    
    if ($tables_exist) {
        echo "<p style='color: orange;'>⚠️ Notification tables already exist. Skipping table creation.</p>\n";
    } else {
        echo "<p>📋 Creating notification tables...</p>\n";
        
        // Create notifications table
        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `notifikasi` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `id_pengguna` int(11) unsigned NOT NULL COMMENT 'ID pengguna yang menerima notifikasi',
                `judul` varchar(255) NOT NULL COMMENT 'Judul notifikasi',
                `pesan` text NOT NULL COMMENT 'Isi pesan notifikasi',
                `jenis` enum('lamaran_baru','status_lamaran','sistem','registrasi_pengguna','jadwal_interview','penilaian','lowongan_baru') NOT NULL DEFAULT 'sistem' COMMENT 'Jenis notifikasi',
                `prioritas` enum('rendah','normal','tinggi','urgent') NOT NULL DEFAULT 'normal' COMMENT 'Tingkat prioritas notifikasi',
                `status` enum('belum_dibaca','dibaca','diarsipkan') NOT NULL DEFAULT 'belum_dibaca' COMMENT 'Status baca notifikasi',
                `id_referensi` int(11) unsigned DEFAULT NULL COMMENT 'ID referensi ke tabel terkait (lamaran, lowongan, dll)',
                `tabel_referensi` varchar(50) DEFAULT NULL COMMENT 'Nama tabel referensi',
                `url_aksi` varchar(255) DEFAULT NULL COMMENT 'URL untuk aksi notifikasi',
                `icon` varchar(50) NOT NULL DEFAULT 'ni ni-bell-55' COMMENT 'Icon notifikasi',
                `warna` varchar(20) NOT NULL DEFAULT 'primary' COMMENT 'Warna notifikasi (primary, success, warning, danger, info)',
                `dibaca_pada` timestamp NULL DEFAULT NULL COMMENT 'Waktu notifikasi dibaca',
                `kedaluwarsa_pada` timestamp NULL DEFAULT NULL COMMENT 'Waktu kedaluwarsa notifikasi',
                `dibuat_oleh` int(11) unsigned DEFAULT NULL COMMENT 'ID pengguna yang membuat notifikasi',
                `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Waktu notifikasi dibuat',
                `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu notifikasi diperbarui',
                PRIMARY KEY (`id`),
                KEY `idx_id_pengguna` (`id_pengguna`),
                KEY `idx_jenis` (`jenis`),
                KEY `idx_status` (`status`),
                KEY `idx_prioritas` (`prioritas`),
                KEY `idx_dibuat_pada` (`dibuat_pada`),
                KEY `idx_referensi` (`id_referensi`,`tabel_referensi`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tabel notifikasi sistem'
        ");
        
        echo "<p>✅ Notifications table created successfully.</p>\n";
        
        // Create notification settings table
        $CI->db->query("
            CREATE TABLE IF NOT EXISTS `pengaturan_notifikasi` (
                `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `id_pengguna` int(11) unsigned NOT NULL,
                `jenis_notifikasi` varchar(50) NOT NULL,
                `aktif` tinyint(1) NOT NULL DEFAULT '1',
                `email_notifikasi` tinyint(1) NOT NULL DEFAULT '0',
                `whatsapp_notifikasi` tinyint(1) NOT NULL DEFAULT '0',
                `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_id_pengguna` (`id_pengguna`),
                KEY `idx_jenis_notifikasi` (`jenis_notifikasi`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Pengaturan notifikasi pengguna'
        ");
        
        echo "<p>✅ Notification settings table created successfully.</p>\n";
        
        // Add foreign key constraints (if pengguna table exists)
        if ($CI->db->table_exists('pengguna')) {
            try {
                $CI->db->query('ALTER TABLE `notifikasi` ADD CONSTRAINT `fk_notifikasi_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT');
                $CI->db->query('ALTER TABLE `notifikasi` ADD CONSTRAINT `fk_notifikasi_dibuat_oleh` FOREIGN KEY (`dibuat_oleh`) REFERENCES `pengguna` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT');
                $CI->db->query('ALTER TABLE `pengaturan_notifikasi` ADD CONSTRAINT `fk_pengaturan_notifikasi_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT');
                echo "<p>✅ Foreign key constraints added successfully.</p>\n";
            } catch (Exception $e) {
                echo "<p style='color: orange;'>⚠️ Foreign key constraints may already exist or pengguna table structure is different.</p>\n";
            }
        }
    }
    
    // Insert default notification settings for existing users
    echo "<p>👥 Setting up default notification preferences for existing users...</p>\n";
    
    $existing_users = $CI->db->get('pengguna')->result();
    $notification_types = ['lamaran_baru', 'status_lamaran', 'sistem', 'registrasi_pengguna', 'jadwal_interview', 'penilaian', 'lowongan_baru'];
    
    foreach ($existing_users as $user) {
        foreach ($notification_types as $type) {
            // Check if setting already exists
            $existing = $CI->db->where('id_pengguna', $user->id)
                             ->where('jenis_notifikasi', $type)
                             ->get('pengaturan_notifikasi')
                             ->row();
            
            if (!$existing) {
                // Determine default settings based on user role and notification type
                $aktif = 1;
                $email_notifikasi = 0;
                $whatsapp_notifikasi = 0;
                
                // Admin gets all notifications
                if ($user->peran == 'admin') {
                    $aktif = 1;
                    $email_notifikasi = ($type == 'lamaran_baru' || $type == 'sistem') ? 1 : 0;
                }
                // Pelamar gets relevant notifications
                elseif ($user->peran == 'pelamar') {
                    $aktif = in_array($type, ['status_lamaran', 'jadwal_interview', 'penilaian', 'lowongan_baru']) ? 1 : 0;
                    $email_notifikasi = ($type == 'status_lamaran' || $type == 'jadwal_interview') ? 1 : 0;
                }
                
                $CI->db->insert('pengaturan_notifikasi', [
                    'id_pengguna' => $user->id,
                    'jenis_notifikasi' => $type,
                    'aktif' => $aktif,
                    'email_notifikasi' => $email_notifikasi,
                    'whatsapp_notifikasi' => $whatsapp_notifikasi
                ]);
            }
        }
    }
    
    echo "<p>✅ Default notification settings configured for " . count($existing_users) . " users.</p>\n";
    
    // Create sample welcome notifications for admin users
    echo "<p>📬 Creating welcome notifications...</p>\n";
    
    $admin_users = $CI->db->where('peran', 'admin')->get('pengguna')->result();
    
    foreach ($admin_users as $admin) {
        // Check if welcome notification already exists
        $existing_welcome = $CI->db->where('id_pengguna', $admin->id)
                                  ->where('judul', 'Sistem Notifikasi SIREK Aktif!')
                                  ->get('notifikasi')
                                  ->row();
        
        if (!$existing_welcome) {
            $CI->db->insert('notifikasi', [
                'id_pengguna' => $admin->id,
                'judul' => 'Sistem Notifikasi SIREK Aktif!',
                'pesan' => 'Selamat! Sistem notifikasi SIREK telah berhasil diaktifkan. Anda akan menerima notifikasi untuk lamaran baru, update status, dan aktivitas sistem lainnya.',
                'jenis' => 'sistem',
                'prioritas' => 'normal',
                'icon' => 'ni ni-check-bold',
                'warna' => 'success',
                'url_aksi' => 'admin/notifikasi',
                'dibuat_oleh' => null
            ]);
        }
    }
    
    echo "<p>✅ Welcome notifications created for admin users.</p>\n";
    
    // Success message
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>🎉 Migration Completed Successfully!</h3>";
    echo "<p><strong>The SIREK notification system has been successfully initialized.</strong></p>";
    echo "<ul>";
    echo "<li>✅ Notification tables created</li>";
    echo "<li>✅ Default settings configured</li>";
    echo "<li>✅ Welcome notifications sent</li>";
    echo "<li>✅ System ready for use</li>";
    echo "</ul>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ol>";
    echo "<li>Access the admin panel: <a href='" . base_url('admin/notifikasi') . "' target='_blank'>Admin → Notifikasi</a></li>";
    echo "<li>Test the notification system by creating a new job application</li>";
    echo "<li>Configure notification settings for users as needed</li>";
    echo "<li>Delete this migration file for security: <code>run_notification_migration.php</code></li>";
    echo "</ol>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>❌ Migration Failed</h3>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p>Please check your database connection and try again.</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><small>Migration completed at: " . date('Y-m-d H:i:s') . "</small></p>";
echo "<p><small><strong>Important:</strong> Please delete this file after successful migration for security reasons.</small></p>";
?>
