<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?><div id="fileErrors_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fileErrors form col-md-8">
<h4><?php echo __('Edit File Error'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('FileError',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('name') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('project_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('milestone_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('project_file_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('file_process_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('file_error_master_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('total_units') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('total_errors') . '</div>'; 
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fileErrors_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
    	ignore: null,
    	errorPlacement: function(error, element) {
            if (
                
			$(element).attr('name') == 'data[FileError][project_id]' ||
			$(element).attr('name') == 'data[FileError][milestone_id]' ||
			$(element).attr('name') == 'data[FileError][project_file_id]' ||
			$(element).attr('name') == 'data[FileError][file_process_id]' ||
			$(element).attr('name') == 'data[FileError][file_error_master_id]')
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

        $('#FileErrorEditForm').validate({        	
            rules: {
				"data[FileError][project_id]": {
                		greaterThanZero: true,
					},
				"data[FileError][milestone_id]": {
                		greaterThanZero: true,
					},
				"data[FileError][project_file_id]": {
                		greaterThanZero: true,
					},
				"data[FileError][file_process_id]": {
                		greaterThanZero: true,
					},
				"data[FileError][file_error_master_id]": {
                		greaterThanZero: true,
					},
                
            }
        }); 
			
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#FileErrorEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#FileErrorEditForm').submit();
            }

        });

		$('#FileErrorProjectId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#FileErrorMilestoneId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#FileErrorProjectFileId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#FileErrorFileProcessId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
		$('#FileErrorFileErrorMasterId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});	

    });
</script>