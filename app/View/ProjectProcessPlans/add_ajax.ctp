<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="projectProcessPlans_ajax">
<?php echo $this->Session->flash();?>	<div class="nav">
		<div class="projectProcessPlans form col-md-8">
			<h4>Add Project Process Plan</h4>
			<?php echo $this->Form->create('ProjectProcessPlan',array('role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
			<fieldset>
					<?php
					echo "<div class='col-md-6'>".$this->Form->input('project_id',array('default'=>$this->request->params['named']['project_id'])) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('milestone_id',array('default'=>$this->request->params['named']['milestone_id'])) . '</div>'; 
					echo "<div class='col-md-6 hide'>".$this->Form->hidden('project_overall_plan_id',array('default'=>$this->request->params['named']['project_overall_plan_id'])) . '</div>'; 
					echo "<div class='col-md-12'>".$this->Form->input('process',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('estimated_units',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('overall_metrics',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('start_date',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('end_date',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('estimated_resource',array()) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('estimated_manhours',array()) . '</div>'; 
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
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectProcessPlans_ajax','async' => 'false')); ?>
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
                
								$(element).attr('name') == 'data[ProjectProcessPlan][project_id]' ||
								$(element).attr('name') == 'data[ProjectProcessPlan][milestone_id]' ||
								$(element).attr('name') == 'data[ProjectProcessPlan][project_overall_plan_id]')
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
        
        $('#ProjectProcessPlanAddAjaxForm').validate({
            rules: {
									"data[ProjectProcessPlan][project_id]": {
                    	greaterThanZero: true,
									},
									"data[ProjectProcessPlan][milestone_id]": {
                    	greaterThanZero: true,
									},
									"data[ProjectProcessPlan][project_overall_plan_id]": {
                    	greaterThanZero: true,
									},
                
            }
        }); 

				$('#ProjectProcessPlanProjectId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProjectProcessPlanMilestoneId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});
				$('#ProjectProcessPlanProjectOverallPlanId').change(function() {
					if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
						$(this).next().next('label').remove();
					}
				});       
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
