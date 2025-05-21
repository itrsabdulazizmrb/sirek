<?= form_open('auth/login', ['class' => 'needs-validation']) ?>
  <div class="mb-3">
    <input type="text" name="username" class="form-control form-control-lg" placeholder="Username" aria-label="Username" required>
  </div>
  <div class="mb-3">
    <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" required>
  </div>
  <div class="form-check form-switch">
    <input class="form-check-input" type="checkbox" id="rememberMe">
    <label class="form-check-label" for="rememberMe">Remember me</label>
  </div>
  <div class="text-center">
    <button type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
  </div>
<?= form_close() ?>
