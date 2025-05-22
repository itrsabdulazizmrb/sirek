<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Kelola Soal Penilaian: <?= $assessment->title ?></h6>
          <div>
            <a href="<?= base_url('admin/penilaian') ?>" class="btn btn-sm btn-outline-primary me-2">
              <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Penilaian
            </a>
            <a href="<?= base_url('import/index') ?>" class="btn btn-sm btn-success me-2">
              <i class="fas fa-file-import me-2"></i> Import Soal
            </a>
            <a href="<?= base_url('admin/add_question/' . $assessment->id) ?>" class="btn btn-sm btn-primary">
              <i class="fas fa-plus me-2"></i> Tambah Soal Baru
            </a>
          </div>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Kelola semua soal untuk penilaian ini</span>
        </p>
      </div>
      <div class="card-body">
        <!-- Assessment Info -->
        <div class="row mb-4">
          <div class="col-md-6">
            <h6 class="text-uppercase text-sm">Informasi Penilaian</h6>
            <div class="row">
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
              <div class="col-md-6">
                <p class="text-xs text-secondary mb-1">Status:</p>
                <p class="text-sm mb-2">
                  <span class="badge badge-sm bg-gradient-<?= $assessment->is_active ? 'success' : 'secondary' ?>">
                    <?= $assessment->is_active ? 'Aktif' : 'Tidak Aktif' ?>
                  </span>
                </p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <h6 class="text-uppercase text-sm">Deskripsi</h6>
            <p class="text-sm"><?= $assessment->description ?></p>
          </div>
        </div>

        <!-- Questions List -->
        <?php if (empty($questions)) : ?>
          <div class="text-center py-5">
            <h4 class="text-secondary">Belum ada soal untuk penilaian ini</h4>
            <p class="text-muted">Mulai dengan menambahkan soal baru atau import soal dari Excel.</p>
            <div class="mt-3">
              <a href="<?= base_url('admin/add_question/' . $assessment->id) ?>" class="btn btn-primary me-2">
                <i class="fas fa-plus me-2"></i> Tambah Soal Baru
              </a>
              <a href="<?= base_url('import/index') ?>" class="btn btn-success">
                <i class="fas fa-file-import me-2"></i> Import Soal dari Excel
              </a>
            </div>
          </div>
        <?php else : ?>
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Soal</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipe</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Poin</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <?php $i = 1; foreach ($questions as $question) : ?>
                  <tr>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= $i++ ?></span>
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= substr(strip_tags($question->question_text), 0, 100) . (strlen(strip_tags($question->question_text)) > 100 ? '...' : '') ?></h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0">
                        <?php
                        switch($question->question_type) {
                          case 'multiple_choice':
                            echo 'Pilihan Ganda';
                            break;
                          case 'true_false':
                            echo 'Benar/Salah';
                            break;
                          case 'essay':
                            echo 'Esai';
                            break;
                          case 'file_upload':
                            echo 'Unggah File';
                            break;
                          default:
                            echo ucfirst($question->question_type);
                        }
                        ?>
                      </p>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= $question->points ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <?php if ($question->question_type == 'multiple_choice' || $question->question_type == 'true_false') : ?>
                        <?php
                        $options = $this->assessment_model->get_question_options($question->id);
                        $option_count = count($options);
                        ?>
                        <span class="text-secondary text-xs font-weight-bold"><?= $option_count ?> opsi</span>
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
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/edit_question/' . $question->id) ?>">Edit</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/question_options/' . $question->id) ?>">Kelola Opsi</a></li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <a class="dropdown-item border-radius-md text-danger btn-delete" href="<?= base_url('admin/delete_question/' . $question->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">
                              Hapus
                            </a>
                          </li>
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
</div>

<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Tips Membuat Soal Efektif</h6>
      </div>
      <div class="card-body">
        <ul class="mb-0">
          <li>Pastikan soal jelas dan tidak ambigu</li>
          <li>Gunakan bahasa yang sederhana dan mudah dipahami</li>
          <li>Untuk pilihan ganda, pastikan semua opsi masuk akal</li>
          <li>Hindari petunjuk yang tidak disengaja pada jawaban yang benar</li>
          <li>Berikan poin yang sesuai dengan tingkat kesulitan soal</li>
          <li>Urutkan soal dari yang mudah ke yang sulit</li>
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Tindakan Lainnya</h6>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <a href="<?= base_url('admin/edit_assessment/' . $assessment->id) ?>" class="btn btn-outline-primary">
            <i class="fas fa-edit me-2"></i> Edit Informasi Penilaian
          </a>
          <a href="<?= base_url('import/index') ?>" class="btn btn-outline-success">
            <i class="fas fa-file-import me-2"></i> Import Soal dari Excel
          </a>
          <a href="<?= base_url('admin/preview_assessment/' . $assessment->id) ?>" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i> Pratinjau Penilaian
          </a>
          <a href="<?= base_url('admin/assign_assessment_to_applicants/' . $assessment->id) ?>" class="btn btn-outline-success">
            <i class="fas fa-user-plus me-2"></i> Tetapkan ke Pelamar
          </a>
          <a href="<?= base_url('admin/assessment_results/' . $assessment->id) ?>" class="btn btn-outline-warning">
            <i class="fas fa-chart-bar me-2"></i> Lihat Hasil Penilaian
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
