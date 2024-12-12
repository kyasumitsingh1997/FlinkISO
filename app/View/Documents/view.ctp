<div id="documents_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="documents form col-md-8">
<h4><?php echo __('View Document'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($document['Document']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Document Number'); ?></td>
		<td>
			<?php echo h($document['Document']['document_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Issue Number'); ?></td>
		<td>
			<?php echo h($document['Document']['issue_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Revision Number'); ?></td>
		<td>
			<?php echo h($document['Document']['revision_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Revision Date'); ?></td>
		<td>
			<?php echo h($document['Document']['revision_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Content'); ?></td>
		<td>
			<?php echo h($document['Document']['content']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Division'); ?></td>
		<td>
			<?php echo $this->Html->link($document['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $document['Division']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($document['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($document['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($document['Document']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($document['Document']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->Form->create('Upload',array('role'=>'form','class'=>'form')); ?>
	<fieldset>		<?php 
			echo $this->Upload->edit('upload',$this->Session->read('User.id').'/'.$this->request->params['controller'].'/'.$document['Document']['id']);
			echo $this->Form->end(); ?>
	</fieldset></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#documents_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$document['Document']['id'] ,'ajax'),array('async' => true, 'update' => '#documents_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
