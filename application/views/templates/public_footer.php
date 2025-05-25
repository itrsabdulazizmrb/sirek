    <footer class="footer py-5">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 mb-5 mb-lg-0">
            <h6 class="text-uppercase mb-4">SIREK</h6>
            <p>SIREK is a comprehensive recruitment system designed to streamline the hiring process for both employers and job seekers.</p>
            <div class="social-icons">
              <a href="#" class="btn btn-icon-only btn-pill btn-facebook me-2" type="button">
                <span class="btn-inner--icon"><i class="fab fa-facebook"></i></span>
              </a>
              <a href="#" class="btn btn-icon-only btn-pill btn-twitter me-2" type="button">
                <span class="btn-inner--icon"><i class="fab fa-twitter"></i></span>
              </a>
              <a href="#" class="btn btn-icon-only btn-pill btn-instagram me-2" type="button">
                <span class="btn-inner--icon"><i class="fab fa-instagram"></i></span>
              </a>
              <a href="#" class="btn btn-icon-only btn-pill btn-linkedin" type="button">
                <span class="btn-inner--icon"><i class="fab fa-linkedin"></i></span>
              </a>
            </div>
          </div>
          <div class="col-lg-3 col-6 ms-auto">
            <h6 class="text-uppercase mb-4">Quick Links</h6>
            <ul class="footer-links list-unstyled">
              <li><a href="<?= base_url() ?>">Home</a></li>
              <li><a href="<?= base_url('home/jobs') ?>">Jobs</a></li>
              <li><a href="<?= base_url('home/blog') ?>">Blog</a></li>
              <li><a href="<?= base_url('home/about') ?>">About Us</a></li>
              <li><a href="<?= base_url('home/contact') ?>">Contact Us</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-6 ms-auto">
            <h6 class="text-uppercase mb-4">For Applicants</h6>
            <ul class="footer-links list-unstyled">
              <li><a href="<?= base_url('auth/register') ?>">Create Account</a></li>
              <li><a href="<?= base_url('auth') ?>">Login</a></li>
              <li><a href="<?= base_url('home/jobs') ?>">Browse Jobs</a></li>
              <li><a href="<?= base_url('pelamar/dasbor') ?>">Applicant Dashboard</a></li>
              <li><a href="<?= base_url('home/faq') ?>">FAQs</a></li>
            </ul>
          </div>
          <div class="col-lg-3 col-6 ms-auto">
            <h6 class="text-uppercase mb-4">Contact</h6>
            <ul class="footer-links list-unstyled">
              <li><i class="fas fa-map-marker-alt me-2"></i> 123 Recruitment St, HR City</li>
              <li><i class="fas fa-phone me-2"></i> +1 234 567 890</li>
              <li><i class="fas fa-envelope me-2"></i> info@sirek.com</li>
              <li><i class="fas fa-clock me-2"></i> Mon - Fri: 9:00 AM - 5:00 PM</li>
            </ul>
          </div>
        </div>
        <hr class="horizontal dark mt-5 mb-4">
        <div class="row">
          <div class="col-12">
            <div class="text-center">
              <p class="mb-0">
                © <script>
                  document.write(new Date().getFullYear())
                </script> SIREK Recruitment System. All rights reserved.
              </p>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </main>

  <!--   Core JS Files   -->
  <script src="<?= base_url('assets/js/core/popper.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/core/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/perfect-scrollbar.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/plugins/smooth-scrollbar.min.js') ?>"></script>

  <!-- Custom scripts -->
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }

    // Add base URL for JavaScript
    var baseUrl = '<?= base_url() ?>';
  </script>

  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?= base_url('assets/js/argon-dashboard.min.js?v=2.1.0') ?>"></script>

  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

  <!-- Custom JS -->
  <script src="<?= base_url('assets/js/custom.js') ?>"></script>

  <!-- SweetAlert2 Initialization -->
  <script src="<?= base_url('assets/js/sweetalert-init.js') ?>"></script>
</body>

</html>
