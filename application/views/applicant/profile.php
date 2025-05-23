<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header pb-0">
        <div class="d-flex align-items-center">
          <p class="mb-0">Edit Profile</p>
          <a href="<?= base_url('pelamar/ubah-password') ?>" class="btn btn-primary btn-sm ms-auto">Change Password</a>
        </div>
      </div>
      <div class="card-body">
        <?= form_open_multipart('pelamar/profil', ['class' => 'needs-validation']) ?>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="full_name" class="form-control-label">Full Name</label>
                <input type="text" name="full_name" id="full_name" class="form-control" value="<?= set_value('full_name', $user->nama_lengkap) ?>" required>
                <?= form_error('full_name', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email" class="form-control-label">Email address</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= set_value('email', $user->email) ?>" required>
                <?= form_error('email', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="phone" class="form-control-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= set_value('phone', $user->telepon) ?>" required>
                <?= form_error('phone', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="date_of_birth" class="form-control-label">Date of Birth</label>
                <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="<?= set_value('date_of_birth', $profile->tanggal_lahir) ?>" required>
                <?= form_error('date_of_birth', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="address" class="form-control-label">Address</label>
                <textarea name="address" id="address" class="form-control" rows="3" required><?= set_value('address', $user->alamat) ?></textarea>
                <?= form_error('address', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="gender" class="form-control-label">Gender</label>
                <select name="gender" id="gender" class="form-control" required>
                  <option value="">Select Gender</option>
                  <option value="laki-laki" <?= set_select('gender', 'laki-laki', ($profile->jenis_kelamin == 'laki-laki')) ?>>Male</option>
                  <option value="perempuan" <?= set_select('gender', 'perempuan', ($profile->jenis_kelamin == 'perempuan')) ?>>Female</option>
                  <option value="lainnya" <?= set_select('gender', 'lainnya', ($profile->jenis_kelamin == 'lainnya')) ?>>Other</option>
                </select>
                <?= form_error('gender', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="profile_picture" class="form-control-label">Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture" class="form-control" accept="image/*">
                <small class="text-muted">Max size: 1MB. Allowed formats: JPG, JPEG, PNG</small>
              </div>
            </div>
          </div>
          <hr class="horizontal dark">
          <p class="text-uppercase text-sm">Professional Information</p>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="education" class="form-control-label">Education</label>
                <textarea name="education" id="education" class="form-control" rows="3" required><?= set_value('education', $profile->pendidikan) ?></textarea>
                <small class="text-muted">Enter your educational background, including degrees, institutions, and graduation years.</small>
                <?= form_error('education', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="experience" class="form-control-label">Work Experience</label>
                <textarea name="experience" id="experience" class="form-control" rows="3" required><?= set_value('experience', $profile->pengalaman) ?></textarea>
                <small class="text-muted">Enter your work experience, including job titles, companies, and employment periods.</small>
                <?= form_error('experience', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="skills" class="form-control-label">Skills</label>
                <textarea name="skills" id="skills" class="form-control" rows="3" required><?= set_value('skills', $profile->keahlian) ?></textarea>
                <small class="text-muted">Enter your skills, separated by commas (e.g., Project Management, Java, SQL, Leadership).</small>
                <?= form_error('skills', '<small class="text-danger">', '</small>') ?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="resume" class="form-control-label">Resume</label>
                <input type="file" name="resume" id="resume" class="form-control" accept=".pdf,.doc,.docx">
                <small class="text-muted">Max size: 2MB. Allowed formats: PDF, DOC, DOCX</small>
              </div>
            </div>
            <div class="col-md-6">
              <?php if ($profile->cv) : ?>
                <div class="form-group">
                  <label class="form-control-label">Current Resume</label>
                  <p class="mb-0"><?= $profile->cv ?></p>
                  <a href="<?= base_url('uploads/resumes/' . $profile->cv) ?>" class="btn btn-sm btn-outline-primary mt-2" target="_blank">View Resume</a>
                </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="linkedin_url" class="form-control-label">LinkedIn URL</label>
                <input type="url" name="linkedin_url" id="linkedin_url" class="form-control" value="<?= set_value('linkedin_url', $profile->url_linkedin) ?>">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="portfolio_url" class="form-control-label">Portfolio URL</label>
                <input type="url" name="portfolio_url" id="portfolio_url" class="form-control" value="<?= set_value('portfolio_url', $profile->url_portofolio) ?>">
              </div>
            </div>
          </div>
          <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        <?= form_close() ?>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-profile">
      <img src="<?= base_url('assets/img/bg-profile.jpg') ?>" alt="Profile Cover" class="card-img-top">
      <div class="row justify-content-center">
        <div class="col-4 col-lg-4 order-lg-2">
          <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
            <a href="javascript:;">
              <img src="<?= $user->foto_profil ? base_url('uploads/profile_pictures/' . $user->foto_profil) : base_url('assets/img/team-2.jpg') ?>" class="rounded-circle img-fluid border border-2 border-white" style="width: 100px; height: 100px; object-fit: cover;">
            </a>
          </div>
        </div>
      </div>
      <div class="card-body pt-0">
        <div class="text-center mt-4">
          <h5><?= $user->nama_lengkap ?></h5>
          <div class="h6 font-weight-300">
            <i class="ni location_pin mr-2"></i><?= $user->email ?>
          </div>
          <div class="h6 mt-2">
            <i class="ni business_briefcase-24 mr-2"></i>Applicant
          </div>
          <div>
            <i class="ni education_hat mr-2"></i>Profile Completion: <?= $profile_completion ?>%
          </div>
        </div>
        <div class="progress mt-3" style="height: 6px;">
          <div class="progress-bar bg-gradient-<?= $profile_completion < 50 ? 'danger' : ($profile_completion < 80 ? 'warning' : 'success') ?>" role="progressbar" style="width: <?= $profile_completion ?>%;" aria-valuenow="<?= $profile_completion ?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>
</div>
