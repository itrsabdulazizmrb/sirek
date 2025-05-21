<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header pb-0">
        <div class="d-flex align-items-center">
          <p class="mb-0">Application Details</p>
          <a href="<?= base_url('applicant/applications') ?>" class="btn btn-primary btn-sm ms-auto">Back to Applications</a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <h6 class="mb-3"><?= $job->title ?></h6>
            <div class="d-flex mb-4">
              <span class="badge badge-sm bg-gradient-<?= $job->job_type == 'full-time' ? 'success' : ($job->job_type == 'part-time' ? 'info' : ($job->job_type == 'contract' ? 'warning' : 'secondary')) ?> me-2"><?= ucfirst(str_replace('-', ' ', $job->job_type)) ?></span>
              <span class="text-sm me-3"><i class="ni ni-pin-3 me-1"></i> <?= $job->location ?></span>
              <span class="text-sm"><i class="ni ni-calendar-grid-58 me-1"></i> Applied on <?= date('d M Y', strtotime($application->application_date)) ?></span>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <div class="timeline timeline-one-side">
              <div class="timeline-block mb-3">
                <span class="timeline-step">
                  <i class="ni ni-send text-primary"></i>
                </span>
                <div class="timeline-content">
                  <h6 class="text-dark text-sm font-weight-bold mb-0">Application Submitted</h6>
                  <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y H:i', strtotime($application->application_date)) ?></p>
                </div>
              </div>
              
              <?php if ($application->status != 'pending') : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-check-bold text-info"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Application Reviewed</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                  </div>
                </div>
              <?php endif; ?>
              
              <?php if (in_array($application->status, ['shortlisted', 'interviewed', 'offered', 'hired'])) : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-trophy text-success"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Shortlisted</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                  </div>
                </div>
              <?php endif; ?>
              
              <?php if (in_array($application->status, ['interviewed', 'offered', 'hired'])) : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-chat-round text-primary"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Interviewed</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                  </div>
                </div>
              <?php endif; ?>
              
              <?php if (in_array($application->status, ['offered', 'hired'])) : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-tie-bow text-warning"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Job Offered</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                  </div>
                </div>
              <?php endif; ?>
              
              <?php if ($application->status == 'hired') : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-check-bold text-success"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Hired</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                  </div>
                </div>
              <?php endif; ?>
              
              <?php if ($application->status == 'rejected') : ?>
                <div class="timeline-block mb-3">
                  <span class="timeline-step">
                    <i class="ni ni-fat-remove text-danger"></i>
                  </span>
                  <div class="timeline-content">
                    <h6 class="text-dark text-sm font-weight-bold mb-0">Application Rejected</h6>
                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0"><?= date('d M Y', strtotime($application->updated_at)) ?></p>
                    <p class="text-sm mt-2 mb-0">Thank you for your interest. We encourage you to apply for other positions that match your skills and experience.</p>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <hr class="horizontal dark">
        
        <div class="row">
          <div class="col-md-12">
            <h6 class="mb-3">Cover Letter</h6>
            <div class="p-3 bg-light rounded">
              <?= nl2br($application->cover_letter) ?>
            </div>
          </div>
        </div>
        
        <hr class="horizontal dark">
        
        <div class="row">
          <div class="col-md-12">
            <h6 class="mb-3">Resume</h6>
            <?php if ($application->resume) : ?>
              <a href="<?= base_url('uploads/resumes/' . $application->resume) ?>" class="btn btn-outline-primary btn-sm" target="_blank">
                <i class="ni ni-single-copy-04 me-2"></i> View Resume
              </a>
            <?php else : ?>
              <p class="text-muted">No resume attached.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <div class="col-md-4">
    <div class="card">
      <div class="card-header pb-0">
        <h6>Application Status</h6>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center">
          <div>
            <span class="badge badge-lg bg-gradient-<?= $application->status == 'pending' ? 'warning' : ($application->status == 'reviewed' ? 'info' : ($application->status == 'shortlisted' ? 'success' : ($application->status == 'interviewed' ? 'primary' : ($application->status == 'offered' ? 'warning' : ($application->status == 'hired' ? 'success' : 'danger'))))) ?> mb-3"><?= ucfirst($application->status) ?></span>
          </div>
        </div>
        <p class="text-sm">
          <?php if ($application->status == 'pending') : ?>
            Your application is currently under review. We will notify you once there's an update.
          <?php elseif ($application->status == 'reviewed') : ?>
            Your application has been reviewed by our hiring team. We will contact you soon with next steps.
          <?php elseif ($application->status == 'shortlisted') : ?>
            Congratulations! You have been shortlisted for this position. We will contact you soon to schedule an interview.
          <?php elseif ($application->status == 'interviewed') : ?>
            You have completed the interview stage. We are currently evaluating all candidates and will get back to you soon.
          <?php elseif ($application->status == 'offered') : ?>
            Congratulations! You have been offered this position. Please check your email for the offer details.
          <?php elseif ($application->status == 'hired') : ?>
            Congratulations! You have been hired for this position. Welcome to our team!
          <?php elseif ($application->status == 'rejected') : ?>
            We appreciate your interest in this position. Unfortunately, we have decided to move forward with other candidates at this time.
          <?php endif; ?>
        </p>
      </div>
    </div>
    
    <?php if (!empty($assessments)) : ?>
      <div class="card mt-4">
        <div class="card-header pb-0">
          <h6>Assessments</h6>
        </div>
        <div class="card-body">
          <?php foreach ($assessments as $assessment) : ?>
            <div class="d-flex align-items-center mb-3">
              <div>
                <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center">
                  <i class="ni ni-ruler-pencil text-white opacity-10"></i>
                </div>
              </div>
              <div class="ms-3">
                <h6 class="mb-0 text-sm"><?= $assessment->assessment_title ?></h6>
                <p class="text-xs text-secondary mb-0"><?= $assessment->type_name ?></p>
                <?php if ($assessment->status == 'not_started') : ?>
                  <a href="<?= base_url('applicant/take_assessment/' . $assessment->assessment_id . '/' . $assessment->application_id) ?>" class="btn btn-sm btn-primary mt-2">Take Assessment</a>
                <?php elseif ($assessment->status == 'in_progress') : ?>
                  <a href="<?= base_url('applicant/take_assessment/' . $assessment->assessment_id . '/' . $assessment->application_id) ?>" class="btn btn-sm btn-warning mt-2">Continue Assessment</a>
                <?php elseif ($assessment->status == 'completed') : ?>
                  <span class="badge badge-sm bg-gradient-success mt-2">Completed</span>
                <?php elseif ($assessment->status == 'graded') : ?>
                  <span class="badge badge-sm bg-gradient-info mt-2">Score: <?= $assessment->score ?></span>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
    
    <div class="card mt-4">
      <div class="card-header pb-0">
        <h6>Job Details</h6>
      </div>
      <div class="card-body">
        <p class="text-sm mb-1"><strong>Job Type:</strong> <?= ucfirst(str_replace('-', ' ', $job->job_type)) ?></p>
        <p class="text-sm mb-1"><strong>Location:</strong> <?= $job->location ?></p>
        <?php if ($job->salary_range) : ?>
          <p class="text-sm mb-1"><strong>Salary Range:</strong> <?= $job->salary_range ?></p>
        <?php endif; ?>
        <p class="text-sm mb-3"><strong>Deadline:</strong> <?= date('d M Y', strtotime($job->deadline)) ?></p>
        
        <a href="<?= base_url('home/job_details/' . $job->id) ?>" class="btn btn-outline-primary btn-sm">View Full Job Details</a>
      </div>
    </div>
  </div>
</div>
