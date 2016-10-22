<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <!-- 
    <div class="card" id="my_profile">
      <img id="user-bgi" class="card-image" src="<?php echo base_url(); ?>assets/images/DefaultCatImage.jpg" alt="Default Cat Image" />
      <div class="pos-up-130">
        <img src="<?php echo base_url(); ?>assets/images/myprofile.jpg" alt="user image" id="user-profile-img">
        <div class="card-block">
          <div class="card-title">
          </div>
          <div class="card-content">
            
          </div>
        </div>
      </div>
    </div>
     -->

    <div class="card">
      <div class="card-block">
        <div class="card-title">
          <h3 id="my_project"><?php echo add_icon('sticky-note'); ?> My Projects</h3>
        </div>
        <div class="card-title">
          <h4>Managed Project(s)</h4>
        </div>
        <div class="card-content">
          <?php if ( ! empty($managed_projects)): ?>
            <?php $count = 0; ?>
            <?php foreach ($managed_projects as $project): ?>
              <?php $count += 1; ?>
              <?php $view = array(
                      'project' => $project,
                      'project_members' => $managed_project_member_table[$project['id']]
                    );
              ?>
              <?php if ($count > 3) { $count = 1; } ?>
              <?php switch($count):
                      case 1: ?>
                  <div class="row">
                    <div class="col-md-4">
                      <?php $this->load->view('user/index_partial/managed_project_content', $view); ?>
                    </div>
                  <?php break; ?>
                <?php case 2: ?>
                    <div class="col-md-4">
                      <?php $this->load->view('user/index_partial/managed_project_content', $view); ?>
                    </div>
                  <?php break; ?>
                <?php case 3: ?>
                    <div class="col-md-4">
                      <?php $this->load->view('user/index_partial/managed_project_content', $view); ?>
                    </div>
                  </div>
                  <?php break; ?>
              <?php endswitch; ?>
            <?php endforeach; ?>
            <?php if ($count !== 3): ?> </div> <?php endif; ?>
          <?php else: ?>
            <p class="hint"><?php echo add_icon('frown-o'); ?> Currently there are no managed projects. </p>
          <?php endif; ?>
        </div>
        <hr>
        <div class="card-title">
          <h4>Participated Project(s)</h4>
        </div>
        <div class="card-content">
          <?php if ( ! empty($participated_projects)): ?>
            <?php $count = 0; ?>
            <?php foreach ($participated_projects as $project): ?>
              <?php $count += 1; ?>
              <?php $view = array(
                      'project' => $project,
                      'project_members' => $participated_project_member_table[$project['id']],
                      'manager' => $project_manager_table[$project['id']],
                      'status' => $project_status_table[$project['id']]
                    );
              ?>
              <?php if ($count > 3) { $count = 1; } ?>
              <?php switch($count):
                      case 1: ?>
                  <div class="row">
                    <div class="col-md-4">
                      <?php $this->load->view('user/index_partial/participated_project_content', $view); ?>
                    </div>
                  <?php break; ?>
                <?php case 2: ?>
                    <div class="col-md-4">
                      <?php $this->load->view('user/index_partial/participated_project_content', $view); ?>
                    </div>
                  <?php break; ?>
                <?php case 3: ?>
                    <div class="col-md-4">
                      <?php $this->load->view('user/index_partial/participated_project_content', $view); ?>
                    </div>
                  </div>
                  <?php break; ?>
              <?php endswitch; ?>
            <?php endforeach; ?>
            <?php if ($count !== 3): ?> </div> <?php endif; ?>
          <?php else: ?>
            <p class="hint"><?php echo add_icon('frown-o'); ?> Currently there are no participated projects. </p>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-block">
        <div class="card-title">
          <h3 id="recent_activity"><?php echo add_icon('sticky-note'); ?> Recent Activity</h3>
        </div>
        <div class="card-content">
          <p class="hint text-center"><?php echo add_icon('wrench'); ?> Under Construction</p>
        </div>
      </div>
    </div>

  </div>
</div>