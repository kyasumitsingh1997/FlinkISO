<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="incidentAffectedPersonals ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Affected Personals','modelClass'=>'IncidentAffectedPersonal','options'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings"),'pluralVar'=>'incidentAffectedPersonals'))); ?>

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
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('department_id'); ?></th>
				<th><?php echo $this->Paginator->sort('designation_id'); ?></th>
				<th><?php echo $this->Paginator->sort('incident_investigator_id'); ?></th>
				<th><?php echo $this->Paginator->sort('date_of_interview'); ?></th>				
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
				<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				</tr>
				<?php if($incidentAffectedPersonals){ ?>
<?php foreach ($incidentAffectedPersonals as $incidentAffectedPersonal): ?>
	<tr class="on_page_src">
                    <td class=" actions">	
		<?php echo $this->element('actions', array('created' => $incidentAffectedPersonal['IncidentAffectedPersonal']['created_by'], 'postVal' => $incidentAffectedPersonal['IncidentAffectedPersonal']['id'], 'softDelete' => $incidentAffectedPersonal['IncidentAffectedPersonal']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentAffectedPersonal['Incident']['id'])); ?>
		</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentAffectedPersonal['Department']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentAffectedPersonal['Designation']['id'])); ?>
		</td>
		<td>
			<?php 
			if($incidentAffectedPersonal['IncidentInvestigator']['id']){
			echo $this->Html->link($incidentAffectedPersonal['IncidentInvestigator']['name'], array('controller' => 'incident_investigators', 'action' => 'view', $incidentAffectedPersonal['IncidentInvestigator']['id']));}else{
				echo $this->Html->link('Add Interview Details',array('action'=>'edit',$incidentAffectedPersonal['IncidentAffectedPersonal']['id']),array('class'=>'btn btn-xs btn-danger'));
			} ?>
		</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['date_of_interview']); ?>&nbsp;</td>		
		<td><?php echo h($PublishedEmployeeList[$incidentAffectedPersonal['IncidentAffectedPersonal']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$incidentAffectedPersonal['IncidentAffectedPersonal']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($incidentAffectedPersonal['IncidentAffectedPersonal']['publish'] == 1) { ?>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings"))); ?>
<?php echo $this->element('common'); ?>
<?php echo $this->element('approvals'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
