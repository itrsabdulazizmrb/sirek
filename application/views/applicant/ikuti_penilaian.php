<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-0"><?= $assessment->judul ?></h6>
            <p class="text-sm mb-0">
              <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
              <span class="font-weight-bold">Penilaian <?= $assessment->type_name ?></span>
            </p>
          </div>
          <?php if ($assessment->batas_waktu) : ?>
            <div class="bg-gradient-danger px-3 py-2 rounded text-white">
              <div class="d-flex align-items-center">
                <i class="ni ni-time-alarm me-2"></i>
                <span id="assessment-timer" data-time-limit="<?= $assessment->batas_waktu ?>"><?= $assessment->batas_waktu ?>:00</span>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <?php if ($assessment->deskripsi) : ?>
          <div class="alert alert-info" role="alert">
            <h6 class="alert-heading mb-1">Deskripsi Penilaian</h6>
            <p class="mb-0"><?= $assessment->deskripsi ?></p>
          </div>
        <?php endif; ?>

        <?php if (empty($questions)) : ?>
          <div class="text-center py-5">
            <h4 class="text-secondary">Tidak ada pertanyaan ditemukan</h4>
            <p class="text-muted">Penilaian ini belum memiliki pertanyaan.</p>
            <a href="<?= base_url('pelamar/penilaian') ?>" class="btn btn-primary mt-3">Kembali ke Daftar Penilaian</a>
          </div>
        <?php else : ?>
          <?= form_open('pelamar/kirim-penilaian', ['id' => 'assessment-form']) ?>
            <input type="hidden" name="assessment_id" value="<?= $assessment->id ?>">
            <input type="hidden" name="application_id" value="<?= $application_id ?>">
            <input type="hidden" name="applicant_assessment_id" value="<?= $applicant_assessment->id ?>">

            <?php $question_number = 1; ?>
            <?php foreach ($questions as $question) : ?>
              <div class="question-card mb-4">
                <h6 class="mb-3">Pertanyaan <?= $question_number ?>: <?= $question->teks_soal ?></h6>

                <?php if ($question->jenis_soal == 'pilihan_ganda' || $question->jenis_soal == 'multiple_choice') : ?>
                  <?php
                  // Get options for this question
                  $this->db->where('id_soal', $question->id);
                  $options_query = $this->db->get('pilihan_soal');
                  $options = $options_query->result();
                  ?>

                  <?php foreach ($options as $option) : ?>
                    <div class="option-item mb-2 p-3 border rounded" data-question-id="<?= $question->id ?>" data-option-id="<?= $option->id ?>">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_<?= $question->id ?>" id="option_<?= $option->id ?>" value="<?= $option->id ?>">
                        <label class="form-check-label" for="option_<?= $option->id ?>">
                          <?= $option->teks_pilihan ?>
                        </label>
                      </div>
                    </div>
                  <?php endforeach; ?>

                <?php elseif ($question->jenis_soal == 'benar_salah' || $question->jenis_soal == 'true_false') : ?>
                  <?php
                  // Get options for true/false question
                  $this->db->where('id_soal', $question->id);
                  $options_query = $this->db->get('pilihan_soal');
                  $options = $options_query->result();
                  ?>

                  <?php if (!empty($options)) : ?>
                    <?php foreach ($options as $option) : ?>
                      <div class="option-item mb-2 p-3 border rounded" data-question-id="<?= $question->id ?>" data-option-id="<?= $option->id ?>">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="question_<?= $question->id ?>" id="option_<?= $option->id ?>" value="<?= $option->id ?>">
                          <label class="form-check-label" for="option_<?= $option->id ?>">
                            <?= $option->teks_pilihan ?>
                          </label>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else : ?>
                    <!-- Fallback for true/false if no options in database -->
                    <div class="option-item mb-2 p-3 border rounded" data-question-id="<?= $question->id ?>" data-option-id="true">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_<?= $question->id ?>" id="option_<?= $question->id ?>_true" value="true">
                        <label class="form-check-label" for="option_<?= $question->id ?>_true">
                          Benar
                        </label>
                      </div>
                    </div>
                    <div class="option-item mb-2 p-3 border rounded" data-question-id="<?= $question->id ?>" data-option-id="false">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_<?= $question->id ?>" id="option_<?= $question->id ?>_false" value="false">
                        <label class="form-check-label" for="option_<?= $question->id ?>_false">
                          Salah
                        </label>
                      </div>
                    </div>
                  <?php endif; ?>

                <?php elseif ($question->jenis_soal == 'esai' || $question->jenis_soal == 'essay') : ?>
                  <div class="form-group">
                    <textarea name="question_<?= $question->id ?>" class="form-control" rows="5" placeholder="Ketik jawaban Anda di sini..."></textarea>
                  </div>

                <?php elseif ($question->jenis_soal == 'unggah_file' || $question->jenis_soal == 'file_upload') : ?>
                  <div class="form-group">
                    <input type="file" name="question_<?= $question->id ?>" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small class="text-muted">Jenis file yang diizinkan: PDF, DOC, DOCX, JPG, JPEG, PNG. Ukuran maksimal: 2MB</small>
                  </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mt-3">
                  <span class="badge bg-gradient-<?= ($question->jenis_soal == 'pilihan_ganda' || $question->jenis_soal == 'multiple_choice') ? 'primary' : (($question->jenis_soal == 'benar_salah' || $question->jenis_soal == 'true_false') ? 'info' : (($question->jenis_soal == 'esai' || $question->jenis_soal == 'essay') ? 'warning' : 'success')) ?>">
                    <?= ucfirst(str_replace('_', ' ', $question->jenis_soal)) ?>
                  </span>
                  <span class="text-xs text-muted">Poin: <?= $question->poin ?></span>
                </div>
              </div>

              <?php $question_number++; ?>
            <?php endforeach; ?>

            <div class="d-flex justify-content-between mt-4">
              <a href="<?= base_url('pelamar/penilaian') ?>" class="btn btn-outline-secondary">Batal</a>
              <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
            </div>
          <?= form_close() ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Mark assessment as in progress
    <?php if ($applicant_assessment->status == 'belum_mulai' || $applicant_assessment->status == 'not_started') : ?>
      fetch('<?= base_url('pelamar/perbarui_status_penilaian/' . $applicant_assessment->id . '/sedang_berlangsung') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
    <?php endif; ?>

    // Assessment timer
    const assessmentTimer = document.getElementById('assessment-timer');
    if (assessmentTimer) {
      const timeLimit = parseInt(assessmentTimer.getAttribute('data-time-limit')) * 60; // Convert to seconds
      let timeRemaining = timeLimit;

      const timerInterval = setInterval(function() {
        timeRemaining--;

        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;

        assessmentTimer.textContent = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');

        if (timeRemaining <= 300) { // 5 minutes remaining
          assessmentTimer.classList.add('text-danger');
        }

        if (timeRemaining <= 0) {
          clearInterval(timerInterval);
          Swal.fire({
            icon: 'warning',
            title: 'Waktu Habis!',
            text: 'Penilaian Anda akan dikirimkan secara otomatis.',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
          }).then(() => {
            document.getElementById('assessment-form').submit();
          });
        }
      }, 1000);
    }

    // Option selection for multiple choice and true/false questions
    const optionItems = document.querySelectorAll('.option-item');
    optionItems.forEach(function(item) {
      item.addEventListener('click', function() {
        const questionId = this.getAttribute('data-question-id');
        const optionId = this.getAttribute('data-option-id');
        const radioInput = document.querySelector('input[name="question_' + questionId + '"][value="' + optionId + '"]');

        // Remove selected class from all options in this question
        const questionOptions = document.querySelectorAll('.option-item[data-question-id="' + questionId + '"]');
        questionOptions.forEach(function(option) {
          option.classList.remove('selected');
        });

        // Add selected class to clicked option
        this.classList.add('selected');

        // Check the radio input
        if (radioInput) {
          radioInput.checked = true;
        }
      });
    });
  });
</script>
