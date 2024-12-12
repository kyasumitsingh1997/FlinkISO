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

$('#MasterListOfFormatDepartmentApproveForm').validate({
    rules: {
	"data[MasterListOfFormatDepartment][branch_id]" : {
	    greaterThanZero:true,
	}}}
);
  $("#submit-indicator").hide();
    $("#submit_id").click(function(){
             if($('#MasterListOfFormatDepartmentApproveForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
		 $("#MasterListOfFormatDepartmentApproveForm").submit();
             }

        });
    $('#MasterListOfFormatDepartmentBranchId').change(function () {
	if( $( this ).val()!=-1 && $(this).next().next('label').hasClass("error")){
	    $(this).next().next('label').remove();
	}
    });
});
</script>

<div id="masterListOfFormatDepartments_ajax">
<?php echo $this->Session->flash();?>
<div class="nav">
<div class="masterListOfFormatDepartments form col-md-8">
<h4><?php echo __('Approve Master List Of Format Department'); ?>
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Import'), '#import',array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
		</h4>
<?php echo $this->Form->create('MasterListOfFormatDepartment',array('role'=>'form','class'=>'form')); ?>
	<fieldset>

	<?php
		echo $this->Form->input('id');


	?>
	<?php if($showApprovals && $showApprovals['show_panel'] == true ) { ?>
		<div class="clearfix">&nbsp;</div><div class="panel panel-default"> <div class="panel-heading"><h3 class="panel-title"><?php echo __("Send for approval") ?></h3></div><div class="panel-body"><?php echo __("Records added to this table will be send to the person you choose from the list below.")?>
			<?php echo $this->Form->input('Approval.user_id',array('options'=>$userids));?>
			<?php echo $this->Form->input('Approval.comments',array('type'=>'textarea'));?>
		<?php if($same == $this->Session->read('User.id'))echo $this->Form->input('publish',array('label'=>'Do not send forward. Publish Now')) ?>
	</div>
		<?php echo $this->element("approval_history");?>
		</div><?php } else { ?>
				<?php echo $this->Form->input('publish', array('label'=> __('Publish')));?>
	<?php } ?>
<?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'),array('div'=>false,'class'=>'btn btn-primary btn-success' ,'id'=>'submit_id')); ?>
            <?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
	</fieldset>
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
		</div></div></div></div>
