<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Detail Lamaran</h6>
          <a href="<?= base_url('admin/applications') ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Lamaran
          </a>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Informasi lengkap tentang lamaran</span>
        </p>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <h5 class="mb-3"><?= $application->job_title ?></h5>
            <div class="d-flex mb-4">
              <span class="badge badge-sm bg-gradient-<?= isset($job->job_type) ? ($job->job_type == 'full-time' ? 'success' : ($job->job_type == 'part-time' ? 'info' : ($job->job_type == 'contract' ? 'warning' : 'secondary'))) : 'secondary' ?> me-2"><?= isset($job->job_type) ? ($job->job_type == 'full-time' ? 'Full Time' : ($job->job_type == 'part-time' ? 'Part Time' : ($job->job_type == 'contract' ? 'Kontrak' : 'Magang'))) : 'Tidak tersedia' ?></span>
              <span class="text-sm me-3"><i class="ni ni-pin-3 me-1"></i> <?= isset($job->location) ? $job->location : 'Tidak tersedia' ?></span>
              <span class="text-sm"><i class="ni ni-calendar-grid-58 me-1"></i> Dilamar pada <?= date('d M Y', strtotime($application->application_date)) ?></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="timeline timeline-one-side">
              <div class="timeline-block mb-3">
                <span class="timeline-step">
                  <i class="ni ni-send text-primary"></i>
                </span>
                <div class="timeline-content">
                  <h6 class="text-dark text-sm font-weight-bold mb-0">Lamaran Dikirim</h6>
                  <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y H:i', strtotime($application->application_date)) ?></p>
                </div>
              </div>

              <?php if ($application->status != 'pending') : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-check-bold text-info"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Lamaran Ditinjau</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                  </div>
                </div>
              <?php endif; ?>

              <?php if (in_array($application->status, ['interview', 'diterima', 'ditolak'])) : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-chat-round text-primary"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Interview</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                  </div>
                </div>
              <?php endif; ?>

              <?php if ($application->status == 'diterima') : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-check-bold text-success"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Diterima</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                  </div>
                </div>
              <?php endif; ?>

              <?php if ($application->status == 'ditolak') : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-fat-remove text-danger"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Lamaran Ditolak</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <hr class="horizontal dark">

        <div class="row">
          <div class="col-md-12">
            <h6 class="mb-3">Surat Lamaran</h6>
            <div class="p-3 bg-light rounded">
              <?= nl2br($application->cover_letter) ?>
            </div>
          </div>
        </div>

        <hr class="horizontal dark">

        <div class="row">
          <div class="col-md-12">
            <h6 class="mb-3">Dokumen Lamaran</h6>

            <?php if (!empty($documents)) : ?>
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Dokumen</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jenis</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Ukuran</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Wajib</th>
                      <th class="text-secondary opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($documents as $doc) : ?>
                      <tr>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div>
                              <i class="ni ni-single-copy-04 text-primary me-3"></i>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm"><?= $doc->nama_dokumen ?? $doc->jenis_dokumen ?></h6>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0"><?= strtoupper(pathinfo($doc->nama_file, PATHINFO_EXTENSION)) ?></p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0"><?= round($doc->ukuran_file / 1024, 2) ?> MB</p>
                        </td>
                        <td>
                          <?php if (isset($doc->wajib)) : ?>
                            <span class="badge badge-sm bg-gradient-<?= $doc->wajib == 1 ? 'success' : 'secondary' ?>"><?= $doc->wajib == 1 ? 'Wajib' : 'Opsional' ?></span>
                          <?php else : ?>
                            <span class="badge badge-sm bg-gradient-secondary">-</span>
                          <?php endif; ?>
                        </td>
                        <td class="align-middle">
                          <a href="<?= base_url('admin/download_dokumen_lamaran/' . $doc->id) ?>" class="btn btn-link text-secondary mb-0">
                            <i class="fa fa-download text-xs"></i> Unduh
                          </a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php elseif ($application->resume) : ?>
              <!-- Legacy support for old applications with just a resume -->
              <a href="<?= base_url('uploads/resumes/' . $application->resume) ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                <i class="ni ni-single-copy-04 me-2"></i> Lihat Resume
              </a>
              <a href="<?= base_url('admin/download_resume/' . $application->id) ?>" class="btn btn-outline-info btn-sm">
                <i class="ni ni-cloud-download-95 me-2"></i> Download Resume
              </a>
            <?php else : ?>
              <p class="text-muted">Tidak ada dokumen yang dilampirkan.</p>
            <?php endif; ?>
          </div>
        </div>

        <hr class="horizontal dark">

        <div class="row">
          <div class="col-md-12">
            <h6 class="mb-3">Catatan Admin</h6>
            <?= form_open('admin/add_application_note/' . $application->id) ?>
              <div class="form-group">
                <textarea class="form-control" name="note" rows="3" placeholder="Tambahkan catatan tentang pelamar ini..."><?= isset($application->admin_notes) ? $application->admin_notes : '' ?></textarea>
              </div>
              <button type="submit" class="btn btn-primary btn-sm mt-3">Simpan Catatan</button>
            <?= form_close() ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Assessments -->
    <div class="card mt-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Penilaian</h6>
          <a href="<?= base_url('admin/assign_assessment/' . $application->job_id . '/' . $application->id) ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Penilaian
          </a>
        </div>
      </div>
      <div class="card-body">
        <?php if (empty($assessments)) : ?>
          <p class="text-center">Belum ada penilaian yang diberikan kepada pelamar ini.</p>
        <?php else : ?>
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penilaian</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipe</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Skor</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($assessments as $assessment) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center me-2">
                            <i class="ni ni-ruler-pencil text-white opacity-10"></i>
                          </div>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= $assessment->assessment_title ?></h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?= $assessment->type_name ?></p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-<?= $assessment->status == 'not_started' ? 'secondary' : ($assessment->status == 'in_progress' ? 'warning' : ($assessment->status == 'completed' ? 'success' : 'info')) ?>"><?= $assessment->status == 'not_started' ? 'Belum Dimulai' : ($assessment->status == 'in_progress' ? 'Sedang Dikerjakan' : ($assessment->status == 'completed' ? 'Selesai' : 'Dinilai')) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <?php if ($assessment->status == 'graded' && $assessment->score !== null) : ?>
                        <span class="text-secondary text-xs font-weight-bold"><?= $assessment->score ?></span>
                      <?php else : ?>
                        <span class="text-secondary text-xs font-weight-bold">-</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle">
                      <div class="dropdown">
                        <a href="#" class="text-secondary font-weight-bold text-xs" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownMenuButton">
                          <?php if ($assessment->status == 'completed' || $assessment->status == 'graded') : ?>
                            <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/view_assessment_results/' . $assessment->id) ?>">Lihat Hasil</a></li>
                          <?php endif; ?>
                          <?php if ($assessment->status == 'completed') : ?>
                            <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/grade_assessment/' . $assessment->id) ?>">Nilai</a></li>
                          <?php endif; ?>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/remove_assessment/' . $assessment->id . '/' . $application->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus penilaian ini?');">Hapus</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <!-- Applicant Info -->
    <div class="card">
      <div class="card-header pb-0">
        <h6>Informasi Pelamar</h6>
      </div>
      <div class="card-body">
        <div class="text-center mb-4">
          <?php if ($applicant->profile_picture) : ?>
            <img src="<?= base_url('uploads/profile_pictures/' . $applicant->profile_picture) ?>" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
          <?php else : ?>
            <img src="<?= base_url('assets/img/team-2.jpg') ?>" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
          <?php endif; ?>
          <h5 class="mt-3 mb-0"><?= $applicant->full_name ?></h5>
          <p class="text-sm text-secondary mb-0"><?= $applicant->email ?></p>
        </div>

        <div class="d-flex align-items-center mb-3">
          <div class="icon icon-shape icon-xs bg-gradient-primary shadow text-center">
            <i class="fas fa-phone opacity-10"></i>
          </div>
          <div class="ms-3">
            <p class="text-sm mb-0">Telepon: <span class="font-weight-bold"><?= $applicant->phone ?? 'Tidak tersedia' ?></span></p>
          </div>
        </div>

        <div class="d-flex align-items-center mb-3">
          <div class="icon icon-shape icon-xs bg-gradient-primary shadow text-center">
            <i class="fas fa-map-marker-alt opacity-10"></i>
          </div>
          <div class="ms-3">
            <p class="text-sm mb-0">Alamat: <span class="font-weight-bold"><?= $applicant->address ?? 'Tidak tersedia' ?></span></p>
          </div>
        </div>

        <div class="d-flex align-items-center mb-3">
          <div class="icon icon-shape icon-xs bg-gradient-primary shadow text-center">
            <i class="fas fa-calendar opacity-10"></i>
          </div>
          <div class="ms-3">
            <p class="text-sm mb-0">Tanggal Lahir: <span class="font-weight-bold"><?= $profile->date_of_birth ? date('d M Y', strtotime($profile->date_of_birth)) : 'Tidak tersedia' ?></span></p>
          </div>
        </div>

        <div class="d-flex align-items-center mb-3">
          <div class="icon icon-shape icon-xs bg-gradient-primary shadow text-center">
            <i class="fas fa-venus-mars opacity-10"></i>
          </div>
          <div class="ms-3">
            <p class="text-sm mb-0">Jenis Kelamin: <span class="font-weight-bold"><?= $profile->gender ? ucfirst($profile->gender) : 'Tidak tersedia' ?></span></p>
          </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
          <a href="<?= base_url('admin/profilPelamar/' . $applicant->id) ?>" class="btn btn-sm btn-outline-primary">Lihat Profil Lengkap</a>
        </div>
      </div>
    </div>

    <!-- Application Status -->
    <div class="card mt-4">
      <div class="card-header pb-0">
        <h6>Status Lamaran</h6>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label for="status" class="form-control-label">Ubah Status</label>
          <select class="form-control" id="status" onchange="updateStatus(this.value)">
            <option value="pending" <?= $application->status == 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="reviewed" <?= $application->status == 'reviewed' ? 'selected' : '' ?>>Direview</option>
            <option value="interview" <?= $application->status == 'interview' ? 'selected' : '' ?>>Interview</option>
            <option value="diterima" <?= $application->status == 'diterima' ? 'selected' : '' ?>>Diterima</option>
            <option value="ditolak" <?= $application->status == 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
          </select>
        </div>

        <div class="form-group mt-3">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="notify_applicant" checked>
            <label class="form-check-label" for="notify_applicant">Kirim notifikasi ke pelamar</label>
          </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
          <button type="button" class="btn btn-primary" onclick="saveStatus()">Simpan Status</button>
        </div>
      </div>
    </div>

    <!-- Job Details -->
    <div class="card mt-4">
      <div class="card-header pb-0">
        <h6>Detail Lowongan</h6>
      </div>
      <div class="card-body">
        <p class="text-sm mb-1"><strong>Judul:</strong> <?= $job->title ?></p>
        <p class="text-sm mb-1"><strong>Tipe:</strong> <?= $job->job_type == 'full-time' ? 'Full Time' : ($job->job_type == 'part-time' ? 'Part Time' : ($job->job_type == 'contract' ? 'Kontrak' : 'Magang')) ?></p>
        <p class="text-sm mb-1"><strong>Lokasi:</strong> <?= $job->location ?></p>
        <p class="text-sm mb-1"><strong>Kategori:</strong> <?= $job->category_name ?></p>
        <?php if ($job->salary_range) : ?>
          <p class="text-sm mb-1"><strong>Kisaran Gaji:</strong> <?= $job->salary_range ?></p>
        <?php endif; ?>
        <p class="text-sm mb-3"><strong>Batas Waktu:</strong> <?= date('d M Y', strtotime($job->deadline)) ?></p>

        <div class="d-flex justify-content-center">
          <a href="<?= base_url('admin/edit_lowongan/' . $job->id) ?>" class="btn btn-sm btn-outline-primary">Lihat Detail Lowongan</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function updateStatus(status) {
    // Change the color of the status dropdown based on the selected status
    const statusSelect = document.getElementById('status');
    statusSelect.className = 'form-control';

    if (status === 'pending') {
      statusSelect.classList.add('border-warning', 'text-warning');
    } else if (status === 'reviewed') {
      statusSelect.classList.add('border-info', 'text-info');
    } else if (status === 'interview') {
      statusSelect.classList.add('border-primary', 'text-primary');
    } else if (status === 'diterima') {
      statusSelect.classList.add('border-success', 'text-success');
    } else if (status === 'ditolak') {
      statusSelect.classList.add('border-danger', 'text-danger');
    }
  }

  function saveStatus() {
    const status = document.getElementById('status').value;
    const notifyApplicant = document.getElementById('notify_applicant').checked;

    // Redirect to the update status URL with the notify parameter
    window.location.href = '<?= base_url('admin/update_application_status/' . $application->id) ?>/' + status + '?notify=' + (notifyApplicant ? '1' : '0');
  }

  // Initialize status color on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateStatus('<?= $application->status ?>');
  });
</script>
