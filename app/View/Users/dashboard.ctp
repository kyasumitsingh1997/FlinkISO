<style type="text/css">
#files-tabs .ui-tabs-panel{min-height: 600px}
</style>
<?php
  echo $this->Html->css('pure-css-speech-bubbles');
  echo $this->fetch('css');
?>

<?php if ($show_nc_alert) { ?>
  <div class="alert alert-danger alert-dismissable row">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <span class="glyphicon glyphicon-warning-sign"></span>
    <b><?php echo __('Non conformities are assigned to you for further actions requires your immediate attention, Check the list below under "Non Conformity Actions Required"'); ?></b>
  </div>
<?php } ?>

<?php /* if ($smtp_alert) { ?>
  <div class="alert alert-danger alert-dismissable row">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <span class="glyphicon glyphicon-warning-sign"></span>
    <b><?php echo __('SMTP is not setup properly.'); ?></b>
    <p><?php echo $this->Html->link(__('click here to setup SMTP'), array('controller' => 'users', 'action' => 'smtp_details')) ?></p>
  </div>
<?php } */ ?>
<?php if ($postMeetingAlert && $this->Session->read('User.is_mr') == true) { ?>
  <div class="alert alert-danger alert-dismissable row">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <span class="glyphicon glyphicon-warning-sign"></span>
    <b>  <?php echo $postMeetingAlert ?></b>
  </div>
<?php } ?>
<?php if ($preMeetingAlert) { ?>
  <div class="alert alert-warning alert-dismissable row">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <span class="glyphicon glyphicon-warning-sign"></span>
    <b>  <?php echo $preMeetingAlert ?> <?php echo $this->Html->link(__('click here for more details'), array('controller' => 'meetings', 'action' => 'meeting_view', $meeting_id)) ?></b>
  </div>
<?php } ?>
<?php if ($this->Session->read('User.is_mr') == true ) { ?>
<div class="row row-max">
  <div class="col-md-8"><h4><?php echo __('Overall Preparedness')?></h4></div>
  <div class="col-md-4 text-right"><?php 
  if (isset($benchmark) || $benchmark != 0)
                $cclass = 'btn btn-success';
              else
                $cclass = 'btn btn-danger';
  if ($this->Session->read('User.is_mr') == true) { ?>
      <div class="btn-group"> 
        <?php echo $this->Html->link(__('Audit Trails'), array('controller' => 'user_sessions'), array('class' => 'btn btn-sm btn-success')); ?> 
        <?php echo $this->Html->link(__('Benchmark'), array('controller' => 'benchmarks'), array('class' => 'btn btn-sm ' .$cclass)); ?> 
    </div>
  <?php } ?>
</div>
</div>

<div class="row row-max">  
  <div class="col-md-4">
    <ul class="list-group">
      <li class="list-group-item" id="get_readyness">
      <div class="row">
        <div class="col-md-7">
        <?php echo $readiness;?>% <span class=""><?php echo $this->Html->link(__('Readiness') . ' <span class="glyphicon glyphicon-hand-right"></span>', array('controller' => 'dashboards', 'action' => 'readiness'), array('escape' => false)); ?></span>      
          <?php 
          $month = date('Y-m'); 
          $previous_month = date('Y-m',strtotime('-1 month',strtotime($month)));
          $next_month = date('Y-m',strtotime('+1 month',strtotime($month)));
          $this_month = date('Y-m');
        ?>
      </div> 
      <div class="col-md-5 text-right">
      <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator-readiness')); ?>
      <span class="btn-group btn-group-xs" role="group">
        <?php         
        echo $this->Js->link('<span class="glyphicon glyphicon-step-backward"></span>', array('controller'=>'dashboards','action'=>'readiness_ajax',$previous_month), array('type'=>'button', 'class'=>'btn  btn-info', 'escape'=>false, 'update' => '#get_readyness','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->link('<span class="glyphicon glyphicon-pause"></span>', array('controller'=>'dashboards','action'=>'readiness_ajax',date('Y-m')), array('type'=>'button', 'class'=>'btn btn-info', 'escape'=>false, 'update' => '#get_readyness','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->link('<span class="glyphicon glyphicon-step-forward"></span>', array('controller'=>'dashboards','action'=>'readiness_ajax',$next_month), array('type'=>'button', 'class'=>'btn btn-info', 'escape'=>false, 'update' => '#get_readyness','evalScripts' => true,'before' => $this->Js->get('#busy-indicator-readiness')->effect('fadeIn',array('buffer' => false)),'complete' => $this->Js->get('#busy-indicator-readiness')->effect('fadeOut',array('buffer' => false)),'htmlAttributes' => array('month' => date('Y-m')))); ?>
        <?php echo $this->Js->writeBuffer(); ?>
      </span>
    </div>
  
    <div class="col-md-12">
      <div class="progress">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $readiness ?>%;"> <?php echo $readiness;?>%<span class="sr-only"><?php echo __('60% Complete (warning)'); ?></span> </div>
      </div>
    </div>
    </div>
      </li>
      <li class="list-group-item">
    <?php 
            $usage = floor($avg);           
            if($usage <= 25){echo __('Poor'); $avg = 25; $usage = 5;}
            elseif($usage > 25 && $usage <= 50 ){echo __('Fair'); $avg = 50;}
            elseif($usage > 50 && $usage <= 75 ){echo __('Good');$avg = 74;}
            elseif($usage > 75 && $usage <= 100 ){echo __('Excellent'); $avg = 100;}
            if($benchmark==0)
            {
              $usage=0;
            }
            else
            {
            $usage = round($usage * 100 / $benchmark);
            }
            if($benchmark > 100)$benchmark_show = 100;
          ?>
      <span class="primary"><?php echo __('System Usage'); ?> <small>
        <?php if($benchmark == ''){ ?><strong class="text-danger"><?php echo __('You must add benchmark.'); ?></strong></small><?php }else { ?>
          (<?php echo __('against your set benchmark of '); ?> <strong><?php  echo $benchmark ?></strong>) </small>
        <?php } ?>
      </span>
      <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $usage ?>%"><?php echo $usage ?>% 
          <span class="sr-only">40% Complete (success)</span> </div>
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 
        <?php  echo $benchmark_show - $usage ?>%"><?php  echo $benchmark_show - $usage ?></div>
      </div>
    </li>    
  </ul>
</div>

<?php echo $this->element('top-info',array(
  'capaReceived'=>$capaReceived,
  'openCapa'=>$openCapa,
  'closeCapa'=>$closeCapa,
  'docChangeReq'=>$docChangeReq,
  'receivedNcs'=>$receivedNcs,
  'countNCs'=>$countNCs,
  'openNcs'=>$openNcs,
  'countNCsOpen'=>$countNCsOpen,
  'complaintReceived'=>$complaintReceived,
  'receivedCc'=>$receivedCc,
  'openCc'=>$openCc,
  'complaintOpen'=>$complaintOpen
  ));?>
</div>
<div class="">
  <?php echo $this->Session->flash(); ?>

  <?php
    $graphDbSize = (str_replace('MB', '', $dbsize));
    $graphUploadSize = (str_replace('MB', '', $uploadSize));
    // $usage = round(($graphDbSize + $graphUploadSize) * 100 / 102400);
    $graphDbSize = (float)$graphDbSize; // Convert to float
$graphUploadSize = (float)$graphUploadSize; // Convert to float

$usage = round(($graphDbSize + $graphUploadSize) * 100 / 102400);

  ?>


<?php } ?>

<?php if (($this->Session->read('User.is_mr') == true )) { ?>
<div class="row row-max">
  <?php echo $this->element('capa_ratings',array('capaRatings'=>$capaRatings));?>
</div>
<div class="row row-max">
  <div class="col-md-12">
    <div id="performance_chart" style="height:400px"><div class="row text-center text-info" id=""><p><br /><br /><i class="fa fa-refresh fa-spin fa-3x fa-fw"></i><br /><a href="#" id="loadGraph">Click to load graph</a></p></div></div>
  </div>
</div>
<?php } ?>
<div class="row row-max">
  <div class="col-md-12">
    <h2><?php echo __('Objectives assigned'); ?></h2>
    <table class="table table-responsive table-bordered no-margin">
        <tr>
          <th><?php echo __('Objective'); ?></th>
          <th><?php echo __('Monitoring Scheduled'); ?></th>
          <th><?php echo __('Assigned To'); ?></th>
          <th><?php echo __('Target Date'); ?></th>
          <th><?php echo __('Status'); ?></th>
          <th width="60"><?php echo __('Act'); ?></th>
        </tr>
    <?php foreach ($objectives as $objective) { ?>
      <?php if($objective){ ?>
        <tr>
          <td><?php echo $objective['Objective']['title'] ; ?></td>
          <td><?php echo $objective['Schedule']['name'] ; ?></td>
          <td><?php echo $objective['Employee']['name'] ; ?></td>
          <td><?php echo $objective['Objective']['target_date'] ; ?></td>
          <td><?php echo ($objective['Objective']['current_status']? 'Close':'Open') ; ?></td>
          <td><?php echo $this->Html->link('View',array('controller'=>'objectives','action'=>'view',$objective['Objective']['id']),array('class'=>'btn btn-danger btn-xs')) ; ?></td>
        </tr>
      <?php }?>
     
    <?php } ?>
    </table> 
  </div>
</div> 


<?php if (($this->Session->read('User.is_mr') == true) ){ ?>
<div class="row row-max">
  <div class="col-md-12">
<h2><?php echo __('Pending Objective Monitoring'); ?></h2>
  <?php if($emplolyee_objective_monitoring){ ?>
    <script>
      $(function() {
        $( "#emp_objective_tabs" ).tabs();
      });
    </script>
      <div id="emp_objective_tabs" class="nav-tabs-info">
        <ul class="nav nav-tabs">
          <?php foreach ($emplolyee_objective_monitoring as $schedule=>$details) { echo $schedule?>
            <li><a href="#tab-<?php echo $schedule; ?>">Scheduled <?php echo $schedule; ?> <span class=" badge btn-danger"><?php echo count($details); ?></span></a> </li>
          <?php } ?>
        </ul>
        <?php foreach ($emplolyee_objective_monitoring as $schedule=>$details) { ?>
          <div id="tab-<?php echo $schedule; ?>" style="padding:0 !important;">
            <table class="table table-responsive table-bordered no-margin">
              <tr>
                <th><?php echo __('Objective'); ?></th>
                <th><?php echo __('Scheduled'); ?></th>
                <th><?php echo __('Assigned To'); ?></th>
                <th><?php echo __('Status'); ?></th>
                <th width="60"><?php echo __('Act'); ?></th>
              </tr>
              <?php if($details){
                foreach ($details as $detail) { ?>
                  <tr>
                    <td><?php echo $detail['title'] ; ?></td>
                    <td><?php echo $schedule ; ?></td>
                    <td><?php echo $detail['assigned_to'] ; ?></td>
                    <td><?php echo $detail['status'] ; ?></td>
                    <td><?php echo $this->Html->link('Add',array('controller'=>'objective_monitorings','action'=>'lists','objective_id'=>$detail['id'],'process_id' => $detail['process_id']),array('class'=>'btn btn-danger btn-xs')) ; ?></td>
                  </tr>
                <?php } 
              }?>
            </table>  
          </div>
        <?php } ?>
      <?php } ?>
    </div>
    <?php if(is_array($monitoring) && count($monitoring) > 0) { ?>


        <script>
          $(function() {
            $( "#objective_tabs" ).tabs();
          });
          </script>
        <div id="objective_tabs" class="nav-tabs-info">
          <ul class="nav nav-tabs">
            <?php foreach ($monitoring as $schedule=>$details) { ?>
            <li><a href="#<?php echo $schedule; ?>">Scheduled <?php echo $schedule; ?> <span class=" badge btn-danger"><?php echo count($details); ?></span></a> </li>
            <?php } ?>
          </ul>
          <?php foreach ($monitoring as $schedule=>$details) { ?>
            <div id="<?php echo $schedule; ?>" style="padding:0 !important;">
              <table class="table table-responsive table-bordered no-margin">
              <tr>
                <th><?php echo __('Objective'); ?></th>
                <th><?php echo __('Clauses'); ?></th>
                <th><?php echo __('Process'); ?></th>
                <th><?php echo __('Owner'); ?></th>
                <th><?php echo __('Scheduled'); ?></th>
                <th><?php echo __('Status'); ?></th>
                <th width="60"><?php echo __('Act'); ?></th>
              </tr>
              <?php
              if($details){
              foreach ($details as $detail) { ?>
                <tr>
                  <td><?php echo $detail['title'] ; ?></td>
                  <td><?php echo $detail['clauses'] ; ?></td>
                  <td><?php echo $detail['process_name'] ; ?></td>
                  <td><?php echo $detail['owner'] ; ?></td>
                  <td><?php echo $schedule ; ?></td>
                  <td><?php echo $detail['status'] ; ?></td>
                  <td><?php echo $this->Html->link('Add',array('controller'=>'objective_monitorings','action'=>'lists','objective_id'=>$detail['objective_id'],'process_id' => $detail['process_id']),array('class'=>'btn btn-danger btn-xs')) ; ?></td>
                </tr>
              <?php } 
                }else{ ?>

              <?php }
              ?>

            </table>  
            </div>
          <?php } ?>
      </div>
  <?php }else{ ?>
    
  <?php } ?>  
  </div>

<?php } ?>
<?php if(isset($new_helps) && $new_helps > 0){ ?>
  <div class="col-md-12">
  <div class="box box-info box-solid">
    <div class="box-header with-border">
      <h3 class="panel-title"><?php echo __("New Updates"); ?> <span class="badge btn-danger pull-right"><?php echo $help_update; ?></span></h3>
    </div>
    <div class="box-body">
      <table class="table table-condensed">
        <tr>
          <th><?php echo __("About"); ?></th>          
          <th><?php echo __("Update Date/Time"); ?></th>          
        </tr>
        <?php foreach ($new_helps as $help): ?>
        <tr>
          <td><?php echo __('Help is available under')?> <strong><?php echo Inflector::Humanize($help['Help']['table_name']); ?></strong> : <?php echo $help['Help']['title']; ?></td>
          <td><?php echo $help['Help']['modified']; ?></td>
        </tr>
        <?php endforeach ?>
      </table>
    </div>
  </div>

</div>
<?php } ?>

  <div class="col-md-12">
    <?php if($this->Session->read('User.is_mr') == false )echo "<h3>". __('Welcome '). $this->Session->read('User.name') ." <small> " . $this->Session->read('User.branch') ." " .__('Branch')." / " .$this->Session->read('User.department'). " ". __('Department')." </small></h3>"; ?>
  <div class="box box-danger box-solid">
    <div class="box-header with-border">
      <h3 class="panel-title"><?php echo __("Pending Approvals"); ?> <span class="badge btn-danger pull-right"><?php echo $approvalsCount; ?></span></h3>
    </div>
    <div class="box-body">
      <table class="table table-condensed">
        <tr>
          <th><?php echo __("From"); ?></th>
          <th><?php echo __("About"); ?></th>
          <th><?php if ($this->request->controller != 'customer_feedbacks') echo __("Comments"); ?></th>
          <th><?php echo __("Date/Time"); ?></th>
          <th width="42"><?php echo __("Act"); ?></th>
        </tr>
        <?php foreach ($approvals as $approval): ?>
        <tr>
          <td><?php echo $approval['From']['name']; ?></td>
          <td><?php 
          if($approval['Approval']['model_name'] == 'ChangeAdditionDeletionRequest')$m = 'ChangeRequest';
          else $m = $approval['Approval']['model_name'];
          echo $this->Html->link(Inflector::humanize($m), array('controller' => $approval['Approval']['controller_name'], 'action' => 'approve', $approval['Approval']['record'], $approval['Approval']['id'])); ?></td>
          <td><?php if ($this->request->controller != 'customer_feedbacks') echo $approval['Approval']['comments']; ?></td>
          <td><?php echo $approval['Approval']['created']; ?></td>
          <td><?php echo $this->Html->link(__('Act'), array('controller' => $approval['Approval']['controller_name'], 'action' => 'approve', $approval['Approval']['record'], $approval['Approval']['id']), array('class' => 'badge btn-danger')) ?></td>
        </tr>
        <?php endforeach ?>
      </table>
    </div>
  </div>
</div>
<!-- Meeting topics -->
<div class="col-md-12">
    
  <div class="box box-danger box-solid">
    <div class="box-header with-border">
      <h3 class="panel-title"><?php echo __("Pending Action Items From Meetings"); ?> <span class="badge btn-danger pull-right"><?php echo count($meeting_actions); ?></span></h3>
    </div>
    <div class="box-body">
      <table class="table table-condensed">
        <tr>
          <th><?php echo __("Meeting"); ?></th>
          <th><?php echo __("Meeting Topic"); ?></th>
          <th><?php echo __("Target Date"); ?></th>
          <th width="42"><?php echo __("Act"); ?></th>
        </tr>
        <?php foreach ($meeting_actions as $meeting_action): ?>
        <tr>
          <td><?php echo $meeting_action['Meeting']['title']; ?></td>
          <td><?php echo $meeting_action['MeetingTopic']['title']; ?></td>
          <td><?php echo $meeting_action['MeetingTopic']['target_date']; ?></td>
          <td><?php echo $this->Html->link(__('Act'), array('controller' => 'meeting_topics', 'action' => 'edit', $meeting_action['MeetingTopic']['id']), array('class' => 'badge btn-danger')) ?></td>
        </tr>
        <?php endforeach ?>
      </table>
    </div>
  </div>
</div>
<div class="">
<div class="col-md-12">
  <div class="panel-group" id="dashboard_files_accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-warning">
      <div class="panel-heading" role="tab" id="dashboard_files_heading">
        <h4 class="panel-title">
          <a role="button" data-toggle="collapse" data-parent="#dashboard_files_accordion" href="#dashboard_files_body" aria-expanded="true" aria-controls="dashboard_files_body">
            <?php echo __("Recently Shared Files"); ?>
          </a>
        </h4>
      </div>
      <div id="dashboard_files_body" class="panel-collapse collapse" role="tabpanel" aria-labelledby="dashboard_files_heading">
        <div class="panel-body">
          <div id="recentfiles"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if (isset($blockedUser) && $blockedUser != null){ ?>
<div class="users form col-md-12">
<?php } else   { ?> 
<div class="users form col-md-12">
<?php } ?>
  <div class="panel panel-info">
    <div class="panel-heading">
      <h3 class="panel-title"><?php echo __("Inbox"); ?> <span class="pull-right"><?php echo $this->Html->link(__('Compose'),array('controller'=>'messages'),array('class'=>''));?></span> </h3>
    </div>
    <div class="panel-body">
      <div id="messages_ajax">Loading Messages...</div>
    </div>
  </div>
  
 <?php if (!isset($blockedUser)) { ?>
  
<?php } else   { ?> 
  <div class="col-md-12">
<?php } ?>
<div class="row">
  <div id="blockuser_ajax" class="task_main">
    <?php if (isset($blockedUser) && $blockedUser != null) echo $this->element('blockeduser',array('users'=>$blockedUser)); ?>
  </div>
</div>
<?php if (isset($blockedUser)) { ?></div><?php } ?>
<div class="">
  <div  class="col-md-12 no-padding">
    <h2><?php echo __('Activities Assigned To You');?> <small>(<?php echo __('MR can see all tasks assigned to all the users')?>)</small></h2>
    <div id="activity-task-tabs" class="nav-tabs-info">
      <ul class="nav nav-tabs">
     
        <li>
          <?php     $totalComplaints_cnt = ($complaintOpen)>0 ? ' <span class="badge btn-danger">'.$complaintOpen.'</span>' : '';
                                  //$totalComplaints_cnt = ' <span class="badge btn-danger">'.$totalComplaints.'</span>'; ?>
          <?php echo $this->Html->link(__('Customer Complaints').' '.$totalComplaints_cnt, array('controller' => 'customer_complaints','action'=>'get_customer_complaints'), array('escape' => false)); ?></li>
        <li>
          <?php     $countNextCalibrations = $countNextCalibrations>0 ? $countNextCalibrations: '';
                                                $Calibrations_cnt = ' <span class="badge btn-danger">'.$countNextCalibrations.'</span>'; ?>
          <?php echo $this->Html->link(__('Calibrations').' '.$Calibrations_cnt, array('controller' => 'calibrations','action'=>'get_next_calibration'), array('escape' => false)); ?></li>
        <li>
          <?php     $qcStepsCount_cnt = $qcStepsCount>0 ? ' <span class="badge btn-danger">'.$qcStepsCount.'</span>': '';
                                //  $qcStepsCount_cnt = ' <span class="badge btn-danger">'.$qcStepsCount.'</span>'; ?>
          <?php echo $this->Html->link(__('Materials').' '.$qcStepsCount_cnt, array('controller' => 'materials','action'=>'get_material_qc_required'), array('escape' => false)); ?></li>
        <li>
          <?php $materialQCrequiredCount_cnt = $materialQCrequiredCount>0 ? ' <span class="badge btn-danger">'.$materialQCrequiredCount.'</span>': '';
                                 // $materialQCrequiredCount_cnt = ' <span class="badge btn-danger">'.$materialQCrequiredCount.'</span>'; ?>
          <?php echo $this->Html->link(__('QC Required').' '.$materialQCrequiredCount_cnt, array('controller' => 'delivery_challans','action'=>'get_delivered_material_qc'), array('escape' => false)); ?></li>
        <li>
          <?php     $deviceMaintainancesCount_cnt = $deviceMaintainancesCount>0 ? ' <span class="badge btn-danger">'.$deviceMaintainancesCount.'</span>': '';
                                  //$deviceMaintainancesCount_cnt = ' <span class="badge btn-danger">'.$deviceMaintainancesCount.'</span>'; ?>
          <?php echo $this->Html->link(__('Device Maintenances').' '.$deviceMaintainancesCount_cnt, array('controller' => 'device_maintenances','action'=>'get_device_maintainance'), array('escape' => false)); ?></li>
        
        <li> <?php echo $this->Html->link(__('Change Requests'), array('controller' => 'change_addition_deletion_requests','action'=>'open_crs'), array('escape' => false)); ?></li>
        </li>
        <li> <?php echo $this->Html->link(__('Today\'s Follow Ups'). '<span class="badge badge-sm label-danger"> '.$todays_followups.'</span>',array('controller'=>'proposal_followups','action'=>'followups','hide_panel'=>true),array('escape'=>false)); ?> </li>

        <li> <?php echo $this->Html->link(__('Fmea Actions'). '<span class="badge badge-sm label-danger"> '.$fmeaCount.'</span>',array('controller'=>'fmea_actions','action'=>'actions_assigned','hide_panel'=>true),array('escape'=>false)); ?> </li>


        <li><?php echo $this->Html->image('indicator.gif', array('id' => 'activity-todo-busy-indicator', 'class' => 'pull-right')); ?></li>
      </ul>
    </div>
  </div>
  <script>
$(document).ready(function() {
  $.ajaxSetup({
             cache:false,
         });

    $( "#activity-task-tabs" ).tabs({
         load: function( event, ui ) {
             $("#activity-todo-busy-indicator").hide();
        },
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html(
                    "<?php echo __('Error loading resource.')?> " +
                    "<?php echo __('Contact Administrator.')?>" );
            }
        }
    });

  $( "#activity-task-tabs li" ).click(function() {
      $("#activity-todo-busy-indicator").show();
  });
});
</script> 
</div>
<div class="">
  <div  class="col-md-12 no-padding">
    <h2><?php echo __('Tasks Assigned To You')?> <small>(<?php echo __('MR can see all tasks assigned to all the users')?>)</small><?php echo $this->Html->link('View All', array('controller' => 'tasks','action'=>'index'), array('class' => 'pull-right btn btn-xs btn-info')); ?></h2>
    <div id="task-tabs" class="nav-tabs-info">
      <ul class="nav nav-tabs">
     
        <li>
          <?php $task_cnt = ($tasks)>0 ? ' <span class="badge btn-danger">'.$tasks_completed.'/' .$tasks. '</span>': '';
                                  //$task_cnt = ' <span class="badge btn-danger">'.$task_cnt.'</span>'; ?>
          <?php echo $this->Html->link(__('Tasks') . '&nbsp;&nbsp;<span class="badge btn-danger">'.$task_count.'</span>', array('controller' => 'tasks','action'=>'get_task'), array('escape' => false)); ?>          
        </li>
        <li>
          <?php $process_task_cnt = ($process_tasks)>0 ? ' <span class="badge btn-danger">'. $process_tasks_completed .'/'. $process_tasks.'</span>': '';
                                  //$task_cnt = ' <span class="badge btn-danger">'.$task_cnt.'</span>'; ?>
          <?php echo $this->Html->link(__('Process Tasks'). '&nbsp;&nbsp;<span class="badge btn-danger">'.$process_task_count.'</span>', array('controller' => 'tasks','action'=>'get_process_task'), array('escape' => false)); ?>
        </li>
        
        <li>
          <?php $project_task_cnt = ($project_tasks)>0 ? ' <span class="badge btn-danger">'. $project_tasks_completed .'/'. $project_tasks.'</span>': '';
                                  //$task_cnt = ' <span class="badge btn-danger">'.$task_cnt.'</span>'; ?>
          <?php echo $this->Html->link(__('Project Tasks'). '&nbsp;&nbsp;<span class="badge btn-danger">'.$project_task_count.'</span>', array('controller' => 'tasks','action'=>'get_project_task'), array('escape' => false)); ?>
        </li>
        <li>
          <?php $project_task_cnt = ($project_tasks)>0 ? ' <span class="badge btn-danger">'. $project_tasks_completed .'/'. $project_tasks.'</span>': '';
                                  //$task_cnt = ' <span class="badge btn-danger">'.$task_cnt.'</span>'; ?>
          <?php echo $this->Html->link(__('Project Timesheets'). '&nbsp;&nbsp;<span class="badge btn-danger">'.$project_task_count.'</span>', array('controller' => 'project_timesheets','action'=>'project_timesheet_ajax'), array('escape' => false)); ?>
        </li>
        <li>
          <?php $project_task_cnt = ($cc_task_count)>0 ? ' <span class="badge btn-danger">'. $cc_task_completed .'/'. $cc_task_count.'</span>': '';
                                  //$task_cnt = ' <span class="badge btn-danger">'.$task_cnt.'</span>'; ?>
          <?php echo $this->Html->link(__('Customer Complaint Tasks'). '&nbsp;&nbsp;<span class="badge btn-danger">'.$cc_task_count.'</span>', array('controller' => 'tasks','action'=>'get_cc_task'), array('escape' => false)); ?>
        </li>

        <li>
          <?php echo $this->Html->link(__('Housekeeping'). '&nbsp;&nbsp;<span class="badge btn-danger">'.$housekeeping_task_count.'</span>', array('controller' => 'users','action'=>'personal_admin'), array('escape' => false)); ?>
        </li>
        <li><?php echo $this->Html->image('indicator.gif', array('id' => 'todo-busy-indicator', 'class' => 'pull-right')); ?></li>
      </ul>
    </div>
  </div>
  <script>
$(document).ready(function() {
  $.ajaxSetup({
             cache:false,
         });

    $( "#task-tabs" ).tabs({
         load: function( event, ui ) {
             $("#todo-busy-indicator").hide();
        },
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html(
                    "<?php echo __('Error loading resource.')?> " +
                    "<?php echo __('Contact Administrator.')?>" );
            }
        }
    });

  $( "#task-tabs li" ).click(function() {
      $("#todo-busy-indicator").show();
  });
});
</script> 
</div>
    
    
    <div class="">
  <div  class="col-md-12 no-padding">
    <h2><?php echo __('Capa Assigned To You')?></h2>
    <div id="capa-tabs" class="nav-tabs-info">
      <ul class="nav nav-tabs">
        
        <li>
          <?php    $assignedInvestigationCapa_cnt = ($assignedInvestigationCapa)>0 ? ' <span class="badge btn-danger">'.$assignedInvestigationCapa.'</span>' : '';
                                  //$totalComplaints_cnt = ' <span class="badge btn-danger">'.$totalComplaints.'</span>'; ?>
          <?php echo $this->Html->link(__('Capa Investigation').' '.$assignedInvestigationCapa_cnt, array('controller' => 'capa_investigations','action'=>'capa_assigned'), array('escape' => false)); ?></li>
            <li>
          <?php     $assignedRootCauseAnalysiCapa_cnt = $assignedRootCauseAnalysiCapa>0 ? ' <span class="badge btn-danger">'.$assignedRootCauseAnalysiCapa.'</span>': '';
                                //  $qcStepsCount_cnt = ' <span class="badge btn-danger">'.$qcStepsCount.'</span>'; ?>
          <?php echo $this->Html->link(__('Capa Root Cause Analysis').' '.$assignedRootCauseAnalysiCapa_cnt, array('controller' => 'capa_root_cause_analysis','action'=>'capa_assigned'), array('escape' => false)); ?></li>
        <li>
          <?php $assignedRevisedDateCapa_cnt = ($assignedRevisedDateCapa)>0 ? ' <span class="badge btn-danger">'.$assignedRevisedDateCapa.'</span>':''; ?>
          <?php echo $this->Html->link(__('Capa Revised Date').' '.$assignedRevisedDateCapa_cnt, array('controller' => 'capa_revised_dates','action'=>'capa_assigned'), array('escape' => false)); ?></li>
    
       
      </ul>
    </div>
  </div>
  <script>
$(document).ready(function() {
  $.ajaxSetup({
             cache:false,
         });

    $( "#capa-tabs" ).tabs({
         load: function( event, ui ) {
             $("#todo-busy-indicator").hide();
        },
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html(
                    "<?php echo __('Error loading resource.')?> " +
                    "<?php echo __('Contact Administrator.')?>" );
            }
        }
    });

  $( "#task-tabs li" ).click(function() {
      $("#todo-busy-indicator").show();
  });
});
</script> 
</div>
<?php if ($this->Session->read('User.is_mr') == "0") { ?>
<div class="">
  <div class="users form col-md-12 no-padding">
    <div id="messages_ajax"> 
      <script>
$(document).ready(function() {
  $.ajaxSetup({
    cache:false,   
    });

    $( "#message_tabs" ).tabs({
          load: function( event, ui ) {
            $("#message-busy-indicator").hide();
        },
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html(
                    "<?php echo __('Error loading resource.')?> " +
                    "<?php echo __('Contact Administrator.')?>" );
            }
        }
    });

  $( "#message_tabs li" ).click(function() {
      $("#message-busy-indicator").show();
  });
});
</script>
      <?php $unread = isset($unread) ? $unread : ''; ?>
      <div id="message_tabs" class="nav-tabs-info">
        <ul class="nav nav-tabs">
          <li><?php echo $this->Html->Link(__('Inbox') . '(' . $unread . __(' Unread Messages)'), array('controller' => 'messages', 'action' => 'inbox',$this->request->params['controller']), array('span' => 'Retriving Data')); ?></li>
          <li><?php echo $this->Html->Link(__('Sent'), array('controller' => 'messages', 'action' => 'sent',$this->request->params['controller']), array('span' => 'Retriving Data')); ?></li>
          <li><?php echo $this->Html->Link(__('Compose'), array('controller' => 'messages', 'action' => 'add',$this->request->params['controller']), array('span' => 'Retriving Data')); ?></li>
          <li><?php echo $this->Html->Link(__('Trash'), array('controller' => 'messages', 'action' => 'trash',$this->request->params['controller']), array('span' => 'Retriving Data')); ?></li>
          <li><?php echo $this->Html->image('indicator.gif', array('id' => 'message-busy-indicator', 'class' => 'pull-right')); ?></li>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php } ?>



        <div class=" text-center text-warning" id="timelineTabsDiv"><p><?php echo __('Loading timeline')?>...</p></div>
<?php if (($this->Session->read('User.is_mr') == true )) { ?>
  <div class="text-center text-warning" id="departmentGraphs"><?php echo __('Loading branch & department gauge')?>...</p></div>
<?php } ?>

<?php
if ($companyMessage){
  echo $this->Html->css('pure-css-speech-bubbles');
  echo $this->fetch('css');
}
?>
<?php if ($companyMessage && $companyMessage['Company']['welcome_message']) { ?>

  <div class="col-sm-12 col-md-6">
    <h3 class="text-primary"><?php echo __('Welcome Message'); ?></h3>
    <div class="callout border-callout">
      <p><?php echo $companyMessage['Company']['welcome_message'] ?></p>
      <b class="border-notch notch"></b> <b class="notch"></b> </div>
  </div>
<?php } ?>
<?php if ($companyMessage && $companyMessage['Company']['description']) { ?>
  <div class="col-sm-12 col-md-6">
    <h3 class="text-primary"><?php echo __('Message form Director'); ?></h3>
    <div class="callout border-callout">
      <p><?php echo $companyMessage['Company']['description'] ?></p>
      <b class="border-notch notch"></b> <b class="notch"></b> </div>
  </div>
<?php } ?>
<?php if ($companyMessage && $companyMessage['Company']['quality_policy']) { ?>
  <div class="col-sm-12 col-md-6">
    <div class="panel panel-default">
      <h3 class="text-primary"><?php echo __('Quality Policy'); ?></h3>
      <div class="callout border-callout">
        <p><?php echo $companyMessage['Company']['quality_policy'] ?></p>
        <b class="border-notch notch"></b> <b class="notch"></b> </div>
    </div>
  </div>
<?php } ?>
<?php if ($companyMessage && $companyMessage['Company']['vision_statement']) { ?>
  <div class="col-sm-12 col-md-6">
    <div class="panel panel-default">
      <h3 class="text-primary"><?php echo __('Vision Statement'); ?></h3>
      <div class="callout border-callout">
        <p><?php echo $companyMessage['Company']['vision_statement'] ?></p>
        <b class="border-notch notch"></b> <b class="notch"></b> </div>
    </div>
  </div>
<?php } ?>
<?php if ($companyMessage && $companyMessage['Company']['mission_statement']) { ?>
  <div class="col-sm-12 col-md-6">
    <h3 class="text-primary"><?php echo __('Mission Statement'); ?></h3>
    <div class="callout border-callout">
      <p><?php echo $companyMessage['Company']['mission_statement'] ?></p>
      <b class="border-notch notch"></b> <b class="notch"></b> </div>
  </div>
<?php } ?>
<?php if (($this->Session->read('User.is_mr') == true )) { ?> 
<script type="text/javascript">
$(document).ajaxStop(function() {
  $('#busy-indicator-readiness').hide();
  if($('#department_guage').length){

  }else{
    // $("#messages_ajax").load('<?php echo Router::url('/', true); ?>/messages/inbox_dashboard');    
    $("#loadGraph").on('click',function(){
        $("#performance_chart").load('<?php echo Router::url('/', true); ?>/histories/performance_chart');
    });
    $("#recentfiles").load('<?php echo Router::url('/', true); ?>/file_uploads/pending_view');
    if ($("#departmentGraphs").length){
      $("#departmentGraphs").load('<?php echo Router::url('/', true); ?>users/dashboardgraphs', function(){
      $("#timelineTabsDiv").load('<?php echo Router::url('/', true); ?>users/timelinetabs');});           
    }else{
      $("#timelineTabsDiv").load('<?php echo Router::url('/', true); ?>users/timelinetabs');
    }
  }
})
  
</script>
<?php }else { ?> 
<script type="text/javascript">
$(document).ready(function() { 
    $("#recentfiles").load('<?php echo Router::url('/', true); ?>/file_uploads/pending_view');
    // $("#messages_ajax").load('<?php echo Router::url('/', true); ?>/messages/inbox_dashboard');  
    $("#timelineTabsDiv").load('<?php echo Router::url('/', true); ?>users/timelinetabs');  
});
  
</script>
<?php } ?>
</div>
