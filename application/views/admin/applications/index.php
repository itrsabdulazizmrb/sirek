<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Manajemen Lamaran</h6>
          <div>
            <a href="<?= base_url('admin/export_applications') ?>" class="btn btn-sm btn-success">
              <i class="fas fa-file-excel me-2"></i> Export Excel
            </a>
          </div>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Kelola semua lamaran pekerjaan di sini</span>
        </p>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <!-- Filter -->
        <div class="px-4 py-3">
          <form action="<?= base_url('admin/applications') ?>" method="get" class="row g-3">
            <div class="col-md-3">
              <div class="form-group">
                <label for="job_id" class="form-label">Lowongan</label>
                <select class="form-control" id="job_id" name="job_id">
                  <option value="">Semua Lowongan</option>
                  <?php foreach ($jobs as $job) : ?>
                    <option value="<?= $job->id ?>" <?= $this->input->get('job_id') == $job->id ? 'selected' : '' ?>><?= $job->title ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                  <option value="">Semua Status</option>
                  <option value="pending" <?= $this->input->get('status') == 'pending' ? 'selected' : '' ?>>Pending</option>
                  <option value="reviewed" <?= $this->input->get('status') == 'reviewed' ? 'selected' : '' ?>>Reviewed</option>
                  <option value="shortlisted" <?= $this->input->get('status') == 'shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
                  <option value="interviewed" <?= $this->input->get('status') == 'interviewed' ? 'selected' : '' ?>>Interviewed</option>
                  <option value="offered" <?= $this->input->get('status') == 'offered' ? 'selected' : '' ?>>Offered</option>
                  <option value="hired" <?= $this->input->get('status') == 'hired' ? 'selected' : '' ?>>Hired</option>
                  <option value="rejected" <?= $this->input->get('status') == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="date_range" class="form-label">Rentang Tanggal</label>
                <input type="text" class="form-control" id="date_range" name="date_range" placeholder="DD/MM/YYYY - DD/MM/YYYY" value="<?= $this->input->get('date_range') ?>">
              </div>
            </div>
            <div class="col-md-3">
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
          <?php if (empty($applications)) : ?>
            <div class="text-center py-5">
              <h4 class="text-secondary">Tidak ada lamaran yang ditemukan</h4>
              <p class="text-muted">Coba ubah filter atau cari dengan kata kunci yang berbeda.</p>
            </div>
          <?php else : ?>
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pelamar</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lowongan</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Lamaran</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penilaian</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($applications as $application) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <?php if ($application->profile_picture) : ?>
                            <img src="<?= base_url('uploads/profile_pictures/' . $application->profile_picture) ?>" class="avatar avatar-sm me-3" alt="user1">
                          <?php else : ?>
                            <img src="<?= base_url('assets/img/team-2.jpg') ?>" class="avatar avatar-sm me-3" alt="user1">
                          <?php endif; ?>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= $application->applicant_name ?></h6>
                          <p class="text-xs text-secondary mb-0"><?= $application->applicant_email ?></p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?= $application->job_title ?></p>
                      <p class="text-xs text-secondary mb-0"><?= date('d M Y', strtotime($application->deadline)) ?></p>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= date('d M Y', strtotime($application->application_date)) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <div class="dropdown">
                        <button class="btn btn-sm bg-gradient-<?= $application->status == 'pending' ? 'warning' : ($application->status == 'reviewed' ? 'info' : ($application->status == 'shortlisted' ? 'success' : ($application->status == 'interviewed' ? 'primary' : ($application->status == 'offered' ? 'warning' : ($application->status == 'hired' ? 'success' : 'danger'))))) ?> dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                          <?= ucfirst($application->status) ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <li><a class="dropdown-item" href="<?= base_url('admin/update_application_status/' . $application->id . '/pending') ?>">Pending</a></li>
                          <li><a class="dropdown-item" href="<?= base_url('admin/update_application_status/' . $application->id . '/reviewed') ?>">Reviewed</a></li>
                          <li><a class="dropdown-item" href="<?= base_url('admin/update_application_status/' . $application->id . '/shortlisted') ?>">Shortlisted</a></li>
                          <li><a class="dropdown-item" href="<?= base_url('admin/update_application_status/' . $application->id . '/interviewed') ?>">Interviewed</a></li>
                          <li><a class="dropdown-item" href="<?= base_url('admin/update_application_status/' . $application->id . '/offered') ?>">Offered</a></li>
                          <li><a class="dropdown-item" href="<?= base_url('admin/update_application_status/' . $application->id . '/hired') ?>">Hired</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="<?= base_url('admin/update_application_status/' . $application->id . '/rejected') ?>">Rejected</a></li>
                        </ul>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <?php
                      // Temporarily set default values until the model methods are implemented
                      $assessment_count = 0;
                      $completed_count = 0;
                      // Uncomment when methods are implemented
                      // $assessment_count = $this->model_penilaian->hitung_penilaian_pelamar($application->id);
                      // $completed_count = $this->model_penilaian->hitung_penilaian_selesai($application->id);
                      ?>
                      <?php if ($assessment_count > 0) : ?>
                        <span class="badge badge-sm bg-gradient-<?= $completed_count == $assessment_count ? 'success' : 'warning' ?>">
                          <?= $completed_count ?>/<?= $assessment_count ?> Selesai
                        </span>
                      <?php else : ?>
                        <span class="badge badge-sm bg-gradient-secondary">Tidak Ada</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle">
                      <div class="dropdown">
                        <a href="#" class="text-secondary font-weight-bold text-xs" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownMenuButton">
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/application_details/' . $application->id) ?>">Lihat Detail</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/assign_assessment/' . $application->job_id . '/' . $application->id) ?>">Atur Penilaian</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/download_resume/' . $application->id) ?>">Download Resume</a></li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <a class="dropdown-item border-radius-md text-danger btn-delete" href="<?= base_url('admin/delete_application/' . $application->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus lamaran ini?');">
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
              <?= $pagination ?>
            </div>
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
        <h6>Statistik Lamaran</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="application-stats-chart" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Tren Lamaran Bulanan</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="monthly-applications-chart" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize date range picker
    if (typeof flatpickr !== 'undefined') {
      flatpickr("#date_range", {
        mode: "range",
        dateFormat: "d/m/Y",
      });
    }

    // Application Stats Chart
    var ctx1 = document.getElementById("application-stats-chart").getContext("2d");
    new Chart(ctx1, {
      type: "pie",
      data: {
        labels: ["Pending", "Reviewed", "Shortlisted", "Interviewed", "Offered", "Hired", "Rejected"],
        datasets: [{
          label: "Pelamar",
          backgroundColor: ["#fb6340", "#11cdef", "#2dce89", "#5e72e4", "#ffd600", "#2dce89", "#f5365c"],
          data: [15, 10, 8, 5, 3, 2, 7],
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

    // Monthly Applications Chart
    var ctx2 = document.getElementById("monthly-applications-chart").getContext("2d");
    new Chart(ctx2, {
      type: "line",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
          label: "Lamaran",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#5e72e4",
          backgroundColor: "rgba(94, 114, 228, 0.3)",
          borderWidth: 3,
          fill: true,
          data: [50, 40, 300, 220, 500, 250, 400, 230, 500, 300, 450, 380],
          maxBarThickness: 6
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
        interaction: {
          intersect: false,
          mode: 'index',
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
