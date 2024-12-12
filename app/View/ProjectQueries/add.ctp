<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?><div id="projectQueries_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="projectQueries form col-md-8">
<h4><?php echo __('Add Project Query'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('ProjectQuery',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('name') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('query_type_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('project_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('milestone_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('project_file_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('project_process_plan_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('sent_to') . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('query') . '</div>'; 
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
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat:'yy-mm-dd',
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectQueries_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if (
                
			$(element).attr('name') == 'data[ProjectQuery][query_type_id]' ||
			$(element).attr('name') == 'data[ProjectQuery][project_id]' ||
			$(element).attr('name') == 'data[ProjectQuery][milestone_id]' ||
			$(element).attr('name') == 'data[ProjectQuery][project_file_id]' ||
			$(element).attr('name') == 'data[ProjectQuery][project_process_plan_id]' ||
			$(element).attr('name') == 'data[ProjectQuery][employee_id]')
						{	
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
    });
    
    $().ready(function() {
    	jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#ProjectQueryAddForm').validate({        	
            rules: {
				"data[ProjectQuery][query_type_id]": {
                		greaterThanZero: true,
					},
				"data[ProjectQuery][project_id]": {
                		greaterThanZero: true,
					},
				"data[ProjectQuery][milestone_id]": {
                		greaterThanZero: true,
					},
				"data[ProjectQuery][project_file_id]": {
                		greaterThanZero: true,
					},
				"data[ProjectQuery][project_process_plan_id]": {
                		greaterThanZero: true,
					},
				"data[ProjectQuery][employee_id]": {
                		greaterThanZero: true,
					},
                
            }
        }); 
			
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ProjectQueryAddForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ProjectQueryAddForm').submit();
            }

        });

		$('#ProjectQueryQueryTypeId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#ProjectQueryProjectId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#ProjectQueryMilestoneId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#ProjectQueryProjectFileId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#ProjectQueryProjectProcessPlanId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#ProjectQueryEmployeeId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});	

    });
</script>