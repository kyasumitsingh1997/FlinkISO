<div id="incidentInvestigators_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="incidentInvestigators form col-md-8">
<h4><?php echo __('View Incident Investigator'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigator['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $incidentInvestigator['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Address'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['address']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Phone'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['phone']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigator['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentInvestigator['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Designation'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentInvestigator['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentInvestigator['Designation']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Age'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['age']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Gender'); ?></td>
		<td>
			<?php echo h($incidentInvestigator['IncidentInvestigator']['gender']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($incidentInvestigator['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($incidentInvestigator['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($incidentInvestigator['IncidentInvestigator']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($incidentInvestigator['IncidentInvestigator']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $incidentInvestigator['IncidentInvestigator']['created_by'], 'recordId' => $incidentInvestigator['IncidentInvestigator']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentInvestigators_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$incidentInvestigator['IncidentInvestigator']['id'] ,'ajax'),array('async' => true, 'update' => '#incidentInvestigators_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
