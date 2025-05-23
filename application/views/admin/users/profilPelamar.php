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
              <p class="form-control-static"><?= $user->full_name ?></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Email</label>
              <p class="form-control-static"><?= $user->email ?></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Nomor Telepon</label>
              <p class="form-control-static"><?= isset($profile->phone) ? $profile->phone : 'Tidak tersedia' ?></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Tanggal Lahir</label>
              <p class="form-control-static"><?= isset($profile->birth_date) ? date('d F Y', strtotime($profile->birth_date)) : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-control-label">Alamat</label>
              <p class="form-control-static"><?= isset($profile->address) ? $profile->address : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <hr class="horizontal dark">
        <p class="text-uppercase text-sm">Informasi Pendidikan</p>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Pendidikan Terakhir</label>
              <p class="form-control-static"><?= isset($profile->education_level) ? $profile->education_level : 'Tidak tersedia' ?></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Institusi Pendidikan</label>
              <p class="form-control-static"><?= isset($profile->institution) ? $profile->institution : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Jurusan</label>
              <p class="form-control-static"><?= isset($profile->major) ? $profile->major : 'Tidak tersedia' ?></p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Tahun Lulus</label>
              <p class="form-control-static"><?= isset($profile->graduation_year) ? $profile->graduation_year : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <hr class="horizontal dark">
        <p class="text-uppercase text-sm">Pengalaman Kerja</p>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-control-label">Pengalaman Kerja</label>
              <p class="form-control-static"><?= isset($profile->work_experience) ? nl2br($profile->work_experience) : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <hr class="horizontal dark">
        <p class="text-uppercase text-sm">Keterampilan & Dokumen</p>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label class="form-control-label">Keterampilan</label>
              <p class="form-control-static"><?= isset($profile->skills) ? $profile->skills : 'Tidak tersedia' ?></p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="form-control-label">Resume/CV</label>
              <?php if (isset($profile->resume) && $profile->resume) : ?>
                <p class="form-control-static">
                  <a href="<?= base_url('uploads/resumes/' . $profile->resume) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
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
              <?php if (isset($profile->portfolio_url) && $profile->portfolio_url) : ?>
                <p class="form-control-static">
                  <a href="<?= $profile->portfolio_url ?>" target="_blank" class="text-primary">
                    <?= $profile->portfolio_url ?>
                  </a>
                </p>
              <?php else : ?>
                <p class="form-control-static">Tidak tersedia</p>
              <?php endif; ?>
            </div>
          </div>
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
              <img src="<?= $user->profile_picture ? base_url('uploads/profile_pictures/' . $user->profile_picture) : base_url('assets/img/team-2.jpg') ?>" class="rounded-circle img-fluid border border-2 border-white" style="width: 100px; height: 100px; object-fit: cover;">
            </a>
          </div>
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="text-center mt-4">
          <h5><?= $user->full_name ?></h5>
          <div class="h6 font-weight-300">
            <i class="ni location_pin mr-2"></i><?= $user->email ?>
          </div>
          <div class="h6 mt-2">
            <i class="ni business_briefcase-24 mr-2"></i>Pelamar
          </div>
          <div>
            <i class="ni education_hat mr-2"></i>Terdaftar sejak: <?= date('d M Y', strtotime($user->created_at)) ?>
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
