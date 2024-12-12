<style type="text/css">
	.modal-dialog{width: 95%}
</style>
<div class="row">
  <div class="col-md-12">
    <div class="btn-group">
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('MIS'), array('controller' => 'projects', 'action' => 'mis',$pid),array()); ?></div>
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('File Tracker'), array('controller' => 'projects', 'action' => 'tracker','project_id'=>$pid),array()); ?></div>
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('Time Sheets'), array('controller' => 'projects', 'action' => 'user_time_sheet','project_id'=>$pid)); ?></div>
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('Production / Quality log'), array('controller' => 'file_processes', 'action' => 'index','project_id'=>$pid)); ?></div>
      <div class="btn btn-sm btn-default"><?php echo $this->Html->link(__('Project team board'), array('controller' => 'projects', 'action' => 'project_team_board')); ?></div>
    </div>
  </div>
</div>

<div class="row">
	<div class="col-md-12">
		<h4>Employeewise Files / Timesheet</h4>
	</div>	
</div>
<div class="">
	<?php echo $this->Form->create('Project',array('action'=>'user_time_sheet'),array('class'=>'form-control'));?>
	<div class="row">
		<div  class="col-md-4 hide"><?php echo $this->Form->input('employee_id',array('options'=>$PublishedEmployeeList, 'class'=>'form-control'));?></div>
		<div  class="col-md-4"><?php echo $this->Form->input('project_id',array('class'=>'form-control'));?></div>
		<div  class="col-md-2 hide"><?php echo $this->Form->input('search_type',array('class'=>'','type'=>'radio', 'options'=>array(0=>'Range',1=>'Day')));?></div>
		<div  class="col-md-2 hide"><?php echo $this->Form->input('result_type',array('class'=>'','type'=>'radio', 'options'=>array(0=>'File',1=>'Date')));?></div>
		<div  class="col-md-4"><?php echo $this->Form->input('date_range',array('class'=>'form-control'));?></div>
		<div  class="col-md-4 hide"><?php echo $this->Form->input('date',array('class'=>'form-control'));?></div>		
		<div  class="col-md-4"><br /><?php echo $this->Form->submit('Submit',array('class'=>'btn btn-sm btn-success'));?></div>
	</div>
	<?php echo $this->Form->end();?>
	<br /><br />
</div>
<div class="row">
	<div class="col-md-12">
	<?php if($projectFiles){ ?>
	    
	      <table class="table table-responsive table-condensed table-bordered">
	        <thead>
	          <tr>
	            <th>#</th>
	            <th>File Name</th>
	            <th>Project</th>
	            <th>Milestone</th>
	            <th>Process</th>
	            <th>Status</th>             
	            <th>Assigned Date</th>
	            <th>Start Time</th>
	            <th>End Time</th>     
	            <th>Hold Time</th>
	            <th>Actual Time</th>
	            <th>Units Completed</th>
	            <th>Overall Matric</th>
	          </tr>
	        </thead>
	        <tbody>
	        	<?php
	        	$i = 1;
	        	 foreach($projectFiles as $projectFile){ 
	        	 	if($projectFile['FileProcess']){ 
	        	 		$total_file_units = 0;
	        	 		?>
						<tr>
			        		<td rowspan="<?php echo count($projectFile['FileProcess']) + 1;?>"><?php echo $i?></td>
				            <td><div class="" onclick='openmodel("project_files","view","<?php echo $projectFile['ProjectFile']['id'];?>",null,null,null);'><?php echo $projectFile['ProjectFile']['name'];?> </div></td>
				            <td><?php echo $projectFile['Project']['title'];?></td>
				            <td><?php echo $projectFile['Milestone']['title'];?></td>
				            <td colspan="9"></td>
				            <!-- <td><?php echo $fileStatuses[$projectFile['ProjectFile']['last_status']];?></td>
				            <td><?php echo $projectFile['ProjectFile']['first_assigned'];?></td>
				            <td><?php echo $projectFile['ProjectFile']['last_start_time'];?></td>
				            <td><?php echo $projectFile['ProjectFile']['last_end_time'];?></td>		             -->		            
				        </tr>
				        <?php foreach($projectFile['FileProcess'] as $fileprocess){ 
				        	$total = $fileprocess['units_completed'] + $total;
				        	$total_file_units = $fileprocess['units_completed'] + $total_file_units;
				        	?>
				        	<tr>
				        		<td colspan="3"></td>
				        		<td><?php echo $processes[$fileprocess['project_process_plan_id']];?></td>
				        		<td><?php echo $fileStatuses[$fileprocess['current_status']];?></td>
				        		<td><?php echo $fileprocess['assigned_date'];?></td>
				        		<td><?php echo $fileprocess['start_time'];?></td>
				        		<td><?php echo $fileprocess['end_time'];?></td>
				        		<td><?php echo substr($fileprocess['hold_time'],0,-3);?></td>
				        		<td><?php echo substr($fileprocess['actual_time_from_process'],0,-3);?></td>
				        		<td><?php echo $fileprocess['units_completed'];?></td>
				        		<td><?php echo round($projectFile['ProjectFile']['overall_metrics'],2);?></td>
				        	</tr>
				        <?php } ?>	
				        <tr style="border-bottom: 2px solid #ccc;">
					        <th colspan="11" class="text-right">Units Completed</th>
			        		<th><?php echo $total_file_units;?></th>
			        		<th></th>
			        	</tr>
			        <?php } ?>			        
	        	<?php $i++; } ?>
	        </tbody>
	        <tfoot>
	        	<tr>
	        		<th colspan="11">Total Units Completed</th>
	        		<th><?php echo $total;?></th>
	        	</tr>
	        </tfoot>
	</table>
	<?php } ?>
</div>
</div>
<?php if($daylyResults){ ?>
<div class="row">
	<div class="col-md-12">
		<table class="table table-responsive table-condensed table-bordered">
	        <thead>
	          <tr>
	            <!-- <th>#</th> -->
	            <!-- <th>Date</th> -->
	            <th>Employee</th>
	            <th>Number</th>
	            <!-- <th>Project</th> -->
	            <!-- <th>Milestone</th> -->
	            <!-- <th>Process</th> -->
	            <!-- <th>Status</th>             
	            <th>Assigned Date</th> -->
	            <!-- <th>Start Time</th> -->
	            <!-- <th>End Time</th>      -->
	            <!-- <th>Hold Start Time</th> -->
	            <!-- <th>Hold End Time</th> -->
	            <!-- <th>Hold Time</th> -->
	            <th>Hours</th>
	            <!-- <th>Units Completed</th> -->
	            <!-- <th>Overall Matric</th> -->
	          </tr>
	        </thead>
	        <?php foreach($daylyResults as $date => $projectFiles){ ?>
	        	<tr>
	        		<th colspan="6"><?php echo $date;?></th>
	        	</tr>
	        	<?php foreach($projectFiles as $projectFile){



	        	$class = '';
	        	if($projectFile['ProjectFile']['name'] == 'Holiday')$class = ' warning';
	        	?>
	        	<tr class="<?php echo $class;?>">
	        		<!-- <td></td> -->
	        		<!-- <td><?php echo $date ;?></td> -->
	        <td><?php echo $projectFile['Employee']['name'];?></td>
	        <td><?php echo $projectFile['FileProcess']['emp_code'];?></td>
					<!-- <td><?php echo $projectFile['Project']['title'];?></td> -->
					<!-- <td><?php echo $projectFile['Milestone']['title'];?></td> -->
					<!-- <td><?php echo $projectFile['ProjectProcessPlan']['process'];?></td> -->
					<!-- <td><?php echo $fileStatuses[$fileprocess['current_status']];?></td>
					<td><?php echo $projectFile['FileProcess']['assigned_date'];?></td> -->
					<!-- <td><?php echo $projectFile['FileProcess']['start_time'];?></td> -->
					<!-- <td><?php echo $projectFile['FileProcess']['end_time'];?></td> -->
					<!-- <td><?php echo $projectFile['FileProcess']['hold_start_time'];?></td> -->
					<!-- <td><?php echo $projectFile['FileProcess']['hold_end_time'];?></td> -->
					<!-- <td><?php echo $projectFile['FileProcess']['hold_diff'];?></td> -->
					<td>
						<?php
						if($projectFile && $projectFile['ProjectFile']['name'] != 'Holiday'){
							if(date('d',strtotime($projectFile['FileProcess']['start_time'])) == date('d',strtotime($date)) || date('d',strtotime($projectFile['FileProcess']['end_time'])) == date('d',strtotime($date))){

								if(date('d',strtotime($projectFile['FileProcess']['start_time'])) == date('d',strtotime($date)) && date('d',strtotime($projectFile['FileProcess']['end_time'])) != date('d',strtotime($date))){
									if($projectFile['Project']['daily_hours'] == 0)echo 8;
									else echo 12;
								}

								if(date('d',strtotime($projectFile['FileProcess']['start_time'])) != date('d',strtotime($date)) && date('d',strtotime($projectFile['FileProcess']['end_time'])) == date('d',strtotime($date))){
									$times2 = date('Y-m-d 09:i:s',strtotime($projectFile['FileProcess']['end_time']));
									$date1 = date_create($projectFile['FileProcess']['end_time']);
									$date2 = date_create($times2);									
									$diff = date_diff($date1,$date2);
									$hour = $diff->h;
									echo $hour;

								}

								if(date('d',strtotime($projectFile['FileProcess']['start_time'])) == date('d',strtotime($date)) && date('d',strtotime($projectFile['FileProcess']['end_time'])) == date('d',strtotime($date))){
									
									$date1 = date_create($projectFile['FileProcess']['start_time']);
									$date2 = date_create($projectFile['FileProcess']['end_time']);									
									$diff = date_diff($date1,$date2);
									$hour = $diff->h;
									echo $hour;

								}

								// echo $projectFile['FileProcess']['hours'];
								// echo $balance;
							}else{
								if($projectFile['Project']['daily_hours'] == 0)echo 8;
								else echo 12;
								$balance = $balance - $projectFile['FileProcess']['hours'];
							}
						}						
					?>
					</td>
					<!-- <td><?php echo $projectFile['FileProcess']['units_completed'];?></td> -->
					<!-- <td></td> -->
	        	</tr>
	        <?php } 
	        }?>
	    </table>
	</div>
</div>	
<?php } ?>
<div id="smodelwin">
	<div class="modal fade" id="producionModal" role="dialog">
	    <div class="modal-dialog">
	        <!-- Modal content-->
	        <div class="modal-content">
	            <div class="modal-header hide">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <h4 class="modal-title">Add/Update Production Details</h4>
	            </div>
	            <div class="modal-body" id="loadhear">

	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
	        </div>
	    </div>
	</div>
</div>
	<script type="text/javascript">
		$().ready(function(){
			$("select").chosen();
			// $("#ProjectDateRange").daterangepicker();
			$("#ProjectDate").datepicker();
		});

		function openmodel(controller,action,id,project_id,milestone_id,project_activity_id,project_overall_plan_id){     
	      $(".modal-body").load("<?php echo Router::url('/', true); ?>"+controller+"/"+action+"/project_id:"+project_id+"/milestone_id:"+milestone_id+"/project_activity_id:"+project_activity_id+"/project_overall_plan_id:"+project_overall_plan_id+"/"+id);
	      $('#producionModal').modal({show:true});

	      $('body').on('click', '.modal-toggle', function (event) {        
	            event.preventDefault();
	            $('.modal-content').empty();
	            $('#producionModal')
	                .removeData('bs.modal')
	                .modal({remote: $(this).attr('href') });
	        });
	   }

	</script>
<script type="text/javascript">
  $("#ProjectProjectId").chosen();
  <?php if($this->request->data){ ?>
    $("#ProjectDateRange").daterangepicker({
    // singleDatePicker: true,
        showDropdowns: true,
        // startDate: moment().add(-1, 'months'),
        // minDate: moment(),
        locale: { 
            // format: 'YYYY-MM-DD'
        }
    });
  <?php }else{ ?>  	
    $("#ProjectDateRange").daterangepicker({
    // singleDatePicker: true,
        showDropdowns: true,
        startDate: moment().add(-1, 'months'),
        // minDate: moment().add(-3, 'months'),
        maxDate: moment(),
        locale: { 
            // format: 'YYYY-MM-DD'
        }
    });
    <?php } ?>
</script>
