<?php
// Load CodeIgniter environment
include_once 'index.php';

// Get CI instance
$CI =& get_instance();

// Load model
$CI->load->model('model_lowongan');

// Get all jobs
$all_jobs = $CI->model_lowongan->dapatkan_lowongan_semua();
echo "Total lowongan: " . count($all_jobs) . "\n";

// Get active jobs
$active_jobs = $CI->db->where('status', 'aktif')->get('lowongan_pekerjaan')->result();
echo "Lowongan aktif: " . count($active_jobs) . "\n";

// Print active jobs
if (count($active_jobs) > 0) {
    echo "\nDaftar lowongan aktif:\n";
    foreach ($active_jobs as $job) {
        echo "- ID: {$job->id}, Judul: {$job->judul}, Status: {$job->status}\n";
    }
} else {
    echo "\nTidak ada lowongan aktif.\n";
}

// Print all jobs
echo "\nDaftar semua lowongan:\n";
foreach ($all_jobs as $job) {
    echo "- ID: {$job->id}, Judul: {$job->judul}, Status: {$job->status}\n";
}
