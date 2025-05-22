<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Tambah Soal Baru</h6>
          <a href="<?= base_url('admin/soal_penilaian/' . $assessment->id) ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Soal
          </a>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Tambahkan soal baru untuk penilaian: <?= $assessment->title ?></span>
        </p>
      </div>
      <div class="card-body">
        <?= form_open('admin/add_question/' . $assessment->id, ['class' => 'needs-validation']) ?>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="question_text" class="form-control-label">Teks Soal <span class="text-danger">*</span></label>
                <textarea id="question_text" name="question_text" class="form-control" rows="4"><?= set_value('question_text') ?></textarea>
                <?= form_error('question_text', '<small class="text-danger">', '</small>') ?>
                <small class="form-text text-muted">Masukkan teks soal dengan jelas dan lengkap.</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="question_type" class="form-control-label">Tipe Soal <span class="text-danger">*</span></label>
                <select id="question_type" name="question_type" class="form-control">
                  <option value="">Pilih Tipe Soal</option>
                  <option value="multiple_choice" <?= set_select('question_type', 'multiple_choice') ?>>Pilihan Ganda</option>
                  <option value="true_false" <?= set_select('question_type', 'true_false') ?>>Benar/Salah</option>
                  <option value="essay" <?= set_select('question_type', 'essay') ?>>Esai</option>
                  <option value="file_upload" <?= set_select('question_type', 'file_upload') ?>>Unggah File</option>
                </select>
                <?= form_error('question_type', '<small class="text-danger">', '</small>') ?>
                <small class="form-text text-muted">Pilih tipe soal yang sesuai.</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="points" class="form-control-label">Poin <span class="text-danger">*</span></label>
                <input type="number" id="points" name="points" class="form-control" value="<?= set_value('points', 1) ?>" min="1">
                <?= form_error('points', '<small class="text-danger">', '</small>') ?>
                <small class="form-text text-muted">Tentukan jumlah poin untuk soal ini.</small>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary">Simpan Soal</button>
              <button type="reset" class="btn btn-secondary">Reset</button>
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
        <h6>Informasi Penilaian</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <p class="text-xs text-secondary mb-1">Judul Penilaian:</p>
            <p class="text-sm mb-2"><?= $assessment->title ?></p>
          </div>
          <div class="col-md-6">
            <p class="text-xs text-secondary mb-1">Tipe Penilaian:</p>
            <p class="text-sm mb-2"><?= $assessment->type_name ?></p>
          </div>
          <div class="col-md-6">
            <p class="text-xs text-secondary mb-1">Batas Waktu:</p>
            <p class="text-sm mb-2"><?= $assessment->time_limit ? $assessment->time_limit . ' menit' : 'Tidak ada batas waktu' ?></p>
          </div>
          <div class="col-md-6">
            <p class="text-xs text-secondary mb-1">Nilai Kelulusan:</p>
            <p class="text-sm mb-2"><?= $assessment->passing_score ? $assessment->passing_score . ' poin' : 'Tidak ditentukan' ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Tips Membuat Soal</h6>
      </div>
      <div class="card-body">
        <ul class="mb-0">
          <li><strong>Pilihan Ganda:</strong> Buat 4-5 opsi dengan satu jawaban benar.</li>
          <li><strong>Benar/Salah:</strong> Buat pernyataan yang jelas benar atau salah.</li>
          <li><strong>Esai:</strong> Berikan petunjuk tentang panjang jawaban yang diharapkan.</li>
          <li><strong>Unggah File:</strong> Jelaskan format file yang diterima dan ukuran maksimum.</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize rich text editor for question text
    if (typeof ClassicEditor !== 'undefined') {
      ClassicEditor.create(document.querySelector('#question_text')).catch(error => {
        console.error(error);
      });
    }
  });
</script>
