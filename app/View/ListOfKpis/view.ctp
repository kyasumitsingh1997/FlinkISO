<div id="listOfKpis_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="listOfKpis form col-md-8">
<h4><?php echo __('View List Of Kpi'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($listOfKpi['ListOfKpi']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Kpi Details'); ?></td>
		<td>
			<?php echo h($listOfKpi['ListOfKpi']['kpi_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $this->Html->link($listOfKpi['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $listOfKpi['Branch']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Department'); ?></td>
		<td>
			<?php echo $this->Html->link($listOfKpi['Department']['name'], array('controller' => 'departments', 'action' => 'view', $listOfKpi['Department']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $this->Html->link($listOfKpi['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $listOfKpi['Employee']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Other Details'); ?></td>
		<td>
			<?php echo h($listOfKpi['ListOfKpi']['other_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($listOfKpi['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($listOfKpi['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($listOfKpi['ListOfKpi']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($listOfKpi['ListOfKpi']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => listOfKpi['ListOfKpi']['created_by'], 'recordId' => listOfKpi['ListOfKpi']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#listOfKpis_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$listOfKpi['ListOfKpi']['id'] ,'ajax'),array('async' => true, 'update' => '#listOfKpis_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>