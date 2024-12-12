


<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>


<div id="meetingTopics_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="meetingTopics form col-md-8">
<h4>Add Meeting Topic</h4>
<?php echo $this->Form->create('MeetingTopic',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('meeting_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('change_addition_deletion_request_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('corrective_preventive_action_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('document_amendment_record_sheet_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('customer_complaint_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('customer_feedback_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('supplier_evaluation_reevaluation_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('summery_of_supplier_evaluation_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('internal_audit_plan_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('title',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('current_status',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('action_plan',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('employee_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('target_date',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('notes',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('division_id',array()) . '</div>'; 
	?>
</fieldset>
<?php
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
            ?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#meetingTopics_ajax','async' => 'false')); ?>
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
        $('#MeetingTopicAddAjaxForm').validate();        
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>