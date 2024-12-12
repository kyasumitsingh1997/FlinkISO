 <div id="fmeas_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fmeas form col-md-8">
<h4><?php echo __('Approve Fmea'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('FMEA',array('role'=>'form','class'=>'form')); ?>
<div class="row">
	<?php
		echo "<div class='col-md-12'>".$this->Form->input('name',array()) . '</div>';
				echo "<div class='col-md-12'>".$this->Form->input('process_id',array()) . '</div>';
				echo "<div class='col-md-6'>".$this->Form->input('design_id',array()) . '</div>'; 
				echo "<div class='col-md-6'>".$this->Form->input('product_id',array()) . '</div>'; 
				echo "<div class='col-md-12'>".$this->Form->input('process_step',array('label'=>'Process Step - <span style ="font-weight:400"><small>What is the process step</small></span>')) . '</div>'; 
				echo "<div class='col-md-12'>".$this->Form->input('process_sub_step',array('label'=>'Process Sub-Step - <span style ="font-weight:400"><small>Can we define sub-steps</small></span>')) . '</div>'; 
				echo "<div class='col-md-12'>".$this->Form->input('contribution_of_sub_step',array('label'=>'Contribution of Sub-Step - <span style ="font-weight:400"><small>What is the function of this step?</small></span>')) . '</div>'; 
				echo "<div class='col-md-12'>".$this->Form->input('potential_failure_mode',array('label'=>'Potential Failure Mode - <span style ="font-weight:400"><small>What can go wrong in the process step ?</small></span>')) . '</div>'; 
				
				echo "<div class='col-md-12'>".$this->Form->input('potential_failure_effects',array('label'=>'Potential Failure Effects - <span style ="font-weight:400"><small>What is the impact on the customer requirements or business objectives ?</small></span>')) . '</div>'; 
				echo "<div class='col-md-12'>".$this->Form->input('fmea_severity_type_id',array('label'=>'SEV - <span style ="font-weight:400"><small>How Severe is the effect to the customer?</small></span>','onChange'=>'getvals()')) . '</div>'; 
				
				echo "<div class='col-md-12'>".$this->Form->input('potential_causes',array('label'=>'Potential Causes - <span style ="font-weight:400"><small>What causes the step to go wrong ? List more than one</small></span>')) . '</div>'; 
				echo "<div class='col-md-12'>".$this->Form->input('fmea_occurence_id',array('label'=>'OCC - <span style ="font-weight:400"><small>How often does cause or FM occur?</small></span>','onChange'=>'getvals()')) . '</div>'; 

				echo "<div class='col-md-12'>".$this->Form->input('current_controls',array('label'=>'Current Controls - <span style ="font-weight:400"><small>What are the existing controls and procedures (inspection and test) that prevent either the cause or the Failure Mode?  Should include an SOP number.</small></span>')) . '</div>'; 
				echo "<div class='col-md-12'>".$this->Form->input('fmea_detection_id',array('label'=>'DET - <span style ="font-weight:400"><small>How well can you detect cause or FM?</small></span>','onChange'=>'getvals()')) . '</div>'; 					
			
				echo "<div class='col-md-6'><h2>RPN : <span id='ranking'>".$this->request->data['Fmea']['rpn']."</span></h2>".$this->Form->hidden('rpn',array()) . '</div>';  
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fmeas_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
<script>
	function getvals(){
		var s = $("#FmeaFmeaSeverityTypeId").val();
		var o = $("#FmeaFmeaOccurenceId").val();
		var d = $("#FmeaFmeaDetectionId").val();

		$.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/getvals/" + s + "/" + o + "/" +  d + "/" , function(data) {
            $('#FmeaRpn').val(data);
            $('#ranking').html(data);
        });

	}
	
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/approve/<?php echo $this->request->params['pass'][0] ?>/<?php echo $this->request->params['pass'][1] ?>",
                type: 'POST',
                target: '#fmeas_ajax',
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
        $('#FmeaApproveForm').validate();        
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>