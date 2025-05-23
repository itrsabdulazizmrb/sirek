<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Edit Penilaian</h6>
          <a href="<?= base_url('admin/penilaian') ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Penilaian
          </a>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Edit informasi penilaian</span>
        </p>
      </div>
      <div class="card-body">
        <?= form_open('admin/edit_penilaian/' . $assessment->id, ['class' => 'needs-validation']) ?>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="title" class="form-control-label">Judul Penilaian <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $assessment->title) ?>" required>
                <?= form_error('title', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="assessment_type_id" class="form-control-label">Tipe Penilaian <span class="text-danger">*</span></label>
                <select class="form-control" id="assessment_type_id" name="assessment_type_id" required>
                  <option value="">Pilih Tipe Penilaian</option>
                  <?php foreach ($assessment_types as $type) : ?>
                    <option value="<?= $type->id ?>" <?= set_select('assessment_type_id', $type->id, ($assessment->assessment_type_id == $type->id)) ?>><?= $type->name ?></option>
                  <?php endforeach; ?>
                </select>
                <?= form_error('assessment_type_id', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="description" class="form-control-label">Deskripsi Penilaian</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?= set_value('description', $assessment->description) ?></textarea>
                <?= form_error('description', '<small class="text-danger">', '</small>') ?>
                <small class="text-muted">Jelaskan tujuan dan instruksi penilaian ini.</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="job_id" class="form-control-label">Lowongan (Opsional)</label>
                <select class="form-control" id="job_id" name="job_id">
                  <option value="">Semua Lowongan</option>
                  <?php foreach ($jobs as $job) : ?>
                    <option value="<?= $job->id ?>" <?= set_select('job_id', $job->id, ($assessment->job_id == $job->id)) ?>><?= $job->title ?></option>
                  <?php endforeach; ?>
                </select>
                <small class="text-muted">Jika dipilih, penilaian ini hanya akan tersedia untuk lowongan tertentu.</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="time_limit" class="form-control-label">Batas Waktu (menit)</label>
                <input type="number" class="form-control" id="time_limit" name="time_limit" value="<?= set_value('time_limit', $assessment->time_limit) ?>" min="0">
                <small class="text-muted">Biarkan kosong jika tidak ada batas waktu.</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="passing_score" class="form-control-label">Nilai Kelulusan</label>
                <input type="number" class="form-control" id="passing_score" name="passing_score" value="<?= set_value('passing_score', $assessment->passing_score) ?>" min="0" max="100">
                <small class="text-muted">Nilai minimum untuk lulus penilaian ini (0-100).</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="max_attempts" class="form-control-label">Jumlah Percobaan Maksimum</label>
                <input type="number" class="form-control" id="max_attempts" name="max_attempts" value="<?= set_value('max_attempts', $assessment->max_attempts) ?>" min="1">
                <small class="text-muted">Berapa kali pelamar dapat mengambil penilaian ini.</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="instructions" class="form-control-label">Instruksi untuk Pelamar</label>
                <textarea class="form-control" id="instructions" name="instructions" rows="4"><?= set_value('instructions', $assessment->instructions) ?></textarea>
                <?= form_error('instructions', '<small class="text-danger">', '</small>') ?>
                <small class="text-muted">Instruksi yang akan ditampilkan kepada pelamar sebelum memulai penilaian.</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= set_checkbox('is_active', '1', ($assessment->is_active == 1)) ?>>
                <label class="form-check-label" for="is_active">Aktifkan Penilaian</label>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
              <a href="<?= base_url('admin/penilaian') ?>" class="btn btn-secondary">Batal</a>
            </div>
          </div>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Soal Penilaian</h6>
          <a href="<?= base_url('admin/soal_penilaian/' . $assessment->id) ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-list me-2"></i> Kelola Soal
          </a>
        </div>
      </div>
      <div class="card-body">
        <?php if (empty($questions)) : ?>
          <p class="text-center">Belum ada soal untuk penilaian ini.</p>
          <div class="text-center">
            <a href="<?= base_url('admin/tambah_soal/' . $assessment->id) ?>" class="btn btn-sm btn-primary">
              <i class="fas fa-plus me-2"></i> Tambah Soal
            </a>
          </div>
        <?php else : ?>
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Soal</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipe</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Poin</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($questions as $question) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= character_limiter($question->question_text, 50) ?></h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">
                        <?= $question->question_type == 'multiple_choice' ? 'Pilihan Ganda' :
                            ($question->question_type == 'true_false' ? 'Benar/Salah' :
                            ($question->question_type == 'essay' ? 'Esai' : 'Unggah File')) ?>
                      </p>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= $question->points ?></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="text-center mt-3">
            <a href="<?= base_url('admin/soal_penilaian/' . $assessment->id) ?>" class="btn btn-sm btn-primary">Lihat Semua Soal</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="card">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Pelamar yang Ditugaskan</h6>
          <a href="<?= base_url('admin/assign_assessment_to_applicants/' . $assessment->id) ?>" class="btn btn-sm btn-primary">
            <i class="fas fa-user-plus me-2"></i> Tetapkan ke Pelamar
          </a>
        </div>
      </div>
      <div class="card-body">
        <?php
        $applicants = $this->model_penilaian->dapatkan_pelamar_penilaian($assessment->id, 5);
        if (empty($applicants)) :
        ?>
          <p class="text-center">Belum ada pelamar yang ditugaskan untuk penilaian ini.</p>
        <?php else : ?>
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pelamar</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lowongan</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Skor</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($applicants as $applicant) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="<?= base_url('assets/img/team-2.jpg') ?>" class="avatar avatar-sm me-3" alt="user1">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= $applicant->applicant_name ?></h6>
                          <p class="text-xs text-secondary mb-0"><?= $applicant->applicant_email ?></p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?= $applicant->job_title ?></p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-<?= $applicant->status == 'not_started' ? 'secondary' : ($applicant->status == 'in_progress' ? 'warning' : ($applicant->status == 'completed' ? 'success' : 'info')) ?>"><?= $applicant->status == 'not_started' ? 'Belum Dimulai' : ($applicant->status == 'in_progress' ? 'Sedang Dikerjakan' : ($applicant->status == 'completed' ? 'Selesai' : 'Dinilai')) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <?php if ($applicant->status == 'graded' && $applicant->score !== null) : ?>
                        <span class="text-secondary text-xs font-weight-bold"><?= $applicant->score ?></span>
                      <?php else : ?>
                        <span class="text-secondary text-xs font-weight-bold">-</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="text-center mt-3">
            <a href="<?= base_url('admin/hasil-penilaian/' . $assessment->id) ?>" class="btn btn-sm btn-primary">Lihat Semua Pelamar</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize rich text editors
    if (typeof ClassicEditor !== 'undefined') {
      ClassicEditor.create(document.querySelector('#description')).catch(error => {
        console.error(error);
      });

      ClassicEditor.create(document.querySelector('#instructions')).catch(error => {
        console.error(error);
      });
    }
  });
</script>
