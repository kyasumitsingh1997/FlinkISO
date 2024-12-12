<div class="row">
  <div class="projects form col-md-12">
    <h4><?php echo __('Project'); ?>
      <?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>      
      <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
    </h4>
  </div>
</div>

<?php
$pid = $project['Project']['id'];

?>
<div class="row">
  <div class="col-md-12">
    <div class="btn-group">
  
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('MIS'), array('controller' => 'projects', 'action' => 'mis',$pid),array()); ?></div>
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('File Tracker'), array('controller' => 'projects', 'action' => 'tracker','project_id'=>$pid),array()); ?></div>      
    </div>
  </div>
</div>

<?php 

echo "<div class='row'>";
echo "<div class='col-md-12'1><h1>Weekly Report</h1></div>";
echo $this->Form->create('Project',array('action'=>'weekly_report/'.$project['Project']['id'],array('class'=>'form')));
echo "<div class='col-md-4'>".$this->Form->input('project_id',array('default'=>$project['Project']['id']))."</div>";
echo "<div class='col-md-4'>".$this->Form->input('dates')."</div>";
echo "<div class='col-md-4'><br />".$this->Form->submit('submit',array('class'=>'btn btn-success btn-sm'))."</div>";
echo $this->Form->end();
echo "</div>";
?>
<script type="text/javascript">
  $("#ProjectProjectId").chosen();
  <?php if($this->request->data){ ?>
    $("#ProjectDates").daterangepicker({
        showDropdowns: true,
        locale: { 
            format: 'YYYY-MM-DD'
        }
    });
  <?php }else{ ?>
    var date = new Date();

    $("#ProjectDates").daterangepicker({
        showDropdowns: true,
        startDate: moment().add(-3, 'months'),
        endDate: moment(date),
        maxDate: new Date(),
        minDate: '<?php echo date("Y-m-d",strtotime($project["Project"]["start_date"]));?>',        
        locale: { 
            format: 'YYYY-MM-DD'
        }
    });
    <?php } ?>
</script>

<div class="row">
  <div class="col-md-12">
    <table class="table table-responsive table-bordered">
      <?php foreach($result as $week => $data){ ?>
        <tr><th><?php echo $week;?></th>
          <?php foreach($data as $p => $rec){ ?>
            <th><?php echo $processes[$p];?></th>
          <?php }?>
          <tr>
        <tr>
          <th>Planned Metrics</th>
          <?php foreach($data as $p => $rec){ ?>
            <td><?php echo $rec['ProjectProcessPlan']['overall_metrics'] * $rec['ProcessWeeklyPlan']['planned'];?></td>
          <?php }?>
        </tr>
        <tr><th>Actual Metrics</th>
          <?php foreach($data as $p => $rec){ ?>
            <td><?php echo $rec['ProjectProcessPlan']['overall_metrics'] * $rec['ProcessWeeklyPlan']['actual_resources'];?></td>
          <?php }?>
        </tr>
        <tr><th>Planned Resources</th>
          <?php foreach($data as $p => $rec){ ?>
            <td><?php echo $rec['ProcessWeeklyPlan']['planned'];?></td>
          <?php }?>
        </tr>
        <tr><th>Actual Resources</th>
          <?php foreach($data as $p => $rec){ ?>
            <td><?php echo $rec['ProcessWeeklyPlan']['actual_resources'];?></td>
          <?php }?>
        </tr>
        <tr><th>Planned Hrs.</th>
          <?php foreach($data as $p => $rec){ ?>
            <td><?php echo $rec['ProcessWeeklyPlan']['hours'];?></td>
          <?php }?>
        </tr>
        <tr><th>Actual Hrs.</th>
          <?php foreach($data as $p => $rec){ ?>
            <td><?php echo $rec['ProcessWeeklyPlan']['actual_manhours'];?></td>
          <?php }?>
        </tr>
        <tr><th>Weekly Planned Output</th>
          <?php foreach($data as $p => $rec){ ?>
            <td><?php echo $rec['ProcessWeeklyPlan']['units'];?></td>
          <?php }?>
        </tr>
        <tr><th>Weekly Actual Output</th>
          <?php foreach($data as $p => $rec){ ?>
            <td><?php echo $rec['ProcessWeeklyPlan']['units_completed'];?></td>
          <?php }?>
        </tr>
        <tr><th>Weekly Variance</th></tr>
      <?php }?>
    </table>
  </div>
</div>

