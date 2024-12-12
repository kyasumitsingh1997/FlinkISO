<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>


<div id="emailTriggers_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="emailTriggers form col-md-8">
<h4>Add Email Trigger</h4>
<?php echo $this->Form->create('EmailTrigger',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
                    
			<?php
		echo "<div class='col-md-12'>".$this->Form->input('name',array()) . '</div>'; 
		//echo "<div class='col-md-12'>".$this->Form->input('Message.to',array('name'=>'Message.to[]','type' => 'select','class'=>'chzn-select', 'multiple','options' => $users,'label'=>__('Recepient'),'style'=>'width:100%')) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('system_table',array()) . '</div>'; ?>
		<div class="col-md-6"><?php echo $this->Form->input('branch_id',  array('style' => 'width:100%', 'label' => __('Branch'), 'options' => $PublishedBranchList)); ?></div>
		<?php
        echo $this->Form->hidden('changed_field',array()); 
		echo "<div class='col-md-6'>".$this->Form->input('if_added',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('if_edited',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('if_publish',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('if_approved',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('if_soft_delete',array()) . '</div>'; 
                echo "<div class='col-md-6'>".$this->Form->hidden('changed_field',array()) . '</div>'; 
                echo "<div class='col-md-12'>".$this->Form->input('recipents',array('name'=>'recipents[]','type'=>'select','multiple','options'=>$PublishedEmployeeList)) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('cc',array('name'=>'cc[]','type'=>'select','multiple','options'=>$PublishedEmployeeList)) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('bcc',array('name'=>'bcc[]','type'=>'select','multiple','options'=>$PublishedEmployeeList)) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('subject',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('template',array()) . '</div>'; 
	?>
</fieldset>
<?php
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
            ?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#emailTriggers_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>
$().ready(function() {
    $.validator.setDefaults({
         ignore: null,
        errorPlacement: function(error, element) {
            if ($(element).attr('name') == 'data[EmailTrigger][branch_id]') {
                $(element).next().after(error);
            } 
            else if ($(element).attr('name') == 'data[EmailTrigger][system_table]') {
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
		
    $("#submit-indicator").hide();
       
        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
            return (value != -1);
        }, "Please select the value");
     //   $('#UserRegisterForm').validate();
        $('#EmailTriggerAddAjaxForm').validate({
         
            rules: {
                "data[EmailTrigger][branch_id]": {
                     greaterThanZero: true,
                },
                "data[EmailTrigger][system_table]": {
                     greaterThanZero: true,
                }
           }
        });
    });
        $('#EmailTriggerBranchId').change(function() {
            $("#EmailTriggerRecipents").load('<?php echo Router::url('/', true); ?>branches/get_employee_list/' + encodeURIComponent(this.value), function(response, status, xhr){
                $("#EmailTriggerRecipents").html(response);
                $('#EmailTriggerRecipents').val(0).trigger('chosen:updated');
            });
             if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#EmailTriggerSystemTable').change(function() {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
         
             
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
