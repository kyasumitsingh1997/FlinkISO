<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="incidentWitnesses ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Witnesses','modelClass'=>'IncidentWitness','options'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","investigation_interview_taken_by"=>"Investigation Interview Taken By","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings"),'pluralVar'=>'incidentWitnesses'))); ?>

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
					<th><?php echo $this->Paginator->sort('incident_id'); ?></th>
					<th><?php echo $this->Paginator->sort('person_type'); ?></th>					
					<th><?php echo $this->Paginator->sort('name'); ?></th>
					<th><?php echo $this->Paginator->sort('investigation_interview_taken_by'); ?></th>
					<th><?php echo $this->Paginator->sort('date_of_interview'); ?></th>					
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>
				</tr>
<?php if($incidentWitnesses){ ?>
<?php foreach ($incidentWitnesses as $incidentWitness): ?>
	<tr class="on_page_src">
                    <td class=" actions">	<?php echo $this->element('actions', array('created' => $incidentWitness['IncidentWitness']['created_by'], 'postVal' => $incidentWitness['IncidentWitness']['id'], 'softDelete' => $incidentWitness['IncidentWitness']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($incidentWitness['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentWitness['Incident']['id'])); ?>
		</td>
		<td><?php echo ($incidentWitness['IncidentWitness']['person_type'])? "Other" : "Employee"; ?>&nbsp;</td>
		<td><?php echo h($incidentWitness['IncidentWitness']['name']); ?>&nbsp;</td>
		<td>
			<?php 
			if($incidentWitness['IncidentWitness']['investigation_interview_taken_by']){
				echo $this->Html->link($incidentWitness['InvestigationInterviewTakenBies']['name'], array('controller' => 'incident_investigators', 'action' => 'view', $incidentAffectedPersonal['InvestigationInterviewTakenBies']['id']));
			}else{
				echo $this->Html->link('Add Interview Details',array('action'=>'edit',$incidentWitness['IncidentWitness']['id'],$incidentWitness['IncidentWitness']['incident_id']),array('class'=>'btn btn-xs btn-danger'));
			} ?>
		&nbsp;</td>
		<td><?php echo h($incidentWitness['IncidentWitness']['date_of_interview']); ?>&nbsp;</td>		
		<td><?php echo h($PublishedEmployeeList[$incidentWitness['IncidentWitness']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$incidentWitness['IncidentWitness']['approved_by']]); ?>&nbsp;</td>
		<td width="60">
			<?php if($incidentWitness['IncidentWitness']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=90>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","investigation_interview_taken_by"=>"Investigation Interview Taken By","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","investigation_interview_taken_by"=>"Investigation Interview Taken By","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings"))); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->element('approvals'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
