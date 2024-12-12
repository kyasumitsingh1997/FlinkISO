


<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>


<div id="capaRevisedDates_ajax">
<?php echo $this->Session->flash();?><div class="nav">
    <?php
   if($modal != 1) { ?>
<div class="capaRevisedDates form col-md-8">
<h4>Add Capa Revised Date</h4>
 <?php } else { ?>
<div class="capaRevisedDates form col-md-12">

 <?php } ?>
<?php echo $this->Form->create('CapaRevisedDate',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('corrective_preventive_action_id',array('options' => $correctivePreventiveActionIds,'value'=>$capaId)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('employee_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('target_date',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('new_revised_date_requested',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('reason',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('revised_date',array()) . '</div>'; 
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
            ?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#capaRevisedDates_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<style>
#ui-datepicker-div{z-index:1999 !important}
</style>
<script>
    
      $("[name*='date']").datepicker({
        changeMonth: true,
        changeYear: true,
        format: 'yyyy-mm-dd',
      autoclose:true,
        'showTimepicker': false,
    }).attr('readonly', 'readonly'); 
  
//    $("[name*='date']").datepicker({
//      changeMonth: true,
//      changeYear: true,
//      format: 'yyyy-mm-dd',
      autoclose:true,
//    });
</script>
<?php if($modal != 1) { ?>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
<?php } ?>
</div>

<script>
    $.validator.setDefaults({
        ignore: null,
        errorPlacement: function (error, element) {
            if ($(element).attr('name') == 'data[CapaRevisedDate][employee_id]') {
                $(element).next().after(error);
            } else if ($(element).attr('name') == 'data[CapaRevisedDate][corrective_preventive_action_id]') {
                $(element).next().after(error);
            } else {
                $(element).after(error);
            }
        },
        submitHandler: function (form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                    $('#revisedDateModal').modal('hide');
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                   
                },
                error: function (request, status, error) {
                    //alert(request.responseText);
                    alert('Action failed!');
                }
            });
        }
    });
        $().ready(function () {
         $('.chosen-select').chosen();
    $("#submit-indicator").hide();
        jQuery.validator.addMethod("greaterThanZero", function (value, element) {
            return this.optional(element) || (parseFloat(value) > 0);
        }, "Please select the value");

        $('#CapaRevisedDateAddAjaxForm').validate({
            rules: {
                "data[CapaRevisedDate][employee_id]": {
                    greaterThanZero: true,
                },
                "data[CapaRevisedDate][corrective_preventive_action_id]": {
                    greaterThanZero: true,
                },
              
            }
        });

        $('#CapaRevisedDateEmployeeId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
        $('#CapaRevisedDateCorrectivePreventiveActionId').change(function () {
            if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
                $(this).next().next('label').remove();
            }
        });
    
 });
</script>


<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
