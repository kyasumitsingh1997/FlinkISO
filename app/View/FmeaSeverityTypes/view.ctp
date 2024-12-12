<div id="fmeaSeverityTypes_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="fmeaSeverityTypes form col-md-8">
<h4><?php echo __('View Fmea Severity Type'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Effect'); ?></td>
		<td>
			<?php echo h($fmeaSeverityType['FmeaSeverityType']['effect']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Criteria'); ?></td>
		<td>
			<?php echo h($fmeaSeverityType['FmeaSeverityType']['criteria']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Ranking'); ?></td>
		<td>
			<?php echo h($fmeaSeverityType['FmeaSeverityType']['ranking']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($fmeaSeverityType['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($fmeaSeverityType['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($fmeaSeverityType['FmeaSeverityType']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($fmeaSeverityType['FmeaSeverityType']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo ('upload-edit', array('usersId' => fmeaSeverityType['FmeaSeverityType']['created_by'], 'recordId' => fmeaSeverityType['FmeaSeverityType']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#fmeaSeverityTypes_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$fmeaSeverityType['FmeaSeverityType']['id'] ,'ajax'),array('async' => true, 'update' => '#fmeaSeverityTypes_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
