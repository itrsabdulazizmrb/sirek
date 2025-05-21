                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <?php if ($title == 'Login') : ?>
                    <p class="mb-4 text-sm mx-auto">
                      Don't have an account?
                      <a href="<?= base_url('auth/register') ?>" class="text-primary text-gradient font-weight-bold">Sign up</a>
                    </p>
                    <p class="mb-0 text-sm mx-auto">
                      <a href="<?= base_url('auth/forgot_password') ?>" class="text-primary text-gradient font-weight-bold">Forgot password?</a>
                    </p>
                  <?php elseif ($title == 'Register') : ?>
                    <p class="mb-4 text-sm mx-auto">
                      Already have an account?
                      <a href="<?= base_url('auth') ?>" class="text-primary text-gradient font-weight-bold">Sign in</a>
                    </p>
                  <?php elseif ($title == 'Forgot Password') : ?>
                    <p class="mb-0 text-sm mx-auto">
                      <a href="<?= base_url('auth') ?>" class="text-primary text-gradient font-weight-bold">Back to login</a>
                    </p>
                  <?php elseif ($title == 'Reset Password') : ?>
                    <p class="mb-0 text-sm mx-auto">
                      <a href="<?= base_url('auth') ?>" class="text-primary text-gradient font-weight-bold">Back to login</a>
                    </p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden" style="background-image: url('<?= base_url('assets/img/recruitment-bg.jpg') ?>'); background-size: cover;">
                <span class="mask bg-gradient-primary opacity-6"></span>
                <h4 class="mt-5 text-white font-weight-bolder position-relative">SIREK Recruitment System</h4>
                <p class="text-white position-relative">Find your dream job or the perfect candidate with our comprehensive recruitment platform.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  
  <!--   Core JS Files   -->
  <script src="<?= base_url('assets/js/core/popper.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/core/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/smooth-scrollbar.min.js') ?>"></script>
  
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?= base_url('assets/js/argon-dashboard.min.js?v=2.1.0') ?>"></script>
  
  <!-- Custom JS -->
  <script src="<?= base_url('assets/js/custom.js') ?>"></script>
</body>

</html>
