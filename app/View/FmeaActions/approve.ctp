 <div id="fmeaActions_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fmeaActions form col-md-8">
<h4><?php echo __('Approve FMEA Action'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('FmeaAction',array('role'=>'form','class'=>'form')); ?>
<div class="row">
	<?php
		echo "<div class='col-md-12'>".$this->Form->input('fmea_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('actions_recommended',array()) . '</div>'; 
		
		echo "<div class='col-md-6'>".$this->Form->input('employee_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('target_date',array()) . '</div>'; 
		
		echo "<div class='col-md-12'>".$this->Form->input('action_taken',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('action_taken_date',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('current_status',array('type'=>'radio','default'=>0)) . '</div>'; 
					
		echo "<div class='col-md-6'>".$this->Form->input('fmea_severity_type_id',array('onChange'=>'getvals()')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('fmea_occurence_id',array('onChange'=>'getvals()')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('fmea_detection_id',array('onChange'=>'getvals()')) . '</div>'; 					
		echo "<div class='col-md-6'>".$this->Form->input('rpn',array()) . '</div>'; 
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
      dateFormat:'yy-mm-dd',
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fmeaActions_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
<script>
	function getvals(){
		var s = $("#FmeaActionFmeaSeverityTypeId").val();
		var o = $("#FmeaActionFmeaOccurenceId").val();
		var d = $("#FmeaActionFmeaDetectionId").val();

		$.get("<?php echo Router::url('/', true); ?>fmeas/getvals/" + s + "/" + o + "/" +  d + "/" , function(data) {
            $('#FmeaActionRpn').val(data);
        });

	}
	
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/approve/<?php echo $this->request->params['pass'][0] ?>/<?php echo $this->request->params['pass'][1] ?>",
                type: 'POST',
                target: '#fmeaActions_ajax',
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
        $('#FmeaActionApproveForm').validate();        
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>