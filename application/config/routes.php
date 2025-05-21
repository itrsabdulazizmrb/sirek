<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'beranda';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Rute untuk halaman publik
$route['lowongan'] = 'beranda/lowongan';
$route['lowongan/(:num)'] = 'beranda/detail_lowongan/$1';
$route['blog'] = 'beranda/blog';
$route['blog/(:any)'] = 'beranda/artikel/$1';
$route['tentang'] = 'beranda/tentang';
$route['kontak'] = 'beranda/kontak';
$route['cari'] = 'beranda/cari';

// Rute untuk otentikasi
$route['login'] = 'otentikasi/login';
$route['daftar'] = 'otentikasi/daftar';
$route['logout'] = 'otentikasi/logout';
$route['lupa-password'] = 'otentikasi/lupa_password';
$route['reset-password/(:any)'] = 'otentikasi/reset_password/$1';

// Rute untuk pelamar
$route['pelamar/dasbor'] = 'pelamar/dasbor';
$route['pelamar/profil'] = 'pelamar/profil';
$route['pelamar/lamaran'] = 'pelamar/lamaran';
$route['pelamar/lamar/(:num)'] = 'pelamar/lamar/$1';
$route['pelamar/detail-lamaran/(:num)'] = 'pelamar/detail_lamaran/$1';
$route['pelamar/penilaian'] = 'pelamar/penilaian';
$route['pelamar/ikuti-penilaian/(:num)/(:num)'] = 'pelamar/ikuti_penilaian/$1/$2';
$route['pelamar/kirim-penilaian'] = 'pelamar/kirim_penilaian';
$route['pelamar/ubah-password'] = 'pelamar/ubah_password';

// Rute untuk admin
$route['admin'] = 'admin/index';
$route['admin/dasbor'] = 'admin/dasbor';
$route['admin/pengguna'] = 'admin/pengguna';
$route['admin/tambah-pengguna'] = 'admin/tambah_pengguna';
$route['admin/edit-pengguna/(:num)'] = 'admin/edit_pengguna/$1';
$route['admin/hapus-pengguna/(:num)'] = 'admin/hapus_pengguna/$1';
$route['admin/lowongan'] = 'admin/lowongan';
$route['admin/tambah-lowongan'] = 'admin/tambah_lowongan';
$route['admin/edit-lowongan/(:num)'] = 'admin/edit_lowongan/$1';
$route['admin/hapus-lowongan/(:num)'] = 'admin/hapus_lowongan/$1';
$route['admin/lamaran'] = 'admin/lamaran';
$route['admin/detail-lamaran/(:num)'] = 'admin/detail_lamaran/$1';
$route['admin/penilaian'] = 'admin/penilaian';
$route['admin/tambah-penilaian'] = 'admin/tambah_penilaian';
$route['admin/edit-penilaian/(:num)'] = 'admin/edit_penilaian/$1';
$route['admin/hapus-penilaian/(:num)'] = 'admin/hapus_penilaian/$1';
$route['admin/soal-penilaian/(:num)'] = 'admin/soal_penilaian/$1';
$route['admin/tambah-soal/(:num)'] = 'admin/tambah_soal/$1';
$route['admin/edit-soal/(:num)'] = 'admin/edit_soal/$1';
$route['admin/hapus-soal/(:num)'] = 'admin/hapus_soal/$1';
$route['admin/blog'] = 'admin/blog';
$route['admin/tambah-artikel'] = 'admin/tambah_artikel';
$route['admin/edit-artikel/(:num)'] = 'admin/edit_artikel/$1';
$route['admin/hapus-artikel/(:num)'] = 'admin/hapus_artikel/$1';
$route['admin/kategori'] = 'admin/kategori';
$route['admin/laporan'] = 'admin/laporan';

// Rute untuk impor
$route['impor'] = 'impor/index';
$route['impor/unduh-template/(:num)'] = 'impor/unduh_template/$1';
$route['impor/proses'] = 'impor/proses';
