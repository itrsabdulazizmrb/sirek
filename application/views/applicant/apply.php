<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card">
      <div class="card-header pb-0">
        <div class="d-flex align-items-center">
          <p class="mb-0">Lamar Pekerjaan</p>
          <a href="<?= base_url('home/job_details/' . $job->id) ?>" class="btn btn-primary btn-sm ms-auto">Kembali ke Detail Lowongan</a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <h5 class="mb-3"><?= $job->title ?></h5>
            <div class="d-flex mb-4">
              <span class="badge badge-sm bg-gradient-<?= $job->job_type == 'full-time' ? 'success' : ($job->job_type == 'part-time' ? 'info' : ($job->job_type == 'contract' ? 'warning' : 'secondary')) ?> me-2"><?= $job->job_type == 'full-time' ? 'Full Time' : ($job->job_type == 'part-time' ? 'Part Time' : ($job->job_type == 'contract' ? 'Kontrak' : 'Magang')) ?></span>
              <span class="badge badge-sm bg-gradient-dark me-2"><?= $job->location ?></span>
              <span class="badge badge-sm bg-gradient-danger">Batas: <?= date('d M Y', strtotime($job->deadline)) ?></span>
            </div>
            <p class="text-sm mb-0"><?= substr(strip_tags($job->description), 0, 200) ?>...</p>
          </div>
        </div>

        <hr class="horizontal dark">

        <?= form_open_multipart('applicant/apply/' . $job->id) ?>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="cover_letter" class="form-control-label">Surat Lamaran</label>
                <textarea class="form-control" id="cover_letter" name="cover_letter" rows="6" placeholder="Tuliskan surat lamaran Anda di sini..."><?= set_value('cover_letter') ?></textarea>
                <?= form_error('cover_letter', '<small class="text-danger">', '</small>') ?>
                <small class="form-text text-muted">Jelaskan mengapa Anda tertarik dengan posisi ini dan mengapa Anda adalah kandidat yang tepat.</small>
              </div>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-md-12">
              <div class="form-group">
                <label for="resume" class="form-control-label">Resume</label>
                <?php if ($profile && $profile->resume) : ?>
                  <div class="d-flex align-items-center mb-3">
                    <div>
                      <i class="ni ni-single-copy-04 text-primary me-2"></i>
                      <span><?= $profile->resume ?></span>
                    </div>
                    <a href="<?= base_url('uploads/resumes/' . $profile->resume) ?>" class="btn btn-sm btn-outline-primary ms-auto" target="_blank">Lihat</a>
                  </div>
                  <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="use_existing_resume" name="use_existing_resume" value="1" checked>
                    <label class="form-check-label" for="use_existing_resume">
                      Gunakan resume yang sudah ada
                    </label>
                  </div>
                  <div id="new_resume_upload" style="display: none;">
                    <input class="form-control" type="file" id="resume" name="resume">
                    <small class="form-text text-muted">Format yang diizinkan: PDF, DOC, DOCX. Ukuran maksimal: 2MB.</small>
                  </div>
                <?php else : ?>
                  <input class="form-control" type="file" id="resume" name="resume">
                  <small class="form-text text-muted">Format yang diizinkan: PDF, DOC, DOCX. Ukuran maksimal: 2MB.</small>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div class="row mt-4">
            <div class="col-md-12">
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="terms_agreement" name="terms_agreement" value="1" required>
                  <label class="form-check-label" for="terms_agreement">
                    Saya menyatakan bahwa semua informasi yang saya berikan adalah benar dan akurat.
                  </label>
                </div>
              </div>
            </div>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <a href="<?= base_url('home/job_details/' . $job->id) ?>" class="btn btn-light me-2">Batal</a>
            <button type="submit" class="btn btn-primary">Kirim Lamaran</button>
          </div>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Toggle resume upload based on checkbox
    const useExistingResumeCheckbox = document.getElementById('use_existing_resume');
    const newResumeUpload = document.getElementById('new_resume_upload');

    if (useExistingResumeCheckbox) {
      useExistingResumeCheckbox.addEventListener('change', function() {
        if (this.checked) {
          newResumeUpload.style.display = 'none';
        } else {
          newResumeUpload.style.display = 'block';
        }
      });
    }
  });
</script>
