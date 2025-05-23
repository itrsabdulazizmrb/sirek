<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Kelola Opsi Jawaban</h6>
          <div>
            <a href="<?= base_url('admin/assessment_questions/' . $question->assessment_id) ?>" class="btn btn-sm btn-outline-primary">
              <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Soal
            </a>
          </div>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Kelola opsi jawaban untuk soal ini</span>
        </p>
      </div>
      <div class="card-body">
        <!-- Question Info -->
        <div class="row mb-4">
          <div class="col-md-12">
            <h6 class="text-uppercase text-sm">Soal</h6>
            <div class="p-3 border rounded">
              <?= $question->teks_soal ?>
            </div>
            <div class="d-flex justify-content-between mt-2">
              <p class="text-xs text-secondary mb-0">
                <strong>Tipe:</strong>
                <?php
                switch($question->jenis_soal) {
                  case 'multiple_choice':
                    echo 'Pilihan Ganda';
                    break;
                  case 'true_false':
                    echo 'Benar/Salah';
                    break;
                  default:
                    echo ucfirst($question->jenis_soal);
                }
                ?>
              </p>
              <p class="text-xs text-secondary mb-0"><strong>Poin:</strong> <?= $question->poin ?></p>
            </div>
          </div>
        </div>

        <!-- Options Form -->
        <div class="row">
          <div class="col-md-12">
            <h6 class="text-uppercase text-sm">Opsi Jawaban</h6>
            <?= form_open('admin/simpan-opsi-soal/' . $question->id, ['class' => 'needs-validation']) ?>
              <?php if ($question->jenis_soal == 'true_false') : ?>
                <!-- True/False Options -->
                <div class="row mb-3">
                  <div class="col-md-10">
                    <div class="form-group">
                      <label class="form-control-label">Opsi Benar</label>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="correct_option" id="option_true" value="true" <?= (empty($options) || (isset($options[0]) && $options[0]->benar && $options[0]->teks_pilihan == 'Benar')) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="option_true">
                          Benar
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="correct_option" id="option_false" value="false" <?= (isset($options[1]) && $options[1]->benar && $options[1]->teks_pilihan == 'Salah') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="option_false">
                          Salah
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              <?php else : ?>
                <!-- Multiple Choice Options -->
                <div id="options-container">
                  <?php if (empty($options)) : ?>
                    <!-- Default 4 options if none exist -->
                    <?php for ($i = 1; $i <= 4; $i++) : ?>
                      <div class="row mb-3 option-row">
                        <div class="col-md-8">
                          <div class="form-group">
                            <label class="form-control-label">Opsi <?= $i ?></label>
                            <input type="text" name="options[]" class="form-control" required>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label class="form-control-label">Jawaban Benar</label>
                            <div class="form-check mt-2">
                              <input class="form-check-input" type="radio" name="correct_option" value="<?= $i-1 ?>" <?= ($i == 1) ? 'checked' : '' ?>>
                              <label class="form-check-label">
                                Benar
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label class="form-control-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger btn-sm d-block mt-2 remove-option" <?= ($i <= 2) ? 'disabled' : '' ?>>
                              <i class="fas fa-trash"></i> Hapus
                            </button>
                          </div>
                        </div>
                      </div>
                    <?php endfor; ?>
                  <?php else : ?>
                    <!-- Existing options -->
                    <?php foreach ($options as $index => $option) : ?>
                      <div class="row mb-3 option-row">
                        <div class="col-md-8">
                          <div class="form-group">
                            <label class="form-control-label">Opsi <?= $index + 1 ?></label>
                            <input type="text" name="options[]" class="form-control" value="<?= $option->teks_pilihan ?>" required>
                            <input type="hidden" name="option_ids[]" value="<?= $option->id ?>">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label class="form-control-label">Jawaban Benar</label>
                            <div class="form-check mt-2">
                              <input class="form-check-input" type="radio" name="correct_option" value="<?= $index ?>" <?= $option->benar ? 'checked' : '' ?>>
                              <label class="form-check-label">
                                Benar
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label class="form-control-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-danger btn-sm d-block mt-2 remove-option" <?= (count($options) <= 2) ? 'disabled' : '' ?>>
                              <i class="fas fa-trash"></i> Hapus
                            </button>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </div>

                <div class="row mb-3">
                  <div class="col-md-12">
                    <button type="button" id="add-option" class="btn btn-outline-primary btn-sm">
                      <i class="fas fa-plus"></i> Tambah Opsi
                    </button>
                  </div>
                </div>
              <?php endif; ?>

              <div class="row mt-4">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary">Simpan Opsi</button>
                  <a href="<?= base_url('admin/assessment_questions/' . $question->assessment_id) ?>" class="btn btn-secondary">Batal</a>
                </div>
              </div>
            <?= form_close() ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Tips Membuat Opsi Jawaban</h6>
      </div>
      <div class="card-body">
        <ul class="mb-0">
          <li>Pastikan semua opsi masuk akal dan relevan dengan pertanyaan</li>
          <li>Hindari penggunaan "semua di atas" atau "tidak ada di atas" sebagai opsi</li>
          <li>Buat semua opsi dengan panjang yang relatif sama</li>
          <li>Hindari petunjuk yang tidak disengaja pada jawaban yang benar</li>
          <li>Pastikan hanya ada satu jawaban yang benar</li>
          <li>Untuk soal Benar/Salah, pastikan pernyataan jelas benar atau salah</li>
        </ul>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Add option button functionality
    const addOptionBtn = document.getElementById('add-option');
    if (addOptionBtn) {
      addOptionBtn.addEventListener('click', function() {
        const optionsContainer = document.getElementById('options-container');
        const optionRows = document.querySelectorAll('.option-row');
        const newIndex = optionRows.length;

        const newRow = document.createElement('div');
        newRow.className = 'row mb-3 option-row';
        newRow.innerHTML = `
          <div class="col-md-8">
            <div class="form-group">
              <label class="form-control-label">Opsi ${newIndex + 1}</label>
              <input type="text" name="options[]" class="form-control" required>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-control-label">Jawaban Benar</label>
              <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="correct_option" value="${newIndex}">
                <label class="form-check-label">
                  Benar
                </label>
              </div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label class="form-control-label">&nbsp;</label>
              <button type="button" class="btn btn-outline-danger btn-sm d-block mt-2 remove-option">
                <i class="fas fa-trash"></i> Hapus
              </button>
            </div>
          </div>
        `;

        optionsContainer.appendChild(newRow);
        updateRemoveButtons();
      });
    }

    // Remove option button functionality
    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-option') || e.target.closest('.remove-option')) {
        const button = e.target.classList.contains('remove-option') ? e.target : e.target.closest('.remove-option');
        if (!button.disabled) {
          const row = button.closest('.option-row');
          row.remove();
          updateRemoveButtons();
          updateOptionLabels();
        }
      }
    });

    function updateRemoveButtons() {
      const optionRows = document.querySelectorAll('.option-row');
      optionRows.forEach(row => {
        const removeButton = row.querySelector('.remove-option');
        if (removeButton) {
          removeButton.disabled = optionRows.length <= 2;
        }
      });
    }

    function updateOptionLabels() {
      const optionRows = document.querySelectorAll('.option-row');
      optionRows.forEach((row, index) => {
        const label = row.querySelector('label');
        if (label && label.textContent.startsWith('Opsi ')) {
          label.textContent = `Opsi ${index + 1}`;
        }

        const radioInput = row.querySelector('input[type="radio"]');
        if (radioInput) {
          radioInput.value = index;
        }
      });
    }
  });
</script>
