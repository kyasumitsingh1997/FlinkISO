<div id="envEvaluationScores_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="envEvaluationScores form col-md-8">
<h4><?php echo __('View Env Evaluation Score'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($envEvaluationScore['EnvEvaluationScore']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Env Activity'); ?></td>
		<td>
			<?php echo $this->Html->link($envEvaluationScore['EnvActivity']['title'], array('controller' => 'env_activities', 'action' => 'view', $envEvaluationScore['EnvActivity']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Env Indentification Id'); ?></td>
		<td>
			<?php echo h($envEvaluationScore['EnvEvaluationScore']['env_indentification_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Env Evaluation Id'); ?></td>
		<td>
			<?php echo h($envEvaluationScore['EnvEvaluationScore']['env_evaluation_id']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Aspect'); ?></td>
		<td>
			<?php echo $this->Html->link($envEvaluationScore['Aspect']['name'], array('controller' => 'aspects', 'action' => 'view', $envEvaluationScore['Aspect']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Score'); ?></td>
		<td>
			<?php echo h($envEvaluationScore['EnvEvaluationScore']['score']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Aspect Details'); ?></td>
		<td>
			<?php echo h($envEvaluationScore['EnvEvaluationScore']['aspect_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Impact Details'); ?></td>
		<td>
			<?php echo h($envEvaluationScore['EnvEvaluationScore']['impact_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($envEvaluationScore['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($envEvaluationScore['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($envEvaluationScore['EnvEvaluationScore']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($envEvaluationScore['EnvEvaluationScore']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $envEvaluationScore['EnvEvaluationScore']['created_by'], 'recordId' => $envEvaluationScore['EnvEvaluationScore']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#envEvaluationScores_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$envEvaluationScore['EnvEvaluationScore']['id'] ,'ajax'),array('async' => true, 'update' => '#envEvaluationScores_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
