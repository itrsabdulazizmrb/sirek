<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Tambah Penilaian Baru</h6>
          <a href="<?= base_url('admin/penilaian') ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Penilaian
          </a>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Isi formulir di bawah ini untuk menambahkan penilaian baru</span>
        </p>
      </div>
      <div class="card-body">
        <?= form_open('admin/tambah_penilaian', ['class' => 'needs-validation']) ?>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="title" class="form-control-label">Judul Penilaian <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title') ?>" required>
                <?= form_error('title', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="assessment_type_id" class="form-control-label">Tipe Penilaian <span class="text-danger">*</span></label>
                <select class="form-control" id="assessment_type_id" name="assessment_type_id" required>
                  <option value="">Pilih Tipe Penilaian</option>
                  <?php foreach ($assessment_types as $type) : ?>
                    <option value="<?= $type->id ?>" <?= set_select('assessment_type_id', $type->id) ?>><?= $type->name ?></option>
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
                <textarea class="form-control" id="description" name="description" rows="4"><?= set_value('description') ?></textarea>
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
                    <option value="<?= $job->id ?>" <?= set_select('job_id', $job->id) ?>><?= $job->title ?></option>
                  <?php endforeach; ?>
                </select>
                <small class="text-muted">Jika dipilih, penilaian ini hanya akan tersedia untuk lowongan tertentu.</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="time_limit" class="form-control-label">Batas Waktu (menit)</label>
                <input type="number" class="form-control" id="time_limit" name="time_limit" value="<?= set_value('time_limit') ?>" min="0">
                <small class="text-muted">Biarkan kosong jika tidak ada batas waktu.</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="passing_score" class="form-control-label">Nilai Kelulusan</label>
                <input type="number" class="form-control" id="passing_score" name="passing_score" value="<?= set_value('passing_score') ?>" min="0" max="100">
                <small class="text-muted">Nilai minimum untuk lulus penilaian ini (0-100).</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="max_attempts" class="form-control-label">Jumlah Percobaan Maksimum</label>
                <input type="number" class="form-control" id="max_attempts" name="max_attempts" value="<?= set_value('max_attempts', 1) ?>" min="1">
                <small class="text-muted">Berapa kali pelamar dapat mengambil penilaian ini.</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="instructions" class="form-control-label">Instruksi untuk Pelamar</label>
                <textarea class="form-control" id="instructions" name="instructions" rows="4"><?= set_value('instructions') ?></textarea>
                <?= form_error('instructions', '<small class="text-danger">', '</small>') ?>
                <small class="text-muted">Instruksi yang akan ditampilkan kepada pelamar sebelum memulai penilaian.</small>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= set_checkbox('is_active', '1', true) ?>>
                <label class="form-check-label" for="is_active">Aktifkan Penilaian</label>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary">Simpan Penilaian</button>
              <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
          </div>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Panduan Membuat Penilaian</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h6 class="text-uppercase text-sm">Tipe Penilaian</h6>
            <ul class="text-sm">
              <li><strong>Tes Kepribadian:</strong> Menilai karakteristik kepribadian dan kesesuaian budaya.</li>
              <li><strong>Tes Teknis:</strong> Menilai keterampilan teknis dan pengetahuan spesifik.</li>
              <li><strong>Tes Logika:</strong> Menilai kemampuan pemecahan masalah dan penalaran logis.</li>
              <li><strong>Tes Bahasa:</strong> Menilai kemampuan komunikasi dan bahasa.</li>
              <li><strong>Tes Pengetahuan:</strong> Menilai pengetahuan umum atau spesifik industri.</li>
            </ul>
          </div>
          <div class="col-md-6">
            <h6 class="text-uppercase text-sm">Tipe Soal</h6>
            <ul class="text-sm">
              <li><strong>Pilihan Ganda:</strong> Pelamar memilih satu jawaban dari beberapa opsi.</li>
              <li><strong>Benar/Salah:</strong> Pelamar menentukan apakah pernyataan benar atau salah.</li>
              <li><strong>Esai:</strong> Pelamar memberikan jawaban dalam bentuk teks.</li>
              <li><strong>Unggah File:</strong> Pelamar mengunggah file sebagai jawaban.</li>
            </ul>
          </div>
        </div>

        <div class="alert alert-info mt-3" role="alert">
          <h6 class="alert-heading mb-1">Tips Membuat Penilaian Efektif</h6>
          <ul class="mb-0">
            <li>Tentukan tujuan penilaian dengan jelas</li>
            <li>Buat soal yang relevan dengan posisi yang dilamar</li>
            <li>Tetapkan batas waktu yang wajar</li>
            <li>Berikan instruksi yang jelas kepada pelamar</li>
            <li>Gunakan berbagai tipe soal untuk menilai berbagai aspek</li>
            <li>Tetapkan nilai kelulusan yang sesuai dengan tingkat kesulitan</li>
          </ul>
        </div>

        <div class="alert alert-warning mt-3" role="alert">
          <strong>Catatan:</strong> Setelah membuat penilaian, Anda perlu menambahkan soal-soal ke dalamnya melalui halaman "Kelola Soal".
        </div>
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
