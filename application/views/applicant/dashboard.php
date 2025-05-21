<div class="row">
  <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">Profile Completion</p>
              <h5 class="font-weight-bolder"><?= $profile_completion ?>%</h5>
              <div class="progress mt-2" style="height: 6px;">
                <div class="progress-bar bg-gradient-<?= $profile_completion < 50 ? 'danger' : ($profile_completion < 80 ? 'warning' : 'success') ?>" role="progressbar" style="width: <?= $profile_completion ?>%;" aria-valuenow="<?= $profile_completion ?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <?php if ($profile_completion < 100) : ?>
                <p class="mb-0 mt-2">
                  <a href="<?= base_url('applicant/profile') ?>" class="text-primary text-sm font-weight-bolder">Complete your profile</a>
                </p>
              <?php else : ?>
                <p class="mb-0 mt-2">
                  <span class="text-success text-sm font-weight-bolder">Profile complete!</span>
                </p>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
              <i class="ni ni-single-02 text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">My Applications</p>
              <h5 class="font-weight-bolder"><?= count($applications) ?></h5>
              <p class="mb-0">
                <?php 
                $pending = 0;
                foreach ($applications as $app) {
                  if ($app->status == 'pending') $pending++;
                }
                ?>
                <span class="text-warning text-sm font-weight-bolder"><?= $pending ?></span>
                pending review
              </p>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
              <i class="ni ni-single-copy-04 text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-4 col-sm-6">
    <div class="card">
      <div class="card-body p-3">
        <div class="row">
          <div class="col-8">
            <div class="numbers">
              <p class="text-sm mb-0 text-uppercase font-weight-bold">Today</p>
              <h5 class="font-weight-bolder"><?= date('d M Y') ?></h5>
              <p class="mb-0">
                <span class="text-success text-sm font-weight-bolder"><?= date('l') ?></span>
              </p>
            </div>
          </div>
          <div class="col-4 text-end">
            <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
              <i class="ni ni-calendar-grid-58 text-lg opacity-10" aria-hidden="true"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-lg-8 mb-lg-0 mb-4">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Recommended Jobs</h6>
        <p class="text-sm mb-0">
          <i class="fa fa-check text-info" aria-hidden="true"></i>
          <span class="font-weight-bold">Jobs matching your profile</span>
        </p>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Deadline</th>
                <th class="text-secondary opacity-7"></th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($recommended_jobs)) : ?>
                <tr>
                  <td colspan="5" class="text-center py-4">No recommended jobs found. Complete your profile to get personalized recommendations.</td>
                </tr>
              <?php else : ?>
                <?php foreach ($recommended_jobs as $job) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="<?= base_url('assets/img/small-logos/logo-company.svg') ?>" class="avatar avatar-sm me-3" alt="job">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= $job->title ?></h6>
                          <p class="text-xs text-secondary mb-0"><?= $job->category_name ?></p>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?= $job->location ?></p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-<?= $job->job_type == 'full-time' ? 'success' : ($job->job_type == 'part-time' ? 'info' : ($job->job_type == 'contract' ? 'warning' : 'secondary')) ?>"><?= ucfirst(str_replace('-', ' ', $job->job_type)) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= date('d M Y', strtotime($job->deadline)) ?></span>
                    </td>
                    <td class="align-middle">
                      <a href="<?= base_url('home/job_details/' . $job->id) ?>" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View job">
                        View Details
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header pb-0">
        <h6>Application Status</h6>
        <p class="text-sm">
          <i class="fa fa-arrow-up text-success" aria-hidden="true"></i>
          <span class="font-weight-bold">Your recent applications</span>
        </p>
      </div>
      <div class="card-body p-3">
        <div class="timeline timeline-one-side">
          <?php if (empty($applications)) : ?>
            <div class="timeline-block mb-3">
              <span class="timeline-step">
                <i class="ni ni-bell-55 text-info"></i>
              </span>
              <div class="timeline-content">
                <h6 class="text-dark text-sm font-weight-bold mb-0">No applications yet</h6>
                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Start applying for jobs to see your application status here.</p>
                <a href="<?= base_url('home/jobs') ?>" class="btn btn-sm btn-primary mt-3">Browse Jobs</a>
              </div>
            </div>
          <?php else : ?>
            <?php 
            $count = 0;
            foreach (array_slice($applications, 0, 5) as $app) : 
              $count++;
            ?>
              <div class="timeline-block mb-3">
                <span class="timeline-step">
                  <i class="ni ni-<?= $app->status == 'pending' ? 'bell-55 text-warning' : ($app->status == 'reviewed' ? 'check-bold text-info' : ($app->status == 'shortlisted' ? 'trophy text-success' : ($app->status == 'interviewed' ? 'chat-round text-primary' : ($app->status == 'offered' ? 'tie-bow text-warning' : ($app->status == 'hired' ? 'check-bold text-success' : 'fat-remove text-danger'))))) ?>"></i>
                </span>
                <div class="timeline-content">
                  <h6 class="text-dark text-sm font-weight-bold mb-0"><?= $app->job_title ?></h6>
                  <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($app->application_date)) ?></p>
                  <span class="badge badge-sm bg-gradient-<?= $app->status == 'pending' ? 'warning' : ($app->status == 'reviewed' ? 'info' : ($app->status == 'shortlisted' ? 'success' : ($app->status == 'interviewed' ? 'primary' : ($app->status == 'offered' ? 'warning' : ($app->status == 'hired' ? 'success' : 'danger'))))) ?> mt-2"><?= ucfirst($app->status) ?></span>
                  <a href="<?= base_url('applicant/application_details/' . $app->id) ?>" class="btn btn-link text-primary text-sm mb-0 ps-0 ms-0 mt-2">View Details</a>
                </div>
              </div>
            <?php endforeach; ?>
            <?php if (count($applications) > 5) : ?>
              <div class="text-center mt-4">
                <a href="<?= base_url('applicant/applications') ?>" class="btn btn-sm btn-primary">View All Applications</a>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
