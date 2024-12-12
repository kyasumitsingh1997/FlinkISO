<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="incidents_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="incidents form col-md-8">
<h4>Add Incident</h4>
<?php echo $this->Form->create('Incident',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
	<fieldset>
		<?php
		echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>';
		echo "<div class='col-md-12'>".$this->Form->input('risk_assessment_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('reported_by',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('branch_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('location',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('department_id',array()) . '</div></div><div class="row">'; 
		echo "<div class='col-md-6'>".$this->Form->input('incident_date',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('incident_reported_lag_time',array()) . '</div>'; 		
		echo "<div class='col-md-12'>".$this->Form->input('location_details',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('activity',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('activity_details',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('damage_details',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('incident_classification_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('first_aid_provided',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('first_aid_details',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('first_aid_provided_by',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('person_responsible_id',array()) . '</div>';		
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
?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#incidents_ajax','async' => 'false')); ?>
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
        $('#IncidentAddAjaxForm').validate();        
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
