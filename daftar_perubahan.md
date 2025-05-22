# Daftar Perubahan Nama File dan Function dari Bahasa Inggris ke Bahasa Indonesia

## Perubahan Nama File Controller

1. `Home.php` → `Beranda.php`
2. `Applicant.php` → `Pelamar.php`
3. `Import.php` → `Impor.php`
4. `Auth.php` → `Auth.php` (tetap)

## Perubahan Nama File Model

1. `User_model.php` → `Model_Pengguna.php`
2. `Job_model.php` → `Model_Lowongan.php`
3. `Application_model.php` → `Model_Lamaran.php`
4. `Applicant_model.php` → `Model_Pelamar.php`
5. `Assessment_model.php` → `Model_Penilaian.php`
6. `Blog_model.php` → `Model_Blog.php`
7. `Category_model.php` → `Model_Kategori.php`

## Perubahan Nama Function di Controller Beranda (Home)

1. `index()` → `index()` (tetap)
2. `jobs()` → `lowongan()`
3. `job_detail($id)` → `detail_lowongan($id)`
4. `blog()` → `blog()` (tetap)
5. `post($slug)` → `artikel($slug)`
6. `about()` → `tentang()`
7. `contact()` → `kontak()`
8. `search()` → `cari()`

## Perubahan Nama Function di Controller Pelamar (Applicant)

1. `index()` → `index()` (tetap)
2. `dashboard()` → `dasbor()`
3. `profile()` → `profil()`
4. `applications()` → `lamaran()`
5. `apply($job_id)` → `lamar($job_id)`
6. `application_detail($id)` → `detail_lamaran($id)`
7. `assessments()` → `penilaian()`
8. `take_assessment($assessment_id, $application_id)` → `ikuti_penilaian($assessment_id, $application_id)`
9. `submit_assessment()` → `kirim_penilaian()`
10. `change_password()` → `ubah_password()`

## Perubahan Nama Function di Controller Admin

1. `index()` → `index()` (tetap)
2. `dashboard()` → `dasbor()`
3. `jobs()` → `lowongan()`
4. `add_job()` → `tambah_lowongan()`
5. `edit_job($id)` → `edit_lowongan($id)`
6. `delete_job($id)` → `hapus_lowongan($id)`
7. `applications()` → `lamaran()`
8. `application_detail($id)` → `detail_lamaran($id)`
9. `assessments()` → `penilaian()`
10. `add_assessment()` → `tambah_penilaian()`
11. `edit_assessment($id)` → `edit_penilaian($id)`
12. `delete_assessment($id)` → `hapus_penilaian($id)`
13. `assessment_questions($id)` → `soal_penilaian($id)`
14. `add_question($assessment_id)` → `tambah_soal($assessment_id)`
15. `edit_question($id)` → `edit_soal($id)`
16. `delete_question($id)` → `hapus_soal($id)`
17. `reports()` → `laporan()`
18. `users()` → `pengguna()`
19. `add_user()` → `tambah_pengguna()`
20. `edit_user($id)` → `edit_pengguna($id)`
21. `delete_user($id)` → `hapus_pengguna($id)`
22. `activate_user($id)` → `aktifkan_pengguna($id)`
23. `deactivate_user($id)` → `nonaktifkan_pengguna($id)`
24. `reset_password($id)` → `reset_kata_sandi($id)`
25. `applicant_profile($id)` → `profil_pelamar($id)`
26. `applicant_applications($id)` → `lamaran_pelamar($id)`
27. `blog()` → `blog()` (tetap)
28. `add_post()` → `tambah_artikel()`
29. `edit_post($id)` → `edit_artikel($id)`
30. `delete_post($id)` → `hapus_artikel($id)`
31. `publish_post($id)` → `publikasi_artikel($id)`
32. `unpublish_post($id)` → `batalkan_publikasi_artikel($id)`
33. `add_blog_category()` → `tambah_kategori_blog()`
34. `edit_blog_category()` → `edit_kategori_blog()`
35. `delete_blog_category($id)` → `hapus_kategori_blog($id)`

## Perubahan Nama Function di Controller Impor (Import)

1. `index()` → `index()` (tetap)
2. `download_template($assessment_id)` → `unduh_template($assessment_id)`
3. `process()` → `proses()`

## Perubahan Nama Function di Controller Auth

1. `index()` → `index()` (tetap)
2. `login()` → `login()` (tetap)
3. `register()` → `daftar()`
4. `forgot_password()` → `lupa_password()`
5. `reset_password($token)` → `reset_password($token)` (tetap)
6. `logout()` → `logout()` (tetap)

## Perubahan Nama Function di Model_Pengguna (User_model)

1. `get_all_users()` → `dapatkan_pengguna_semua()`
2. `get_user($id)` → `dapatkan_pengguna($id)`
3. `get_user_by_username($username)` → `dapatkan_pengguna_dari_username($username)`
4. `get_user_by_email($email)` → `dapatkan_pengguna_dari_email($email)`
5. `add_user($data)` → `tambah_pengguna($data)`
6. `update_user($id, $data)` → `perbarui_pengguna($id, $data)`
7. `delete_user($id)` → `hapus_pengguna($id)`
8. `update_password($id, $password)` → `perbarui_password($id, $password)`
9. `count_users()` → `hitung_pengguna()`
10. `count_users_by_role($role)` → `hitung_pengguna_berdasarkan_role($role)`
11. `count_applicants()` → `hitung_pelamar()`
12. `get_users_paginated($limit, $start)` → `dapatkan_pengguna_paginasi($limit, $start)`
13. `search_users($keyword)` → `cari_pengguna($keyword)`
14. `filter_users_by_role($role)` → `filter_pengguna_berdasarkan_role($role)`
15. `filter_users_by_status($status)` → `filter_pengguna_berdasarkan_status($status)`
16. `get_monthly_user_stats($year)` → `dapatkan_statistik_pengguna_bulanan($year)`
17. `verify_login($username, $password)` → `verifikasi_login($username, $password)`

## Perubahan Nama Function di Model_Lowongan (Job_model)

1. `get_all_jobs()` → `dapatkan_lowongan_semua()`
2. `get_job($id)` → `dapatkan_lowongan($id)`
3. `add_job($data)` → `tambah_lowongan($data)`
4. `update_job($id, $data)` → `perbarui_lowongan($id, $data)`
5. `delete_job($id)` → `hapus_lowongan($id)`
6. `count_jobs()` → `hitung_lowongan()`
7. `count_active_jobs()` → `hitung_lowongan_aktif()`
8. `get_active_jobs($limit, $start)` → `dapatkan_lowongan_aktif($limit, $start)`
9. `get_featured_jobs($limit)` → `dapatkan_lowongan_unggulan($limit)`
10. `get_related_jobs($id, $category_id, $limit)` → `dapatkan_lowongan_terkait($id, $category_id, $limit)`
11. `get_recommended_jobs($user_id, $limit)` → `dapatkan_lowongan_rekomendasi($user_id, $limit)`
12. `search_jobs($keyword)` → `cari_lowongan($keyword)`

## Perubahan Nama Function di Model_Lamaran (Application_model)

1. `get_all_applications()` → `dapatkan_lamaran_semua()`
2. `get_application($id)` → `dapatkan_lamaran($id)`
3. `get_applicant_applications($user_id)` → `dapatkan_lamaran_pelamar($user_id)`
4. `get_job_applications($job_id)` → `dapatkan_lamaran_lowongan($job_id)`
5. `add_application($data)` → `tambah_lamaran($data)`
6. `update_status($id, $status)` → `perbarui_status($id, $status)`
7. `delete_application($id)` → `hapus_lamaran($id)`
8. `has_applied($user_id, $job_id)` → `sudah_melamar($user_id, $job_id)`
9. `count_applications()` → `hitung_lamaran()`
10. `count_new_applications()` → `hitung_lamaran_baru()`
11. `get_latest_applications($limit)` → `dapatkan_lamaran_terbaru($limit)`
12. `get_monthly_application_stats($year)` → `dapatkan_statistik_lamaran_bulanan($year)`
13. `count_applications_by_job($job_id)` → `hitung_lamaran_berdasarkan_lowongan($job_id)`
14. `get_applications_paginated($limit, $start)` → `dapatkan_lamaran_paginasi($limit, $start)`
15. `filter_applications_by_status($status)` → `filter_lamaran_berdasarkan_status($status)`

## Perubahan Nama Function di Model_Pelamar (Applicant_model)

1. `get_profile($user_id)` → `dapatkan_profil($user_id)`
2. `create_profile($user_id)` → `buat_profil($user_id)`
3. `add_profile($data)` → `tambah_profil($data)`
4. `update_profile($user_id, $data)` → `perbarui_profil($user_id, $data)`
5. `delete_profile($user_id)` → `hapus_profil($user_id)`
6. `get_profile_completion_percentage($user_id)` → `dapatkan_persentase_kelengkapan_profil($user_id)`
7. `get_applicants_by_skills($skills)` → `dapatkan_pelamar_berdasarkan_skills($skills)`
8. `get_applicants_by_education($education)` → `dapatkan_pelamar_berdasarkan_pendidikan($education)`
9. `get_applicants_by_experience($experience)` → `dapatkan_pelamar_berdasarkan_pengalaman($experience)`
10. `search_applicants($keyword)` → `cari_pelamar($keyword)`
11. `get_applicants_paginated($limit, $start)` → `dapatkan_pelamar_paginasi($limit, $start)`
12. `count_applicants()` → `hitung_pelamar()`

## Perubahan Nama Function di Model_Penilaian (Assessment_model)

1. `get_assessments()` → `dapatkan_penilaian()`
2. `get_assessment($id)` → `dapatkan_penilaian($id)`
3. `add_assessment($data)` → `tambah_penilaian($data)`
4. `update_assessment($id, $data)` → `perbarui_penilaian($id, $data)`
5. `delete_assessment($id)` → `hapus_penilaian($id)`
6. `get_assessment_types()` → `dapatkan_jenis_penilaian()`
7. `get_assessment_questions($assessment_id)` → `dapatkan_soal_penilaian($assessment_id)`
8. `get_question($id)` → `dapatkan_soal($id)`
9. `add_question($data)` → `tambah_soal($data)`
10. `update_question($id, $data)` → `perbarui_soal($id, $data)`
11. `delete_question($id)` → `hapus_soal($id)`
12. `get_question_options($question_id)` → `dapatkan_opsi_soal($question_id)`
13. `add_question_option($data)` → `tambah_opsi_soal($data)`
14. `update_question_option($id, $data)` → `perbarui_opsi_soal($id, $data)`
15. `delete_question_option($id)` → `hapus_opsi_soal($id)`
16. `get_applicant_assessments($application_id)` → `dapatkan_penilaian_pelamar($application_id)`
17. `get_specific_applicant_assessment($application_id, $assessment_id)` → `dapatkan_penilaian_pelamar_spesifik($application_id, $assessment_id)`
18. `get_all_applicant_assessments($user_id)` → `dapatkan_semua_penilaian_pelamar($user_id)`
19. `add_applicant_assessment($data)` → `tambah_penilaian_pelamar($data)`
20. `update_applicant_assessment_status($id, $status)` → `perbarui_status_penilaian_pelamar($id, $status)`
21. `add_applicant_answer($data)` → `tambah_jawaban_pelamar($data)`
22. `get_applicant_answers($applicant_assessment_id)` → `dapatkan_jawaban_pelamar($applicant_assessment_id)`
23. `calculate_applicant_assessment_score($applicant_assessment_id)` → `hitung_skor_penilaian_pelamar($applicant_assessment_id)`
24. `assign_assessment_to_job($job_id, $assessment_id)` → `tetapkan_penilaian_ke_lowongan($job_id, $assessment_id)`
25. `remove_assessment_from_job($job_id, $assessment_id)` → `hapus_penilaian_dari_lowongan($job_id, $assessment_id)`

## Perubahan Nama Function di Model_Blog (Blog_model)

1. `get_all_posts()` → `dapatkan_artikel_semua()`
2. `get_post($id)` → `dapatkan_artikel($id)`
3. `get_post_by_slug($slug)` → `dapatkan_artikel_dari_slug($slug)`
4. `add_post($data)` → `tambah_artikel($data)`
5. `update_post($id, $data)` → `perbarui_artikel($id, $data)`
6. `delete_post($id)` → `hapus_artikel($id)`
7. `get_latest_posts($limit)` → `dapatkan_artikel_terbaru($limit)`
8. `get_published_posts($limit, $start)` → `dapatkan_artikel_terpublikasi($limit, $start)`
9. `count_published_posts()` → `hitung_artikel_terpublikasi()`
10. `count_posts()` → `hitung_artikel()`
11. `add_post_category($post_id, $category_id)` → `tambah_kategori_artikel($post_id, $category_id)`
12. `delete_all_post_categories($post_id)` → `hapus_semua_kategori_artikel($post_id)`
13. `get_post_categories($post_id)` → `dapatkan_kategori_artikel($post_id)`
14. `get_posts_by_category($category_id, $limit)` → `dapatkan_artikel_berdasarkan_kategori($category_id, $limit)`
15. `get_related_posts($post_id, $limit)` → `dapatkan_artikel_terkait($post_id, $limit)`
16. `increment_views($post_id)` → `tambah_dilihat($post_id)`
17. `search_posts($keyword)` → `cari_artikel($keyword)`

## Perubahan Nama Function di Model_Kategori (Category_model)

1. `get_job_categories()` → `dapatkan_kategori_lowongan()`
2. `get_job_category($id)` → `dapatkan_kategori_lowongan_dari_id($id)`
3. `add_job_category($data)` → `tambah_kategori_lowongan($data)`
4. `update_job_category($id, $data)` → `perbarui_kategori_lowongan($id, $data)`
5. `delete_job_category($id)` → `hapus_kategori_lowongan($id)`
6. `get_blog_categories()` → `dapatkan_kategori_blog()`
7. `get_blog_category($id)` → `dapatkan_kategori_blog_dari_id($id)`
8. `add_blog_category($data)` → `tambah_kategori_blog($data)`
9. `update_blog_category($id, $data)` → `perbarui_kategori_blog($id, $data)`
10. `delete_blog_category($id)` → `hapus_kategori_blog($id)`
11. `count_jobs_by_category($category_id)` → `hitung_lowongan_berdasarkan_kategori($category_id)`
12. `count_posts_by_category($category_id)` → `hitung_artikel_berdasarkan_kategori($category_id)`
13. `get_job_categories_with_count()` → `dapatkan_kategori_lowongan_dengan_jumlah()`
14. `get_blog_categories_with_count()` → `dapatkan_kategori_blog_dengan_jumlah()`
