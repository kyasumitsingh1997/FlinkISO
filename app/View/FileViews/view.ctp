<div id="fileViews_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fileViews form col-md-8">
<h4><?php echo __('View File View'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('File Upload'); ?></td>
		<td>
			<?php echo $this->Html->link($fileView['FileUpload']['name'], array('controller' => 'file_uploads', 'action' => 'view', $fileView['FileUpload']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('User'); ?></td>
		<td>
			<?php echo $this->Html->link($fileView['User']['name'], array('controller' => 'users', 'action' => 'view', $fileView['User']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('User Session'); ?></td>
		<td>
			<?php echo $this->Html->link($fileView['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $fileView['UserSession']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division'); ?></td>
		<td>
			<?php echo $this->Html->link($fileView['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $fileView['Division']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($fileView['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($fileView['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($fileView['FileView']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($fileView['FileView']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->Form->create('Upload',array('role'=>'form','class'=>'form')); ?>
	<fieldset>		<?php 
			echo $this->Upload->edit('upload',$this->Session->read('User.id').'/'.$this->request->params['controller'].'/'.$fileView['FileView']['id']);
			echo $this->Form->end(); ?>
	</fieldset></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fileViews_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$fileView['FileView']['id'] ,'ajax'),array('async' => true, 'update' => '#fileViews_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
