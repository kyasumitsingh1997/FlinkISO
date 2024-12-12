<div id="task_ajax">
	<div class="row">
		<h3>Task Report</h3>
		<?php echo $this->Session->flash();?>	
		<?php echo $this->Form->create('Task',array('role'=>'form','class'=>'form')); ?>
		<div class="col-md-4"><?php echo $this->Form->input('user_id',array('multiple','name'=>'data[Task][user_id][]'));?></div>
		<div class="col-md-3"><?php echo $this->Form->input('start_date');?></div>
		<div class="col-md-3"><?php echo $this->Form->input('end_date');?></div>
		<div class="col-md-2"><br /><?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
		<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
		<?php echo $this->Form->end(); ?></div>
	</div>
	<?php echo $this->Js->writeBuffer();?>
</div>
<div class="row">
	<div class="col-md-12"  style="overflow:auto">
		<?php if($tasks){ ?> 
			<table class="table table-responsive table-bordered table-condensed">
				<tr>
					<th style="width:200px">Task</th>
					<?php
						$col_span = 0;
						$date     = $start_date;
				        $end_date = $end_date;
							while (strtotime($date) <= strtotime($end_date)) { ?>
								<th>
									<button type="button" class="btn btn-default btn-xs" data-html="true" data-toggle="tooltip" data-placement="bottom" title="Week <?php echo date('W',strtotime($date));?> <br /> <?php echo $date ?> - <?php echo date("Y-m-d", strtotime("+7 days", strtotime($date)));?>"><?php echo date('W',strtotime($date));?></button>
									
								</th>
								<?php $date = date("Y-m-d", strtotime("+7 days", strtotime($date)));
							$col_span++;
							} ?>						
				</tr>
				<?php foreach ($tasks as $task) { ?>
					<tr>
						<td colspan=<?php echo $col_span;?>>
							<?php echo $task['Task']['name'];?> / <?php echo $users[$task['Task']['user_id']];?>
							<div class="progress-group" style="width:250px !important">
	                            <div class="progress xs">
	                                <?php
	                                    if($task['Task']['task_completion'] <= 100 )$class = ' progress-bar-success';
	                                    if($task['Task']['task_completion'] <= 80 )$class = ' progress-bar-aqua';
	                                    if($task['Task']['task_completion'] <= 60 )$class = ' progress-bar-yellow';
	                                    if($task['Task']['task_completion'] <= 40)$class = ' progress-bar-red';
	                                ?>
	                              <div style="width: <?php echo $task['Task']['task_completion'];?>%" class="progress-bar <?php echo $class;?>"></div>
	                            </div>
	                        </div>
						</td>
					</tr>
					<tr>
						<td></td>
						<?php
						$date     = $start_date;
				        $end_date = $end_date;
							while (strtotime($date) <= strtotime($end_date)) { ?>
							<td class='text-danger text-center'>
								<?php 
								// print_r($task['TaskStatus']);
									// if(!$task['TaskStatus']){
									// 	// echo "<span class='fa fa-close'></span>";
									// }else{
										foreach ($task['TaskStatus'] as $taskStatus) {
											if(date('WY',strtotime($taskStatus['task_date'])) == date('WY',strtotime($date)) && $taskStatus['task_performed'] == 1){
												echo "<span class='fa fa-check text-success'></span>";
											}elseif(
													(date('WY',strtotime($taskStatus['task_date'])) == date('WY',strtotime($date)) && $taskStatus['task_performed'] != 1) || 
													(date('WY',strtotime($taskStatus['task_date'])) == date('WY',strtotime($date)) && !$taskStatus)
												){
												echo "<span class='fa fa-close'></span>";
											}else{
												// echo "Asd";
											}
										}	
									// }
									
									// if($status == 0)echo "";
									// else echo "";
								?>
							
								<?php $date = date("Y-m-d", strtotime("+7 days", strtotime($date))); ?>
								</td>
							<?php } ?>						
					</tr>
				<?php } ?>
			</table>
		<?php }else{ ?> 
			No tasks   found.
		<?php } ?>
	</div>
</div>
<script> 
$().ready(function(){
	$('#submit-indicator').hide();
	$('.chosen-select').chosen();
});
$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); 
</script>