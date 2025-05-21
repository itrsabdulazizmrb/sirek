<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Cek apakah user sudah login
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        // Cek apakah user adalah admin
        if ($this->session->userdata('role') !== 'admin') {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses ke halaman ini');
            redirect('dashboard');
        }

        // Load model
        $this->load->model('assessment_model');

        // Load helper
        $this->load->helper('phpspreadsheet');
    }

    // Halaman utama import soal
    public function index() {
        // Get all assessments
        $data['assessments'] = $this->assessment_model->get_assessments();

        // Load views
        $data['title'] = 'Import Soal';
        $this->load->view('templates/admin_header', $data);
        $this->load->view('admin/import/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // Download template Excel untuk import soal
    public function download_template($assessment_id = null) {
        // Cek apakah assessment_id valid
        if ($assessment_id) {
            $assessment = $this->assessment_model->get_assessment($assessment_id);
            if (!$assessment) {
                $this->session->set_flashdata('error', 'Penilaian tidak ditemukan');
                redirect('import');
            }
            $assessment_name = $assessment->title;
        } else {
            $assessment_name = 'Template';
        }

        // Buat objek Spreadsheet baru
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set judul worksheet
        $sheet->setTitle('Soal Pilihan Ganda');

        // Atur lebar kolom
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(50);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(25);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(10);

        // Atur header
        $sheet->setCellValue('A1', 'TEMPLATE IMPORT SOAL PILIHAN GANDA');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        if ($assessment_id) {
            $sheet->setCellValue('A2', 'Penilaian: ' . $assessment_name);
            $sheet->mergeCells('A2:H2');
            $sheet->getStyle('A2')->getFont()->setBold(true);
            $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        // Atur header tabel
        $headerRow = $assessment_id ? 4 : 3;
        $sheet->setCellValue('A' . $headerRow, 'No');
        $sheet->setCellValue('B' . $headerRow, 'Pertanyaan');
        $sheet->setCellValue('C' . $headerRow, 'Opsi A');
        $sheet->setCellValue('D' . $headerRow, 'Opsi B');
        $sheet->setCellValue('E' . $headerRow, 'Opsi C');
        $sheet->setCellValue('F' . $headerRow, 'Opsi D');
        $sheet->setCellValue('G' . $headerRow, 'Kunci Jawaban');
        $sheet->setCellValue('H' . $headerRow, 'Bobot');

        // Style header tabel
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2EFDA',
                ],
            ],
        ];

        $sheet->getStyle('A' . $headerRow . ':H' . $headerRow)->applyFromArray($headerStyle);

        // Tambahkan beberapa baris contoh
        $startRow = $headerRow + 1;
        for ($i = 0; $i < 5; $i++) {
            $row = $startRow + $i;
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, 'Contoh pertanyaan ' . ($i + 1));
            $sheet->setCellValue('C' . $row, 'Opsi A');
            $sheet->setCellValue('D' . $row, 'Opsi B');
            $sheet->setCellValue('E' . $row, 'Opsi C');
            $sheet->setCellValue('F' . $row, 'Opsi D');
            $sheet->setCellValue('G' . $row, 'A');
            $sheet->setCellValue('H' . $row, '1');
        }

        // Style untuk baris contoh
        $rowStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $sheet->getStyle('A' . $startRow . ':H' . ($startRow + 4))->applyFromArray($rowStyle);

        // Tambahkan validasi data untuk kolom kunci jawaban
        $validation = $sheet->getDataValidation('G' . $startRow . ':G100');
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setFormula1('"A,B,C,D"');
        $validation->setPromptTitle('Kunci Jawaban');
        $validation->setPrompt('Pilih salah satu: A, B, C, atau D');
        $validation->setErrorTitle('Input salah');
        $validation->setError('Nilai harus salah satu dari: A, B, C, atau D');

        // Tambahkan validasi data untuk kolom bobot
        $validation = $sheet->getDataValidation('H' . $startRow . ':H100');
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setFormula1('1');
        $validation->setFormula2('10');
        $validation->setPromptTitle('Bobot Soal');
        $validation->setPrompt('Masukkan nilai antara 1-10');
        $validation->setErrorTitle('Input salah');
        $validation->setError('Nilai harus berupa angka antara 1-10');

        // Tambahkan petunjuk pengisian
        $instructionRow = $startRow + 6;
        $sheet->setCellValue('A' . $instructionRow, 'Petunjuk Pengisian:');
        $sheet->mergeCells('A' . $instructionRow . ':H' . $instructionRow);
        $sheet->getStyle('A' . $instructionRow)->getFont()->setBold(true);

        $instructions = [
            'Kolom Pertanyaan: Isi dengan teks pertanyaan',
            'Kolom Opsi A-D: Isi dengan pilihan jawaban',
            'Kolom Kunci Jawaban: Isi dengan huruf opsi yang benar (A, B, C, atau D)',
            'Kolom Bobot: Isi dengan nilai bobot soal (1-10)',
            'Pastikan semua kolom terisi dengan benar',
            'Jangan mengubah format template'
        ];

        foreach ($instructions as $index => $instruction) {
            $sheet->setCellValue('A' . ($instructionRow + 1 + $index), ($index + 1) . '. ' . $instruction);
            $sheet->mergeCells('A' . ($instructionRow + 1 + $index) . ':H' . ($instructionRow + 1 + $index));
        }

        // Buat file Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        // Set header untuk download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Soal_' . str_replace(' ', '_', $assessment_name) . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Simpan file ke output
        $writer->save('php://output');
    }

    // Proses import soal dari file Excel
    public function process() {
        // Cek apakah ada file yang diupload
        if (empty($_FILES['excel_file']['name'])) {
            $this->session->set_flashdata('error', 'Pilih file Excel terlebih dahulu');
            redirect('import');
        }

        // Cek apakah assessment_id valid
        $assessment_id = $this->input->post('assessment_id');
        if (!$assessment_id) {
            $this->session->set_flashdata('error', 'Pilih penilaian terlebih dahulu');
            redirect('import');
        }

        $assessment = $this->assessment_model->get_assessment($assessment_id);
        if (!$assessment) {
            $this->session->set_flashdata('error', 'Penilaian tidak ditemukan');
            redirect('import');
        }

        // Konfigurasi upload
        $config['upload_path'] = './uploads/temp/';
        $config['allowed_types'] = 'xlsx|xls';
        $config['max_size'] = 2048; // 2MB

        // Buat direktori jika belum ada
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, TRUE);
        }

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('excel_file')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', strip_tags($error));
            redirect('import');
        }

        $upload_data = $this->upload->data();
        $file_path = $upload_data['full_path'];

        // Load file Excel
        require_once FCPATH . 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_path);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        // Tentukan baris awal data (setelah header)
        $startRow = 4;
        if ($sheet->getCell('A2')->getValue() != '') {
            $startRow = 5;
        }

        // Inisialisasi counter
        $success_count = 0;
        $error_count = 0;
        $error_rows = [];

        // Mulai transaksi database
        $this->db->trans_start();

        // Loop melalui baris data
        for ($row = $startRow; $row <= $highestRow; $row++) {
            // Baca data dari Excel
            $question_text = $sheet->getCell('B' . $row)->getValue();
            $option_a = $sheet->getCell('C' . $row)->getValue();
            $option_b = $sheet->getCell('D' . $row)->getValue();
            $option_c = $sheet->getCell('E' . $row)->getValue();
            $option_d = $sheet->getCell('F' . $row)->getValue();
            $correct_answer = $sheet->getCell('G' . $row)->getValue();
            $points = $sheet->getCell('H' . $row)->getValue();

            // Lewati baris kosong
            if (empty($question_text)) {
                continue;
            }

            // Validasi data
            $is_valid = true;
            $error_message = '';

            if (empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d)) {
                $is_valid = false;
                $error_message = 'Semua opsi jawaban harus diisi';
            } elseif (!in_array($correct_answer, ['A', 'B', 'C', 'D'])) {
                $is_valid = false;
                $error_message = 'Kunci jawaban harus A, B, C, atau D';
            } elseif (!is_numeric($points) || $points < 1 || $points > 10) {
                $is_valid = false;
                $error_message = 'Bobot soal harus berupa angka antara 1-10';
            }

            if (!$is_valid) {
                $error_count++;
                $error_rows[] = [
                    'row' => $row,
                    'message' => $error_message
                ];
                continue;
            }

            // Simpan data soal
            $question_data = [
                'assessment_id' => $assessment_id,
                'question_text' => $question_text,
                'question_type' => 'multiple_choice',
                'points' => $points,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $question_id = $this->assessment_model->insert_question($question_data);

            if ($question_id) {
                // Simpan opsi jawaban
                $options = [
                    'A' => $option_a,
                    'B' => $option_b,
                    'C' => $option_c,
                    'D' => $option_d
                ];

                foreach ($options as $key => $value) {
                    $option_data = [
                        'question_id' => $question_id,
                        'option_text' => $value,
                        'is_correct' => ($key == $correct_answer) ? 1 : 0,
                        'created_at' => date('Y-m-d H:i:s')
                    ];

                    $this->assessment_model->insert_question_option($option_data);
                }

                $success_count++;
            } else {
                $error_count++;
                $error_rows[] = [
                    'row' => $row,
                    'message' => 'Gagal menyimpan soal'
                ];
            }
        }

        // Selesaikan transaksi
        $this->db->trans_complete();

        // Hapus file temporary
        @unlink($file_path);

        // Set pesan flash
        if ($success_count > 0) {
            $this->session->set_flashdata('success', $success_count . ' soal berhasil diimport');
        }

        if ($error_count > 0) {
            $error_message = $error_count . ' soal gagal diimport. ';
            if (!empty($error_rows)) {
                $error_message .= 'Error pada baris: ';
                foreach ($error_rows as $index => $error) {
                    if ($index > 0) {
                        $error_message .= ', ';
                    }
                    $error_message .= $error['row'] . ' (' . $error['message'] . ')';

                    // Batasi jumlah error yang ditampilkan
                    if ($index >= 2 && count($error_rows) > 3) {
                        $error_message .= ', dan ' . (count($error_rows) - 3) . ' lainnya';
                        break;
                    }
                }
            }
            $this->session->set_flashdata('error', $error_message);
        }

        redirect('admin/assessment_questions/' . $assessment_id);
    }
}
