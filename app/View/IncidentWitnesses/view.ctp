<div id="incidentWitnesses_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="incidentWitnesses form col-md-8">
<h4><?php echo __('View Incident Witness'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Incident'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentWitness['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentWitness['Incident']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Person Type'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['person_type']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentWitness['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $incidentWitness['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Address'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['address']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Phone'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['phone']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentWitness['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentWitness['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Designation'); ?></td>
		<td>
			<?php echo $this->Html->link($incidentWitness['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentWitness['Designation']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Age'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['age']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Gender'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['gender']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Investigation Interview Taken By'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['investigation_interview_taken_by']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Date Of Interview'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['date_of_interview']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Investigation Interview Findings'); ?></td>
		<td>
			<?php echo h($incidentWitness['IncidentWitness']['investigation_interview_findings']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($incidentWitness['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($incidentWitness['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($incidentWitness['IncidentWitness']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($incidentWitness['IncidentWitness']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $incidentWitness['IncidentWitness']['created_by'], 'recordId' => $incidentWitness['IncidentWitness']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentWitnesses_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$incidentWitness['IncidentWitness']['id'] ,'ajax'),array('async' => true, 'update' => '#incidentWitnesses_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
