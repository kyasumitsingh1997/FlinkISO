<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="projectActivityRequirements_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="projectActivityRequirements form col-md-8">
<h4>Add Project Activity Requirement</h4>
<?php echo $this->Form->create('ProjectActivityRequirement',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
			<?php
      if($selected_activity){
        echo "<div class='col-md-4'>".$this->Form->input('project_id',array('default'=>$selected_activity['ProjectActivity']['project_id'])) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('milestone_id',array('default'=>$selected_activity['ProjectActivity']['milestone_id'])) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('project_activity_id',array('default'=>$selected_activity['ProjectActivity']['id'])) . '</div>';    
      }else{
        echo "<div class='col-md-4'>".$this->Form->input('project_id',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('milestone_id',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('project_activity_id',array()) . '</div>'; 
      }
		
		
		echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('manpower',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('manpower_Details',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('infrastructure',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('other',array()) . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('branch_id',array()) . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('users',array()) . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('user_session_id',array()) . '</div>'; 
	?>
</fieldset>
<?php
    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?></div>
<div class="row">
<?php
  if ($showApprovals && $showApprovals['show_panel'] == true) {
    echo $this->element('approval_form');
  } else {
    echo $this->Form->input('publish', array('label' => __('Publish')));
  }?>
  <?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectActivityRequirements_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
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
    <h4>Milestones</h4>
    <ul class="list-group">
      <?php foreach ($project_milestones as $milestone) { ?>
            <li class="list-group-item">
              <?php echo $milestone['Milestone']['title'];?>
              <?php echo $this->html->link('edit',array('controller'=>'milestones','action'=>'edit',$milestone['Milestone']['id']),array('class'=>'btn btn-xs btn-warning pull-right')); ?>
            </li>
        
      <?php } ?> 
    </ul>
    <?php } ?>

    <?php if(isset($all_activities)){ ?>
    <h4>Activities</h4>
    <ul class="list-group">
      <?php foreach ($all_activities as $key => $value) { ?>
            <li class="list-group-item">
              <?php echo $value;?>
              <?php echo $this->html->link('edit',array('controller'=>'project_activities', 'action'=>'edit',$key),array('class'=>'btn btn-xs btn-warning pull-right')); ?>
            </li>
        
      <?php } ?> 
    </ul>
    <?php } ?>

    <?php if(isset($all_requirements)){ ?>
    <h4>Requirements</h4>
    <ul class="list-group">
      <?php foreach ($all_requirements as $key => $value) { ?>
            <li class="list-group-item">
              <?php echo $value;?>
              <?php echo $this->html->link('edit',array('controller'=>'project_activity_requirements', 'action'=>'edit',$key),array('class'=>'btn btn-xs btn-warning pull-right')); ?>
            </li>
        
      <?php } ?> 
    </ul>
    <?php } ?>
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    });
        }
    });
		$().ready(function() {
    $("#submit-indicator").hide();
        $('#ProjectActivityRequirementAddAjaxForm').validate();        
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>