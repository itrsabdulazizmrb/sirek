<?php
/**
 * Script untuk membuat directory uploads dan set permissions
 */

echo "<h2>Create Upload Directories</h2>";
echo "<hr>";

// Base path
$base_path = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
$profile_path = $base_path . DIRECTORY_SEPARATOR . 'profile_pictures';
$documents_path = $base_path . DIRECTORY_SEPARATOR . 'documents';
$question_images_path = $base_path . DIRECTORY_SEPARATOR . 'gambar_soal';

echo "<h3>1. Current Directory Info</h3>";
echo "<p><strong>Current Directory:</strong> " . __DIR__ . "</p>";
echo "<p><strong>Base Upload Path:</strong> " . $base_path . "</p>";

echo "<h3>2. Creating Directories</h3>";

// Create base uploads directory
if (!is_dir($base_path)) {
    if (mkdir($base_path, 0755, true)) {
        echo "<p>✅ Created: uploads/</p>";
        chmod($base_path, 0755);
    } else {
        echo "<p>❌ Failed to create: uploads/</p>";
    }
} else {
    echo "<p>✅ Already exists: uploads/</p>";
}

// Create profile_pictures directory
if (!is_dir($profile_path)) {
    if (mkdir($profile_path, 0755, true)) {
        echo "<p>✅ Created: uploads/profile_pictures/</p>";
        chmod($profile_path, 0755);
    } else {
        echo "<p>❌ Failed to create: uploads/profile_pictures/</p>";
    }
} else {
    echo "<p>✅ Already exists: uploads/profile_pictures/</p>";
}

// Create documents directory
if (!is_dir($documents_path)) {
    if (mkdir($documents_path, 0755, true)) {
        echo "<p>✅ Created: uploads/documents/</p>";
        chmod($documents_path, 0755);
    } else {
        echo "<p>❌ Failed to create: uploads/documents/</p>";
    }
} else {
    echo "<p>✅ Already exists: uploads/documents/</p>";
}

// Create question images directory
if (!is_dir($question_images_path)) {
    if (mkdir($question_images_path, 0755, true)) {
        echo "<p>✅ Created: uploads/gambar_soal/</p>";
        chmod($question_images_path, 0755);
    } else {
        echo "<p>❌ Failed to create: uploads/gambar_soal/</p>";
    }
} else {
    echo "<p>✅ Already exists: uploads/gambar_soal/</p>";
}

echo "<h3>3. Directory Status Check</h3>";

$directories = [
    'uploads' => $base_path,
    'uploads/profile_pictures' => $profile_path,
    'uploads/documents' => $documents_path,
    'uploads/gambar_soal' => $question_images_path
];

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Directory</th><th>Exists</th><th>Writable</th><th>Permissions</th><th>Real Path</th></tr>";

foreach ($directories as $name => $path) {
    $exists = is_dir($path) ? '✅ Yes' : '❌ No';
    $writable = is_writable($path) ? '✅ Yes' : '❌ No';
    $perms = is_dir($path) ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A';
    $realpath = is_dir($path) ? realpath($path) : 'N/A';
    
    echo "<tr>";
    echo "<td>$name</td>";
    echo "<td>$exists</td>";
    echo "<td>$writable</td>";
    echo "<td>$perms</td>";
    echo "<td style='font-size: 10px;'>$realpath</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>4. Test Upload Path Configuration</h3>";

// Test CodeIgniter upload path format
$test_paths = [
    $profile_path,
    $profile_path . DIRECTORY_SEPARATOR,
    realpath($profile_path),
    realpath($profile_path) . DIRECTORY_SEPARATOR
];

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Path Format</th><th>Value</th><th>Valid</th></tr>";

foreach ($test_paths as $test_path) {
    $valid = (is_dir($test_path) && is_writable($test_path)) ? '✅ Valid' : '❌ Invalid';
    echo "<tr>";
    echo "<td>Path</td>";
    echo "<td style='font-size: 10px;'>$test_path</td>";
    echo "<td>$valid</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>5. Create .htaccess for Security</h3>";

// Create .htaccess for uploads directory
$htaccess_content = "# Prevent direct access to uploaded files
Options -Indexes
<Files ~ \"\\.(php|php3|php4|php5|phtml|pl|py|jsp|asp|sh|cgi)$\">
    Order allow,deny
    Deny from all
</Files>";

$htaccess_path = $base_path . DIRECTORY_SEPARATOR . '.htaccess';

if (file_put_contents($htaccess_path, $htaccess_content)) {
    echo "<p>✅ Created .htaccess security file</p>";
} else {
    echo "<p>❌ Failed to create .htaccess file</p>";
}

echo "<h3>6. Test File Creation</h3>";

// Test creating a dummy file
$test_file = $profile_path . DIRECTORY_SEPARATOR . 'test_' . time() . '.txt';
if (file_put_contents($test_file, 'Test file creation')) {
    echo "<p>✅ File creation test: SUCCESS</p>";
    unlink($test_file); // Clean up
} else {
    echo "<p>❌ File creation test: FAILED</p>";
}

echo "<h3>7. Recommended Upload Configuration</h3>";

echo "<div style='background: #f0f8ff; padding: 15px; border-left: 4px solid #007bff;'>";
echo "<h4>🎯 Recommended CodeIgniter Upload Config</h4>";
echo "<pre>";
echo "\$config['upload_path'] = '" . realpath($profile_path) . DIRECTORY_SEPARATOR . "';\n";
echo "\$config['allowed_types'] = 'gif|jpg|jpeg|png';\n";
echo "\$config['max_size'] = 2048; // 2MB\n";
echo "\$config['file_name'] = 'profile_' . time();\n";
echo "</pre>";
echo "</div>";

echo "<h3>8. Troubleshooting</h3>";

echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107;'>";
echo "<h4>⚠️ If Upload Still Fails</h4>";
echo "<ol>";
echo "<li><strong>Check PHP upload settings:</strong>";
echo "<ul>";
echo "<li>upload_max_filesize: " . ini_get('upload_max_filesize') . "</li>";
echo "<li>post_max_size: " . ini_get('post_max_size') . "</li>";
echo "<li>max_file_uploads: " . ini_get('max_file_uploads') . "</li>";
echo "<li>file_uploads: " . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "</li>";
echo "</ul>";
echo "</li>";
echo "<li><strong>Check directory permissions:</strong> Should be 755 or 777</li>";
echo "<li><strong>Check web server permissions:</strong> Apache/Nginx user should have write access</li>";
echo "<li><strong>Check CodeIgniter upload library:</strong> Make sure it's loaded properly</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><strong>Next Step:</strong> Test upload functionality di form tambah pengguna</p>";
echo "<p><strong>Test URL:</strong> <a href='admin/tambah_pengguna' target='_blank'>http://localhost/sirek/admin/tambah_pengguna</a></p>";
?>
