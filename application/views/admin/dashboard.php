<div class="row">
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Lowongan</p>
              <h5 class="font-weight-bolder"><?= $total_jobs ?></h5>
              <p class="mb-0">
                <span class="text-success text-sm font-weight-bolder"><?= $active_jobs ?></span>
                <span class="text-sm">Aktif</span>
              </p>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
              <i class="ni ni-briefcase-24 text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Lamaran</p>
              <h5 class="font-weight-bolder"><?= $total_applications ?></h5>
              <p class="mb-0">
                <span class="text-success text-sm font-weight-bolder"><?= $new_applications ?></span>
                <span class="text-sm">Hari Ini</span>
              </p>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
              <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Pengguna</p>
              <h5 class="font-weight-bolder"><?= $total_users ?></h5>
              <p class="mb-0">
                <span class="text-success text-sm font-weight-bolder"><?= $total_applicants ?></span>
                <span class="text-sm">Pelamar</span>
              </p>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
              <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Penilaian</p>
              <h5 class="font-weight-bolder"><?= $total_assessments ?? 0 ?></h5>
              <p class="mb-0">
                <span class="text-success text-sm font-weight-bolder"><?= $completed_assessments ?? 0 ?></span>
                <span class="text-sm">Selesai</span>
              </p>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
              <i class="ni ni-ruler-pencil text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-7 mb-lg-0 mb-4">
    <div class="card z-index-2 h-100">
      <div class="card-header pb-0 pt-3 bg-transparent">
        <h6 class="text-capitalize">Tren Lamaran</h6>
        <p class="text-sm mb-0">
          <i class="fa fa-arrow-up text-success"></i>
          <span class="font-weight-bold">4% lebih tinggi</span> dari bulan lalu
        </p>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="card card-carousel overflow-hidden h-100 p-0">
      <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
        <div class="carousel-inner border-radius-lg h-100">
          <div class="carousel-item h-100 active" style="background-image: url('<?= base_url('assets/img/carousel-1.jpg') ?>'); background-size: cover;">
            <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
              <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                <i class="ni ni-bulb-61 text-dark opacity-10"></i>
              </div>
              <h5 class="text-white mb-1">Tingkatkan Proses Rekrutmen</h5>
              <p>Gunakan penilaian online untuk menyaring kandidat dengan lebih efektif.</p>
            </div>
          </div>
          <div class="carousel-item h-100" style="background-image: url('<?= base_url('assets/img/carousel-2.jpg') ?>'); background-size: cover;">
            <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
              <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                <i class="ni ni-trophy text-dark opacity-10"></i>
              </div>
              <h5 class="text-white mb-1">Temukan Talenta Terbaik</h5>
              <p>Buat lowongan yang menarik untuk menjangkau kandidat berkualitas.</p>
            </div>
          </div>
          <div class="carousel-item h-100" style="background-image: url('<?= base_url('assets/img/carousel-3.jpg') ?>'); background-size: cover;">
            <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
              <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                <i class="ni ni-chart-bar-32 text-dark opacity-10"></i>
              </div>
              <h5 class="text-white mb-1">Analisis Rekrutmen</h5>
              <p>Pantau metrik rekrutmen untuk meningkatkan proses perekrutan.</p>
            </div>
          </div>
        </div>
        <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-5 mb-lg-0 mb-4">
    <div class="card">
      <div class="card-header pb-0 p-3">
        <h6 class="mb-0">Jumlah Pelamar per Posisi</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="applications-per-job-chart" class="chart-canvas" height="300"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-7 mb-lg-0 mb-4">
    <div class="card">
      <div class="card-header pb-0 p-3">
        <div class="d-flex justify-content-between">
          <h6 class="mb-2">Lamaran Terbaru</h6>
          <a href="<?= base_url('admin/lamaran') ?>" class="btn btn-sm btn-primary">Lihat Semua</a>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-items-center mb-0 datatable">
          <thead>
            <tr>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pelamar</th>
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lowongan</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
              <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
              <th class="text-secondary opacity-7"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_applications as $application) : ?>
              <tr>
                <td>
                  <div class="d-flex px-2 py-1">
                    <div>
                      <?php if (isset($application->profile_picture) && $application->profile_picture) : ?>
                        <img src="<?= base_url('uploads/profile_pictures/' . $application->profile_picture) ?>" class="avatar avatar-sm me-3" alt="user1">
                      <?php else : ?>
                        <img src="<?= base_url('assets/img/team-2.jpg') ?>" class="avatar avatar-sm me-3" alt="user1">
                      <?php endif; ?>
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm"><?= $application->applicant_name ?></h6>
                      <p class="text-xs text-secondary mb-0"><?= $application->applicant_email ?? '' ?></p>
                    </div>
                  </div>
                </td>
                <td>
                  <p class="text-xs font-weight-bold mb-0"><?= $application->job_title ?></p>
                </td>
                <td class="align-middle text-center">
                  <span class="text-secondary text-xs font-weight-bold"><?= date('d M Y', strtotime($application->tanggal_lamaran)) ?></span>
                </td>
                <td class="align-middle text-center text-sm">
                  <span class="badge badge-sm bg-gradient-<?= $application->status == 'menunggu' ? 'warning' : ($application->status == 'direview' ? 'info' : ($application->status == 'seleksi' ? 'success' : ($application->status == 'wawancara' ? 'primary' : ($application->status == 'diterima' ? 'success' : 'danger')))) ?>"><?= ucfirst($application->status) ?></span>
                </td>
                <td class="align-middle">
                  <a href="<?= base_url('admin/detail_lamaran/' . $application->id) ?>" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Lihat detail">
                    Detail
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header pb-0 p-3">
        <h6 class="mb-0">Kategori Lowongan</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart mb-3">
          <canvas id="job-category-chart" class="chart-canvas" height="200"></canvas>
        </div>
        <ul class="list-group">
          <?php foreach ($job_categories ?? [] as $category) : ?>
            <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
              <div class="d-flex align-items-center">
                <div class="icon icon-shape icon-sm me-3 bg-gradient-dark shadow text-center">
                  <i class="ni ni-tag text-white opacity-10"></i>
                </div>
                <div class="d-flex flex-column">
                  <h6 class="mb-1 text-dark text-sm"><?= $category->nama ?></h6>
                  <span class="text-xs"><?= $category->jumlah_lowongan ?? 0 ?> Lowongan</span>
                </div>
              </div>
              <div class="d-flex">
                <a href="<?= base_url('admin/lowongan?category=' . $category->id) ?>" class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto">
                  <i class="ni ni-bold-right" aria-hidden="true"></i>
                </a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <div class="card mt-4">
      <div class="card-header pb-0 p-3">
        <h6 class="mb-0">Status Lamaran</h6>
      </div>
      <div class="card-body p-3">
        <div class="chart">
          <canvas id="application-status-chart" class="chart-canvas" height="200"></canvas>
        </div>
      </div>
    </div>

    <div class="card mt-4">
      <div class="card-header pb-0 p-3">
        <h6 class="mb-0">Aktivitas Terbaru</h6>
      </div>
      <div class="card-body p-3">
        <div class="timeline timeline-one-side">
          <?php foreach ($recent_activities ?? [] as $activity) : ?>
            <div class="timeline-block mb-3">
              <span class="timeline-step">
                <?php if (isset($activity->type) && $activity->type == 'job') : ?>
                  <i class="ni ni-briefcase-24 text-primary"></i>
                <?php elseif (isset($activity->type) && $activity->type == 'application') : ?>
                  <i class="ni ni-paper-diploma text-danger"></i>
                <?php elseif (isset($activity->type) && $activity->type == 'user') : ?>
                  <i class="ni ni-single-02 text-success"></i>
                <?php elseif (isset($activity->type) && $activity->type == 'assessment') : ?>
                  <i class="ni ni-ruler-pencil text-warning"></i>
                <?php else : ?>
                  <i class="ni ni-bell-55 text-info"></i>
                <?php endif; ?>
              </span>
              <div class="timeline-content">
                <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $activity->description ?? 'Aktivitas baru' ?></h6>
                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= isset($activity->created_at) ? time_elapsed_string($activity->created_at) : date('d M Y H:i') ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Tren Lamaran Chart (Line Chart)
    var ctx1 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');

    new Chart(ctx1, {
      type: "line",
      data: {
        labels: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
        datasets: [{
          label: "Lamaran",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#5e72e4",
          backgroundColor: gradientStroke1,
          borderWidth: 3,
          fill: true,
          data: [
            <?= $monthly_application_stats[1] ?? 0 ?>,
            <?= $monthly_application_stats[2] ?? 0 ?>,
            <?= $monthly_application_stats[3] ?? 0 ?>,
            <?= $monthly_application_stats[4] ?? 0 ?>,
            <?= $monthly_application_stats[5] ?? 0 ?>,
            <?= $monthly_application_stats[6] ?? 0 ?>,
            <?= $monthly_application_stats[7] ?? 0 ?>,
            <?= $monthly_application_stats[8] ?? 0 ?>,
            <?= $monthly_application_stats[9] ?? 0 ?>,
            <?= $monthly_application_stats[10] ?? 0 ?>,
            <?= $monthly_application_stats[11] ?? 0 ?>,
            <?= $monthly_application_stats[12] ?? 0 ?>
          ],
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
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#666',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#666',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });

    // Kategori Lowongan Chart (Doughnut Chart)
    var ctx2 = document.getElementById("job-category-chart").getContext("2d");
    new Chart(ctx2, {
      type: "doughnut",
      data: {
        labels: [
          <?php foreach ($job_categories ?? [] as $category) : ?>
            "<?= $category->nama ?>",
          <?php endforeach; ?>
        ],
        datasets: [{
          label: "Lowongan",
          backgroundColor: [
            "#5e72e4", "#2dce89", "#f5365c", "#fb6340", "#11cdef", "#ffd600", "#8965e0", "#f3a4b5", "#172b4d", "#5603ad"
          ],
          data: [
            <?php foreach ($job_categories ?? [] as $category) : ?>
              <?= $category->jumlah_lowongan ?? 0 ?>,
            <?php endforeach; ?>
          ],
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              boxWidth: 10,
              font: {
                size: 10
              }
            }
          }
        },
        cutout: '60%'
      },
    });

    // Status Lamaran Chart (Pie Chart)
    var ctx3 = document.getElementById("application-status-chart").getContext("2d");
    new Chart(ctx3, {
      type: "pie",
      data: {
        labels: ["Menunggu", "Direview", "Seleksi", "Wawancara", "Diterima"],
        datasets: [{
          label: "Pelamar",
          backgroundColor: ["#fb6340", "#11cdef", "#2dce89", "#5e72e4", "#2dce89"],
          data: [
            <?= $application_status_stats['menunggu'] ?? 0 ?>,
            <?= $application_status_stats['direview'] ?? 0 ?>,
            <?= $application_status_stats['seleksi'] ?? 0 ?>,
            <?= $application_status_stats['wawancara'] ?? 0 ?>,
            <?= $application_status_stats['diterima'] ?? 0 ?>
          ],
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              boxWidth: 10,
              font: {
                size: 10
              }
            }
          }
        },
      },
    });

    // Jumlah Pelamar per Posisi Chart (Bar Chart)
    var ctx4 = document.getElementById("applications-per-job-chart").getContext("2d");
    new Chart(ctx4, {
      type: "bar",
      data: {
        labels: [
          <?php foreach ($applications_per_job ?? [] as $job) : ?>
            "<?= substr($job->judul, 0, 20) . (strlen($job->judul) > 20 ? '...' : '') ?>",
          <?php endforeach; ?>
        ],
        datasets: [{
          label: "Jumlah Pelamar",
          backgroundColor: "#5e72e4",
          data: [
            <?php foreach ($applications_per_job ?? [] as $job) : ?>
              <?= $job->jumlah_lamaran ?? 0 ?>,
            <?php endforeach; ?>
          ],
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
            beginAtZero: true,
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              precision: 0
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false
            },
            ticks: {
              font: {
                size: 10
              }
            }
          },
        },
      },
    });
  });
</script>
