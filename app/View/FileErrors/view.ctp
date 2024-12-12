<div id="fileErrors_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fileErrors form col-md-8">
<h4><?php echo __('View File Error'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($fileError['FileError']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($fileError['Project']['title'], array('controller' => 'projects', 'action' => 'view', $fileError['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Milestone'); ?></td>
		<td>
			<?php echo $this->Html->link($fileError['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $fileError['Milestone']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project File Id'); ?></td>
		<td>
			<?php echo h($fileError['FileError']['project_file_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('File Process Id'); ?></td>
		<td>
			<?php echo h($fileError['FileError']['file_process_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('File Error Master'); ?></td>
		<td>
			<?php echo $this->Html->link($fileError['FileErrorMaster']['name'], array('controller' => 'file_error_masters', 'action' => 'view', $fileError['FileErrorMaster']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Total Units'); ?></td>
		<td>
			<?php echo h($fileError['FileError']['total_units']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Total Errors'); ?></td>
		<td>
			<?php echo h($fileError['FileError']['total_errors']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($fileError['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($fileError['ApprovedBy']['name']); ?>&nbsp;</td></tr>
	<tr><td><?php echo __('Publish'); ?></td>

	<td>
	<?php if($fileError['FileError']['publish'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
	<tr><td><?php echo __('Soft Delete'); ?></td>

	<td>
	<?php if($fileError['FileError']['soft_delete'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => ${$singularVar}['{$modelClass}']['created_by'], 'recordId' => ${$singularVar}['{$modelClass}']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fileErrors_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$fileError['FileError']['id'] ,'ajax'),array('async' => true, 'update' => '#fileErrors_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
