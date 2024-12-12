<div id="customerContacts_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="customerContacts form col-md-8">
<h4><?php echo __('View Customer Contact'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($customerContact['CustomerContact']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Customer'); ?></td>
		<td>
			<?php echo $this->Html->link($customerContact['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $customerContact['Customer']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Phone'); ?></td>
		<td>
			<?php echo h($customerContact['CustomerContact']['phone']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Mobile'); ?></td>
		<td>
			<?php echo h($customerContact['CustomerContact']['mobile']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Email'); ?></td>
		<td>
			<?php echo h($customerContact['CustomerContact']['email']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Address'); ?></td>
		<td>
			<?php echo h($customerContact['CustomerContact']['address']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('State Id'); ?></td>
		<td>
			<?php echo h($customerContact['CustomerContact']['state_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($customerContact['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($customerContact['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($customerContact['CustomerContact']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($customerContact['CustomerContact']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $customerContact['CustomerContact']['created_by'], 'recordId' => $customerContact['CustomerContact']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#customerContacts_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$customerContact['CustomerContact']['id'] ,'ajax'),array('async' => true, 'update' => '#customerContacts_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
