<div class="inner-card">
  <div class="card-block">
    <div class="card-title">
      <h4><?php echo $project['title']; ?></h4>
    </div>
    <div class="card-content">

      <div class="row">
        <div class="col-md-2">
          <p><?php echo add_icon('archive'); ?></p>
        </div>
        <div class="col-md-10">
          <p><?php echo truncate($project['description'], '...'); ?></p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-2">
          <p><?php echo add_icon('users'); ?></p>    
        </div>
        <div class="col-md-10">
          <p><?php echo pluralize(count($project_members), 'Member', 'Members'); ?></p>
        </div>
      </div>

      <div class="row member-tags-section">
        <div class="col-md-12">
          <?php if ( ! empty($project_members)): ?>
            <?php for ($i = 0; $i < 5 ; $i++): ?>
              <?php if (empty($project_members[$i])) { break; } ?>
              <a href="#" class="btn btn-member-tag"><?php echo add_icon('tag'); ?> <?php echo $project_members[$i]['username']; ?></a>
            <?php endfor; ?>
          <?php else: ?>
            <a href="#" class="btn btn-add-member">Add Members?</a>
          <?php endif; ?>
        </div>
      </div>

      <div class="row control-actions">
        <div class="col-md-6 btn-left">
          <a href="/project/detail/<?php echo $project['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="View Project"><?php echo add_icon('eye'); ?></a>
          <!-- 
          <a href="#" data-toggle="tooltip" data-placement="bottom" title="History Record"><?php echo add_icon('tasks'); ?></a>
           -->
        </div>
        <div class="col-md-6 btn-right">
          <a href="<?php echo base_url(); ?>project/manage/<?php echo $project['id']; ?>" data-toggle="tooltip" data-placement="bottom" title="Settings"><?php echo add_icon('wrench'); ?></a>
        </div>
      </div>

    </div>
  </div>
</div>