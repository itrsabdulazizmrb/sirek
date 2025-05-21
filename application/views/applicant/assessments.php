<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <h6>My Assessments</h6>
        <p class="text-sm mb-0">
          <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
          <span class="font-weight-bold">Track all your assessments for job applications</span>
        </p>
      </div>
      <div class="card-body px-0 pt-0 pb-2">
        <div class="table-responsive p-0">
          <?php if (empty($assessments)) : ?>
            <div class="text-center py-5">
              <h4 class="text-secondary">No assessments found</h4>
              <p class="text-muted">You don't have any assessments assigned to you yet.</p>
              <a href="<?= base_url('applicant/applications') ?>" class="btn btn-primary mt-3">View My Applications</a>
            </div>
          <?php else : ?>
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Assessment</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Job</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                  <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>
                  <th class="text-secondary opacity-7"></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($assessments as $assessment) : ?>
                  <tr>
                    <td>
                      <div class="d-flex px-2 py-1">
                        <div>
                          <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center me-2">
                            <i class="ni ni-ruler-pencil text-white opacity-10"></i>
                          </div>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                          <h6 class="mb-0 text-sm"><?= $assessment->assessment_title ?></h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <p class="text-xs font-weight-bold mb-0"><?= $assessment->job_title ?></p>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <span class="badge badge-sm bg-gradient-primary"><?= $assessment->type_name ?></span>
                    </td>
                    <td class="align-middle text-center text-sm">
                      <?php if ($assessment->status == 'not_started') : ?>
                        <span class="badge badge-sm bg-gradient-secondary">Not Started</span>
                      <?php elseif ($assessment->status == 'in_progress') : ?>
                        <span class="badge badge-sm bg-gradient-warning">In Progress</span>
                      <?php elseif ($assessment->status == 'completed') : ?>
                        <span class="badge badge-sm bg-gradient-success">Completed</span>
                      <?php elseif ($assessment->status == 'graded') : ?>
                        <span class="badge badge-sm bg-gradient-info">Graded</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle text-center">
                      <?php if ($assessment->status == 'graded' && $assessment->score !== null) : ?>
                        <span class="text-secondary text-xs font-weight-bold"><?= $assessment->score ?></span>
                      <?php else : ?>
                        <span class="text-secondary text-xs font-weight-bold">-</span>
                      <?php endif; ?>
                    </td>
                    <td class="align-middle">
                      <?php if ($assessment->status == 'not_started' || $assessment->status == 'in_progress') : ?>
                        <a href="<?= base_url('applicant/take_assessment/' . $assessment->assessment_id . '/' . $assessment->id) ?>" class="btn btn-sm btn-primary">
                          <?= $assessment->status == 'not_started' ? 'Take Assessment' : 'Continue' ?>
                        </a>
                      <?php else : ?>
                        <a href="<?= base_url('applicant/application_details/' . $assessment->application_id) ?>" class="text-secondary font-weight-bold text-xs">
                          View Application
                        </a>
                      <?php endif; ?>
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
        <h6>Assessment Guide</h6>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-secondary me-3">Not Started</span>
              <p class="text-xs mb-0">You have not started this assessment yet.</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-warning me-3">In Progress</span>
              <p class="text-xs mb-0">You have started but not completed this assessment.</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-success me-3">Completed</span>
              <p class="text-xs mb-0">You have completed this assessment and it is awaiting grading.</p>
            </div>
            <div class="d-flex align-items-center mb-3">
              <span class="badge badge-sm bg-gradient-info me-3">Graded</span>
              <p class="text-xs mb-0">This assessment has been graded and your score is available.</p>
            </div>
          </div>
        </div>
        
        <div class="alert alert-info mt-3" role="alert">
          <h6 class="alert-heading mb-1">Assessment Tips</h6>
          <ul class="mb-0 ps-4">
            <li>Make sure you have a stable internet connection before starting an assessment.</li>
            <li>Some assessments have time limits. Once started, you must complete them within the allocated time.</li>
            <li>Read all questions carefully before answering.</li>
            <li>For technical assessments, make sure you understand the requirements before coding.</li>
            <li>For multiple-choice questions, eliminate obviously incorrect answers first.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
