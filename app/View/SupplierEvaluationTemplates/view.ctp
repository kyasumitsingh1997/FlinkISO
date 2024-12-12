<div id="supplierEvaluationTemplates_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="supplierEvaluationTemplates form col-md-8">
<h4><?php echo __('View Supplier Evaluation Template'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($supplierEvaluationTemplate['SupplierEvaluationTemplate']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td colspan="2"><?php echo __('Details'); ?>
			<?php echo $supplierEvaluationTemplate['SupplierEvaluationTemplate']['details']; ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division'); ?></td>
		<td>
			<?php echo $this->Html->link($supplierEvaluationTemplate['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $supplierEvaluationTemplate['Division']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($supplierEvaluationTemplate['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($supplierEvaluationTemplate['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($supplierEvaluationTemplate['SupplierEvaluationTemplate']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($supplierEvaluationTemplate['SupplierEvaluationTemplate']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $supplierEvaluationTemplate['SupplierEvaluationTemplate']['created_by'], 'recordId' => $supplierEvaluationTemplate['SupplierEvaluationTemplate']['id'])); ?>
;
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#supplierEvaluationTemplates_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$supplierEvaluationTemplate['SupplierEvaluationTemplate']['id'] ,'ajax'),array('async' => true, 'update' => '#supplierEvaluationTemplates_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
