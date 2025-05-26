<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>Tutorial & Dokumentasi SIREK</h6>
        <p class="text-sm mb-0">Panduan lengkap untuk pengembangan dan pengelolaan sistem rekrutmen SIREK</p>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="row p-4">
          <div class="col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body d-flex flex-column">
                <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg mb-3">
                  <i class="ni ni-laptop text-white opacity-10"></i>
                </div>
                <h5 class="font-weight-bolder">Development Guide</h5>
                <p class="mb-4">Panduan pengembangan lengkap termasuk struktur proyek, instalasi, dan konfigurasi environment.</p>
                <a href="<?= base_url('admin/tutorial?section=development') ?>" class="btn btn-outline-primary btn-sm mt-auto">Lihat Panduan</a>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body d-flex flex-column">
                <div class="icon icon-shape icon-lg bg-gradient-success shadow text-center border-radius-lg mb-3">
                  <i class="ni ni-app text-white opacity-10"></i>
                </div>
                <h5 class="font-weight-bolder">CRUD Operations</h5>
                <p class="mb-4">Tutorial lengkap untuk membuat operasi CRUD baru dengan contoh implementasi dari modul yang ada.</p>
                <a href="<?= base_url('admin/tutorial?section=crud') ?>" class="btn btn-outline-success btn-sm mt-auto">Lihat Tutorial</a>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body d-flex flex-column">
                <div class="icon icon-shape icon-lg bg-gradient-warning shadow text-center border-radius-lg mb-3">
                  <i class="ni ni-bullet-list-67 text-white opacity-10"></i>
                </div>
                <h5 class="font-weight-bolder">Menu Management</h5>
                <p class="mb-4">Cara menambahkan menu baru, konfigurasi routing, dan pengaturan permission untuk akses kontrol.</p>
                <a href="<?= base_url('admin/tutorial?section=menu') ?>" class="btn btn-outline-warning btn-sm mt-auto">Lihat Tutorial</a>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
              <div class="card-body d-flex flex-column">
                <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg mb-3">
                  <i class="ni ni-collection text-white opacity-10"></i>
                </div>
                <h5 class="font-weight-bolder">Knowledge Base</h5>
                <p class="mb-4">Standar coding, konvensi penamaan, best practices, dan troubleshooting guide untuk proyek SIREK.</p>
                <a href="<?= base_url('admin/tutorial?section=knowledge') ?>" class="btn btn-outline-info btn-sm mt-auto">Lihat Knowledge Base</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$section = $this->input->get('section');
if ($section):
?>
<div id="tutorial-content">
  <?php if ($section == 'development'): ?>
    <?php $this->load->view('admin/tutorial/development'); ?>
  <?php elseif ($section == 'crud'): ?>
    <?php $this->load->view('admin/tutorial/crud'); ?>
  <?php elseif ($section == 'menu'): ?>
    <?php $this->load->view('admin/tutorial/menu'); ?>
  <?php elseif ($section == 'knowledge'): ?>
    <?php $this->load->view('admin/tutorial/knowledge'); ?>
  <?php endif; ?>
</div>
<?php endif; ?>
