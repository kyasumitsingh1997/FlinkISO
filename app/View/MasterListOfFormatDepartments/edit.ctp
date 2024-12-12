<?php echo $this->Html->script(array('jquery.validate.min','jquery-form.min'));?>
<?php echo $this->fetch('script');?>

<script>
$.validator.setDefaults({
ignore: null,
errorPlacement: function(error, element) {
    if($(element).attr('name') == 'data[MasterListOfFormatDepartment][branch_id]'){
	 $(element).next().after(error);
    }else{
	   $(element).after(error);
    }
},
});

$().ready(function() {
    jQuery.validator.addMethod("greaterThanZero", function(value, element) {
	return this.optional(element) || (parseFloat(value) > 0);
    }, "Please select the value");

    $('#MasterListOfFormatDepartmentEditForm').validate({
	rules: {
	    "data[MasterListOfFormatDepartment][branch_id]" : {
		greaterThanZero:true,
	    }}}
    );
    $("#submit-indicator").hide();
    $("#submit_id").click(function(){
	if($('#MasterListOfFormatDepartmentEditForm').valid()) {
	     $("#submit_id").prop("disabled", true);
	     $("#submit-indicator").show();
	     $("#MasterListOfFormatDepartmentEditForm").submit();
	 }
    });
    $('#MasterListOfFormatDepartmentBranchId').change(function() {
	if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
	    $(this).next().next('label').remove();
	}
    });
});
</script>

<div id="masterListOfFormatDepartments_ajax">
<?php echo $this->Session->flash();?>
<div class="nav panel panel-default">
<div class="masterListOfFormatDepartments form col-md-8">
<h4><?php echo __('Edit Master List Of Format Department'); ?>
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Import'), '#import',array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
		</h4>
<?php echo $this->Form->create('MasterListOfFormatDepartment',array('role'=>'form','class'=>'form')); ?>

	<?php echo $this->Form->input('id'); ?>

	    <div class="row">
		<div class="col-md-6"><?php echo $this->Form->input('branch_id',array('style'=>'width:100%', 'label'=> __('Branch'))); ?></div>
	    </div>

<?php if($showApprovals && $showApprovals['show_panel'] == true ) { ?>
<?php echo $this->element('approval_form'); ?>
<?php } else {echo $this->Form->input('publish', array('label'=> __('Publish'))); } ?>
<?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'),array('div'=>false,'class'=>'btn btn-primary btn-success' ,'id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>

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
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#masterListOfFormatDepartments_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
		<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title"><?php echo __('Import from file (excel & csv formats only)'); ?></h4>
		</div>
<div class="modal-body"><?php echo $this->element('import'); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close');?></button>
		</div></div></div></div></div>
