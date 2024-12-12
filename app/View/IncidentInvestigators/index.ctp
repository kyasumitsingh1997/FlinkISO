<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="incidentInvestigators ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Incident Investigators','modelClass'=>'IncidentInvestigator','options'=>array("sr_no"=>"Sr No","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender"),'pluralVar'=>'incidentInvestigators'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('address'); ?></th>
				<th><?php echo $this->Paginator->sort('phone'); ?></th>
				<th><?php echo $this->Paginator->sort('department_id'); ?></th>
				<th><?php echo $this->Paginator->sort('designation_id'); ?></th>
				<th><?php echo $this->Paginator->sort('age'); ?></th>
				<th><?php echo $this->Paginator->sort('gender'); ?></th>
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($incidentInvestigators){ ?>
<?php foreach ($incidentInvestigators as $incidentInvestigator): ?>
	<tr class="on_page_src">
                    <td class=" actions">	<?php echo $this->element('actions', array('created' => $incidentInvestigator['IncidentInvestigator']['created_by'], 'postVal' => $incidentInvestigator['IncidentInvestigator']['id'], 'softDelete' => $incidentInvestigator['IncidentInvestigator']['soft_delete'])); ?>	</td>		<td>
			<?php echo $this->Html->link($incidentInvestigator['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $incidentInvestigator['Employee']['id'])); ?>
		</td>
		<td><?php echo h($incidentInvestigator['IncidentInvestigator']['name']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigator['IncidentInvestigator']['address']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigator['IncidentInvestigator']['phone']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigator['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentInvestigator['Department']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigator['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentInvestigator['Designation']['id'])); ?>
		</td>
		<td><?php echo h($incidentInvestigator['IncidentInvestigator']['age']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigator['IncidentInvestigator']['gender']); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$incidentInvestigator['IncidentInvestigator']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$incidentInvestigator['IncidentInvestigator']['approved_by']]); ?>&nbsp;</td>

		<td width="60">
			<?php if($incidentInvestigator['IncidentInvestigator']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=75>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender"))); ?>
<?php echo $this->element('export'); ?>
<?php echo $this->element('approvals'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
