<div id="envEvaluations_ajax">
<?php echo $this->Session->flash();?>	
	<div class="nav panel panel-default">
		<div class="envEvaluations form col-md-8">
			<h4><?php echo __('View Env Evaluation'); ?>		
			<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
			<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
			<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
			<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>
		<table class="table table-responsive">
		<tr><td><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($envEvaluation['EnvEvaluation']['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Env Activity'); ?></td>
		<td>
			<?php echo $this->Html->link($envEvaluation['EnvActivity']['title'], array('controller' => 'env_activities', 'action' => 'view', $envEvaluation['EnvActivity']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Env Identification'); ?></td>
		<td>
			<?php echo $this->Html->link($envEvaluation['EnvIdentification']['title'], array('controller' => 'env_identifications', 'action' => 'view', $envEvaluation['EnvIdentification']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td>
			<?php echo __('Score'); ?></td>
		<td>
			<?php echo h($envEvaluation['EnvEvaluation']['score']); ?>			
			&nbsp;
		</td></tr>
		<tr><td colspan="2">
				<table class="table table-responsive table-condesed">
					<tr>
				<?php foreach ($scores as $score) {
					echo "<td>". $score['EvaluationCriteria']['name'] ."</td>";
				} ?></tr>
				<tr>
				<?php foreach ($scores as $score) {				
					echo "<td>".$score['EnvEvaluationScore']['score'] ."</td>";
				} ?></tr>				
				</table>			
		</td></tr>
		<tr><td><?php echo __('Aspect Details'); ?></td>
		<td>
			<?php echo h($envEvaluation['EnvEvaluation']['aspect_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Impact Details'); ?></td>
		<td>
			<?php echo h($envEvaluation['EnvEvaluation']['impact_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($envEvaluation['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($envEvaluation['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($envEvaluation['EnvEvaluation']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($envEvaluation['EnvEvaluation']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $envEvaluation['EnvEvaluation']['created_by'], 'recordId' => $envEvaluation['EnvEvaluation']['id'])); ?>
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#envEvaluations_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$envEvaluation['EnvEvaluation']['id'] ,'ajax'),array('async' => true, 'update' => '#envEvaluations_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
