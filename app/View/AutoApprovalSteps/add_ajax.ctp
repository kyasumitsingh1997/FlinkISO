


<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>


<div id="autoApprovalSteps_ajax">
<?php echo $this->Session->flash();?>	<div class="nav">
		<div class="autoApprovalSteps form col-md-8">
			<h4>Add Auto Approval Step</h4>
			<?php echo $this->Form->create('AutoApprovalStep',array('role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
			<fieldset>
					<?php
					echo "<div class='col-md-6'>".$this->Form->input('auto_approval_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('name',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('step_number',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('allow_approval',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('show_details',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('user_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('branch_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('department_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('details',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('system_table',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('division_id',array()) . '</div>'; 
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
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#autoApprovalSteps_ajax','async' => 'false')); ?>
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
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if (
                
								$(element).attr('name') == 'data[AutoApprovalStep][auto_approval_id]' ||
								$(element).attr('name') == 'data[AutoApprovalStep][user_id]' ||
								$(element).attr('name') == 'data[AutoApprovalStep][branch_id]' ||
								$(element).attr('name') == 'data[AutoApprovalStep][department_id]' ||
								$(element).attr('name') == 'data[AutoApprovalStep][division_id]')
						{	
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
        
        $('#AutoApprovalStepAddAjaxForm').validate({
            rules: {
									"data[AutoApprovalStep][auto_approval_id]": {
                    	greaterThanZero: true,
									},
									"data[AutoApprovalStep][user_id]": {
                    	greaterThanZero: true,
									},
									"data[AutoApprovalStep][branch_id]": {
                    	greaterThanZero: true,
									},
									"data[AutoApprovalStep][department_id]": {
                    	greaterThanZero: true,
									},
									"data[AutoApprovalStep][division_id]": {
                    	greaterThanZero: true,
									},
                
            }
        }); 

				$('#AutoApprovalStepAutoApprovalId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#AutoApprovalStepUserId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#AutoApprovalStepBranchId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#AutoApprovalStepDepartmentId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#AutoApprovalStepDivisionId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});       
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
