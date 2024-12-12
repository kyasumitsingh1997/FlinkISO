 <div id="capaRootCauseAnalysis_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="capaRootCauseAnalysis form col-md-8">
<h4><?php echo __('Edit Capa Root Cause Analysi'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('CapaRootCauseAnalysi',array('role'=>'form','class'=>'form')); ?>
<div class="row">
		<?php
		echo "<div class='col-md-6'>".$this->Form->input('corrective_preventive_action_id',array('options' => $correctivePreventiveActionIds)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('options' => $PublishedEmployeeList)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('determined_by',array('options' => $PublishedEmployeeList)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('determined_on_date',array()) . '</div>'; 
                echo "<div class='col-md-6'>".$this->Form->input('root_cause_details',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('root_cause_remarks',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('action_assigned_to',array('options' => $PublishedEmployeeList)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('action_completed_on_date',array()) . '</div>'; 
                echo "<div class='col-md-6'>".$this->Form->input('proposed_action',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('action_completion_remarks',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('effectiveness',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('closure_remarks',array()) . '</div>'; 
		echo "<div class='col-md-6'>"."<label>" . __('Current Status') . "</label>";
		echo $this->Form->input('current_status', array('value' => '0', 'label' => false, 'legend' => false,  'div' => false, 'options' => array('0' => 'Open', '1' => 'Close'), 'type' => 'radio', 'style' => 'float:none','onclick' => 'currentStatus()')). '</div>'; 
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
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[CapaRootCauseAnalysi][employee_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CapaRootCauseAnalysi][corrective_preventive_action_id]') {
                $(element).next().after(error);
            
            } else if ($(element).attr('name') == 'data[CapaRootCauseAnalysi][determined_by]') {
                $(element).next().after(error);
            }  else if ($(element).attr('name') == 'data[CapaRootCauseAnalysi][action_assigned_to]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        }
    });
        $().ready(function () {
  
      $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#CapaRootCauseAnalysiEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#CapaRootCauseAnalysiEditForm').submit();
            }

        });
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#CapaRootCauseAnalysiEditForm').validate({
            rules: {
                "data[CapaRootCauseAnalysi][employee_id]": {
                    greaterThanZero: true,
                },
                "data[CapaRootCauseAnalysi][corrective_preventive_action_id]": {
                    greaterThanZero: true,
                },
                "data[CapaRootCauseAnalysi][determined_by]": {
                    greaterThanZero: true,
                },
                "data[CapaRootCauseAnalysi][action_assigned_to]": {
                    greaterThanZero: true,
                },
              
            }
        });

        $('#CapaRootCauseAnalysiEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CapaRootCauseAnalysiCorrectivePreventiveActionId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CapaRootCauseAnalysiDeterminedBy').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CapaRootCauseAnalysiActionAssignedTo').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    
 });


</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
