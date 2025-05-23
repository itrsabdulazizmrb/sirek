<div class="row">
  <div class="col-12">
    <div class="card shadow-sm mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1">Lamaran dari <?= $user->full_name ?></h6>
            <p class="text-sm mb-0">
              <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
              <span class="font-weight-bold">Daftar semua lamaran yang diajukan oleh pelamar ini</span>
            </p>
          </div>
          <div>
            <a href="<?= base_url('admin/profilPelamar/' . $user->id) ?>" class="btn btn-sm btn-outline-primary me-2">
              <i class="fas fa-arrow-left me-2"></i> Kembali ke Profil
            </a>
            <a href="<?= base_url('admin/export_applicant_applications/' . $user->id) ?>" class="btn btn-sm btn-success">
              <i class="fas fa-file-excel me-2"></i> Export Excel
            </a>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive p-0" style="min-height: 300px; overflow-y: auto;">
          <?php if (empty($applications)) : ?>
            <div class="text-center py-5">
              <h4 class="text-secondary">Tidak ada lamaran yang ditemukan</h4>
              <p class="text-muted">Pelamar ini belum mengajukan lamaran pekerjaan.</p>
            </div>
          <?php else : ?>
            <table class="table align-items-center mb-0 datatable">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Lowongan</th>
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
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= $application->job_title ?></h6>
                          <p class="text-xs text-secondary mb-0">
                            <span class="badge bg-gradient-<?= $application->job_type == 'full_time' ? 'primary' : ($application->job_type == 'part_time' ? 'info' : ($application->job_type == 'contract' ? 'warning' : 'secondary')) ?> badge-sm">
                              <?= $application->job_type == 'full_time' ? 'Full Time' : ($application->job_type == 'part_time' ? 'Part Time' : ($application->job_type == 'contract' ? 'Kontrak' : $application->job_type)) ?>
                            </span>
                            <span class="ms-1"><?= $application->location ?></span>
                          </p>
                        </div>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= date('d M Y', strtotime($application->application_date)) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <div class="dropdown">
                        <button class="btn btn-sm bg-gradient-<?= $application->status == 'pending' ? 'warning' : ($application->status == 'reviewed' ? 'info' : ($application->status == 'interview' ? 'primary' : ($application->status == 'diterima' ? 'success' : 'danger'))) ?> dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                          <?= $application->status == 'pending' ? 'Pending' : ($application->status == 'reviewed' ? 'Direview' : ($application->status == 'interview' ? 'Interview' : ($application->status == 'diterima' ? 'Diterima' : 'Ditolak'))) ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <li><a class="dropdown-item status-update" href="javascript:void(0)" data-id="<?= $application->id ?>" data-status="pending" data-name="<?= $user->full_name ?>">Pending</a></li>
                          <li><a class="dropdown-item status-update" href="javascript:void(0)" data-id="<?= $application->id ?>" data-status="reviewed" data-name="<?= $user->full_name ?>">Direview</a></li>
                          <li><a class="dropdown-item status-update" href="javascript:void(0)" data-id="<?= $application->id ?>" data-status="interview" data-name="<?= $user->full_name ?>">Interview</a></li>
                          <li><a class="dropdown-item status-update" href="javascript:void(0)" data-id="<?= $application->id ?>" data-status="diterima" data-name="<?= $user->full_name ?>">Diterima</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger status-update" href="javascript:void(0)" data-id="<?= $application->id ?>" data-status="ditolak" data-name="<?= $user->full_name ?>">Ditolak</a></li>
                        </ul>
                      </div>
                    </td>
                    <td class="align-middle text-center">
                      <?php
                      // Get assessment counts
                      $assessment_count = $this->model_penilaian->hitung_penilaian_pelamar($application->id);
                      $completed_count = $this->model_penilaian->hitung_penilaian_selesai($application->id);
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
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/detail_lamaran/' . $application->id) ?>"><i class="fas fa-eye me-2"></i> Lihat Detail</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/assign_assessment/' . $application->job_id . '/' . $application->id) ?>"><i class="fas fa-tasks me-2"></i> Atur Penilaian</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/editPelamar/' . $application->id) ?>"><i class="fas fa-edit me-2"></i> Edit</a></li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <a class="dropdown-item border-radius-md text-danger btn-delete" href="javascript:void(0)" data-id="<?= $application->id ?>" data-name="<?= $user->full_name ?>">
                              <i class="fas fa-trash me-2"></i> Hapus
                            </a>
                          </li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/downloadResume/' . $application->id) ?>"><i class="fas fa-download me-2"></i> Download Resume</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/printPelamar/' . $application->id) ?>"><i class="fas fa-print me-2"></i> Cetak Lamaran</a></li>
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // DataTables initialization is handled by the global datatables-init.js script

    // Handle status update with SweetAlert2
    const statusUpdateLinks = document.querySelectorAll('.status-update');
    statusUpdateLinks.forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault();

        const applicationId = this.getAttribute('data-id');
        const status = this.getAttribute('data-status');
        const applicantName = this.getAttribute('data-name');

        // Determine status text and color based on status value
        let statusText = '';
        let confirmButtonColor = '#3085d6';

        switch(status) {
          case 'pending':
            statusText = 'Pending';
            confirmButtonColor = '#fb6340'; // warning
            break;
          case 'reviewed':
            statusText = 'Direview';
            confirmButtonColor = '#11cdef'; // info
            break;
          case 'interview':
            statusText = 'Interview';
            confirmButtonColor = '#5e72e4'; // primary
            break;
          case 'diterima':
            statusText = 'Diterima';
            confirmButtonColor = '#2dce89'; // success
            break;
          case 'ditolak':
            statusText = 'Ditolak';
            confirmButtonColor = '#f5365c'; // danger
            break;
          default:
            statusText = status;
        }

        Swal.fire({
          title: 'Konfirmasi Perubahan Status',
          text: `Apakah Anda yakin ingin mengubah status lamaran ${applicantName} menjadi "${statusText}"?`,
          icon: status === 'ditolak' ? 'warning' : 'question',
          showCancelButton: true,
          confirmButtonColor: confirmButtonColor,
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, Ubah Status',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = `<?= base_url('admin/updateStatusPelamar/') ?>${applicationId}/${status}`;
          }
        });
      });
    });

    // Handle delete with SweetAlert2
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(function(button) {
      button.addEventListener('click', function(e) {
        e.preventDefault();

        const applicationId = this.getAttribute('data-id');
        const applicantName = this.getAttribute('data-name');

        Swal.fire({
          title: 'Konfirmasi Hapus',
          text: `Apakah Anda yakin ingin menghapus lamaran dari ${applicantName}? Tindakan ini tidak dapat dibatalkan.`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#f5365c',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = `<?= base_url('admin/deletePelamar/') ?>${applicationId}`;
          }
        });
      });
    });
  });
</script>
