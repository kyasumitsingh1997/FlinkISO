<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="objectiveMonitorings ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Objective Monitorings','modelClass'=>'ObjectiveMonitoring','options'=>array("sr_no"=>"Sr No","analysis"=>"Analysis","improvements_required"=>"Improvements Required"),'pluralVar'=>'objectiveMonitorings'))); ?>

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
					
				<th><?php echo $this->Paginator->sort('objective_id'); ?></th>
				<th><?php echo __('Assigned To') ?></th>
				<th><?php echo __('KPIs') ?></th>
				<th><?php echo $this->Paginator->sort('process_id'); ?></th>
				<!--<th><?php echo $this->Paginator->sort('analysis'); ?></th>
				<th><?php echo $this->Paginator->sort('improvements_required'); ?></th>-->
				<th><?php echo $this->Paginator->sort('completion'); ?></th>
					<th><?php echo $this->Paginator->sort('created'); ?></th>		
					<!--<th><?php echo $this->Paginator->sort('approved_by'); ?></th>		-->
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($objectiveMonitorings){ ?>
<?php foreach ($objectiveMonitorings as $objectiveMonitoring): ?>
	<tr class="on_page_src">
		<td class=" actions">	
			<?php echo $this->element('actions', array('created' => $objectiveMonitoring['ObjectiveMonitoring']['created_by'], 'postVal' => $objectiveMonitoring['ObjectiveMonitoring']['id'], 'softDelete' => $objectiveMonitoring['ObjectiveMonitoring']['soft_delete'])); ?>	
		</td>		
		<td>
			<?php echo $this->Html->link($objectiveMonitoring['Objective']['title'], array('controller' => 'objectives', 'action' => 'view', $objectiveMonitoring['Objective']['id'])); ?>
		</td>
		<td>
			<?php if($objectiveMonitoring['Objective']['branch_id'])echo h($PublishedBranchList[$objectiveMonitoring['Objective']['branch_id']]) .'/'; ?>
			<?php if($objectiveMonitoring['Objective']['department_id'])echo h($PublishedDepartmentList[$objectiveMonitoring['Objective']['department_id']]) .'/'; ?>
			<?php if($objectiveMonitoring['Objective']['employee_id'])echo h($PublishedEmployeeList[$objectiveMonitoring['Objective']['employee_id']]); ?>
		</td>
		<td>
			<?php
				if($objectiveMonitoring['Objective']['list_of_kpi_id'])echo $listOfKpis[$objectiveMonitoring['Objective']['list_of_kpi_id']].'<br />';
				if($objectiveMonitoring['Objective']['list_of_kpi_ids']){
					$kpis = json_decode($objectiveMonitoring['Objective']['list_of_kpi_ids']);
						foreach ($kpis as $key => $value) {
							echo $listOfKpis[$value].'<br />';
						}
					}
			?>	
		</td>
		<td>
			<?php echo $this->Html->link($objectiveMonitoring['Process']['title'], array('controller' => 'processes', 'action' => 'view', $objectiveMonitoring['Process']['id'])); ?>
		</td>
		<!--<td><?php echo h($objectiveMonitoring['ObjectiveMonitoring']['analysis']); ?>&nbsp;</td>
		<td><?php echo h($objectiveMonitoring['ObjectiveMonitoring']['improvements_required']); ?>&nbsp;</td>-->
		<td><span class="badge  label-primary"><?php echo h($objectiveMonitoring['ObjectiveMonitoring']['completion']); ?> % </span>&nbsp;</td>
		<td><?php echo h(date('M-Y',strtotime($objectiveMonitoring['ObjectiveMonitoring']['created'])) ); ?>&nbsp;</td>
		<!-- <td><?php echo h($PublishedEmployeeList[$objectiveMonitoring['ObjectiveMonitoring']['approved_by']]); ?>&nbsp;</td> -->

		<td width="60">
			<?php if($objectiveMonitoring['ObjectiveMonitoring']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=63>No results found</td></tr>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","analysis"=>"Analysis","improvements_required"=>"Improvements Required"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","analysis"=>"Analysis","improvements_required"=>"Improvements Required"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>

</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
