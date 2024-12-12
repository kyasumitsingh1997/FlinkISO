<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="objectives ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Objectives','modelClass'=>'Objective','options'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","objective"=>"Objective","desired_output"=>"Desired Output","team"=>"Team","requirments"=>"Requirments","system_table"=>"System Table"),'pluralVar'=>'objectives'))); ?>

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
				<th><?php echo $this->Paginator->sort('list_of_kpis','Linked KPI'); ?></th>
				<th><?php echo $this->Paginator->sort('clauses'); ?></th>
				<th><?php echo $this->Paginator->sort('objective'); ?></th>
				<th width="90"><?php echo __('Processes') ?></th>
				<th width="90"><?php echo __('#Monitoring') ?></th>
				<th width="90"><?php echo __('Last Monitored') ?></th>
				<!--<th><?php echo $this->Paginator->sort('desired_output'); ?></th>
				<th><?php echo $this->Paginator->sort('owner_id'); ?></th>
				<th><?php echo $this->Paginator->sort('team'); ?></th>
				<th><?php echo $this->Paginator->sort('requirments'); ?></th>
				<th><?php echo $this->Paginator->sort('system_table'); ?></th>
				<th><?php echo $this->Paginator->sort('input_process_id'); ?></th>
				<th><?php echo $this->Paginator->sort('output_process_id'); ?></th> -->
					<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>		
					<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
					<th><?php echo $this->Paginator->sort('publish'); ?></th>		

				
				</tr>
				<?php if($objectives){ ?>
<?php foreach ($objectives as $objective): ?>
	<tr class="on_page_src">
		<td class=" actions">	
		<?php echo $this->element('actions', array('created' => $objective['Objective']['created_by'], 'postVal' => $objective['Objective']['id'], 'softDelete' => $objective['Objective']['soft_delete'])); ?>	
		</td>		
		<td><?php echo h($objective['Objective']['title']); ?>&nbsp;</td>
		<td><?php echo h($objective['ListOfKpi']['title']); ?>&nbsp;</td>
		<td><?php echo h($objective['Objective']['clauses']); ?>&nbsp;</td>
		<td><?php echo h($objective['Objective']['objective']); ?>&nbsp;</td>
		<td>
			<div class="btn-group">
				<?php echo $this->Html->link('Add',array('controller'=>'processes','action'=>'lists',$objective['Objective']['id']),array('class'=>'btn btn-info btn-xs')) ?>
				<?php if($objective['ProcessCount'] == 0){ ?>
					<span class="btn btn-xs btn-danger"><?php echo $objective['ProcessCount']; ?></span>
				<?php }else{ ?> 
					<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $objective['ProcessCount']; ?>					
    				</button>
					<ul class="dropdown-menu">
						<?php foreach ($objective['Process'] as $processKey => $processName) {
							echo "<li>" . $this->Html->link($processName , array('controller'=>'processes','action'=>'view' ,$processKey)) . "</li>";
						} ?>					    
					  </ul>
				<?php } ?>
				
			</div>
			</td>
			<td>
				<div class="btn-group">
				<?php echo $this->Html->link('Add',array(
						'controller'=>'objective_monitorings',
						'action'=>'lists',
						'objective_id'=>$objective['Objective']['id'],
						'process_id'=>$objective['Objective']['process_id'],
					),
					array('class'=>'btn btn-info btn-xs')) ?>
					<?php if($objective['ObjectiveMonitoring'] == 0){ ?>
						<span class="btn btn-xs btn-danger"><?php echo $objective['ObjectiveMonitoring']; ?></span>
					<?php }else{ ?> 
						<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php echo count($objective['ObjectiveMonitoring']); ?>					
	    				</button>
						<ul class="dropdown-menu">
							<?php foreach ($objective['ObjectiveMonitoring'] as $monitoring) {
								echo "<li>" . $this->Html->link($monitoring['ObjectiveMonitoring']['target_date'] .'/'.($monitoring['ObjectiveMonitoring']['current_status']?'Close':'Open') , array('controller'=>'objective_monitorings','action'=>'view' ,$$monitoring['id'])) . "</li>";
							} ?>					    
						  </ul>
				<?php } ?>
				
			</div>
				
			<td><?php 
// Configure::write('debug',1);
// 			debug($objective['ObjectiveMonitoring']);
			// echo $objective['objectiveMonitoring'][0]['objectiveMonitoring']['created']?></td>
		<!--<td><?php echo h($objective['Objective']['desired_output']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($objective['Owner']['name'], array('controller' => 'users', 'action' => 'view', $objective['Owner']['id'])); ?>
		</td>
		<td><?php echo h($objective['Objective']['team']); ?>&nbsp;</td>
		<td><?php echo h($objective['Objective']['requirments']); ?>&nbsp;</td>
		<td><?php echo h($objective['Objective']['system_table']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($objective['InputProcess']['id'], array('controller' => 'input_processes', 'action' => 'view', $objective['InputProcess']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($objective['OutputProcess']['id'], array('controller' => 'output_processes', 'action' => 'view', $objective['OutputProcess']['id'])); ?>
		</td> -->		
		<td><?php echo h($PublishedEmployeeList[$objective['Objective']['prepared_by']]); ?>&nbsp;</td>
		<td><?php echo h($PublishedEmployeeList[$objective['Objective']['approved_by']]); ?>&nbsp;</td>		
		<td width="60">
			<?php if($objective['Objective']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=8>No results found</td></tr>
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
	
	<div class="row">
		<div class="col-md-12">
			<table class="table">
				<tr>
					<th><h4><?php echo __('Step-1'); ?></h4></th>
					<th><h4><?php echo __('Step-2'); ?></h4></th>
					<th><h4><?php echo __('Step-3'); ?></h4></th>
				</tr>
				<tr>
					<td>
						<strong><?php echo __('Creating Objectives & Process'); ?></strong><br />
						<ul>
							<li>Add Objectives</li>
							<li>Add Processes to Objectives by clicking on add button in the list (
								<div class="btn-group">
									<span class="btn btn-group btn-xs btn-danger disabled">0</span>
										<a class="btn btn-group btn-info btn-xs disabled" href="#">Add</a>
									</div> )
							</li>
							<li>Add Team who would execte the process in the Process form</li>
							<li>While adding processes, you can chose the monitoring schedule.</li>
						</ul>
					</td>
					<td>
						<strong><?php echo __('Adding Tasks'); ?></strong><br />
						<ul>
							
							<li>Based on your schedule, Objective Monitoring tabs on your dashboard will display the objectives & processes.</li>
							<li>Once you create these processes, you will have to create add Tasks and assign those tasks to users.</li>
							<li>Go to process's index page and click add tasks to create the associated tasks.</li>
						</ul>
					</td>
					<td>
						<strong><?php echo __('Monitoring'); ?></strong><br />
						<ul>
							
							<li>Once you create objectives, processes and tasks and assign various tasks to a owner or a team member, you can monitor them simply by clicking on a "Pending Objective Monitoring" tabs on your dashboards.</li>
							<li>Users will be able to see Tasks assigned to them on their dashboards along with the schedule.</li>
							<li>Users are expected to perform these tasks and upload related documents from the dashboard.</li>
							<li>You can monitor the performance based on the tasks completion.</li>
						</ul>
					</td>
					<td></td>					
				</tr>
			</table>		
		</div>
	</div>	
	</div>
<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","objective"=>"Objective","desired_output"=>"Desired Output","team"=>"Team","requirments"=>"Requirments","system_table"=>"System Table"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","clauses"=>"Clauses","objective"=>"Objective","desired_output"=>"Desired Output","team"=>"Team","requirments"=>"Requirments","system_table"=>"System Table"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>


</div>



<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
