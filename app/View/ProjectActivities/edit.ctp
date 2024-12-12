 <div id="projectActivities_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projectActivities form col-md-8">
<h4><?php echo __('Edit Project Activity'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Current Project Details'), array('controller'=>'projects','action' => 'view',$project['Project']['id']),array('id'=>'current_view','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit Project'), array('controller'=>'projects','action' => 'edit',$project['Project']['id']),array('id'=>'current_view','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Back To Projects'), array('controller'=>'projects','action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('ProjectActivity',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
      if($this->request->params['named']['project_id']){
        echo "<div class='col-md-6'>".$this->Form->input('project_id',array('default'=>$this->request->params['named']['project_id'])) . '</div>'; 
      }else echo "<div class='col-md-6'>".$this->Form->input('project_id',array()) . '</div>'; 
		
		echo "<div class='col-md-6'>".$this->Form->input('milestone_id',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>';     
    echo "<div class='col-md-12'>".$this->Form->input('user_id',array('options'=>$PublishedUserList)) . '</div>'; 
		echo "<div class='col-md-3'>".$this->Form->input('estimated_cost',array()) . '</div>'; 
		echo "<div class='col-md-3'>".$this->Form->input('start_date',array('label'=>'Date Range')) . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('end_date',array()) . '</div>'; 
		echo "<div class='col-md-3'>".$this->Form->input('sequence',array()) . '</div>'; 
    echo "<div class='col-md-3'>".$this->Form->input('current_status',array('options'=>array('Open','Close'))) . '</div>'; 
    echo "<div class='col-md-12'><div class='alert alert-danger hide' id='zero_balance'>You do not have sufficient balace for this activity. Try adjusting project and milestone cost.</div></div>"; ?>
		
		<div id="cost" class="hide"></div>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="">
		

<?php
	if ($showApprovals && $showApprovals['show_panel'] == true) {
		echo $this->element('approval_form');
	}else{
		echo $this->Form->input('publish', array('label' => __('Publish')));
	}
?>
<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> 
	$("#ProjectActivityStartDate").daterangepicker({
              format: 'MM/DD/YYYY',
              startDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["ProjectActivity"]["start_date"]))?>',
              endDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["ProjectActivity"]["end_date"]))?>',
              minDate: '<?php echo date("yyyy-MM-dd",strtotime($project["Project"]["start_date"]))?>',
              maxDate: '<?php echo date("yyyy-MM-dd",strtotime($project["Project"]["end_date"]))?>',
          // startDate: 'd',
          autoclose:true,
      }); 

    $("#ProjectActivityMilestoneId").on('change',function(){
    $("#cost").html('');
    $("#cost").load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_cost/"+ this.value, function(){
        $("#ProjectActivityEstimatedCost").val($("#cost").html());
        if($("#ProjectActivityEstimatedCost").val() <= 0)$("#zero_balance").removeClass('hide').addClass('show');
    });
    $("#cost").on('change',function(){
      //
      // alert('as');
    });
    
    
  });
</script>
<div class="col-md-4">
	<?php if(isset($project)){ ?> 
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="panel-title"><?php echo $project['Project']['title'];?></div>
      </div>
      <div class="panel-body">
        <p><strong>Start Date </strong>:<?php echo $project['Project']['start_date'];?> <strong>End Date</strong>  : <?php echo $project['Project']['end_date'];?></p>
        <p><strong>Goal</strong> : <?php echo $project['Project']['goal'];?></p>
        <p><strong>Current Status</strong> : <?php echo $project['Project']['current_status'];?></p></div>
    </div>
    <?php } ?>
    <?php if(isset($project_milestones)){ ?>
    <ul class="list-group">
      <?php foreach ($project_milestones as $milestone) { ?>
            <li class="list-group-item">
              <?php echo $milestone['Milestone']['title'];?>
              <?php echo $this->html->link('edit',array('action'=>'edit',$milestone['Milestone']['id']),array('class'=>'btn btn-xs btn-warning pull-right')); ?>
            </li>
        
      <?php } ?> 
    </ul>
    <?php } ?>
    <?php if(isset($project_activities)){ ?>
    <ul class="list-group">
      <?php foreach ($project_activities as $activities) { ?>
            <li class="list-group-item">
              <?php echo $activities['ProjectActivity']['title'];?>
              <?php echo $this->html->link('edit',array('action'=>'edit',$activities['ProjectActivity']['id']),array('class'=>'btn btn-xs btn-warning pull-right')); ?>
            </li>
        
      <?php } ?> 
    </ul>
    <?php } ?>
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectActivities_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults();
    $().ready(function() {
        $('#ProjectActivityEditForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ProjectActivityEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ProjectActivityEditForm').submit();
            }

        });
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
