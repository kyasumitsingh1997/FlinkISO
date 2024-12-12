<div id="objectives_ajax">
	<?php echo $this->Session->flash();?>	
	<div class="nav panel panel-default">
		<div class="objectives form col-md-8">
			<h4><?php echo __('View Objective'); ?>		
				<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
				<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
				<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
				<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
			</h4>
			<div id="onlyobjective">
			<table class="table table-responsive">
				<tr><td width="20%"><?php echo __('Title'); ?></td>
				<td>
					<?php echo h($objective['Objective']['title']); ?>
					&nbsp;
				</td></tr>
				<tr><td width="20%"><?php echo __('KPI'); ?></td>
				<td>
					<?php echo h($objective['ListOfKpi']['title']); ?>
					&nbsp;
				</td></tr>
				<tr><td width="20%"><?php echo __('Linked KPI'); ?></td>
				<td>
					<?php 
						$kpis = json_decode($objective['Objective']['list_of_kpi_ids']);
						// print_r($listOfKpis);
						foreach ($kpis as $kpi) {
							// echo $kpi;
							echo $listOfKpis[$kpi] .', ';
						}
						// echo h($objective['Objective']['list_of_kpi_ids']); 
					?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Clauses'); ?></td>
				<td>
					<?php echo h($objective['Objective']['clauses']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Objective'); ?></td>
				<td>
					<?php echo h($objective['Objective']['objective']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Desired Output'); ?></td>
				<td>
					<?php echo h($objective['Objective']['desired_output']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Requirments'); ?></td>
				<td>
					<?php echo h($objective['Objective']['requirments']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Resource Requirments'); ?></td>
				<td>
					<?php echo h($objective['Objective']['resource_requirments']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Evaluation Method'); ?></td>
				<td>
					<?php echo h($objective['Objective']['evaluation_method']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Schedule'); ?></td>
				<td>
					<?php echo h($objective['Schedule']['name']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Assigned Branch'); ?></td>
				<td>
					<?php echo h($objective['Branch']['name']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Assigned Department'); ?></td>
				<td>
					<?php echo h($objective['Department']['name']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Employee'); ?></td>
				<td>
					<?php echo h($objective['Employee']['name']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Target Date'); ?></td>
				<td>
					<?php echo h($objective['Objective']['target_date']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Current Status'); ?></td>
				<td>
					<?php echo h($objective['Objective']['current_status']? 'Close':'Open'); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Prepared By'); ?></td>

			<td><?php echo h($objective['ApprovedBy']['name']); ?>&nbsp;</td></tr>
				<tr><td><?php echo __('Approved By'); ?></td>

			<td><?php echo h($objective['ApprovedBy']['name']); ?>&nbsp;</td></tr>
				<tr><td><?php echo __('Publish'); ?></td>

				<td>
					<?php if($objective['Objective']['publish'] == 1) { ?>
					<span class="fa fa-check"></span>
					<?php } else { ?>
					<span class="fa fa-ban"></span>
					<?php } ?>&nbsp;</td>
		&nbsp;</td></tr>
				<tr><td><?php echo __('Soft Delete'); ?></td>

				<td>
					<?php if($objective['Objective']['soft_delete'] == 1) { ?>
					<span class="fa fa-check"></span>
					<?php } else { ?>
					<span class="fa fa-ban"></span>
					<?php } ?>&nbsp;</td>
		&nbsp;</td></tr>
		</table>
		
		<?php if($objective['ObjectiveMonitoring']){ 
			foreach ($objective['ObjectiveMonitoring'] as $objectiveMonitoring):
				$total = $total + $objectiveMonitoring['completion'];
			endforeach;
			$total =  $total/count($objective['ObjectiveMonitoring']);
				// echo round($total);
		} ?>
		<h3><?php echo __('Monitoring History');?> <span class="badge label-label-info"><?php echo round($completion)?>%</span></h3>
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th><?php echo $this->Paginator->sort('objective_id'); ?></th>
					<th><?php echo __('Assigned To') ?></th>
					<th><?php echo __('KPIs') ?></th>
					<th><?php echo $this->Paginator->sort('process_id'); ?></th>
					<th><?php echo $this->Paginator->sort('completion'); ?></th>
					<th><?php echo $this->Paginator->sort('created'); ?></th>		
					<th><?php echo $this->Paginator->sort('publish'); ?></th>
				</tr>
				<?php if($objective['ObjectiveMonitoring']){ ?>
					<?php foreach ($objective['ObjectiveMonitoring'] as $objectiveMonitoring): ?>
						<tr>
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
							<td><span class="badge  label-primary"><?php echo h($objectiveMonitoring['completion']); ?> % </span>&nbsp;</td>
							<td><?php echo h(date('M-Y',strtotime($objectiveMonitoring['created'])) ); ?>&nbsp;</td>
							<td width="60">
								<?php if($objectiveMonitoring['publish'] == 1) { ?>
								<span class="fa fa-check"></span>
								<?php } else { ?>
								<span class="fa fa-ban"></span>
								<?php } ?>&nbsp;
							</td>
						</tr>
					<?php endforeach; ?>
						<tr>
							<th>Monitoring</th>
							<th><?php echo round($completion)?>%</th>
							<th></th>
							<th>Completions</th>
							<th><?php echo round($total);?>%</th>
							<th></th>
							<th></th>
						</tr>
				<?php }else{ ?>
						<tr><td colspan="8">No results found</td></tr>
				<?php } ?>
			</table>
	</div>
	<?php if($objective['Process']){ 	
	echo "<h3>".__('Processes')."</h3>";
	foreach ($objective['Process'] as $process) { ?>
		
		<table class="table table-responsive">
		<tr><td width="20%"><?php echo __('Title'); ?></td>
		<td>
			<?php echo h($process['title']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Clauses'); ?></td>
		<td>
			<?php echo h($process['clauses']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process Requirments'); ?></td>
		<td>
			<?php echo h($process['process_requirments']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Branches'); ?></td>
		<td>
			<?php if($process['ProcessTeam']['Branches']){				
				foreach ($process['ProcessTeam']['Branches'] as $id => $branches) {
					echo $branches .", ";
				}
			} ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Departments'); ?></td>
		<td>
			<?php if($process['ProcessTeam']['Departments']){				
				foreach ($process['ProcessTeam']['Departments'] as $id => $departments) {
					echo $departments .", ";
				}
			} ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Process Owner'); ?></td>
		<td>
			<?php echo $process['Owner']['name']; ?>
			&nbsp;
		</td></tr>

		<tr><td><?php echo __('Team'); ?></td>
		<td>
			<?php if($process['ProcessTeam']['Users']){				
				foreach ($process['ProcessTeam']['Users'] as $id => $users) {
					echo $users .", ";
				}
			} ?>
			
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Measurement Details'); ?></td>
		<td>
			<?php echo h($process['ProcessTeam']['measurement_details']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Schedule'); ?></td>
		<td>
			<?php echo $this->Html->link($process['Schedule']['name'], array('controller' => 'schedules', 'action' => 'view', $process['Schedule']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Start Date'); ?></td>
		<td>
			<?php echo h($process['ProcessTeam']['start_date']); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('End Date'); ?></td>
		<td>
			<?php echo h($process['ProcessTeam']['end_date']); ?>
			&nbsp;
		</td></tr>

		<tr><td><?php echo __('Input Process'); ?></td>
		<td>
			<?php echo $this->Html->link($process['InputProcess']['title'], array('controller' => 'input_processes', 'action' => 'view', $process['InputProcess']['id'])); ?>
			&nbsp;
		</td></tr>
		<tr><td><?php echo __('Output Process'); ?></td>
		<td>
			<?php echo $this->Html->link($process['OutputProcess']['title'], array('controller' => 'output_processes', 'action' => 'view', $process['OutputProcess']['id'])); ?>
			&nbsp;
		</td></tr>
		
		<!--<tr><td><?php echo __('Prepared By'); ?></td>

	<td><?php echo h($process['ApprovedBy']['name']); ?>&nbsp;</td></tr>
		<tr><td><?php echo __('Approved By'); ?></td>

	<td><?php echo h($process['ApprovedBy']['name']); ?>&nbsp;</td></tr>-->
		<tr><td><?php echo __('Publish'); ?></td>

		<td>
			<?php if($process['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
		<tr><td><?php echo __('Soft Delete'); ?></td>

		<td>
			<?php if($process['soft_delete'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
&nbsp;</td></tr>
</table>

	<?php } ?> 

<?php } ?>


<?php echo $this->element('upload-edit', array('usersId' => $objective['Objective']['created_by'], 'recordId' => $objective['Objective']['id'])); ?>
</div>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#objectives_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$objective['Objective']['id'] ,'ajax'),array('async' => true, 'update' => '#objectives_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
