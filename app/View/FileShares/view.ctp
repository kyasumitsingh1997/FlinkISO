<div id="fileShares_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fileShares form col-md-8">
<h4><?php echo __('View File Share'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('File Upload'); ?></td>
		<td>
			<?php echo $this->Html->link($fileShare['FileUpload']['id'], array('controller' => 'file_uploads', 'action' => 'view', $fileShare['FileUpload']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $this->Html->link($fileShare['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $fileShare['Branch']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Everyone'); ?></td>
		<td>
			<?php echo h($fileShare['FileShare']['everyone']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Users'); ?></td>
		<td>
			<?php echo h($fileShare['FileShare']['users']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('User Session'); ?></td>
		<td>
			<?php echo $this->Html->link($fileShare['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $fileShare['UserSession']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($fileShare['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($fileShare['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($fileShare['FileShare']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($fileShare['FileShare']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->Form->create('Upload',array('role'=>'form','class'=>'form')); ?>
	<fieldset>		<?php 
			echo $this->Upload->edit('upload',$this->Session->read('User.id').'/'.$this->request->params['controller'].'/'.$fileShare['FileShare']['id']);
			echo $this->Form->end(); ?>
	</fieldset></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fileShares_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$fileShare['FileShare']['id'] ,'ajax'),array('async' => true, 'update' => '#fileShares_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
