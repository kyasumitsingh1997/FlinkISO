<div id="envIdentifications_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="envIdentifications form col-md-8">
<h4><?php echo __('View Env Identification'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($envIdentification['EnvIdentification']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Env Activity'); ?></td>
		<td>
			<?php echo $this->Html->link($envIdentification['EnvActivity']['title'], array('controller' => 'env_activities', 'action' => 'view', $envIdentification['EnvActivity']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Aspect Number'); ?></td>
		<td>
			<?php echo h($envIdentification['EnvIdentification']['aspect_number']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Aspect Details'); ?></td>
		<td>
			<?php echo h($envIdentification['EnvIdentification']['aspect_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Env Impact'); ?></td>
		<td>
			<?php echo $this->Html->link($envIdentification['EnvImpact']['name'], array('controller' => 'env_impacts', 'action' => 'view', $envIdentification['EnvImpact']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Impact Details'); ?></td>
		<td>
			<?php echo h($envIdentification['EnvIdentification']['impact_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($envIdentification['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($envIdentification['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($envIdentification['EnvIdentification']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($envIdentification['EnvIdentification']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $envIdentification['EnvIdentification']['created_by'], 'recordId' => $envIdentification['EnvIdentification']['id'])); ?>
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#envIdentifications_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$envIdentification['EnvIdentification']['id'] ,'ajax'),array('async' => true, 'update' => '#envIdentifications_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
