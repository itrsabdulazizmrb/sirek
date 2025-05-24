<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Penilaian Saya</h6>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Pantau semua penilaian untuk lamaran pekerjaan Anda</span>
        </p>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <?php if (empty($assessments)) : ?>
            <div class="text-center py-5">
              <h4 class="text-secondary">Tidak ada penilaian ditemukan</h4>
              <p class="text-muted">Anda belum memiliki penilaian yang ditetapkan untuk Anda.</p>
              <a href="<?= base_url('pelamar/lamaran') ?>" class="btn btn-primary mt-3">Lihat Lamaran Saya</a>
            </div>
          <?php else : ?>
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Penilaian</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lowongan</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jenis</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nilai</th>
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
                      <p class="text-xs font-weight-bold mb-0"><?= $assessment->job_title ?></p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-primary"><?= $assessment->type_name ?></span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <?php if ($assessment->status == 'belum_mulai' || $assessment->status == 'not_started') : ?>
                        <span class="badge badge-sm bg-gradient-secondary">Belum Dimulai</span>
                      <?php elseif ($assessment->status == 'sedang_berlangsung' || $assessment->status == 'in_progress') : ?>
                        <span class="badge badge-sm bg-gradient-warning">Sedang Berlangsung</span>
                      <?php elseif ($assessment->status == 'selesai' || $assessment->status == 'completed') : ?>
                        <span class="badge badge-sm bg-gradient-success">Selesai</span>
                      <?php elseif ($assessment->status == 'sudah_dinilai' || $assessment->status == 'graded') : ?>
                        <span class="badge badge-sm bg-gradient-info">Sudah Dinilai</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle text-center">
                      <?php if (($assessment->status == 'sudah_dinilai' || $assessment->status == 'graded') && $assessment->nilai !== null) : ?>
                        <span class="text-secondary text-xs font-weight-bold"><?= $assessment->nilai ?></span>
                      <?php else : ?>
                        <span class="text-secondary text-xs font-weight-bold">-</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle">
                      <?php if ($assessment->status == 'belum_mulai' || $assessment->status == 'not_started' || $assessment->status == 'sedang_berlangsung' || $assessment->status == 'in_progress') : ?>
                        <a href="<?= base_url('pelamar/ikuti-penilaian/' . $assessment->id_penilaian . '/' . $assessment->id) ?>" class="btn btn-sm btn-primary">
                          <?= ($assessment->status == 'belum_mulai' || $assessment->status == 'not_started') ? 'Ikuti Penilaian' : 'Lanjutkan' ?>
                        </a>
                      <?php else : ?>
                        <a href="<?= base_url('pelamar/detail-lamaran/' . $assessment->id_lamaran) ?>" class="text-secondary font-weight-bold text-xs">
                          Lihat Lamaran
                        </a>
                      <?php endif; ?>
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

<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Panduan Penilaian</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-secondary me-3">Belum Dimulai</span>
              <p class="text-xs mb-0">Anda belum memulai penilaian ini.</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-warning me-3">Sedang Berlangsung</span>
              <p class="text-xs mb-0">Anda telah memulai tetapi belum menyelesaikan penilaian ini.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-success me-3">Selesai</span>
              <p class="text-xs mb-0">Anda telah menyelesaikan penilaian ini dan sedang menunggu penilaian.</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-info me-3">Sudah Dinilai</span>
              <p class="text-xs mb-0">Penilaian ini telah dinilai dan nilai Anda sudah tersedia.</p>
            </div>
          </div>
        </div>

        <div class="alert alert-info mt-3" role="alert">
          <h6 class="alert-heading mb-1">Tips Penilaian</h6>
          <ul class="mb-0 ps-4">
            <li>Pastikan Anda memiliki koneksi internet yang stabil sebelum memulai penilaian.</li>
            <li>Beberapa penilaian memiliki batas waktu. Setelah dimulai, Anda harus menyelesaikannya dalam waktu yang ditentukan.</li>
            <li>Baca semua pertanyaan dengan seksama sebelum menjawab.</li>
            <li>Untuk penilaian teknis, pastikan Anda memahami persyaratan sebelum mengerjakan.</li>
            <li>Untuk pertanyaan pilihan ganda, eliminasi jawaban yang jelas-jelas salah terlebih dahulu.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
