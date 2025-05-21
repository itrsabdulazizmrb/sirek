<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header pb-0">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-0"><?= $assessment->title ?></h6>
            <p class="text-sm mb-0">
              <i class="fa fa-info-circle text-primary" aria-hidden="true"></i>
              <span class="font-weight-bold"><?= $assessment->type_name ?> Assessment</span>
            </p>
          </div>
          <?php if ($assessment->time_limit) : ?>
            <div class="bg-gradient-danger px-3 py-2 rounded text-white">
              <div class="d-flex align-items-center">
                <i class="ni ni-time-alarm me-2"></i>
                <span id="assessment-timer" data-time-limit="<?= $assessment->time_limit ?>"><?= $assessment->time_limit ?>:00</span>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <?php if ($assessment->description) : ?>
          <div class="alert alert-info" role="alert">
            <h6 class="alert-heading mb-1">Assessment Description</h6>
            <p class="mb-0"><?= $assessment->description ?></p>
          </div>
        <?php endif; ?>
        
        <?php if (empty($questions)) : ?>
          <div class="text-center py-5">
            <h4 class="text-secondary">No questions found</h4>
            <p class="text-muted">This assessment doesn't have any questions yet.</p>
            <a href="<?= base_url('applicant/assessments') ?>" class="btn btn-primary mt-3">Back to Assessments</a>
          </div>
        <?php else : ?>
          <?= form_open('applicant/submit_assessment', ['id' => 'assessment-form']) ?>
            <input type="hidden" name="assessment_id" value="<?= $assessment->id ?>">
            <input type="hidden" name="application_id" value="<?= $application_id ?>">
            <input type="hidden" name="applicant_assessment_id" value="<?= $applicant_assessment->id ?>">
            
            <?php $question_number = 1; ?>
            <?php foreach ($questions as $question) : ?>
              <div class="question-card mb-4">
                <h6 class="mb-3">Question <?= $question_number ?>: <?= $question->question_text ?></h6>
                
                <?php if ($question->question_type == 'multiple_choice') : ?>
                  <?php 
                  // Get options for this question
                  $this->db->where('question_id', $question->id);
                  $options_query = $this->db->get('question_options');
                  $options = $options_query->result();
                  ?>
                  
                  <?php foreach ($options as $option) : ?>
                    <div class="option-item mb-2 p-3 border rounded" data-question-id="<?= $question->id ?>" data-option-id="<?= $option->id ?>">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="question_<?= $question->id ?>" id="option_<?= $option->id ?>" value="<?= $option->id ?>">
                        <label class="form-check-label" for="option_<?= $option->id ?>">
                          <?= $option->option_text ?>
                        </label>
                      </div>
                    </div>
                  <?php endforeach; ?>
                  
                <?php elseif ($question->question_type == 'true_false') : ?>
                  <div class="option-item mb-2 p-3 border rounded" data-question-id="<?= $question->id ?>" data-option-id="true">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question_<?= $question->id ?>" id="option_<?= $question->id ?>_true" value="true">
                      <label class="form-check-label" for="option_<?= $question->id ?>_true">
                        True
                      </label>
                    </div>
                  </div>
                  <div class="option-item mb-2 p-3 border rounded" data-question-id="<?= $question->id ?>" data-option-id="false">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="question_<?= $question->id ?>" id="option_<?= $question->id ?>_false" value="false">
                      <label class="form-check-label" for="option_<?= $question->id ?>_false">
                        False
                      </label>
                    </div>
                  </div>
                  
                <?php elseif ($question->question_type == 'essay') : ?>
                  <div class="form-group">
                    <textarea name="question_<?= $question->id ?>" class="form-control" rows="5" placeholder="Type your answer here..."></textarea>
                  </div>
                  
                <?php elseif ($question->question_type == 'file_upload') : ?>
                  <div class="form-group">
                    <input type="file" name="question_<?= $question->id ?>" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <small class="text-muted">Allowed file types: PDF, DOC, DOCX, JPG, JPEG, PNG. Max size: 2MB</small>
                  </div>
                <?php endif; ?>
                
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <span class="badge bg-gradient-<?= $question->question_type == 'multiple_choice' ? 'primary' : ($question->question_type == 'true_false' ? 'info' : ($question->question_type == 'essay' ? 'warning' : 'success')) ?>">
                    <?= ucfirst(str_replace('_', ' ', $question->question_type)) ?>
                  </span>
                  <span class="text-xs text-muted">Points: <?= $question->points ?></span>
                </div>
              </div>
              
              <?php $question_number++; ?>
            <?php endforeach; ?>
            
            <div class="d-flex justify-content-between mt-4">
              <a href="<?= base_url('applicant/assessments') ?>" class="btn btn-outline-secondary">Cancel</a>
              <button type="submit" class="btn btn-primary">Submit Assessment</button>
            </div>
          <?= form_close() ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Mark assessment as in progress
    <?php if ($applicant_assessment->status == 'not_started') : ?>
      fetch('<?= base_url('applicant/update_assessment_status/' . $applicant_assessment->id . '/in_progress') ?>', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      });
    <?php endif; ?>
    
    // Assessment timer
    const assessmentTimer = document.getElementById('assessment-timer');
    if (assessmentTimer) {
      const timeLimit = parseInt(assessmentTimer.getAttribute('data-time-limit')) * 60; // Convert to seconds
      let timeRemaining = timeLimit;
      
      const timerInterval = setInterval(function() {
        timeRemaining--;
        
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        
        assessmentTimer.textContent = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
        
        if (timeRemaining <= 300) { // 5 minutes remaining
          assessmentTimer.classList.add('text-danger');
        }
        
        if (timeRemaining <= 0) {
          clearInterval(timerInterval);
          alert('Time is up! Your assessment will be submitted automatically.');
          document.getElementById('assessment-form').submit();
        }
      }, 1000);
    }
    
    // Option selection for multiple choice and true/false questions
    const optionItems = document.querySelectorAll('.option-item');
    optionItems.forEach(function(item) {
      item.addEventListener('click', function() {
        const questionId = this.getAttribute('data-question-id');
        const optionId = this.getAttribute('data-option-id');
        const radioInput = document.querySelector('input[name="question_' + questionId + '"][value="' + optionId + '"]');
        
        // Remove selected class from all options in this question
        const questionOptions = document.querySelectorAll('.option-item[data-question-id="' + questionId + '"]');
        questionOptions.forEach(function(option) {
          option.classList.remove('selected');
        });
        
        // Add selected class to clicked option
        this.classList.add('selected');
        
        // Check the radio input
        if (radioInput) {
          radioInput.checked = true;
        }
      });
    });
  });
</script>
