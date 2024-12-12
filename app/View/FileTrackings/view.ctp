<div id="fileTrackings_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fileTrackings form col-md-8">
<h4><?php echo __('View File Tracking'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Project File'); ?></td>
		<td>
			<?php echo $this->Html->link($fileTracking['ProjectFile']['name'], array('controller' => 'project_files', 'action' => 'view', $fileTracking['ProjectFile']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Project'); ?></td>
		<td>
			<?php echo $this->Html->link($fileTracking['Project']['title'], array('controller' => 'projects', 'action' => 'view', $fileTracking['Project']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Milestone'); ?></td>
		<td>
			<?php echo $this->Html->link($fileTracking['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $fileTracking['Milestone']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('From'); ?></td>
		<td>
			<?php echo h($fileTracking['FileTracking']['from']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('To'); ?></td>
		<td>
			<?php echo h($fileTracking['FileTracking']['to']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('By'); ?></td>
		<td>
			<?php echo h($fileTracking['FileTracking']['by']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Current Status'); ?></td>
		<td>
			<?php echo h($fileTracking['FileTracking']['current_status']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Changed On'); ?></td>
		<td>
			<?php echo h($fileTracking['FileTracking']['changed_on']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Changetype'); ?></td>
		<td>
			<?php echo h($fileTracking['FileTracking']['changetype']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Function'); ?></td>
		<td>
			<?php echo h($fileTracking['FileTracking']['function']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Comment'); ?></td>
		<td>
			<?php echo h($fileTracking['FileTracking']['comment']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($fileTracking['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($fileTracking['ApprovedBy']['name']); ?>&nbsp;</td></tr>
	<tr><td><?php echo __('Publish'); ?></td>

	<td>
	<?php if($fileTracking['FileTracking']['publish'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
	<tr><td><?php echo __('Soft Delete'); ?></td>

	<td>
	<?php if($fileTracking['FileTracking']['soft_delete'] == 1) { ?>
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fileTrackings_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$fileTracking['FileTracking']['id'] ,'ajax'),array('async' => true, 'update' => '#fileTrackings_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
