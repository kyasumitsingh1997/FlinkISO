<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="fileErrors_ajax">
<?php echo $this->Session->flash();?>	<div class="nav">
		<div class="fileErrors form col-md-8">
			<h4>Add File Error</h4>
			<?php echo $this->Form->create('FileError',array('role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
			<fieldset>
					<?php
					echo "<div class='col-md-6'>".$this->Form->input('name',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('project_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('milestone_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('project_file_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('file_process_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('file_error_master_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('total_units',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('total_errors',array()) . '</div>'; 
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
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#fileErrors_ajax','async' => 'false')); ?>
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
        
        $('#FileErrorAddAjaxForm').validate({
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
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>