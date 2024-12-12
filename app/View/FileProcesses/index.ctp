<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fileProcesses ">
<h2>Daily Tracksheet</h2>
<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>	

<?php 
// if($project_id);

echo "<div class='row'>";
echo $this->Form->create('FileProcess');
echo "<div class='col-md-3'>".$this->Form->input('project_id',array('default'=>$project_id))."</div>";
echo "<div class='col-md-3'>".$this->Form->input('date')."</div>";
echo "<div class='col-md-3'><br />".$this->Form->submit('submit',array('class'=>'btn btn-success btn-sm'))."</div>";
echo $this->Form->end();
echo "</div>";
?>
<hr />
<script>
	$("#FileProcessDate").datepicker();
</script>

		<div class="table-responsive">
		
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th>Project</th>
					<th><?php echo $this->Paginator->sort('project_process_plan_id'); ?></th>
					<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
					<th><?php echo $this->Paginator->sort('project_file_id'); ?></th>
					<th>File Start Date</th>
					<th>File Start Time</th>
					<th>File End Date</th>
					<th>File End Time</th>		
					<th>Hold Time</th>		
					<th>Total Time</th>					
					<th>Matric</th>
					<th>Expected Units</th>
					<th>Units Completed</th>
					<th>Matric Achieved</th>

				</tr>
				<?php if($fileProcesses){ ?>
<?php foreach ($fileProcesses as $fileProcess): ?>
	<tr>
		<td><?php echo h($fileProcess['Project']['title']); ?></td>
		<td><?php echo h($projectProcesses[$fileProcess['ProjectProcessPlan']['id']]); ?></td>
		<td><?php echo h($fileProcess['Employee']['name']); ?></td>
		<td><?php echo $this->Html->link($fileProcess['ProjectFile']['name'],array('controller'=>'project_files','action'=>'view',$fileProcess['ProjectFile']['id']),array('target'=>'_blank')); ?> (<?php echo h($fileProcess['ProjectFile']['unit']); ?>)</td>
		
		<?php 
		// Configure::write('debug',1);
		// debug();
		// debug($fileProcess['FileProcess']['actual_time_from_process']);
		
			$timings  = json_decode($fileProcess['FileProcess']['actual_time_from_process'],true);			
			debug($timings);
		?>
		<td><?php echo h(date('Y-m-d',strtotime($timings['Start']))); ?>&nbsp;</td>
		<td><?php echo h(date('H:i:s',strtotime($timings['Start']))); ?>&nbsp;</td>
		<td><?php echo h(date('Y-m-d',strtotime($timings['End']))); ?>&nbsp;</td>
		<td><?php echo h(date('H:i:s',strtotime($timings['End']))); ?>&nbsp;</td>
		<td><?php echo h($timings['Hold']); ?>&nbsp;</td>		
		<td><?php echo h($timings['Final']); ?>&nbsp;</td>
		
		<!-- <td><?php echo $fileProcess['FileProcess']['matric'];?> <?php echo ($fileProcess['FileProcess']['ctype']?'Hr/Units':'Units/Hr')?> (<?php echo $fileProcess['FileProcess']['ctype'];?>)</td> -->

		<td><?php echo ($fileProcess['FileProcess']['ctype']?'1 Unit in '.$fileProcess['FileProcess']['matric'].'Hr':$fileProcess['FileProcess']['matric'].' Units/Hr')?>
			
		<td>
			<?php 
			if($timings['Diff']){
				if($fileProcess['FileProcess']['ctype'] == 0){
					$time = $timings['Diff'];
					$parsed = date_parse($time);
					$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
					$ma = round($fileProcess['FileProcess']['matric']*($seconds/60/60),2);
				}else{

					$time = $timings['Diff'];
					$parsed = date_parse($time);
					$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
					$ma = round(($seconds/60/60)/$fileProcess['FileProcess']['matric'],2);


				}
			}		

			echo $ma;

			?>
		</td>
		<td><?php echo h($fileProcess['FileProcess']['units_completed']); ?></td>
		<td>
			<?php 
			// if($fileProcess['FileProcess']['ctype'] == 1){
				// echo round($fileProcess['FileProcess']['units_completed'] * 100/ $ma,1);
			// }else{
				// echo round($ma * 100/$fileProcess['FileProcess']['units_completed'],1);
			// } 
			// echo round($ma * $fileProcess['FileProcess']['units_completed'] / ($seconds/60/60),1);
			echo round(($fileProcess['FileProcess']['units_completed'] * 100) / $ma,1);
			?>%

		</td>

	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=66>No results found</td></tr>
<?php } ?>
			</table>

		</div>
			
		</div>
	</div>
	</div>	

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","current_status"=>"Current Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","current_status"=>"Current Status"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
