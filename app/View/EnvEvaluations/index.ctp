<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="envEvaluations ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Env Evaluations','modelClass'=>'EnvEvaluation','options'=>array("sr_no"=>"Sr No","title"=>"Title","score"=>"Score","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details"),'pluralVar'=>'envEvaluations'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('env_activity_id'); ?></th>
				<th><?php echo $this->Paginator->sort('env_indentification_id'); ?></th>
				<th><?php echo $this->Paginator->sort('Evaluation Criteria'); ?></th>
				<th><?php echo $this->Paginator->sort('score'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('aspect_details'); ?></th>
				<th><?php echo $this->Paginator->sort('impact_details'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($envEvaluations){ ?>
<?php foreach ($envEvaluations as $envEvaluation): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $envEvaluation['EnvEvaluation']['created_by'], 'postVal' => $envEvaluation['EnvEvaluation']['id'], 'softDelete' => $envEvaluation['EnvEvaluation']['soft_delete'])); ?>	</td>		<td><?php echo h($envEvaluation['EnvEvaluation']['title']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($envEvaluation['EnvActivity']['title'], array('controller' => 'env_activities', 'action' => 'view', $envEvaluation['EnvActivity']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($envEvaluation['EnvIdentification']['title'], array('controller' => 'env_identifications', 'action' => 'view', $envEvaluation['EnvIdentification']['id'])); ?>
		</td>
		<td>
			<table class="table table-responsive table-condesed">
				<tr>
			<?php foreach ($envEvaluation['EvaluationCriteria'] as $scores) {
				echo "<td>". $scores['EvaluationCriteria']['name'] ."</td>";
			} ?></tr>
			<tr>
			<?php foreach ($envEvaluation['EvaluationCriteria'] as $scores) {				
				echo "<td>".$scores['EnvEvaluationScore']['score'] ."</td>";
			} ?></tr>				
			</table>			
		</td>
		<td><?php echo h($envEvaluation['EnvEvaluation']['score']); ?>&nbsp;</td>
		<!-- <td><?php echo h($envEvaluation['EnvEvaluation']['aspect_details']); ?>&nbsp;</td>
		<td><?php echo h($envEvaluation['EnvEvaluation']['impact_details']); ?>&nbsp;</td> -->
		<td><?php echo h($PublishedEmployeeList[$envEvaluation['EnvEvaluation']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$envEvaluation['EnvEvaluation']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($envEvaluation['EnvEvaluation']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=66>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","score"=>"Score","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","score"=>"Score","aspect_details"=>"Aspect Details","impact_details"=>"Impact Details"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
