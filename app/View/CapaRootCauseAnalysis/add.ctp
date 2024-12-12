 <div id="capaRootCauseAnalysis_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="capaRootCauseAnalysis form col-md-8">
<h4><?php echo __('Add Capa Root Cause Analysi'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('CapaRootCauseAnalysi',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('corrective_preventive_action_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('root_cause_details') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('determined_by') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('determined_on_date') . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('root_cause_remarks') . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('proposed_action') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('action_assigned_to') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('action_completed_on_date') . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('action_completion_remarks') . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('effectiveness') . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('closure_remarks') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('current_status') . '</div>'; 
	?>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="row">
		

<?php

		
	if ($showApprovals && $showApprovals['show_panel'] == true) {
		
		echo $this->element('approval_form');
		
	} else {
		
		echo $this->Form->input('publish', array('label' => __('Publish')));
		
	}
?>
<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#capaRootCauseAnalysis_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults();
    $().ready(function() {
        $('#CapaRootCauseAnalysiAddForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#CapaRootCauseAnalysiAddForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#CapaRootCauseAnalysiAddForm').submit();
            }

        });
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
