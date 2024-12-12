<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="incidentInvestigations ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Investigations','modelClass'=>'IncidentInvestigation','options'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken"),'pluralVar'=>'incidentInvestigations'))); ?>

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
					<th><?php echo $this->Paginator->sort('incident_id'); ?> <small><?php echo h($incidentInvestigation['IncidentInvestigation']['reference_number']); ?></small></th>
					<th><?php echo $this->Paginator->sort('investigation_date_from'); ?></th>
					<th><?php echo $this->Paginator->sort('investigation_date_to'); ?></th>
					<th><?php echo $this->Paginator->sort('title'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		
				</tr>
<?php if($incidentInvestigations){ ?>
<?php foreach ($incidentInvestigations as $incidentInvestigation): ?>
	<tr class="on_page_src">
                    <td class=" actions">	<?php echo $this->element('actions', array('created' => $incidentInvestigation['IncidentInvestigation']['created_by'], 'postVal' => $incidentInvestigation['IncidentInvestigation']['id'], 'softDelete' => $incidentInvestigation['IncidentInvestigation']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($incidentInvestigation['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentInvestigation['Incident']['id'])); ?>
		</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_date_from']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_date_to']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['title']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$incidentInvestigation['IncidentInvestigation']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$incidentInvestigation['IncidentInvestigation']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($incidentInvestigation['IncidentInvestigation']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=99>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken"))); ?>
<?php echo $this->element('export'); ?>
<?php echo $this->element('approvals'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
