<div class="row">
	<div class="col-md-12">
		<h3>Timesheet History</h3>
		<table class="table table-bordered table-responsive">
			<tr>
				<th>Project</th>
				<th>Project Acitivity</th>
				<th>Activity Description</th>
				<th>Start Time</th>
				<th>End Time</th>
				<th>Total Time</th>				
				<th>Estimated Cost</th>
				<th></th>
			</tr>
			<?php foreach ($projectTimesheets as $projectTimesheet) { ?> 
				<tr>
					<td>
						<?php echo $this->Html->link($projectTimesheet['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectTimesheet['Project']['id'])); ?>
					</td>
					<td>
						<?php echo $this->Html->link($projectTimesheet['ProjectActivity']['title'], array('controller' => 'project_activities', 'action' => 'view', $projectTimesheet['ProjectActivity']['id'])); ?>
					</td>
					<td><?php echo h($projectTimesheet['ProjectTimesheet']['activity_description']); ?>&nbsp;</td>
					<td><?php echo h(date('Y-m-d',strtotime($projectTimesheet['ProjectTimesheet']['start_time']))); ?>&nbsp;</td>
					<td><?php echo h(date('Y-m-d',strtotime($projectTimesheet['ProjectTimesheet']['end_time']))); ?>&nbsp;</td>
					
					<td><?php echo h($projectTimesheet['ProjectTimesheet']['total']); ?>&nbsp;</td>
					<td><?php echo h($projectTimesheet['ProjectTimesheet']['total_cost']); ?>&nbsp;</td>
					<td><?php echo $this->Html->link('Edit',array('action'=>'edit',$projectTimesheet['ProjectTimesheet']['id']),array('class'=>'btn btn-sm btn-default','target'=>'_blank'));?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
	<div class="col-md-12">
		<h3>Update Timesheet</h3>
		<?php $i = 0;?>
		<?php echo $this->Form->create('ProjectTimesheet',array('role'=>'form','class'=>'form','default'=>true)); ?>
		<table class="table table-bordered table-responsive">
			<tr>
				<th>Project</th>
				<th>Project Acitivity</th>
				<th>Activity Description</th>
				<th>Start Time</th>
				<th>End Time</th>
				<th>Total Time</th>				
				<th>Estimated Cost</th>
				<!-- <th>Balance Mandays</th> -->
			</tr>
			<?php foreach ($projectResources as $projectResource) { 
				if($projectResource['ProjectActivities']){ ?>
						<tr>
							<td rowspan="<?php echo count($projectResource['ProjectActivities']) + 1?>"><strong><?php echo $projectResource['Project']['title']?></strong></td>
						</tr>
					<?php foreach ($projectResource['ProjectActivities'] as $key => $value) { ?>
						<tr>
							<td><?php echo $value . $this->Form->hidden('ProjectTimesheet.'.$i.'.project_activity_id',array('default'=>$key))?></td>
							<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.activity_description',array('rows'=>1, 'label'=>false))?></td>
							<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.start_time',array('label'=>false))?></td>
							<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.end_time',array('label'=>false))?></td>
							<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.total',array('onchange'=>'calc('.$i.')', 'default'=>0, 'label'=>false))?></td>							
							<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.total_cost',array('default'=>0,'label'=>false))?>
								<?php echo $this->Form->hidden('ProjectTimesheet.'.$i.'.resource_cost',array('default'=>$projectResource['ProjectResource']['resource_cost'],'label'=>false));?>
								<?php echo $this->Form->hidden('ProjectTimesheet.'.$i.'.project_id',array('default'=>$projectResource['ProjectResource']['project_id'],'label'=>false));?>
								<?php echo $this->Form->hidden('ProjectTimesheet.'.$i.'.user_id',array('default'=>$projectResource['ProjectResource']['user_id'],'label'=>false));?>
							</td>
							<!-- <td>
								<?php echo $this->Form->input('ProjectTimesheet.'.$i.'.balance_mandays',array('default'=>0,'label'=>false));?>
								<?php echo $this->Form->input('ProjectTimesheet.'.$i.'.mandays',array('default'=>$projectResource['ProjectResource']['mandays'],'label'=>false));?>
							</td> -->

						</tr>
					<?php $i++;} ?>
				<?php }else{ ?>
					<tr>
						<td><strong><?php echo $projectResource['Project']['title']?></strong></td>
						<td>N/A</td>
						<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.activity_description',array('rows'=>1,'label'=>false))?></td>
						<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.start_time',array('label'=>false))?></td>
						<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.end_time',array('label'=>false))?></td>
						<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.total',array('onchange'=>'calc('.$i.')', 'default'=>0, 'label'=>false))?></td>							
						<td><?php echo $this->Form->input('ProjectTimesheet.'.$i.'.total_cost',array('default'=>0,'label'=>false))?>
							<?php echo $this->Form->hidden('ProjectTimesheet.'.$i.'.resource_cost',array('default'=>$projectResource['ProjectResource']['resource_cost'],'label'=>false));?>			<?php echo $this->Form->hidden('ProjectTimesheet.'.$i.'.project_id',array('default'=>$projectResource['ProjectResource']['project_id'],'label'=>false));?>
							<?php echo $this->Form->hidden('ProjectTimesheet.'.$i.'.user_id',array('default'=>$projectResource['ProjectResource']['user_id'],'label'=>false));?>
						</td>
						<!-- <td>
							<?php echo $this->Form->input('ProjectTimesheet.'.$i.'.balance_mandays',array('default'=>0,'label'=>false));?>
							<?php echo $this->Form->input('ProjectTimesheet.'.$i.'.mandays',array('default'=>$projectResource['ProjectResource']['mandays'],'label'=>false));?></td> -->
					</tr>
				<?php $i++;
				}?>
			
		<?php } ?>
		</table>
		<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#projectTimesheets_ajax','async' => 'false')); ?>
	<?php echo $this->Form->end(); ?>
	<?php echo $this->Js->writeBuffer();?>
	</div>
</div>
<script type="text/javascript">
	function calc(i){
		var cost = 0;
		cost = parseInt($("#ProjectTimesheet"+i+"Total").val()) * parseInt($("#ProjectTimesheet"+i+"ResourceCost").val());
		$("#ProjectTimesheet"+i+"TotalCost").val(cost);
	}

	$().ready(function(){
		$(".chosen-select").chosen();
		$("[name*='time']").datepicker({
	      changeMonth: true,
	      changeYear: true,
	      format: 'yyyy-mm-dd',
	      autoclose:true,
	    }); 
	});
</script>