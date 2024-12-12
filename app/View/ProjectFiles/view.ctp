<div id="projectFiles_ajax">
	<?php echo $this->Session->flash();?>	
	<div class="nav panel panel-default">
		<div class="projectFiles form col-md-12">
			<h4><?php echo h($projectFile['ProjectFile']['name']); ?></h4>
				<table class="table table-responsive">
					<tr>
						<td><?php echo __('Units'); ?></td>
						<td><?php echo h($projectFile['ProjectFile']['unit']); ?>&nbsp;
							<br /><?php echo h($projectFile['ProjectFile']['id']); ?>
						</td>
					</tr>
					<tr>
						<td><?php echo __('Category'); ?></td>
						<td><?php echo $projectFile['FileCategory']['name']; ?>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo __('City'); ?></td>
						<td><?php echo $projectFile['ProjectFile']['city']; ?>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo __('Block'); ?></td>
						<td><?php echo $projectFile['ProjectFile']['block']; ?>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo __('Completed Date'); ?></td>
						<td>
							<?php
							if($projectFile['FileProcess'][0]['FileProcess']['completed_date']){
								echo $projectFile['FileProcess'][0]['FileProcess']['completed_date'];
							  
							}else{
								if($projectFile['FileProcess'][0]['FileProcess']['end_time']){
									echo h($projectFile['FileProcess'][0]['FileProcess']['end_time'] . '* (last end date)') ;	
								}
								
							}?>&nbsp;
						</td>
					</tr>
					<tr>
						<td><?php echo __('Start Date'); ?></td>
						<td><?php echo h($projectFile['FileProcess'][count($projectFile['FileProcess'])-1]['FileProcess']['assigned_date']); ?>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo __('End Date'); ?></td>
						<td><?php echo h($projectFile['FileProcess'][0]['FileProcess']['end_time']); ?>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo __('Actual Time'); ?></td>
						<td><?php echo h(substr($projectFile['ProjectFile']['actual_time_from_process'],0,-3)); ?>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo __('Hold Time'); ?></td>
						<td><?php echo h(substr($projectFile['ProjectFile']['hold_time_from_process'],0,-3)); ?>&nbsp;</td>
					</tr>
					<tr>
						<td><?php echo __('Current Status'); ?></td>
						<td><?php echo h($currentStatuses[$projectFile['ProjectFile']['current_status']]); ?>&nbsp;</td>
					</tr>
				</table>
				<table class="table table-responsive table-bordered">
					<tr>
						<th>Process/Tasks</th>
						<th>Assigned To</th>
						<!-- <th>Assigned Time</th> -->
						<th>Start Time</th>
						<th>End time</th>
						<th>Estimated Time</th>
						<th>Hold start time</th>
						<th>Hold end time</th>			
						<th>Reason for hold</th>
						<th>Units Completed</th>
						<th>Hold Time</th>
						<th>Actual Time</th>
						<th>Current Status</th>
						<!-- <th>Comments</th>
						<th>Change User Comments</th> -->
					</tr>
					<?php
					if($projectFile['Project']['daily_hours'] == 0)$dhr = 8;
					else $dhr = 12;
					$dhr = 12;
					 foreach ($projectFile['FileProcess'] as $pro) { 			
						if($pro['employee_id'] != 'Not Assigned'){ 

						if($pro['FileProcess']['actual_time'] != 0)$class = ' text-success';
						else $class = '';
							?>
					<tr class="<?php echo $class;?>">
						<td>
							<!-- <?php echo $pro['FileProcess']['sr_no'];?><br /> -->
							<?php echo $projectProcesses[$pro['FileProcess']['project_process_plan_id']]?>&nbsp;<br />
							<!-- <?php echo $pro['FileProcess']['project_process_plan_id'];?> -->
						</td>
						<td><?php echo $PublishedEmployeeList[$pro['FileProcess']['employee_id']]?>&nbsp;</td>
						<!-- <td><?php if($pro['FileProcess']['assigned_date'])echo date('Y-m-d H:i',strtotime($pro['FileProcess']['assigned_date']))?>&nbsp;</td> -->
						<td><?php if($pro['FileProcess']['start_time'])echo date('Y-m-d H:i',strtotime($pro['FileProcess']['start_time']))?>&nbsp;</td>
						<td><?php if($pro['FileProcess']['end_time'])echo date('Y-m-d H:i',strtotime($pro['FileProcess']['end_time']))?>&nbsp;</td>
						<td><?php echo $pro['FileProcess']['estimated_time']?>&nbsp;</td>
						<td><?php if($pro['FileProcess']['hold_start_time'])echo date('Y-m-d H:i',strtotime($pro['FileProcess']['hold_start_time']))?>&nbsp;</td>
						<td><?php if($pro['FileProcess']['hold_end_time'])echo date('Y-m-d H:i',strtotime($pro['FileProcess']['hold_end_time']))?>&nbsp;</td>
						<td><?php echo $holdTypes[$pro['FileProcess']['hold_type_id']]?>&nbsp;</td>
						<td>&nbsp;
							<table class="table table-responsive table-bordered">
							<?php foreach($pro['OtherMeasurableUnitValue'] as $vals){ ?>
								<tr><th><?php echo $vals['OtherMeasurableUnit']['unit_name'];?></th><td><?php echo $vals['OtherMeasurableUnitValue']['value'];?></td></tr>								
							<?php } ?>
							<tr><th>Units Completed</th><td><?php echo $pro['FileProcess']['units_completed']?></td></tr>
							</table>
						</td>
						<td><?php echo substr($pro['FileProcess']['hold_time'], 0,-3)?>&nbsp;</td>
						<td>
							<?php if($pro['FileProcess']['actual_time']) echo substr($pro['FileProcess']['actual_time'], 0,-3)?>&nbsp;<br /><small><?php echo round(substr($pro['FileProcess']['actual_time'], 0,-3)/ $dhr)?> days</small>&nbsp;
						</td>
						<td><?php echo $currentStatuses[$pro['FileProcess']['current_status']]?>&nbsp;</td>
						<!-- <td><?php echo $pro['FileProcess']['comments']?></td>
						<td><?php echo $pro['FileProcess']['change_user_comments']?></td> -->
					</tr>
					<?php $total = $total + $pro['FileProcess']['units_completed'];?>
				<?php }} ?>
				<tr>
					<th colspan="9" class="text-right">Total</th>
					<th colspan="4"><?php echo $total;?></th>
				</tr>
			</table>
			<?php if($mergedFiles){ ?>
				<div id="file-tabs" class="nav-tabs-info">
      				<ul class="nav nav-tabs">
						<?php foreach ($mergedFiles as $key => $value) {
							echo "<li>" . $this->Html->link($value,array('action'=>'view',$key))."</li>";
						} ?>
					</ul>
				</div>
				<script type="text/javascript"> $("#file-tabs").tabs(); </script>
			<?php } ?>
		</div>
		<?php echo $this->Js->writeBuffer();?>
	</div>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
