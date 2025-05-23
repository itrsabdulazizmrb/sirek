<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Manajemen Lowongan Pekerjaan</h6>
          <a href="<?= base_url('admin/add_job') ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Lowongan Baru
          </a>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Kelola semua lowongan pekerjaan di sini</span>
        </p>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <?php if (empty($jobs)) : ?>
            <div class="text-center py-5">
              <h4 class="text-secondary">Tidak ada lowongan yang ditemukan</h4>
              <p class="text-muted">Mulai dengan menambahkan lowongan pekerjaan baru.</p>
              <a href="<?= base_url('admin/add_job') ?>" class="btn btn-primary mt-3">Tambah Lowongan Baru</a>
            </div>
          <?php else : ?>
            <table class="table align-items-center mb-0 datatable">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lowongan</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kategori</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lokasi</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Batas Waktu</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pelamar</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($jobs as $job) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="<?= base_url('assets/img/small-logos/logo-company.svg') ?>" class="avatar avatar-sm me-3" alt="job">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= $job->title ?></h6>
                          <p class="text-xs text-secondary mb-0">Dibuat oleh: <?= $job->created_by_name ?></p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?= $job->category_name ?></p>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?= $job->location ?></p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-<?= $job->job_type == 'full-time' ? 'success' : ($job->job_type == 'part-time' ? 'info' : ($job->job_type == 'contract' ? 'warning' : 'secondary')) ?>"><?= $job->job_type == 'full-time' ? 'Full Time' : ($job->job_type == 'part-time' ? 'Part Time' : ($job->job_type == 'contract' ? 'Kontrak' : 'Magang')) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= date('d M Y', strtotime($job->deadline)) ?></span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-<?= $job->status == 'active' ? 'success' : ($job->status == 'draft' ? 'secondary' : 'danger') ?>"><?= $job->status == 'active' ? 'Aktif' : ($job->status == 'draft' ? 'Draft' : 'Tidak Aktif') ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <?php
                      $applicant_count = $this->model_lamaran->hitung_lamaran_berdasarkan_lowongan($job->id);
                      ?>
                      <a href="<?= base_url('admin/job_applications/' . $job->id) ?>" class="text-secondary font-weight-bold text-xs">
                        <?= $applicant_count ?> Pelamar
                      </a>
                    </td>
                    <td class="align-middle">
                      <div class="dropdown">
                        <a href="#" class="text-secondary font-weight-bold text-xs" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownMenuButton">
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('home/job_details/' . $job->id) ?>" target="_blank">Lihat</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/edit_job/' . $job->id) ?>">Edit</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/job_applications/' . $job->id) ?>">Lihat Pelamar</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/assign_assessment/' . $job->id) ?>">Atur Penilaian</a></li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <a class="dropdown-item border-radius-md text-danger btn-delete" href="<?= base_url('admin/delete_job/' . $job->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus lowongan ini?');">
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
        <h6>Statistik Lowongan</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="job-stats-chart" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Lowongan Berdasarkan Kategori</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="job-category-chart" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Job Stats Chart
    var ctx1 = document.getElementById("job-stats-chart").getContext("2d");
    new Chart(ctx1, {
      type: "bar",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
          label: "Lowongan Aktif",
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

    // Job Category Chart
    var ctx2 = document.getElementById("job-category-chart").getContext("2d");
    new Chart(ctx2, {
      type: "doughnut",
      data: {
        labels: ["IT & Teknologi", "Keuangan", "Marketing", "HR", "Operasional", "Lainnya"],
        datasets: [{
          label: "Lowongan",
          backgroundColor: ["#5e72e4", "#2dce89", "#fb6340", "#11cdef", "#f5365c", "#8898aa"],
          data: [35, 20, 15, 10, 10, 10],
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
  });
</script>
