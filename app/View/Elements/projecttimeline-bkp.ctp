<style type="text/css">
    .modal-dialog{width: 95%}
    #dailyprotabs li{min-width: 19.2%; margin: 5px !important}
    #dailyprotabs li > a{border-radius:4px !important;}    
</style>

<style type="text/css">
  .time-label-child{padding-left: 20px}
</style>
<?php 
  $new_project_details = $project_details;
  unset($new_project_details['PurchaseOrder']);
  
  if($this->request->controller != 'projects'){ ?> 
    <div class="box box-success box-solid">
      <div class="box-header with-border">
        <?php echo $this->Html->link($project['Project']['title'],array('controller'=>'projects','action'=>'view',$project['Project']['id']),array('class'=>'box-title'));?></div>
        <div class="box-body">
              From : <?php echo date('d M Y',strtotime($project['Project']['start_date']));?>To : <?php echo date('d M Y',strtotime($project['Project']['end_date']));?>
              <br /><?php echo ($project['Project']['start_date']?'Open':'Close');?>
        </div>        
    </div>
  <?php }?>
<h3><?php echo __('Project Timeline');?></h3>
    <ul class="timeline">
      <li class="time-label"><span class="bg-red"  style="padding: 12px">Start : <?php echo date('d M Y',strtotime($project['Project']['start_date']));?></span></li>
      <?php 
      if($new_project_details[0]){
      foreach ($new_project_details as $project_detail) { 
          if($project_detail['Milestone']['current_status'] == 0){$time_class = 'bg-blue';$label_class='label-danger';}
          elseif($project_detail['Milestone']['current_status'] == 1){$time_class = 'bg-green';$label_class='label-success';}
          else $time_class = 'bg-gray';
        ?>
        <li class="time-label time-label-child"><span class="<?php echo $time_class ?> small">Milestone start: <?php echo date('d M Y',strtotime($project_detail['Milestone']['start_date']));?></span>
          <!-- <i class="fa fa-envelope bg-blue"></i> -->

          <div class="timeline-item">
            <span class="time">
              <span class="label <?php echo $label_class; ?>"><?php echo ($project_detail['Milestone']['current_status']?'Close':'Open');?></span>
              <a class="btn btn-default btn-xs"><?php echo $this->Number->currency($project_detail['Milestone']['estimated_cost'],'INR. ');?></a>
                <?php echo $this->Html->link(
                  'Edit','#',
                    // array('controller'=>'milestones','action'=>'edit',$project_detail['Milestone']['id'],'project_id'=>$project_detail['Milestone']['project_id']),
                    array('class'=>'btn btn-default btn-xs','onclick'=>'openmodel("milestones","edit","'.$project_detail['Milestone']['id'].'","'.$project_id.'","'.$project_detail['Milestone']['id'].'")')
                  ); ?>
                  <?php echo $this->Html->link(
                  'Add new Milestone',
                  '#',
                    // array('controller'=>'milestones','action'=>'lists','project_id'=>$project_detail['Milestone']['project_id']),
                    array('class'=>'btn btn-default btn-xs','onclick'=>'openmodel("milestones","add_ajax",null,"'.$project_id.'",null)')
                  ); ?>
              </span>
            <h4 class="timeline-header"><a href="#"><?php echo $project_detail['Milestone']['title'];?></a></h4>

            <div class="timeline-body">
          <?php echo $project_detail['Milestone']['details'];?>
        </div>
        <div class="timeline-footer"></div>
        <?php if($project_detail['Milestone']['ProjectActivity']){ ?> 
          <!-- <h3><?php echo __('Activites'); ?> </h3> -->
                  <ul class="timeline activities-timeline">
              <?php foreach ($project_detail['Milestone']['ProjectActivity'] as $activity) { 
              if($activity['ProjectActivity']['current_status'] == 0)$activity_time_class = 'bg-light-blue';
                elseif($activity['ProjectActivity']['current_status'] == 1)$activity_time_class = 'bg-light-green';
                else $activity_time_class = 'bg-gray';
                ?>
                <li class="time-label"><span class="<?php echo $activity_time_class ?> smaller">Activity Start : <?php echo date('d M Y',strtotime($activity['ProjectActivity']['start_date']));?></span>
                  <!-- <i class="fa fa-envelope bg-blue"></i> -->

                  <div class="timeline-item">
                    <span class="time"><a class="btn btn-default btn-xs"><?php echo $this->Number->currency($activity['ProjectActivity']['estimated_cost'],'INR. ');?></a>
                      <?php echo $this->Html->link('Edit','#',
                      // array('controller'=>'project_activities','action'=>'edit',$activity['ProjectActivity']['id']),
                      array('class'=>'btn btn-default btn-xs','onclick'=>'openmodel("project_activities","edit","'.$activity['ProjectActivity']['id'].'",null,null,null)')); ?>
                    </span>
                    <h4 class="timeline-header"><a href="#"><?php echo $activity['ProjectActivity']['title'];?></a></h4>

                    <div class="timeline-body">
                      <?php echo $activity['ProjectActivity']['details'];?>

                    </div>
                    <?php if($activity['ProjectActivityRequirement']){ ?> 
                    <div class="timeline-footer activity-footer">
                      <strong><?php echo __('Activity Requirments');?></strong><br />
                      <ul>
                      <?php
                        foreach ($activity['ProjectActivityRequirement'] as $requirement) {
                          echo "<li>".$requirement['ProjectActivityRequirement']['title'];
                          echo "<span class=' pull-right'><span class='label label-info'>".$requirement['ProjectActivityRequirement']['manpower']."</span></span></li>";
                        }
                      ?>
                      </ul>
                  </div>
                  <?php }?>
                  <?php if($activity['Tasks']){ ?> 
                    <div class="timeline-footer activity-footer">
                      <div class="tasks-div">
                        <h4><?php echo __('Tasks');?><small class="pull-right"><small><span class="glyphicon glyphicon-new-window"></span></small></small></h4>
                        <?php
                          $total_tasks = $performed = 0;
                          foreach ($activity['Tasks'] as $task_id=>$task_name) {
                            $task = $this->requestAction(array('controller'=>'tasks','action'=>'view',$task_id)); 
                            foreach ($task[1] as $date => $task_status): 
                            $total_tasks = $total_tasks + 1;
                            if (isset($task_status['TaskStatus']['task_performed']) && $task_status['TaskStatus']['task_performed'] == 1){
                                $performed = $performed + 1;
                            }
                            endforeach;
                            if($task[0]['Task']['task_status'] == 1){
                              $per = 100;
                            }else{
                              $per = round($performed/$total_tasks*100);
                            }
                            ?>
                            <div class="row">
                              <div class="col-sm-12">
                                <div class="clearfix">
                                  <span class="pull-left"><?php echo $this->Html->link($task_name ,array('controller'=>'tasks','action'=>'view',$task_id),array('target'=>'_blank','escape'=>false))?></span>
                                  <span><br ><?php echo $task[0]['User']['name']?></span>
                                  <small class="pull-right"><?php echo $per; ?>%</small>
                                </div>
                                <div class="progress xs">
                                  <div style="width: <?php echo $per; ?>%;" class="progress-bar progress-bar-green"></div>
                                </div>
                              </div>
                            </div>
                          <?php } ?>
                      </div>
                    </div>
                  <?php }else{?>
                    <div class="timeline-footer activity-footer">
                      <div class="tasks-div">
                        <h4><?php echo __('Tasks');?><small class="pull-right"><small><span class="glyphicon glyphicon-new-window"></span></small></small></h4>
                            <div class="row">
                              <div class="col-sm-12">
                                <?php echo $this->Html->link('Add Task',"#",
                                // array('controller'=>'tasks','action'=>'lists','project_id'=>$activity['ProjectActivity']['project_id'],'project_activity_id'=>$activity['ProjectActivity']['id']),
                                array(
                                  'class'=>'btn btn-sm btn-default',
                                  'onclick'=>'openmodel(
                                    "tasks",
                                    "add_ajax",
                                    null,
                                    "'.$project_id.'",
                                    "'.$activity['Milestone']['id'].'",
                                    "'.$activity['ProjectActivity']['id'].'"
                                  )'
                                ));?>
                              </div>
                            </div>                          
                      </div>
                    </div>
                  <?php } ?>                 
                </li>
                 <li class="time-label time-label">
                                <?php echo $this->Html->link('Add Task',"#",
                                // array('controller'=>'tasks','action'=>'lists','project_id'=>$activity['ProjectActivity']['project_id'],'project_activity_id'=>$activity['ProjectActivity']['id']),
                                array(
                                  'class'=>'btn btn-sm btn-default',
                                  'onclick'=>'openmodel(
                                    "tasks",
                                    "add_ajax",
                                    null,
                                    "'.$project_id.'",
                                    "'.$project_detail['Milestone']['id'].'",
                                    "'.$activity['ProjectActivity']['id'].'"
                                  )'
                                ));?>
                              
                      </li>                
                <li class="time-label time-label"><span class="bg-red smaller">Activity end : <?php echo date('d M Y',strtotime($project_detail['Milestone']['end_date']));?></span>                                    
              <?php } ?>
              <!-- <li class="time-label"><?php echo $this->Html->link('Add New Activity',array('controller'=>'project_activities','action'=>'lists','project_id'=>$project['Project']['id'],'milestone_id'=>$project_detail['Milestone']['id']),array('class'=>'smaller btn btn-sm btn-warning')) ?></li> -->
          </ul>
          <?php }?>               
            <!-- <div class="timeline-footer"></div> -->            
          </div>
          <li class="time-label time-label-child"><?php echo $this->Html->link('Add New Activity','#',
            // array('controller'=>'project_activities','action'=>'lists','project_id'=>$project['Project']['id'],'milestone_id'=>$project_detail['Milestone']['id']),
            // array('class'=>'smaller btn btn-sm btn-warning')
            array('class'=>'smaller btn btn-sm btn-warning','onclick'=>'openmodel("project_activities","add_ajax",null,"'.$project_id.'","'.$project_detail['Milestone']['id'].'")')
            ) ?></li>
          <li class="time-label time-label-child"><span class="bg-red small">Milestone end : <?php echo date('d M Y',strtotime($project_detail['Milestone']['end_date']));?></span>
      </li>
      <?php } ?>        
    <?php }else{ ?> 
        <li class="time-label">
          <div class="timeline-item">Details Not Added.
            <div class="timeline-footer activity-footer">
              <div class="tasks-div time-label-child">
                  <div class="row">
                      <div class="col-sm-12">
                        <?php echo $this->Html->link('Add Milestone','#',
                        // array('controller'=>'milestones','action'=>'lists','project_id'=>$project_details['Project_id']),
                        array('class'=>'btn btn-default btn-xs','onclick'=>'openmodel("milestones","add_ajax",null,"'.$project_id.'",null)')
                      );?>
                      </div>
                    </div>                          
              </div>
            </div>
          </div>
        </li>
    <?php } ?>        
        <li class="time-label time-label-child">
          <?php echo $this->Html->link('Add Milestone','#',
          // array('controller'=>'milestones','action'=>'lists','project_id'=>$project_id),
          array('class'=>'btn btn-default btn-sm','onclick'=>'openmodel("milestones","add_ajax",null,"'.$project_id.'",null)'));?>
            
          </li>
        <li class="time-label"><span class="bg-green"  style="padding: 12px">End <?php echo date('d M Y',strtotime($project['Project']['end_date']));?></span></li>
    </ul>

