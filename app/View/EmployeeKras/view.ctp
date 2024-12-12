<div id="employeeKras_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="employeeKras form col-md-8">
<h4><?php echo __('View Employee Kra'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($employeeKra['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $employeeKra['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($employeeKra['EmployeeKra']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($employeeKra['EmployeeKra']['description']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Target'); ?></td>
		<td>
			<?php echo h($employeeKra['EmployeeKra']['target']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Target Achieved'); ?></td>
		<td>
			<?php echo h($employeeKra['EmployeeKra']['target_achieved']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Final Rating'); ?></td>
		<td>
			<?php echo h($employeeKra['EmployeeKra']['final_rating']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division'); ?></td>
		<td>
			<?php echo $this->Html->link($employeeKra['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $employeeKra['Division']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($employeeKra['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($employeeKra['ApprovedBy']['name']); ?>&nbsp;</td></tr>
	<tr><td><?php echo __('Publish'); ?></td>

	<td>
	<?php if($employeeKra['EmployeeKra']['publish'] == 1) { ?>
	<span class="fa fa-check"></span>
	<?php } else { ?>
	<span class="fa fa-ban"></span>
	<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
	<tr><td><?php echo __('Soft Delete'); ?></td>

	<td>
	<?php if($employeeKra['EmployeeKra']['soft_delete'] == 1) { ?>
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#employeeKras_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$employeeKra['EmployeeKra']['id'] ,'ajax'),array('async' => true, 'update' => '#employeeKras_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
