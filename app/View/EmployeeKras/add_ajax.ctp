<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="employeeKras_ajax">
<?php echo $this->Session->flash();?>	<div class="nav">
		<div class="employeeKras form col-md-8">
			<h4>Add Employee Kra</h4>
			<?php echo $this->Form->create('EmployeeKra',array('role'=>'form','class'=>'form','default'=>false)); ?>
			<div class="row">
			<fieldset>
					<?php
					echo "<div class='col-md-6'>".$this->Form->input('employee_id',array()) . '</div>'; 
					echo "<div class='col-md-6'>".$this->Form->input('title',array('required'=>'required')) . '</div>'; 
					echo "<div class='col-md-12'>".$this->Form->input('process_id',array()) . '</div>'; 
					echo "<div class='col-md-12'>".$this->Form->input('description',array('required'=>'required')) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('target',array('required'=>'required')) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('target_achieved',array('type'=>'radio','options'=>array(0=>'Yes',1=>'No'))) . '</div>'; 
					echo "<div class='col-md-4'>".$this->Form->input('final_rating',array('required'=>'required')) . '</div>'; 
					// echo "<div class='col-md-6'>".$this->Form->input('division_id',array()) . '</div>'; 
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
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#employeeKras_ajax','async' => 'false')); ?>
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
               	$(element).attr('name') == 'data[EmployeeKra][employee_id]' 
               	// || $(element).attr('name') == 'data[EmployeeKra][division_id]'
               	)
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

		$("#EmployeeKraEmployeeId").on('change',function(){
			$.get("<?php echo Router::url('/', true); ?>employee_kras/get_process/employee_id:" + $("#EmployeeKraEmployeeId").val(), function(data) {
             $("#EmployeeKraProcessId").html(data).trigger("chosen:updated");                 
            });
		});

    	$("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");
        
        $('#EmployeeKraAddAjaxForm').validate({
            rules: {
				"data[EmployeeKra][employee_id]": {
					greaterThanZero: true,
				},
				// "data[EmployeeKra][division_id]": {
				// 	greaterThanZero: true,
				// },
                
            }
        }); 

		$('#EmployeeKraEmployeeId').change(function() {
			if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				$(this).next().next('label').remove();
			}
		});
				// $('#EmployeeKraDivisionId').change(function() {
				// 	if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
				// 		$(this).next().next('label').remove();
				// 	}
				// });       
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
