<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="riskAssessments ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Risk Assessments','modelClass'=>'RiskAssessment','options'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes"),'pluralVar'=>'riskAssessments'))); ?>

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
				<th><?php echo $this->Paginator->sort('process_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('task'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('ra_date'); ?></th>
				<th><?php echo $this->Paginator->sort('ra_expert_1'); ?></th>
				<th><?php echo $this->Paginator->sort('ra_expert_2'); ?></th>
				<th><?php echo $this->Paginator->sort('management'); ?></th>
				<th><?php echo $this->Paginator->sort('technical_expert'); ?></th>
				<th><?php echo $this->Paginator->sort('risk_control_exprt'); ?></th>
				<th><?php echo $this->Paginator->sort('reference_number'); ?></th> -->
				<th><?php echo $this->Paginator->sort('hazard_type_id'); ?></th>
				<th><?php echo $this->Paginator->sort('hazard_source_id'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('what_could_happan'); ?></th>
				<th><?php echo $this->Paginator->sort('accident_type_id'); ?></th>
				<th><?php echo $this->Paginator->sort('severiry_type_id'); ?></th>
				<th><?php echo $this->Paginator->sort('existing_controls'); ?></th> -->
				<th><?php echo $this->Paginator->sort('likelihood'); ?></th>
				<th><?php echo $this->Paginator->sort('risk_rating_id'); ?></th>
				<!-- <th><?php echo $this->Paginator->sort('additional_control_needed'); ?></th>
				<th><?php echo $this->Paginator->sort('person_responsible'); ?></th>
				<th><?php echo $this->Paginator->sort('target_date'); ?></th>
				<th><?php echo $this->Paginator->sort('completions_date'); ?></th>
				<th><?php echo $this->Paginator->sort('process_notes'); ?></th>
				<th><?php echo $this->Paginator->sort('task_notes'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($riskAssessments){ ?>
<?php foreach ($riskAssessments as $riskAssessment): ?>
	<tr>
	<td class=" actions">	<?php echo $this->element('actions', array('created' => $riskAssessment['RiskAssessment']['created_by'], 'postVal' => $riskAssessment['RiskAssessment']['id'], 'softDelete' => $riskAssessment['RiskAssessment']['soft_delete'])); ?>	</td>		<td><?php echo h($riskAssessment['RiskAssessment']['title']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['Process']['title'], array('controller' => 'processes', 'action' => 'view', $riskAssessment['Process']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $riskAssessment['Branch']['id'])); ?>
		</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['task']); ?>&nbsp;</td>
		<!-- <td><?php echo h($riskAssessment['RiskAssessment']['ra_date']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['ra_expert_1']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['ra_expert_2']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['management']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['technical_expert']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['risk_control_exprt']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['reference_number']); ?>&nbsp;</td> -->
		<td>
			<?php echo $this->Html->link($riskAssessment['HazardType']['name'], array('controller' => 'hazard_types', 'action' => 'view', $riskAssessment['HazardType']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['HazardSource']['name'], array('controller' => 'hazard_sources', 'action' => 'view', $riskAssessment['HazardSource']['id'])); ?>
		</td>
		<!-- <td><?php echo html_entity_decode($riskAssessment['RiskAssessment']['what_could_happan']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['AccidentType']['name'], array('controller' => 'accident_types', 'action' => 'view', $riskAssessment['AccidentType']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['SeveriryType']['name'], array('controller' => 'severiry_types', 'action' => 'view', $riskAssessment['SeveriryType']['id'])); ?>
		</td>
		<td><?php echo html_entity_decode($riskAssessment['RiskAssessment']['existing_controls']); ?>&nbsp;</td> -->
		<td><?php echo html_entity_decode($riskAssessment['RiskAssessment']['likelihood']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['risk_rating_id']); ?>&nbsp;</td>
		<!-- <td><?php echo html_entity_decode($riskAssessment['RiskAssessment']['additional_control_needed']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['person_responsible']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['target_date']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['completions_date']); ?>&nbsp;</td>
		<td><?php echo html_entity_decode($riskAssessment['RiskAssessment']['process_notes']); ?>&nbsp;</td>
		<td><?php echo html_entity_decode($riskAssessment['RiskAssessment']['task_notes']); ?>&nbsp;</td> -->
		<td><?php echo h($PublishedEmployeeList[$riskAssessment['RiskAssessment']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$riskAssessment['RiskAssessment']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($riskAssessment['RiskAssessment']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=126>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
