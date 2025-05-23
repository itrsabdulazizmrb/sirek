<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header pb-0">
        <div class="d-flex align-items-center">
          <p class="mb-0">Profil Pelamar</p>
          <a href="<?= base_url('admin/lamaran_pelamar/' . $user->id) ?>" class="btn btn-primary btn-sm ms-auto">Lihat Lamaran</a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Nama Lengkap</label>
              <p class="form-control-static"><?= $user->nama_lengkap ?? 'Tidak tersedia' ?></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Email</label>
              <p class="form-control-static"><?= $user->email ?? 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Nomor Telepon</label>
              <p class="form-control-static"><?= isset($user->telepon) ? $user->telepon : 'Tidak tersedia' ?></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Tanggal Lahir</label>
              <p class="form-control-static"><?= isset($profile->tanggal_lahir) ? date('d F Y', strtotime($profile->tanggal_lahir)) : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Jenis Kelamin</label>
              <p class="form-control-static"><?= isset($profile->jenis_kelamin) ? ucfirst($profile->jenis_kelamin) : 'Tidak tersedia' ?></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Alamat</label>
              <p class="form-control-static"><?= isset($user->alamat) ? $user->alamat : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <hr class="horizontal dark">
        <p class="text-uppercase text-sm">Informasi Pendidikan</p>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-control-label">Pendidikan</label>
              <p class="form-control-static"><?= isset($profile->pendidikan) ? nl2br($profile->pendidikan) : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <hr class="horizontal dark">
        <p class="text-uppercase text-sm">Pengalaman Kerja</p>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-control-label">Pengalaman Kerja</label>
              <p class="form-control-static"><?= isset($profile->pengalaman) ? nl2br($profile->pengalaman) : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <hr class="horizontal dark">
        <p class="text-uppercase text-sm">Keterampilan & Dokumen</p>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-control-label">Keterampilan</label>
              <p class="form-control-static"><?= isset($profile->keahlian) ? $profile->keahlian : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Resume/CV</label>
              <?php if (isset($profile->cv) && $profile->cv) : ?>
                <p class="form-control-static">
                  <a href="<?= base_url('uploads/resumes/' . $profile->cv) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-file-pdf me-2"></i> Lihat Resume
                  </a>
                </p>
              <?php else : ?>
                <p class="form-control-static">Tidak tersedia</p>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Portfolio URL</label>
              <?php if (isset($profile->url_portofolio) && $profile->url_portofolio) : ?>
                <p class="form-control-static">
                  <a href="<?= $profile->url_portofolio ?>" target="_blank" class="text-primary">
                    <?= $profile->url_portofolio ?>
                  </a>
                </p>
              <?php else : ?>
                <p class="form-control-static">Tidak tersedia</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">LinkedIn URL</label>
              <?php if (isset($profile->url_linkedin) && $profile->url_linkedin) : ?>
                <p class="form-control-static">
                  <a href="<?= $profile->url_linkedin ?>" target="_blank" class="text-primary">
                    <?= $profile->url_linkedin ?>
                  </a>
                </p>
              <?php else : ?>
                <p class="form-control-static">Tidak tersedia</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <hr class="horizontal dark">
        <p class="text-uppercase text-sm">Dokumen Pendukung</p>
        <div class="row">
          <?php if (isset($documents) && !empty($documents)) : ?>
            <?php foreach ($documents as $doc) : ?>
              <div class="col-md-6 mb-3">
                <div class="form-group">
                  <label class="form-control-label"><?= $doc->nama_dokumen ?></label>
                  <p class="form-control-static">
                    <?php
                    $file_path = '';
                    $icon_class = 'fas fa-file';

                    // Determine file path and icon based on document type
                    if ($doc->jenis_dokumen == 'cv') {
                        $file_path = base_url('uploads/resumes/' . $doc->nama_file);
                        $icon_class = 'fas fa-file-pdf';
                    } else {
                        $file_path = base_url('uploads/documents/' . $doc->nama_file);

                        // Set icon based on file type
                        if (strpos($doc->tipe_file, 'pdf') !== false) {
                            $icon_class = 'fas fa-file-pdf';
                        } elseif (strpos($doc->tipe_file, 'image') !== false) {
                            $icon_class = 'fas fa-file-image';
                        } elseif (strpos($doc->tipe_file, 'word') !== false || strpos($doc->tipe_file, 'document') !== false) {
                            $icon_class = 'fas fa-file-word';
                        }
                    }
                    ?>
                    <a href="<?= $file_path ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                      <i class="<?= $icon_class ?> me-2"></i> Lihat <?= $doc->nama_dokumen ?>
                    </a>
                    <small class="text-muted d-block mt-1">
                      Ukuran: <?= number_format($doc->ukuran_file / 1024, 2) ?> KB
                    </small>
                  </p>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else : ?>
            <div class="col-md-12">
              <p class="form-control-static text-muted">Belum ada dokumen yang diunggah</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-profile">
      <img src="<?= base_url('assets/img/bg-profile.jpg') ?>" alt="Profile Cover" class="card-img-top">
      <div class="row justify-content-center">
        <div class="col-4 col-lg-4 order-lg-2">
          <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
            <a href="javascript:;">
              <img src="<?= $user->foto_profil ? base_url('uploads/profile_pictures/' . $user->foto_profil) : base_url('assets/img/team-2.jpg') ?>" class="rounded-circle img-fluid border border-2 border-white" style="width: 100px; height: 100px; object-fit: cover;">
            </a>
          </div>
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="text-center mt-4">
          <h5><?= $user->nama_lengkap ?? 'Tidak tersedia' ?></h5>
          <div class="h6 font-weight-300">
            <i class="ni location_pin mr-2"></i><?= $user->email ?? 'Tidak tersedia' ?>
          </div>
          <div class="h6 mt-2">
            <i class="ni business_briefcase-24 mr-2"></i>Pelamar
          </div>
          <div>
            <i class="ni education_hat mr-2"></i>Terdaftar sejak: <?= isset($user->dibuat_pada) ? date('d M Y', strtotime($user->dibuat_pada)) : 'Tidak tersedia' ?>
          </div>
        </div>
        <div class="d-flex justify-content-center mt-4">
          <a href="<?= base_url('admin/edit_pengguna/' . $user->id) ?>" class="btn btn-sm btn-primary me-2">
            <i class="fas fa-edit me-2"></i>Edit Pengguna
          </a>
          <a href="<?= base_url('admin/lamaran_pelamar/' . $user->id) ?>" class="btn btn-sm btn-info">
            <i class="fas fa-list me-2"></i>Lamaran
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
