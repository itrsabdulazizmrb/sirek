<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <h6>Edit Artikel</h6>
          <a href="<?= base_url('admin/blog') ?>" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar Artikel
          </a>
        </div>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Edit informasi artikel blog</span>
        </p>
      </div>
      <div class="card-body">
        <?= form_open_multipart('admin/edit_artikel/' . $post->id, ['class' => 'needs-validation']) ?>
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="title" class="form-control-label">Judul Artikel <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" value="<?= set_value('title', $post->title) ?>" required>
                <?= form_error('title', '<small class="text-danger">', '</small>') ?>
              </div>

              <div class="form-group mt-3">
                <label for="slug" class="form-control-label">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug" value="<?= set_value('slug', $post->slug) ?>">
                <small class="text-muted">Biarkan kosong untuk menghasilkan slug otomatis dari judul.</small>
                <?= form_error('slug', '<small class="text-danger">', '</small>') ?>
              </div>

              <div class="form-group mt-3">
                <label for="content" class="form-control-label">Konten Artikel <span class="text-danger">*</span></label>
                <textarea class="form-control" id="content" name="content" rows="15" required><?= set_value('content', $post->content) ?></textarea>
                <?= form_error('content', '<small class="text-danger">', '</small>') ?>
              </div>

              <div class="form-group mt-3">
                <label for="excerpt" class="form-control-label">Kutipan</label>
                <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?= set_value('excerpt', $post->excerpt) ?></textarea>
                <small class="text-muted">Ringkasan singkat dari artikel. Biarkan kosong untuk menghasilkan kutipan otomatis.</small>
                <?= form_error('excerpt', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>

            <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                  <h6 class="mb-3">Publikasi</h6>

                  <div class="form-group">
                    <label for="status" class="form-control-label">Status</label>
                    <select class="form-control" id="status" name="status">
                      <option value="draft" <?= set_select('status', 'draft', ($post->status == 'draft')) ?>>Draft</option>
                      <option value="published" <?= set_select('status', 'published', ($post->status == 'published')) ?>>Publikasikan</option>
                    </select>
                  </div>

                  <div class="form-group mt-3">
                    <label for="featured_image" class="form-control-label">Gambar Unggulan</label>
                    <?php if ($post->featured_image) : ?>
                      <div class="mb-2">
                        <img src="<?= base_url('uploads/blog_images/' . $post->featured_image) ?>" class="img-fluid rounded" alt="Featured Image">
                      </div>
                    <?php endif; ?>
                    <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                    <small class="text-muted">Format yang diizinkan: JPG, JPEG, PNG. Maks 2MB.</small>
                  </div>

                  <div class="form-group mt-3">
                    <label for="categories" class="form-control-label">Kategori <span class="text-danger">*</span></label>
                    <select class="form-control" id="categories" name="categories[]" multiple required>
                      <?php
                      $post_category_ids = array_map(function($cat) {
                        return $cat->id;
                      }, $post_categories);

                      foreach ($categories as $category) :
                        $selected = in_array($category->id, $post_category_ids);
                      ?>
                        <option value="<?= $category->id ?>" <?= set_select('categories[]', $category->id, $selected) ?>><?= $category->name ?></option>
                      <?php endforeach; ?>
                    </select>
                    <?= form_error('categories[]', '<small class="text-danger">', '</small>') ?>
                    <small class="text-muted">Tekan Ctrl (atau Cmd di Mac) untuk memilih beberapa kategori.</small>
                  </div>

                  <div class="form-group mt-3">
                    <label for="tags" class="form-control-label">Tag</label>
                    <input type="text" class="form-control" id="tags" name="tags" value="<?= set_value('tags', $post->tags) ?>">
                    <small class="text-muted">Pisahkan tag dengan koma (mis. rekrutmen, karir, wawancara).</small>
                  </div>

                  <div class="form-check form-switch mt-3">
                    <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" value="1" <?= set_checkbox('allow_comments', '1', ($post->allow_comments == 1)) ?>>
                    <label class="form-check-label" for="allow_comments">Izinkan Komentar</label>
                  </div>

                  <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" <?= set_checkbox('featured', '1', ($post->featured == 1)) ?>>
                    <label class="form-check-label" for="featured">Jadikan Artikel Unggulan</label>
                  </div>

                  <div class="d-grid gap-2 mt-4">
                    <button type="submit" name="save_draft" class="btn btn-secondary">Simpan sebagai Draft</button>
                    <button type="submit" name="publish" class="btn btn-primary">Publikasikan</button>
                  </div>
                </div>
              </div>

              <div class="card mt-4">
                <div class="card-body">
                  <h6 class="mb-3">SEO</h6>

                  <div class="form-group">
                    <label for="meta_title" class="form-control-label">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" name="meta_title" value="<?= set_value('meta_title', $post->meta_title) ?>">
                    <small class="text-muted">Biarkan kosong untuk menggunakan judul artikel.</small>
                  </div>

                  <div class="form-group mt-3">
                    <label for="meta_description" class="form-control-label">Meta Description</label>
                    <textarea class="form-control" id="meta_description" name="meta_description" rows="3"><?= set_value('meta_description', $post->meta_description) ?></textarea>
                    <small class="text-muted">Biarkan kosong untuk menggunakan kutipan artikel.</small>
                  </div>

                  <div class="form-group mt-3">
                    <label for="meta_keywords" class="form-control-label">Meta Keywords</label>
                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="<?= set_value('meta_keywords', $post->meta_keywords) ?>">
                    <small class="text-muted">Pisahkan kata kunci dengan koma.</small>
                  </div>
                </div>
              </div>

              <div class="card mt-4">
                <div class="card-body">
                  <h6 class="mb-3">Informasi Artikel</h6>

                  <p class="text-sm mb-1">
                    <strong>Dibuat pada:</strong> <?= date('d M Y H:i', strtotime($post->created_at)) ?>
                  </p>
                  <p class="text-sm mb-1">
                    <strong>Diperbarui pada:</strong> <?= date('d M Y H:i', strtotime($post->updated_at)) ?>
                  </p>
                  <p class="text-sm mb-1">
                    <strong>Penulis:</strong> <?= $post->author_name ?>
                  </p>
                  <p class="text-sm mb-1">
                    <strong>Dilihat:</strong> <?= $post->views ?> kali
                  </p>

                  <div class="d-grid mt-3">
                    <a href="<?= base_url('home/blog_post/' . $post->slug) ?>" class="btn btn-outline-primary btn-sm" target="_blank">Lihat Artikel</a>
                  </div>
                </div>
              </div>
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
        <h6>Komentar</h6>
      </div>
      <div class="card-body">
        <?php
        $comments = $this->blog_model->get_post_comments($post->id);
        if (empty($comments)) :
        ?>
          <p class="text-center">Belum ada komentar untuk artikel ini.</p>
        <?php else : ?>
          <div class="table-responsive">
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Pengguna</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Komentar</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($comments as $comment) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <div class="avatar avatar-sm bg-gradient-secondary rounded-circle">
                            <span class="text-white text-xs"><?= substr($comment->name, 0, 2) ?></span>
                          </div>
                        </div>
                        <div class="d-flex flex-column justify-content-center ms-3">
                          <h6 class="mb-0 text-sm"><?= $comment->name ?></h6>
                          <p class="text-xs text-secondary mb-0"><?= $comment->email ?></p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?= character_limiter($comment->comment, 100) ?></p>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= date('d M Y H:i', strtotime($comment->created_at)) ?></span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-<?= $comment->status == 'approved' ? 'success' : ($comment->status == 'pending' ? 'warning' : 'danger') ?>"><?= $comment->status == 'approved' ? 'Disetujui' : ($comment->status == 'pending' ? 'Menunggu' : 'Ditolak') ?></span>
                    </td>
                    <td class="align-middle">
                      <div class="dropdown">
                        <a href="#" class="text-secondary font-weight-bold text-xs" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end px-2 py-3" aria-labelledby="dropdownMenuButton">
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/approve_comment/' . $comment->id) ?>">Setujui</a></li>
                          <li><a class="dropdown-item border-radius-md" href="<?= base_url('admin/reject_comment/' . $comment->id) ?>">Tolak</a></li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <a class="dropdown-item border-radius-md text-danger btn-delete" href="<?= base_url('admin/delete_comment/' . $comment->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus komentar ini?');">
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Initialize rich text editor
    if (typeof ClassicEditor !== 'undefined') {
      ClassicEditor.create(document.querySelector('#content'), {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'mediaEmbed', '|', 'undo', 'redo']
      }).catch(error => {
        console.error(error);
      });
    }

    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const originalSlug = slugInput.value;

    titleInput.addEventListener('keyup', function() {
      if (slugInput.value === originalSlug) {
        slugInput.value = generateSlug(this.value);
      }
    });

    function generateSlug(text) {
      return text.toString().toLowerCase()
        .replace(/\s+/g, '-')           // Replace spaces with -
        .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
        .replace(/\-\-+/g, '-')         // Replace multiple - with single -
        .replace(/^-+/, '')             // Trim - from start of text
        .replace(/-+$/, '');            // Trim - from end of text
    }

    // Handle form submission
    const form = document.querySelector('form');
    const saveAsDraftBtn = document.querySelector('button[name="save_draft"]');
    const publishBtn = document.querySelector('button[name="publish"]');
    const statusSelect = document.getElementById('status');

    saveAsDraftBtn.addEventListener('click', function(e) {
      e.preventDefault();
      statusSelect.value = 'draft';
      form.submit();
    });

    publishBtn.addEventListener('click', function(e) {
      e.preventDefault();
      statusSelect.value = 'published';
      form.submit();
    });
  });
</script>
