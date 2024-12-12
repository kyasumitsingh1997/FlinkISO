<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="evaluationCriterias ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Evaluation Criterias','modelClass'=>'EvaluationCriteria','options'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value"),'pluralVar'=>'evaluationCriterias'))); ?>

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>	
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th ><input type="checkbox" id="selectAll"></th>
					
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('aspect_category_id'); ?></th>
				<th colspan="2"><?php echo $this->Paginator->sort('scale_1'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_1_value'); ?></th> -->
				<th colspan="2"><?php echo $this->Paginator->sort('scale_2'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_2_value'); ?></th> -->
				<th colspan="2"><?php echo $this->Paginator->sort('scale_3'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_3_value'); ?></th> -->
				<th colspan="2"><?php echo $this->Paginator->sort('scale_4'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_4_value'); ?></th> -->
				<th colspan="2"><?php echo $this->Paginator->sort('scale_5'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_5_value'); ?></th> -->
				<th colspan="2"><?php echo $this->Paginator->sort('scale_6'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_6_value'); ?></th> -->
				<th colspan="2"><?php echo $this->Paginator->sort('scale_7'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_7_value'); ?></th> -->
				<th colspan="2"><?php echo $this->Paginator->sort('scale_8'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_8_value'); ?></th> -->
				<th colspan="2"><?php echo $this->Paginator->sort('scale_9'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_9_value'); ?></th> -->
				<th colspan="2"><?php echo $this->Paginator->sort('scale_10'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('scale_10_value'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
<?php 
$options = array('Very Low','Low','Medium','High','Very High','Negligible','Moderate','Severe','Fatal','Within acceptable limit','Marginal at acceptable limit','Out of acceptable limit');
?>				
				<?php if($evaluationCriterias){ ?>
<?php foreach ($evaluationCriterias as $evaluationCriteria): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $evaluationCriteria['EvaluationCriteria']['created_by'], 'postVal' => $evaluationCriteria['EvaluationCriteria']['id'], 'softDelete' => $evaluationCriteria['EvaluationCriteria']['soft_delete'])); ?>	</td>		

		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($evaluationCriteria['AspectCategory']['name'], array('controller' => 'aspect_categories', 'action' => 'view', $evaluationCriteria['AspectCategory']['id'])); ?>
		</td>
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_1']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_1_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_2']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_2_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_3']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_3_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_4']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_4_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_5']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_5_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_6']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_6_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_7']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_7_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_8']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_8_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_9']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_9_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($evaluationCriteria['EvaluationCriteria']['scale_10']); ?>&nbsp;</td>
		<td><?php echo h($options[$evaluationCriteria['EvaluationCriteria']['scale_10_value']]); ?>&nbsp;</td>
		
		<td><?php echo h($PublishedEmployeeList[$evaluationCriteria['EvaluationCriteria']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$evaluationCriteria['EvaluationCriteria']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($evaluationCriteria['EvaluationCriteria']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=114>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#main',
			'evalScripts' => true,
			'before' => $this->Js->get('#busy-indicator')->effect('fadeIn', array('buffer' => false)),
			'complete' => $this->Js->get('#busy-indicator')->effect('fadeOut', array('buffer' => false)),
			));
			
			echo $this->Paginator->counter(array(
			'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
			));
			?>			</p>
			<ul class="pagination">
			<?php
		echo "<li class='previous'>".$this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'))."</li>";
		echo "<li>".$this->Paginator->numbers(array('separator' => ''))."</li>";
		echo "<li class='next'>".$this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'))."</li>";
	?>
			</ul>
		</div>
	</div>
	</div>	

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
