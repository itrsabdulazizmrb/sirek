<?php
// Copy dari assessments/questions.php dengan perbaikan bahasa Indonesia
?>

<style>
/* Fix DataTables dropdown visibility */
.dt-button-collection {
    z-index: 1056 !important;
    position: absolute !important;
    background: white !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 0.375rem !important;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    min-width: 160px !important;
}

.dt-button-background {
    z-index: 1055 !important;
}

.dt-button-collection .dt-button {
    display: block !important;
    width: 100% !important;
    text-align: left !important;
    border: none !important;
    background: transparent !important;
    padding: 0.5rem 1rem !important;
    margin: 0 !important;
}

.dt-button-collection .dt-button:hover {
    background-color: #f8f9fa !important;
}

/* Ensure dropdown is visible above other elements */
.dataTables_wrapper {
    position: relative;
    z-index: 1;
}

.card {
    overflow: visible !important;
}

/* Make table larger and more spacious */
#tabelSoal {
    font-size: 14px !important;
    width: 100% !important;
}

#tabelSoal th,
#tabelSoal td {
    padding: 12px 15px !important;
    vertical-align: middle !important;
}

#tabelSoal th {
    font-weight: 600 !important;
    background-color: #f8f9fa !important;
    border-bottom: 2px solid #dee2e6 !important;
}

/* Adjust column widths for better distribution */
#tabelSoal th:nth-child(1),
#tabelSoal td:nth-child(1) {
    width: 8% !important; /* No column */
}

#tabelSoal th:nth-child(2),
#tabelSoal td:nth-child(2) {
    width: 50% !important; /* Soal column - much wider */
}

#tabelSoal th:nth-child(3),
#tabelSoal td:nth-child(3) {
    width: 15% !important; /* Tipe column */
}

#tabelSoal th:nth-child(4),
#tabelSoal td:nth-child(4) {
    width: 8% !important; /* Poin column */
}

#tabelSoal th:nth-child(5),
#tabelSoal td:nth-child(5) {
    width: 10% !important; /* Opsi column */
}

#tabelSoal th:nth-child(6),
#tabelSoal td:nth-child(6) {
    width: 9% !important; /* Aksi column */
}

/* Make the table container full width */
.table-responsive {
    width: 100% !important;
}

.card-body {
    padding: 0 !important;
}

/* Increase row height for better readability */
#tabelSoal tbody tr {
    height: 70px !important;
}

/* Better spacing for badges and buttons */
.badge {
    padding: 6px 12px !important;
    font-size: 12px !important;
}

.dropdown-toggle::after {
    margin-left: 8px !important;
}

/* Better text wrapping for long question text */
#tabelSoal td:nth-child(2) {
    word-wrap: break-word !important;
    white-space: normal !important;
    line-height: 1.4 !important;
}
</style>
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Kelola Soal Penilaian: <?= $assessment->judul ?></h6>
          <div>
            <a href="<?= base_url('admin/tambah-soal/' . $assessment->id) ?>" class="btn btn-primary btn-sm me-2">
              <i class="fas fa-plus me-2"></i> Tambah Soal
            </a>
            <a href="<?= base_url('admin/penilaian') ?>" class="btn btn-sm btn-outline-primary">
              <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
          </div>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          Kelola soal-soal untuk penilaian ini
        </p>
      </div>
    </div>
  </div>
</div>

<!-- Assessment Info Card -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Informasi Penilaian</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <div class="info-item">
              <p class="text-xs text-secondary mb-1">Batas Waktu:</p>
              <p class="text-sm mb-2"><?= $assessment->batas_waktu ? $assessment->batas_waktu . ' menit' : 'Tidak ada batas waktu' ?></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-item">
              <p class="text-xs text-secondary mb-1">Nilai Kelulusan:</p>
              <p class="text-sm mb-2"><?= $assessment->nilai_lulus ? $assessment->nilai_lulus . ' poin' : 'Tidak ditentukan' ?></p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-item">
              <p class="text-xs text-secondary mb-1">Status:</p>
              <span class="badge badge-sm bg-gradient-<?= $assessment->aktif ? 'success' : 'secondary' ?>">
                <?= $assessment->aktif ? 'Aktif' : 'Tidak Aktif' ?>
              </span>
            </div>
          </div>
          <div class="col-md-3">
            <div class="info-item">
              <p class="text-xs text-secondary mb-1">Total Soal:</p>
              <p class="text-sm mb-2"><?= count($questions) ?> soal</p>
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-12">
            <p class="text-xs text-secondary mb-1">Deskripsi:</p>
            <p class="text-sm"><?= $assessment->deskripsi ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Questions List -->
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Daftar Soal</h6>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <?php if (!empty($questions)) : ?>
          <div class="table-responsive p-0">
            <table class="table align-items-center mb-0 manual-init" id="tabelSoal">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Soal</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tipe</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Poin</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Opsi</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($questions as $index => $question) : ?>
                  <tr>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= $index + 1 ?></span>
                    </td>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= substr(strip_tags($question->teks_soal), 0, 100) . (strlen(strip_tags($question->teks_soal)) > 100 ? '...' : '') ?></h6>
                        </div>
                      </div>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-info">
                        <?php
                        switch($question->jenis_soal) {
                          case 'pilihan_ganda':
                            echo 'Pilihan Ganda';
                            break;
                          case 'benar_salah':
                            echo 'Benar/Salah';
                            break;
                          case 'esai':
                            echo 'Esai';
                            break;
                          case 'unggah_file':
                            echo 'Unggah File';
                            break;
                          default:
                            echo ucfirst($question->jenis_soal);
                        }
                        ?>
                      </span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= $question->poin ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <?php if ($question->jenis_soal == 'pilihan_ganda' || $question->jenis_soal == 'benar_salah') : ?>
                        <span class="text-secondary text-xs font-weight-bold">
                          <?= isset($question->option_count) ? $question->option_count : '0' ?> opsi
                        </span>
                      <?php else : ?>
                        <span class="text-secondary text-xs">-</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle text-center">
                      <div class="dropdown">
                        <button class="btn btn-link text-secondary mb-0" type="button" id="dropdownMenuButton<?= $question->id ?>" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fa fa-ellipsis-v text-xs"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton<?= $question->id ?>">
                          <?php if ($question->jenis_soal == 'pilihan_ganda' || $question->jenis_soal == 'benar_salah') : ?>
                            <li><a class="dropdown-item" href="<?= base_url('admin/opsi-soal/' . $question->id) ?>"><i class="fas fa-list me-2"></i> Kelola Opsi</a></li>
                          <?php endif; ?>
                          <li><a class="dropdown-item" href="<?= base_url('admin/edit-soal/' . $question->id) ?>"><i class="fas fa-edit me-2"></i> Edit</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item text-danger" href="#" onclick="hapusSoal(<?= $question->id ?>, '<?= substr(strip_tags($question->teks_soal), 0, 50) ?>')"><i class="fas fa-trash me-2"></i> Hapus</a></li>
                        </ul>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php else : ?>
          <div class="text-center py-4">
            <div class="d-flex flex-column align-items-center">
              <i class="fas fa-question-circle fa-3x text-secondary mb-3"></i>
              <h6 class="text-secondary">Belum ada soal</h6>
              <p class="text-xs text-secondary mb-3">Mulai dengan menambahkan soal pertama untuk penilaian ini</p>
              <a href="<?= base_url('admin/tambah-soal/' . $assessment->id) ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-2"></i> Tambah Soal Pertama
              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Check if DataTable is already initialized and destroy it
    if ($.fn.DataTable.isDataTable('#tabelSoal')) {
        $('#tabelSoal').DataTable().destroy();
    }

    // Inisialisasi DataTable dengan layout yang sama seperti auto-init
    const indonesianLanguage = {
        "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
        "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
        "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
        "lengthMenu": "Tampilkan _MENU_ entri",
        "loadingRecords": "Memuat...",
        "processing": "Memproses...",
        "search": "Cari:",
        "zeroRecords": "Tidak ditemukan data yang sesuai",
        "paginate": {
            "first": "Pertama",
            "last": "Terakhir",
            "next": "Selanjutnya",
            "previous": "Sebelumnya"
        },
        "buttons": {
            "copy": "Salin",
            "print": "Cetak",
            "excel": "Excel",
            "pdf": "PDF",
            "csv": "CSV",
            "colvis": "Visibilitas Kolom"
        }
    };

    $('#tabelSoal').DataTable({
        "destroy": true,
        language: indonesianLanguage,
        responsive: true,
        // Layout yang sama dengan auto-init
        dom: '<"card-header border-0 d-flex justify-content-between align-items-center"<"d-flex align-items-center"l><"d-flex align-items-center dt-buttons-inline"B><"d-flex align-items-center ms-auto"f>>' +
             '<"table-responsive px-0"t>' +
             '<"card-footer border-0 d-flex justify-content-between align-items-center py-3"<"text-muted"i><"pagination mb-0"p>>',
        lengthMenu: [
            [10, 25, 50, 100, -1],
            ['10 baris', '25 baris', '50 baris', '100 baris', 'Semua']
        ],
        pageLength: 10,
        buttons: [
            {
                extend: 'collection',
                text: '<i class="fas fa-download me-1"></i> Export',
                className: 'btn btn-sm btn-primary',
                autoClose: true,
                fade: 150,
                buttons: [
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-copy me-1"></i> Salin',
                        className: 'btn btn-sm btn-outline-primary',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv me-1"></i> CSV',
                        className: 'btn btn-sm btn-outline-primary',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel me-1"></i> Excel',
                        className: 'btn btn-sm btn-outline-primary',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                        className: 'btn btn-sm btn-outline-primary',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print me-1"></i> Cetak',
                        className: 'btn btn-sm btn-outline-primary',
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        }
                    }
                ]
            },
            {
                extend: 'colvis',
                text: '<i class="fas fa-columns me-1"></i> Kolom',
                className: 'btn btn-sm btn-primary ms-2'
            }
        ],
        columnDefs: [
            {
                targets: -1,
                orderable: false,
                searchable: false,
                width: '80px',
                className: 'text-center'
            },
            {
                targets: '_all',
                className: 'align-middle'
            }
        ],
        order: [[0, "asc"]],
        initComplete: function() {
            // Style the search input
            $('.dataTables_filter input')
                .addClass('form-control form-control-sm')
                .attr('placeholder', 'Cari...')
                .css('width', '200px');

            $('.dataTables_filter label').contents().filter(function() {
                return this.nodeType === 3;
            }).remove();

            // Style the length menu
            $('.dataTables_length select').addClass('form-select form-select-sm');

            // Style the buttons container
            $('.dt-buttons-inline').addClass('mx-2');

            // Ensure buttons are properly styled
            $('.dt-button').addClass('btn-sm');
            $('.dt-button-collection').addClass('dropdown-menu-end');

            // Fix dropdown positioning and z-index
            $('.dt-button-collection').on('click', function() {
                setTimeout(function() {
                    $('.dt-button-background').css('z-index', '1055');
                    $('.dt-button-collection').css({
                        'z-index': '1056',
                        'position': 'absolute',
                        'display': 'block'
                    });
                }, 10);
            });
        }
    });
});

function hapusSoal(id, teks) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        html: `Apakah Anda yakin ingin menghapus soal:<br><strong>"${teks}..."</strong>?<br><br><small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url("admin/hapus-soal/") ?>' + id;
        }
    });
}
</script>
