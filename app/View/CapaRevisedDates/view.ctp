<div id="capaRevisedDates_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="capaRevisedDates form col-md-8">
<h4><?php echo __('View Capa Revised Date'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Corrective Preventive Action'); ?></td>
		<td>
			<?php echo $this->Html->link($capaRevisedDate['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $capaRevisedDate['CorrectivePreventiveAction']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($capaRevisedDate['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $capaRevisedDate['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Target Date'); ?></td>
		<td>
			<?php echo h($capaRevisedDate['CapaRevisedDate']['target_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('New Revised Date Requested'); ?></td>
		<td>
			<?php echo h($capaRevisedDate['CapaRevisedDate']['new_revised_date_requested']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Reason'); ?></td>
		<td>
			<?php echo h($capaRevisedDate['CapaRevisedDate']['reason']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Revised Date'); ?></td>
		<td>
			<?php echo h($capaRevisedDate['CapaRevisedDate']['revised_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($capaRevisedDate['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($capaRevisedDate['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($capaRevisedDate['CapaRevisedDate']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($capaRevisedDate['CapaRevisedDate']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $capaRevisedDate['CapaRevisedDate']['created_by'], 'recordId' => $capaRevisedDate['CapaRevisedDate']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#capaRevisedDates_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$capaRevisedDate['CapaRevisedDate']['id'] ,'ajax'),array('async' => true, 'update' => '#capaRevisedDates_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
