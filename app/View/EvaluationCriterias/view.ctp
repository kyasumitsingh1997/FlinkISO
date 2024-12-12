<div id="evaluationCriterias_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="evaluationCriterias form col-md-8">
<h4><?php echo __('View Evaluation Criteria'); ?>		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
		</h4>

<table class="table table-responsive">
		<tr><td><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['name']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Aspect Category'); ?></td>
		<td>
			<?php echo $this->Html->link($evaluationCriteria['AspectCategory']['name'], array('controller' => 'aspect_categories', 'action' => 'view', $evaluationCriteria['AspectCategory']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 1'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_1']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 1 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_1_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 2'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_2']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 2 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_2_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 3'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_3']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 3 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_3_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 4'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_4']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 4 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_4_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 5'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_5']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 5 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_5_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 6'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_6']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 6 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_6_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 7'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_7']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 7 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_7_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 8'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_8']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 8 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_8_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 9'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_9']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 9 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_9_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 10'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_10']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Scale 10 Value'); ?></td>
		<td>
			<?php echo h($evaluationCriteria['EvaluationCriteria']['scale_10_value']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($evaluationCriteria['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($evaluationCriteria['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($evaluationCriteria['EvaluationCriteria']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($evaluationCriteria['EvaluationCriteria']['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>
<?php echo $this->element('upload-edit', array('usersId' => $evaluationCriteria['EvaluationCriteria']['created_by'], 'recordId' => $evaluationCriteria['EvaluationCriteria']['id'])); ?></div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#evaluationCriterias_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$evaluationCriteria['EvaluationCriteria']['id'] ,'ajax'),array('async' => true, 'update' => '#evaluationCriterias_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
