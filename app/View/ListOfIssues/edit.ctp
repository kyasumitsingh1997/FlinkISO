<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?><div id="listOfIssues_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="listOfIssues form col-md-8">
<h4><?php echo __('Edit List Of Issue'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('ListOfIssue',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('issue_details',array()) . '</div>'; 
		echo "<div class='col-md-4'>".$this->Form->input('branch_id',array()) . '</div>'; 
		echo "<div class='col-md-4'>".$this->Form->input('department_id',array()) . '</div>'; 
		echo "<div class='col-md-4'>".$this->Form->input('employee_id',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('other_details',array()) . '</div>'; 
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#listOfIssues_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if (
                
			$(element).attr('name') == 'data[ListOfIssue][branch_id]' ||
			$(element).attr('name') == 'data[ListOfIssue][department_id]' ||
			$(element).attr('name') == 'data[ListOfIssue][employee_id]')
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

        $('#ListOfIssueEditForm').validate({        	
            rules: {
				"data[ListOfIssue][branch_id]": {
                		greaterThanZero: true,
					},
				"data[ListOfIssue][department_id]": {
                		greaterThanZero: true,
					},
				"data[ListOfIssue][employee_id]": {
                		greaterThanZero: true,
					},
                
            }
        }); 
			
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ListOfIssueEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ListOfIssueEditForm').submit();
            }

        });

		$('#ListOfIssueBranchId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#ListOfIssueDepartmentId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#ListOfIssueEmployeeId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});	

    });
</script>