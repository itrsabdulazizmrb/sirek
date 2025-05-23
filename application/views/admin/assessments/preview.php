<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Pratinjau Penilaian: <?= $assessment->title ?></h6>
          <a href="<?= base_url('admin/soal_penilaian/' . $assessment->id) ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Soal
          </a>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Pratinjau tampilan penilaian seperti yang akan dilihat oleh pelamar</span>
        </p>
      </div>
      <div class="card-body">
        <div class="row mb-4">
          <div class="col-md-12">
            <div class="card card-body border">
              <h5 class="mb-3"><?= $assessment->title ?></h5>
              <p><?= $assessment->description ?></p>
              <div class="row">
                <div class="col-md-4">
                  <p class="mb-1"><strong>Jenis Penilaian:</strong> <?= $assessment->type_name ?></p>
                </div>
                <div class="col-md-4">
                  <p class="mb-1"><strong>Batas Waktu:</strong> <?= $assessment->time_limit ? $assessment->time_limit . ' menit' : 'Tidak ada' ?></p>
                </div>
                <div class="col-md-4">
                  <p class="mb-1"><strong>Nilai Kelulusan:</strong> <?= $assessment->passing_score ?>%</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <?php if (empty($questions)) : ?>
          <div class="alert alert-info" role="alert">
            <strong>Info!</strong> Belum ada soal yang ditambahkan untuk penilaian ini.
          </div>
        <?php else : ?>
          <form>
            <?php $question_number = 1; ?>
            <?php foreach ($questions as $question) : ?>
              <div class="card mb-4">
                <div class="card-header p-3">
                  <h6 class="mb-0">Soal <?= $question_number ?>:</h6>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <p><?= $question->question_text ?></p>
                    <p class="text-xs text-muted">Poin: <?= $question->points ?></p>
                  </div>

                  <?php if ($question->question_type == 'multiple_choice') : ?>
                    <div class="mb-3">
                      <?php foreach ($question->options as $index => $option) : ?>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="question_<?= $question->id ?>" id="option_<?= $question->id ?>_<?= $index ?>" value="<?= $option->id ?>">
                          <label class="form-check-label" for="option_<?= $question->id ?>_<?= $index ?>">
                            <?= $option->option_text ?>
                            <?php if ($option->is_correct) : ?>
                              <span class="text-success ms-2"><i class="fas fa-check"></i> (Jawaban Benar)</span>
                            <?php endif; ?>
                          </label>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php elseif ($question->question_type == 'true_false') : ?>
                    <div class="mb-3">
                      <?php foreach ($question->options as $index => $option) : ?>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="question_<?= $question->id ?>" id="option_<?= $question->id ?>_<?= $index ?>" value="<?= $option->id ?>">
                          <label class="form-check-label" for="option_<?= $question->id ?>_<?= $index ?>">
                            <?= $option->option_text ?>
                            <?php if ($option->is_correct) : ?>
                              <span class="text-success ms-2"><i class="fas fa-check"></i> (Jawaban Benar)</span>
                            <?php endif; ?>
                          </label>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php elseif ($question->question_type == 'essay') : ?>
                    <div class="mb-3">
                      <textarea class="form-control" rows="4" placeholder="Jawaban esai..." disabled></textarea>
                      <p class="text-xs text-muted mt-1">Jawaban esai akan dinilai secara manual oleh admin.</p>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
              <?php $question_number++; ?>
            <?php endforeach; ?>

            <div class="d-grid gap-2 col-md-6 mx-auto mt-4">
              <button type="button" class="btn btn-primary" disabled>
                <i class="fas fa-paper-plane me-2"></i> Kirim Jawaban
              </button>
              <p class="text-center text-xs text-muted mt-2">Tombol ini hanya untuk pratinjau dan tidak berfungsi.</p>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
