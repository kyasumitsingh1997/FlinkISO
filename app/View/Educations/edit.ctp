 <div id="educations_ajax">
<?php echo $this->Session->flash();?>
<div class="nav panel panel-default">
<div class="educations form col-md-8">
<h4><?php echo __('Edit Education'); ?>
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Import'), '#import',array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
		</h4>
<?php echo $this->Form->create('Education',array('role'=>'form','class'=>'form')); ?>
	<fieldset>

	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title');
		echo $this->Form->input('branchid',array('type'=>'hidden','value'=>$this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid',array('type'=>'hidden','value'=>$this->Session->read('User.department_id')));
	?>
	<?php if($show_approvals && $show_approvals['show_panel'] == true ) { ?>
		<div class="clearfix">&nbsp;</div>
				<div class="panel panel-default">
				<div class="panel-heading"><h3 class="panel-title">
				<?php echo __("Send for approval") ?></h3></div>
				<div class="panel-body"><?php echo __("Records added to this table will be send to the person you choose from the list below.")?>
			<?php echo $this->Form->input('Approval.user_id',array('options'=>$userids));?>
			<?php echo $this->Form->input('Approval.comments',array('type'=>'textarea'));?>
		<?php if($show_approvals['show_publish'] == true)echo $this->Form->input('publish',array('label'=>'Do not send forward. Publish Now')) ?>
	</div> <?php } else {echo $this->Form->input('publish'); }
 ?>


<?php echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success'));?>
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#educations_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
		<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Import from file (excel & csv formats only)</h4>
		</div>
<div class="modal-body"><?php echo $this->element('import'); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>
