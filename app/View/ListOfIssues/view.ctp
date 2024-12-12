<div id="listOfIssues_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="listOfIssues form col-md-8">
<h4><?php echo __('View List Of Issue'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($listOfIssue['ListOfIssue']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Issue Details'); ?></td>
		<td>
			<?php echo h($listOfIssue['ListOfIssue']['issue_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $this->Html->link($listOfIssue['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $listOfIssue['Branch']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($listOfIssue['Department']['name'], array('controller' => 'departments', 'action' => 'view', $listOfIssue['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($listOfIssue['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $listOfIssue['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Other Details'); ?></td>
		<td>
			<?php echo h($listOfIssue['ListOfIssue']['other_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($listOfIssue['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($listOfIssue['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($listOfIssue['ListOfIssue']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($listOfIssue['ListOfIssue']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => listOfIssue['ListOfIssue']['created_by'], 'recordId' => listOfIssue['ListOfIssue']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#listOfIssues_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$listOfIssue['ListOfIssue']['id'] ,'ajax'),array('async' => true, 'update' => '#listOfIssues_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
