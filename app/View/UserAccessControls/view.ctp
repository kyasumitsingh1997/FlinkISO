<div id="userAccessControls_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="userAccessControls form col-md-8">
<h4><?php echo __('View User Access Control'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($userAccessControl['UserAccessControl']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($userAccessControl['UserAccessControl']['description']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('User Access'); ?></td>
		<td>
			<?php echo h($userAccessControl['UserAccessControl']['user_access']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division'); ?></td>
		<td>
			<?php echo $this->Html->link($userAccessControl['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $userAccessControl['Division']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($userAccessControl['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($userAccessControl['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($userAccessControl['UserAccessControl']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($userAccessControl['UserAccessControl']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo ('upload-edit', array('usersId' => userAccessControl['UserAccessControl']['created_by'], 'recordId' => userAccessControl['UserAccessControl']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#userAccessControls_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$userAccessControl['UserAccessControl']['id'] ,'ajax'),array('async' => true, 'update' => '#userAccessControls_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
