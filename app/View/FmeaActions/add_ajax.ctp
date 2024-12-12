<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="fmeaActions_ajax">
<?php echo $this->Session->flash();?>	<div class="nav">
		<div class="fmeaActions form col-md-8">
			<h4>Add FMEA Action</h4>
			<?php echo $this->Form->create('FmeaAction',array('role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
				<div class="col-md-12">
						<table class="table table-responsive">
							<tr><td><?php echo __('Process'); ?></td>
							<td>
								<?php echo $this->Html->link($fmea['Process']['title'], array('controller' => 'processes', 'action' => 'view', $fmea['Process']['id'])); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Product'); ?></td>
							<td>
								<?php echo $this->Html->link($fmea['Product']['name'], array('controller' => 'products', 'action' => 'view', $fmea['Product']['id'])); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Process Step'); ?></td>
							<td>
								<?php echo h($fmea['Fmea']['process_step']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Process Sub Step'); ?></td>
							<td>
								<?php echo h($fmea['Fmea']['process_sub_step']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Contribution Of Sub Step'); ?></td>
							<td>
								<?php echo h($fmea['Fmea']['contribution_of_sub_step']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Potential Failure Mode'); ?></td>
							<td>
								<?php echo h($fmea['Fmea']['potential_failure_mode']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Potential Failure Effects'); ?></td>
							<td>
								<?php echo h($fmea['Fmea']['potential_failure_effects']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Fmea Severity Type'); ?></td>
							<td>
								<?php echo $fmeaSeverityTypes[$fmea['FmeaSeverityType']['id']]; ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Potential Causes'); ?></td>
							<td>
								<?php echo h($fmea['Fmea']['potential_causes']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Fmea Occurence'); ?></td>
							<td>
								<?php echo $fmeaOccurences[$fmea['FmeaOccurence']['id']]; ?>
								
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Current Controls'); ?></td>
							<td>
								<?php echo h($fmea['Fmea']['current_controls']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Fmea Detection'); ?></td>
							<td>
								<?php echo $fmeaDetections[$fmea['FmeaDetection']['id']]; ?>								
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Rpn'); ?></td>
							<td>
								<?php echo h($fmea['Fmea']['rpn']); ?>
								&nbsp;
							</td></tr>
							<tr><td><?php echo __('Prepared By'); ?></td>

						<td><?php echo h($fmea['ApprovedBy']['name']); ?>&nbsp;</td></tr>
							<tr><td><?php echo __('Approved By'); ?></td>

						<td><?php echo h($fmea['ApprovedBy']['name']); ?>&nbsp;</td></tr>
							<tr><td><?php echo __('Publish'); ?></td>

							<td>
								<?php if($fmea['Fmea']['publish'] == 1) { ?>
								<span class="fa fa-check"></span>
								<?php } else { ?>
								<span class="fa fa-ban"></span>
								<?php } ?>&nbsp;</td>
					&nbsp;</td></tr>
							<tr><td><?php echo __('Soft Delete'); ?></td>

							<td>
								<?php if($fmea['Fmea']['soft_delete'] == 1) { ?>
								<span class="fa fa-check"></span>
								<?php } else { ?>
								<span class="fa fa-ban"></span>
								<?php } ?>&nbsp;</td>
					&nbsp;</td></tr>
					</table>
				</div>
			</div>
			<div class="row">
			<fieldset>
					<?php
					echo "<div class='col-md-12'>".$this->Form->input('fmea_id',array('default'=>$this->request->params['named']['fmea_id'])) . '</div>'; 
					echo "<div class='col-md-12'>".$this->Form->input('actions_recommended',array()) . '</div>'; 
					
					echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('label'=>'Assign To')) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('target_date',array()) . '</div>'; 
					
					echo "<div class='col-md-12'>".$this->Form->input('action_taken',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('action_taken_date',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('current_status',array('type'=>'radio','default'=>0)) . '</div>'; 
					
					echo "<div class='col-md-6'>".$this->Form->input('fmea_severity_type_id',array('onChange'=>'getvals()')) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('fmea_occurence_id',array('onChange'=>'getvals()')) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('fmea_detection_id',array('onChange'=>'getvals()')) . '</div>'; 					
					echo "<div class='col-md-6'>".$this->Form->input('rpn',array()) . '</div>'; 
	?>
			</fieldset>
			<?php
			    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
			    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
			    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
			?>
		</div>
		<div class="">
			<?php

					if ($showApprovals && $showApprovals['show_panel'] == true) {
						echo $this->element('approval_form');
					} else {
						echo $this->Form->input('publish', array('label' => __('Publish')));
					}?>		
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#fmeaActions_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
		</div>
	</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); 
</script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
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
    	ignore: null,
    	errorPlacement: function(error, element) {
            if(
                $(element).attr('name') == 'data[FmeaAction][fmea_id]' ||
				$(element).attr('name') == 'data[FmeaAction][employee_id]'
			){	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
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
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        
        $('#FmeaActionAddAjaxForm').validate({
            rules: {
				"data[FmeaAction][fmea_id]": {
					greaterThanZero: true,
				},
				"data[FmeaAction][employee_id]": {
					greaterThanZero: true,
				}
                
            }
        }); 

				$('#FmeaActionFmeaId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#FmeaActionEmployeeId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				     
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>