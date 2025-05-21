<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>My Applications</h6>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Track all your job applications</span>
        </p>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <?php if (empty($applications)) : ?>
            <div class="text-center py-5">
              <h4 class="text-secondary">No applications found</h4>
              <p class="text-muted">You haven't applied for any jobs yet.</p>
              <a href="<?= base_url('home/jobs') ?>" class="btn btn-primary mt-3">Browse Jobs</a>
            </div>
          <?php else : ?>
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Job</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Location</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Applied Date</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($applications as $application) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <img src="<?= base_url('assets/img/small-logos/logo-company.svg') ?>" class="avatar avatar-sm me-3" alt="job">
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= $application->job_title ?></h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?= $application->job_location ?></p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-<?= $application->job_type == 'full-time' ? 'success' : ($application->job_type == 'part-time' ? 'info' : ($application->job_type == 'contract' ? 'warning' : 'secondary')) ?>"><?= ucfirst(str_replace('-', ' ', $application->job_type)) ?></span>
                    </td>
                    <td class="align-middle text-center">
                      <span class="text-secondary text-xs font-weight-bold"><?= date('d M Y', strtotime($application->application_date)) ?></span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-<?= $application->status == 'pending' ? 'warning' : ($application->status == 'reviewed' ? 'info' : ($application->status == 'shortlisted' ? 'success' : ($application->status == 'interviewed' ? 'primary' : ($application->status == 'offered' ? 'warning' : ($application->status == 'hired' ? 'success' : 'danger'))))) ?>"><?= ucfirst($application->status) ?></span>
                    </td>
                    <td class="align-middle">
                      <a href="<?= base_url('applicant/application_details/' . $application->id) ?>" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View details">
                        View Details
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row mt-4">
  <div class="col-12">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Application Status Guide</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-warning me-3">Pending</span>
              <p class="text-xs mb-0">Your application has been submitted and is waiting for review.</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-info me-3">Reviewed</span>
              <p class="text-xs mb-0">Your application has been reviewed by the hiring team.</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-success me-3">Shortlisted</span>
              <p class="text-xs mb-0">You have been shortlisted for the next stage of the recruitment process.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-primary me-3">Interviewed</span>
              <p class="text-xs mb-0">You have completed the interview stage.</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-warning me-3">Offered</span>
              <p class="text-xs mb-0">You have been offered the position.</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-success me-3">Hired</span>
              <p class="text-xs mb-0">You have accepted the offer and have been hired.</p>
            </div>
            <div class="d-flex align-items-center">
              <span class="badge badge-sm bg-gradient-danger me-3">Rejected</span>
              <p class="text-xs mb-0">Your application was not selected for this position.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
