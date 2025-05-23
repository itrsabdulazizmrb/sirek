<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Manajemen Pengguna</h6>
          <a href="<?= base_url('admin/tambah_pengguna') ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Pengguna Baru
          </a>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Kelola semua pengguna di sini</span>
        </p>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <!-- Filter -->
        <div class="px-4 py-3">
          <form action="<?= base_url('admin/pengguna') ?>" method="get" class="row g-3">
            <div class="col-md-4">
              <div class="form-group">
                <label for="role" class="form-label">Peran</label>
                <select class="form-control" id="role" name="role">
                  <option value="">Semua Peran</option>
                  <option value="admin" <?= $this->input->get('role') == 'admin' ? 'selected' : '' ?>>Admin</option>
                  <option value="applicant" <?= $this->input->get('role') == 'applicant' ? 'selected' : '' ?>>Pelamar</option>
                  <option value="recruiter" <?= $this->input->get('role') == 'recruiter' ? 'selected' : '' ?>>Rekruter</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                  <option value="">Semua Status</option>
                  <option value="active" <?= $this->input->get('status') == 'active' ? 'selected' : '' ?>>Aktif</option>
                  <option value="inactive" <?= $this->input->get('status') == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="search" class="form-label">Cari</label>
                <div class="input-group">
                  <input type="text" class="form-control" id="search" name="search" placeholder="Nama atau email..." value="<?= $this->input->get('search') ?>">
                  <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="table-responsive p-0">
          <?php if (empty($users)) : ?>
            <div class="text-center py-5">
              <h4 class="text-secondary">Tidak ada pengguna yang ditemukan</h4>
              <p class="text-muted">Coba ubah filter atau cari dengan kata kunci yang berbeda.</p>
            </div>
          <?php else : ?>
            <table class="table align-items-center mb-0 datatable">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pengguna</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Peran</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Daftar</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Login Terakhir</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $user) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <?php if ($user->profile_picture) : ?>
                            <img src="<?= base_url('uploads/profile_pictures/' . $user->profile_picture) ?>" class="avatar avatar-sm me-3" alt="user1">
                          <?php else : ?>
                            <img src="<?= base_url('assets/img/team-2.jpg') ?>" class="avatar avatar-sm me-3" alt="user1">
                          <?php endif; ?>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= $user->full_name ?></h6>
                          <p class="text-xs text-secondary mb-0"><?= $user->email ?></p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">
                        <?= $user->role == 'admin' ? 'Admin' : ($user->role == 'applicant' ? 'Pelamar' : 'Rekruter') ?>
                      </p>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= date('d M Y', strtotime($user->created_at)) ?></span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-<?= $user->status == 'active' ? 'success' : 'secondary' ?>"><?= $user->status == 'active' ? 'Aktif' : 'Tidak Aktif' ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= $user->last_login ? date('d M Y H:i', strtotime($user->last_login)) : 'Belum Pernah' ?></span>
                    </td>
                    <td class="align-middle">
                      <div class="dropdown">
                        <a href="#" class="text-secondary font-weight-bold text-xs" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownMenuButton">
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/edit_pengguna/' . $user->id) ?>">Edit</a></li>
                          <?php if ($user->role == 'applicant') : ?>
                            <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/profil_pelamar/' . $user->id) ?>">Lihat Profil</a></li>
                            <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/lamaran_pelamar/' . $user->id) ?>">Lihat Lamaran</a></li>
                          <?php endif; ?>
                          <?php if ($user->status == 'active') : ?>
                            <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/nonaktifkan_pengguna/' . $user->id) ?>">Nonaktifkan</a></li>
                          <?php else : ?>
                            <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/aktifkan_pengguna/' . $user->id) ?>">Aktifkan</a></li>
                          <?php endif; ?>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/reset_kata_sandi/' . $user->id) ?>">Reset Password</a></li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <a class="dropdown-item border-radius-md text-danger btn-delete" href="<?= base_url('admin/hapus_pengguna/' . $user->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.');">
                              Hapus
                            </a>
                          </li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Pengguna Berdasarkan Peran</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="user-role-chart" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Pendaftaran Pengguna Bulanan</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="user-registration-chart" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // User Role Chart
    var ctx1 = document.getElementById("user-role-chart").getContext("2d");
    new Chart(ctx1, {
      type: "doughnut",
      data: {
        labels: ["Pelamar", "Admin", "Rekruter"],
        datasets: [{
          label: "Pengguna",
          backgroundColor: ["#5e72e4", "#f5365c", "#2dce89"],
          data: [<?= $user_stats['applicant_count'] ?>, <?= $user_stats['admin_count'] ?>, <?= $user_stats['recruiter_count'] ?>],
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
          }
        },
      },
    });

    // User Registration Chart
    var ctx2 = document.getElementById("user-registration-chart").getContext("2d");
    new Chart(ctx2, {
      type: "bar",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
          label: "Pendaftaran",
          backgroundColor: "#5e72e4",
          data: [15, 20, 25, 30, 25, 20, 15, 20, 25, 30, 25, 20],
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        scales: {
          y: {
            beginAtZero: true
          }
        },
      },
    });
  });
</script>
