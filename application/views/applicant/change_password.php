<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card">
      <div class="card-header pb-0">
        <div class="d-flex align-items-center">
          <p class="mb-0">Change Password</p>
          <a href="<?= base_url('pelamar/profil') ?>" class="btn btn-primary btn-sm ms-auto">Back to Profile</a>
        </div>
      </div>
      <div class="card-body">
        <?= form_open('pelamar/ubah-password', ['class' => 'needs-validation']) ?>
          <div class="form-group">
            <label for="current_password" class="form-control-label">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
            <?= form_error('current_password', '<small class="text-danger">', '</small>') ?>
          </div>

          <div class="form-group mt-3">
            <label for="new_password" class="form-control-label">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" data-password-strength required>
            <small class="text-muted">Password must be at least 6 characters long</small>
            <?= form_error('new_password', '<small class="text-danger">', '</small>') ?>
          </div>

          <div class="form-group mt-3">
            <label for="confirm_password" class="form-control-label">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            <?= form_error('confirm_password', '<small class="text-danger">', '</small>') ?>
          </div>

          <div class="alert alert-info mt-4" role="alert">
            <h6 class="alert-heading mb-1">Password Guidelines</h6>
            <ul class="mb-0 ps-4">
              <li>Use at least 8 characters</li>
              <li>Include at least one uppercase letter</li>
              <li>Include at least one lowercase letter</li>
              <li>Include at least one number or special character</li>
              <li>Avoid using personal information</li>
              <li>Don't reuse passwords from other websites</li>
            </ul>
          </div>

          <div class="d-flex justify-content-end mt-4">
            <button type="submit" class="btn btn-primary">Change Password</button>
          </div>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Password strength meter
    const passwordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    if (passwordInput) {
      const strengthMeter = document.createElement('div');
      strengthMeter.classList.add('progress', 'mt-2');
      strengthMeter.style.height = '5px';

      const strengthBar = document.createElement('div');
      strengthBar.classList.add('progress-bar');
      strengthBar.style.width = '0%';

      strengthMeter.appendChild(strengthBar);

      const strengthText = document.createElement('small');
      strengthText.classList.add('text-muted', 'mt-1', 'd-block');

      passwordInput.parentNode.insertBefore(strengthMeter, passwordInput.nextSibling);
      passwordInput.parentNode.insertBefore(strengthText, strengthMeter.nextSibling);

      passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;

        // Length check
        if (password.length >= 8) strength += 25;

        // Uppercase check
        if (/[A-Z]/.test(password)) strength += 25;

        // Lowercase check
        if (/[a-z]/.test(password)) strength += 25;

        // Number/special char check
        if (/[0-9!@#$%^&*]/.test(password)) strength += 25;

        // Update UI
        strengthBar.style.width = strength + '%';

        if (strength < 25) {
          strengthBar.className = 'progress-bar bg-danger';
          strengthText.textContent = 'Very Weak';
        } else if (strength < 50) {
          strengthBar.className = 'progress-bar bg-warning';
          strengthText.textContent = 'Weak';
        } else if (strength < 75) {
          strengthBar.className = 'progress-bar bg-info';
          strengthText.textContent = 'Medium';
        } else {
          strengthBar.className = 'progress-bar bg-success';
          strengthText.textContent = 'Strong';
        }
      });

      // Password match check
      confirmPasswordInput.addEventListener('input', function() {
        if (this.value === passwordInput.value) {
          this.classList.remove('is-invalid');
          this.classList.add('is-valid');
        } else {
          this.classList.remove('is-valid');
          this.classList.add('is-invalid');
        }
      });
    }
  });
</script>
