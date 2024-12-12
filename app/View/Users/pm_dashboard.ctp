<?php


if(isset($dontshow)){ ?>
	<div class="row">
		<div class="col-md-12">
			<br /><br />
			<div class="info-box" style="background: #dad7d7">
		            <span class="info-box-icon bg-red"><i class="ion-alert-circled"></i></span>
		            	<div class="info-box-content">
		              		<span class="info-box-text"><p class="hide">Project is not not correctly set up, hence system is unable to assign files to you.Contact your manager to resolve the issue.</p>
		              			<p><?php echo $dontshow ;?></p>
		              		</span>		              		
		            	</div>		            
		    </div>
		</div>
	</div>

<?php }else{ ?>

<script type="text/javascript" src="http://igen/js/timeknots-master/src/d3.v2.min.js"></script>
<script type="text/javascript" src="http://igen/js/timeknots-master/src/timeknots-min.js"></script>
<script type="text/javascript" src="http://igen/js/event-countdown-timer-multi/multi-countdown.js"></script>
<script type="text/javascript" src="http://igen/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://igen/js/jquery-form.min.js"></script>

<?php 
// echo $this->Html->script(array(
//     'timeknots-master/src/d3.v2.min',
//     'timeknots-master/src/timeknots-min',
//     'event-countdown-timer-multi/multi-countdown',
//     'jquery.validate.min', 
//     'jquery-form.min'
// ));
// echo $this->fetch('script');
?>

<style type="text/css">
	.info-box-number{font-size: 40px}
	.countdown{font-size: 120%;font-weight: 900;color: red;}
	.fa-2{font-size: 180% !important;float: left;}
	#QueryModal .modal-dialog{width: 80% !important}
	.text-strike, .text-strike label{text-decoration: Line-Through;}
	.titleh1{font-size: 22px; font-weight: 800; padding-top: 6px;}
</style>
<div classs="">
	<div class="row">
		<div class="col-md-12"><?php echo $this->Session->flash();?></div>
		<div class="col-md-12"><h3>Project Management Dashboard</h3><hr ><br /></div>
	</div>        
	<div class="row">        
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box" style="background: #dad7d7">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-gear-outline"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Open Projects</span>
              <span class="info-box-number"><?php echo $this->Html->link($openProjects,array('controller'=>'projects'));?></span>
            </div>            
          </div>          
        </div>
        

        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box" style="background: #dad7d7">
            <span class="info-box-icon bg-yellow"><i class="ion ion-stop"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Closed Projects</span>
              <span class="info-box-number"><?php echo $closedProjects;?></span>
            </div>
           </div>          
        </div>        
        <div class="col-md-4 col-sm-6 col-xs-12">
          <div class="info-box" style="background: #dad7d7">
            <span class="info-box-icon bg-red"><i class="ion-alert-circled"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Delayed</span>
              <span class="info-box-number"><?php echo $delayedProjects; ?></span>
            </div>            
          </div>          
        </div>        
      </div>
</div>

<?php if($holdFile && ($holdFile['ProjectFile']['id'] != $projectFile['ProjectFile']['id'])){ ?>
	<br />
	<div class="alert alert-danger text-center">
			<h4 class="">You have <?php echo $holdFile['ProjectFile']['name'];?> file on hold. Would you like to start work on that file ? </h4>
			<p class="badge-danger">If you start work on  <?php echo $holdFile['ProjectFile']['name'];?>, current file <?php echo $projectFile['ProjectFile']['name'];?> will be put on hold.</p>
			<p><?php echo $this->Html->link('Switch File',array('action'=>'pm_dashboard','project_file_id'=>$holdFile['ProjectFile']['id'],$this->request->params['pass'][0]),array('class'=>'btn btn-warning btn-md'));?></p>
			
	</div>
<?php }  ?>

<?php if($this->request->params['named']['project_file_id']){ ?>
			<div class="alert alert-danger text-center">		
			<p><?php echo $this->Html->link('Goto Previous File',array('action'=>'pm_dashboard',$this->request->params['pass'][0]),array('class'=>'btn btn-warning btn-md'));?></p>
	</div>
<?php } ?>


<?php if($projectFile){ 
$disabledSubmit = false;
?> 	
<?php	echo $this->Form->create('ProjectFile',array('action'=>'edit/'.$projectFile['ProjectFile']['id']),array('role'=>'form','class'=>'form')); ?>
<div class="row">
	<div class="col-md-12">
		<div class="box box-info box-solid">
	    <div class="box-header with-border">
	      <h1 class="panel-title pull-left titleh1">
	      		<?php echo $this->Html->link('File Assigned : '. $projectFile['ProjectFile']['name'],array('controller'=>'project_files','action'=>'view',$projectFile['ProjectFile']['id']),array('class'=>'','target'=>'_blank')) . ' : <small> '. $projectFile['ProjectFile']['unit'] . '(Total units) / <span id="tuc"></span>(Units completed)</small>';?></h1>
	      <div class="pull-right">
	      	<?php if($projectFile) { ?>
			      <div class="btn-group">
			      	<?php if($currentProcesses['FileProcess']['hold_type_id'] && !$currentProcesses['FileProcess']['hold_end_time']){
			      		echo '<div class="btn btn-sm btn-danger">File On hold - reason : ' . $holdTypes[$currentProcesses['FileProcess']['hold_type_id']] .'</div>';
			      	}else if(!$currentProcesses['FileProcess']['hold_type_id'] && $currentProcesses['FileProcess']['hold_start_time'] && !$currentProcesses['FileProcess']['hold_end_time']){ 
			      		echo '<div class="btn btn-sm btn-danger">File On hold - reason : ??' . $holdTypes[$currentProcesses['FileProcess']['hold_type_id']] .'</div>'; 
			      	}?>
					<?php if(!$currentProcesses['FileProcess']['start_time'] && $currentProcesses['FileProcess']['current_status'] != 7){ ?>
						<button class="btn btn-success btn-sm" id="start_button" onClick="upadatestartend('<?php echo $currentProcesses['FileProcess']['id']?>','0');return false;">START</button>
						<?php // } ?>
					<?php } ?>
		    		<?php if(!$currentProcesses['FileProcess']['hold_start_time'] || ($currentProcesses['FileProcess']['hold_start_time'] && $currentProcesses['FileProcess']['hold_end_time'])){?>	
		    			<button class="btn btn-danger btn-sm" id="hold_button" onClick="upadatestartend('<?php echo $currentProcesses['FileProcess']['id']?>','2');return false;">PUT FILE ON HOLD?</button>
		    		<?php } ?>
		    		<?php if($currentProcesses['FileProcess']['hold_type_id'] && !$currentProcesses['FileProcess']['hold_end_time']){ ?>
		    			<button class="btn btn-warning btn-sm" id="release_button" onClick="upadatestartend('<?php echo $currentProcesses['FileProcess']['id']?>','3');return false;">RELEASE HOLD</button>
		    		<?php }else if(!$currentProcesses['FileProcess']['hold_type_id'] && $currentProcesses['FileProcess']['hold_start_time'] && !$currentProcesses['FileProcess']['hold_end_time']){ ?>
		    			<button class="btn btn-warning btn-sm" id="release_button" onClick="upadatestartend('<?php echo $currentProcesses['FileProcess']['id']?>','3');return false;">RELEASE HOLD</button>
		    		<?php } ?>
		    	</div>				
				</div>
	    </div>
			<table class="table table-responsive table-bordered" >
			<?php
			
        	if(in_array($currentProcesses['FileProcess']['project_process_plan_id'], array_keys($projectProcesses))){
        		unset($displayOptions[13]);
        	}else{
        		echo "<tr><th class='alert alert-danger'>This is file is incorrectly assigned. Click start, then add Units completed as 0 and comment as 'Incorrectly assigned' and submit the file with 'Incorrect File Assigned' option.</th></tr>";                    		
        		// Configure::write('debug',1);
        		unset($displayOptions[1]);
        		unset($displayOptions[5]);
        		unset($displayOptions[7]);
        		unset($displayOptions[8]);
        		unset($displayOptions[9]);
        		unset($displayOptions[11]);                    		
        	}                    
				?>
			</table>
			<table class="table table-responsive table-bordered" >
				<thead>
					<tr>
						<th>Category</th>
						<th>Process/Task</th>
						<th>Project</th>
						<th>Milestone</th>
						<th>City</th>
						<th>Block</th>
					</tr>
				</thead>
				<tbody> 
					<tr>
						<?php echo $this->Form->hidden('id',array('default'=>$projectFile['ProjectFile']['id'])); ?>
						<?php echo $this->Form->hidden('file_process_id',array('type'=>'text', 'default'=>$currentProcesses['FileProcess']['id'])); ?>
						<?php echo $this->Form->hidden('project_id',array('default'=>$projectFile['ProjectFile']['project_id'])); ?>
						<?php echo $this->Form->hidden('milestone_id',array('default'=>$projectFile['ProjectFile']['milestone_id'])); ?>
						<?php echo $this->Form->hidden('project_process_plan_id',array('default'=>$currentProcesses['ProjectProcessPlan']['id']))?>
						<?php echo $this->Form->hidden('employee_id',array('default'=>$this->Session->read('User.employee_id')))?>
						<?php echo $this->Form->hidden('publish',array('default'=>1))?>
						<?php echo $this->Form->hidden('publish',array('default'=>1))?>
						<?php echo $this->Form->hidden('curr_stage',array('default'=>$projectFile['ProjectFile']['curr_stage']))?>
						<?php echo $this->Form->hidden('assigned_date',array('default'=>$currentProcesses['FileProcess']['assigned_date']))?>
						<td><?php echo $projectFile['FileCategory']['name'] ?></td>
						<td>
							<div class="pull-left">
								<?php if($currentProcesses['FileProcess']['sent_back'] == 1){ echo $this->Form->hidden('sent_back',array('default'=>1));}
								if($projectFile['ProjectFile']['current_status'] != 8){
              		echo "<strong>" .  $projectFile['ProjectProcessPlan']['process'] . "</strong><br />";
                  	$nextProcess = $this->requestAction(array('controller'=>'project_files','action'=>'get_next_process',
                  		$projectFile['ProjectFile']['project_id'],
                  		$projectFile['ProjectFile']['milestone_id'],
                  		$projectFile['ProjectFile']['id'],
                  		$projectFile['ProjectProcessPlan']['id'],
                  		0
                  	));
								if($nextProcess)echo "<small>Next Process :".$projectProcesses[$nextProcess] ."</small>";
				                    	
              	if($nextProcess){
              		$nextEmployee = $this->requestAction(array('controller'=>'project_files','action'=>'get_employee',
                		$projectFile['ProjectFile']['project_id'],
                		$projectFile['ProjectFile']['milestone_id'],
                		$this->Session->read('User.employee_id'),
                		$projectFile['ProjectFile']['id'],
                		$nextProcess,
                		null
                	));

                	if($nextEmployee[0]){
                		// echo "<br /><small>Next Employee :".$nextEmployee[0]['Employee']['name'] ."</small>";			                    		
                	}
              	}
							}	else{		            
            		if($projectFile['ProjectProcessPlan']['qc'] == 0 && $currentProcesses['FileProcess']['sent_back'] != 1){		                    		
            			echo "<strong>" .  $projectFile['ProjectProcessPlan']['process'] . "</strong><br />";
                	$nextProcess = $this->requestAction(array('controller'=>'project_files','action'=>'get_next_process',
                		$projectFile['ProjectFile']['project_id'],
                		$projectFile['ProjectFile']['milestone_id'],
                		$projectFile['ProjectFile']['id'],
                		$projectFile['ProjectProcessPlan']['id'],
                		1
                	));

                	echo $this->Form->hidden('returned_file',array('default'=>true));
                	
                	if($nextProcess)echo "<small>Next Process :".$projectProcesses[$nextProcess] ."</small>";		                    
                	
                	if($nextProcess){
                		$nextEmployee = $this->requestAction(array('controller'=>'project_files','action'=>'get_employee',
                  		$projectFile['ProjectFile']['project_id'],
                  		$projectFile['ProjectFile']['milestone_id'],
                  		$this->Session->read('User.employee_id'),
                  		$projectFile['ProjectFile']['id'],
                  		$nextProcess,
                  		null
                  	));

                  	if($nextEmployee[0]){
                  		echo "<br /><small>Next Employee :".$nextEmployee[0]['Employee']['name'] ."</small>";			                    		
                  	}
                	}
            		}else{
            			
            			$nextProcess = $this->requestAction(array('controller'=>'project_files','action'=>'get_next_process',
                		$projectFile['ProjectFile']['project_id'],
                		$projectFile['ProjectFile']['milestone_id'],
                		$projectFile['ProjectFile']['id'],
                		$projectFile['ProjectProcessPlan']['id'],
                		0
                	));
                	if($nextProcess)echo "<small>Next Process :".$projectProcesses[$nextProcess] ."</small>";

                	if($projectFile['FileProcess'][count($projectFile['FileProcess'])-1]['project_process_plan_id']){
                		echo $this->Form->hidden('next_qc_process_id',array('type'=>'text','default'=>$nextProcess));				                    		
                	}
                	
                	if($nextProcess){
                		$nextEmployee = $this->requestAction(array('controller'=>'project_files','action'=>'get_employee',
                  		$projectFile['ProjectFile']['project_id'],
                  		$projectFile['ProjectFile']['milestone_id'],
                  		$this->Session->read('User.employee_id'),
                  		$projectFile['ProjectFile']['id'],
                  		$nextProcess,
                  		null
                  	));
                  	if($nextEmployee[0]){
                  		echo "<br /><small>To Employee --:". $nextEmployee[0]['Employee']['name'] ."</small>";
                  	}
                	}
            		}
            	}?>
            	</div>
            </td>
            <td><?php echo $this->Html->link($projects[$projectFile['ProjectFile']['project_id']],array('controller'=>'projects','action'=>'view',$projectFile['ProjectFile']['project_id']),array('target'=>'_blank'))?>
            </td>
            <td><?php echo $milestones[$projectFile['ProjectFile']['milestone_id']]?></td>
						<td><?php echo $projectFile['ProjectFile']['city']?></td>
						<td><?php echo $projectFile['ProjectFile']['block']?></td>
				</tr>				
				<tr>
					<th>Assigned Date/Time</th>
					<th>Estimated Completion Time</th>
					<th>Estimated Time</th>
					<th>Actual Time</th>
					<th>Start Time</th>
					<th>End Time</th>
				</tr>
				<tr>
					<td><?php echo $currentProcesses['FileProcess']['assigned_date']?></td>
					<td>
						<?php echo date('Y-m-d H:i:s',strtotime('+'. $currentProcesses['FileProcess']['estimated_time'] .' hours',strtotime($currentProcesses['FileProcess']['assigned_date'])));?>
						<?php echo $this->Form->hidden('completed_date',array('required', 'label'=>false, ))?>
					</td>
					<td><?php echo $currentProcesses['FileProcess']['estimated_time'] .' Hours';
										echo $this->Form->hidden('estimated_time',array('default'=>$projectFile['ProjectFile']['estimated_time']));?>
					</td>
					<td><?php echo $this->Datediff->getdiff($currentProcesses['FileProcess']['assigned_date'],date('Y-m-d H:i:s'));?><?php echo $projectFile['ProjectFile']['actual_time']?>
					</td>
        	<td><?php echo $currentProcesses['FileProcess']['start_time'];?></td>
					<td><?php echo $currentProcesses['FileProcess']['end_time'];?></td>
				</tr>
				<tr>
					<td colspan="6" id="tmtd">
						<div id="timeline" style="width:100%;height:75px; clear: both; position: relative;top:-2px;"></div>
						<?php 
							$xx = 1;
							foreach($projectFile['FileProcess'] as $pro){
								if($pro['current_status'] == 0){
									$color = "#00a65a"; $name = $projectProcesses[$pro['project_process_plan_id']] . "- " . $PublishedEmployeeList[$pro['employee_id']] . " - Assigned";
								}elseif($pro['current_status'] == 1){
									$color = "#dd4b39"; $name = $projectProcesses[$pro['project_process_plan_id']] . "- " . $PublishedEmployeeList[$pro['employee_id']] . " - Completed";
								}elseif($pro['current_status'] == 2){
									$color = "#f39c12"; $name = $projectProcesses[$pro['project_process_plan_id']] . "- " . $PublishedEmployeeList[$pro['employee_id']] . " - Delayed";
								}elseif($pro['current_status'] == 3){
									$color = "#dd4b39"; $name = $projectProcesses[$pro['project_process_plan_id']] . "- " . $PublishedEmployeeList[$pro['employee_id']] . " - Canceled";
								}elseif($pro['current_status'] == 4){
									$color = "#dd4b39"; $name = $projectProcesses[$pro['project_process_plan_id']] . "- " . $PublishedEmployeeList[$pro['employee_id']] . " - Not Assigned";
								}else{
									$color = "#dd4b39"; $name = $projectProcesses[$pro['project_process_plan_id']] . "- " . $PublishedEmployeeList[$pro['employee_id']];
								}
								$agenda[] = array('name'=>$name,'date'=>$pro['modified'],'color'=>$color);	
								$xx++;}
							?>
							<script type="text/javascript">
								var agenda = '';
								agenda = <?php echo json_encode($agenda)?>;	
									TimeKnots.draw("#timeline", agenda, {
										width:$("#tmtd").width(), 
										height:70, 
										showLabels: true, 
										dateFormat: "%Y/%m/%d", 
										labelFormat:"%Y/%m/%d", 
										radius: 8,
										lineWidth:6
									});
								</script>
					</td>
				</tr>
				<?php if($currentProcesses['ProjectProcessPlan']['qc'] == 1){ ?>
					<tr>
						<td colspan="6">
							<div class="row">
								<div class="col-md-12">
									<div class="box box-warning resizable">
										<div class="box-header with-border"><h4>QC</h4>
											<div class="btn-group box-tools pull-right"><button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
											</div>
										</div>
										<div class="box-body" style="padding: 0px" id="qcqabox">
											<table class="table table-responsive table-bordered" id="holdtypes" width="100%">
												<tr>
													<th>Error/Hold type</th>
													<th>Completed Units</th>
													<th>Total Errors</th>
													<th>Acceptable Errors (%)</th>
													<th>%</th>
													<th>Comment</th>
												</tr>
												<?php $fe = 0;?>
												<?php foreach ($errorMasters as $id => $error) { ?>
													<tr>
														<td><?php echo $error?>
					                			<?php echo $this->Form->hidden('FileError.'.$fe.'.project_id',array('default'=>$projectFile['ProjectFile']['project_id']))?>
					                			<?php echo $this->Form->hidden('FileError.'.$fe.'.milestone_id',array('default'=>$projectFile['ProjectFile']['milestone_id']))?>
					                			<?php echo $this->Form->hidden('FileError.'.$fe.'.file_error_master_id',array('default'=>$id))?>
					                			<?php echo $this->Form->hidden('FileError.'.$fe.'.project_file_id',array('default'=>$projectFile['ProjectFile']['id']))?>
														</td>
														<td><?php echo $this->Form->input('FileError.'.$fe.'.total_units',array('onchange'=>'errcal('.$fe.')', 'label'=>false,'default'=>0, 'readonly'=>'readonly'))?></td>
														<td><?php echo $this->Form->input('FileError.'.$fe.'.total_errors',array('onchange'=>'errcal('.$fe.')','label'=>false,'default'=>0,'class'=>' add_new_class_here'))?></td>
														<td><?php echo $projectFile['Milestone']['acceptable_errors']?>%</td>
														<td><div class="actc" id="act<?php echo $fe;?>">0</div></td>
														<td><?php echo $this->Form->input('FileError.'.$fe.'.comments',array('rows'=>1, 'label'=>false,'default'=>'N/A'))?></td>
													</tr>
					                <script type="text/javascript">
					                	$("#FileError<?php echo $fe;?>TotalUnits").addClass(' unitsum units_completed_1')
					                	$("#FileError<?php echo $fe;?>TotalErrors").addClass(' errsum')
					                </script>
				                	<?php $fe++;} ?>
													<tr>
														<th>Total</th>
														<th class="sumc" id="unittotal">0</th>
														<th class="sumc" id="errtotal">0</th>
														<th class="sumc" id="perc">0</th>
														<th id="percf">0</th>
														<th></th>
													</tr>            
					            		<tr class="hide">
					            			<td><?php echo $this->Form->hidden('ProjectFile.pre_project_process_plan_id',array('options'=>$preProcessPlans, 'default'=>$preProcess))?></td>
					            			<td><?php echo $this->Form->hidden('ProjectFile.pre_employee_id',array('options'=>$PublishedEmployeeList, 'default'=>$preEmployee_id))?></td>
					            		</tr>
					            	</table>
					            	<script type="text/javascript">
										        		function errcal(cnt){
										        												        		
																var x = 0;
																var actcx = 0;
																	$(".errsum").each(function(){
																		x = x + parseFloat(this.value);
																	});	

                                  $("#errtotal").html(x);
                                  var a = $("#FileError"+cnt+"TotalErrors").val() / $("#ProjectFileUnitsCompleted").val();
                                  // console.log(x);
                                  // console.log(a);

                                  // // errtotal

                                  if(a >= 9)perc = 0.4;
                                  else if(a >= 8)perc = 0.5;
                                  else if(a >= 7)perc = 0.6;
                                  else if(a >= 6)perc = 0.7;
                                  else if(a >= 5)perc = 0.8;
                                  else if(a >= 4)perc = 0.85;
                                  else if(a >= 3)perc = 0.9;
                                  else if(a >= 2)perc = 0.95;
                                  else if(a >= 1)perc = 0.99;
                                  else perc = 0;

                                  $("#act"+cnt).html(parseFloat(perc).toFixed(2));
                                  // $("#perc").html(parseFloat(perc).toFixed(2));

                                  var perc2 = 0;

                                  
                                  var t = $("#errtotal").html() / $("#unittotal").html();
                                  if(t >= 9)perc = 0.4;
                                  else if(t >= 8)perc2 = 0.5;
                                  else if(t >= 7)perc2 = 0.6;
                                  else if(t >= 6)perc2 = 0.7;
                                  else if(t >= 5)perc2 = 0.8;
                                  else if(t >= 4)perc2 = 0.85;
                                  else if(t >= 3)perc2 = 0.9;
                                  else if(t >= 2)perc2 = 0.95;
                                  else if(t >= 1)perc2 = 0.99;
                                  else perc2 = 0;
                                  

                 //                  $(".actc").each(function(){
                                  	// console.log(perc);
																	// 	actcx = actcx + parseFloat(this.innerHTML);
																	// });	
                                  $("#percf").html(perc2 * 100);

										        		}
													</script>
												</div>
											</div>
										</div>
									</div>
								</td>
							</tr>
						<?php }?>
						<?php if($currentProcesses['ProjectProcessPlan']['qc'] == 2){ ?>
		                	<script type="text/javascript">
		                		$().ready(function(){
		                			calcunitsc();
		                		});
		                		
		                		function calcunitsc(){
		                			var i = 0;
		                			$(".calcunitsc").each(function(){
		                				if($("#"+this.id).prop("checked") == true){
		                					i = i + parseFloat($("#calcunitsc-"+this.value).html());		                					
		                				}
		                			});	
													$("#ProjectFileUnitsCompleted").val(i);
		                		}
		                	</script>
								<tr><td colspan="6"><h3>Merge Files</h3></td></tr>
								<tr>
									<td colspan="6">
										<?php if($toMerge){ ?>
											<table class="table table-responsive table-condensed table-bordered draggable">
				                <tr>
				                  <th>File Name</th>
				                  <th>Category</th>
				                  <th>City</th>
				                  <th>Block</th>
				                  <th>Prioriy</th>
				                  <!-- <th>Last Process</th> -->
				                  <th>Current Process</th>
				                  <th>Unit</th>
				                  <th>Units Completed</th>
				                  <th>Assigned to</th>
				                  <th>Assigned Date/Time</th>
				                  <th>Status</th>
				                  <th>Completed Date/Time</th>
				                  <th>Estimated Time</th>
				                  <th>Actual Time</th>
				                  <th>Comments</th>
				                </tr>
												<?php
						                $newM = 0; 
						                $open = $closed = $delayed = $at = $eat =  0;
						                foreach ($toMerge as $file) { 
						                	if($file['ProjectFile']['current_status'] == 12 || ($file['ProjectFile']['current_status'] == 0 && $file['ProjectFile']['employee_id'] == $this->Session->read('User.employee_id')))$textclass = 'text-success';
						                	else $textclass = 'text-strike disabled';
						                ?>
						                  <tr class="<?php echo $textclass?>">
						                    <td>
						                      <?php 
						                      if($file['ProjectFile']['current_status'] == 12 || ($file['ProjectFile']['current_status'] == 0 && $file['ProjectFile']['employee_id'] == $this->Session->read('User.employee_id'))){
						                      	echo $this->Form->input('ProjectFile.file_ids',array('value'=>$file['ProjectFile']['id'], 'name'=>'data[ProjectFile][file_ids][]','id'=>'ProjectFileFileIds'.$file['ProjectFile']['id'], 'type'=>'checkbox','class'=>'calcunitsc',  'onchange'=>'calcunitsc();' ,'label'=>'<strong>'. $file['ProjectFile']['name'] . '</strong>'));	
						                      }else{
						                      	echo $this->Form->input('ProjectFile.file_ids',array('value'=>$file['ProjectFile']['id'], 'name'=>'data[ProjectFile][file_ids][]','id'=>'ProjectFileFileIds'.$file['ProjectFile']['id'], 'type'=>'checkbox', 'disabled', 'label'=> $file['ProjectFile']['name']));
						                      }?>

						                    </td>
						                    <td><?php echo $projectFile['FileCategory']['name'] ?></td>
						                    <td><?php echo $file['ProjectFile']['city']?></td>
						                    <td><?php echo $file['ProjectFile']['block']?></td>
						                    <td id="<?php echo $file['ProjectFile']['id']?>_prioroty"><?php echo $file['ProjectFile']['priority'] ?></td>
						                    <!-- <td><?php echo $projectProcesses[$file['ProjectFile']['last_process']] ?></td> -->
						                    <td><?php echo $projectProcesses[$file['ProjectFile']['project_process_plan_id']] ?></td>
						                    <td><?php echo $file['ProjectFile']['unit'];
						                    if($file['ProjectFile']['current_status'] == 12)$mergedUnits = $mergedUnits + $file['ProjectFile']['ucompleted'];
						                    ?></td>
						                    <td><div id="calcunitsc-<?php echo $file['ProjectFile']['id']?>"><?php echo $file['ProjectFile']['ucompleted'];?></div></td>
						                    <td>
						                        <?php 

						                      echo $this->Html->link($file['Employee']['name'],"javascript:void(0);",
						                      array(
						                        'class'=>'',
						                        'onclick'=>'openmodel(
						                          "employees",
						                          "view",
						                          "'.$file['Employee']['id'].'",
						                          null,
						                          null,
						                          null
						                        )'
						                      )); 
						                      ?>

						                    </td>
						                    <td><?php echo $file['ProjectFile']['assigned_date']?></td>
						                    <td><?php echo $currentStatuses[$file['ProjectFile']['current_status']]?>  <?php // echo $file['ProjectFile']['current_status']?></td>
						                    <td><?php echo $file['ProjectFile']['completed_date']?></td>
						                    <td><?php echo $file['ProjectFile']['estimated_time']?></td>
						                    <td><?php echo $file['ProjectFile']['actual_time']?></td>
						                    <td><?php echo $file['ProjectFile']['comments']?></td>
						                  </tr>
						                  <script type="text/javascript">
						                    $("#NewMember<?php echo $newM?>").chosen('destroy');
						                    $("#NewMember<?php echo $newM?>").chosen({width: "100%"});
						                  </script>
						                <?php 
						                if($file['ProjectFile']['current_status'] == 0)$open++;
						                if($file['ProjectFile']['current_status'] == 1)$closed++;
						                if($file['ProjectFile']['current_status'] == 3)$delayed++;
						                $at = $at + $file['ProjectFile']['actual_time'];
						                $eat = $eat + $file['ProjectFile']['estimated_time'];
						                $newM++; 
						              } ?>
				                <tr class="warning">
				                  <th colspan="2">Total Files : <?php echo count($milestone['ProjectFile'])?></th>
				                  <th colspan="3">Open : <?php echo $open;?></th>
				                  <th colspan="3">Closed : <?php echo $closed;?></th>
				                  <th colspan="2">Delayed : <?php echo $delayed;?></th>
				                  <th colspan="2">Estimated Time : <?php echo $eat?></th>
				                  <th colspan="3">Total Time : <?php echo $at?></th>
				                </tr>
												<tr><td colspan="14"><?php echo $this->Form->input('ProjectFile.new_filename',array('required'=>'required'));?></td></tr>
											</table>
										<?php } ?> 
									</td>
								</tr>
							<?php }?>							
						<tr>
							<td><?php 
              	if(isset($mergedUnits))$unitsCompleted = $mergedUnits;
              	else $unitsCompleted = $currentProcesses['FileProcess']['units_completed'];
              	echo $this->Form->input('units_completed',array('default'=>$unitsCompleted,'type'=>'float','required'=>'required'))?>
							</td>
							<td><?php echo $this->Form->input('comments',array('required','rows'=>1, ))?></td>
							<td colspan="2">
            		<?php echo $this->Form->hidden('merge_close',array('default'=>0));		                		
            		$adate = date('Y/m/d H:i:s',strtotime('+'.$currentProcesses['FileProcess']['estimated_time'].' hours',strtotime($currentProcesses['FileProcess']['assigned_date'])));

            		if($adate > date('Y-m-d H:i:s')){		                			
            			$projectFile['ProjectFile']['current_status'] = 2;
            		}else{
            			
            		}

            		if($nextProcess){
            			$newCurrStatus = array(0,2,3,4,8);
            			unset($displayOptions[5]);

            		}else{
            			$newCurrStatus = array(0,2,3,4,8);
            			unset($displayOptions[1]);
            			unset($displayOptions[9]);
            		}
            		if($currentProcesses['ProjectProcessPlan']['qc'] != 2){
            			unset($displayOptions[11]);		
            			$def = $projectFile['ProjectFile']['current_status'];
            		}else{
            			$def = 11;
            		}

            		if($currentProcesses['ProjectProcessPlan']['qc'] == 0){
            			unset($displayOptions[8]);
            			unset($displayOptions[9]);
            		}

            		if($currentProcesses['ProjectProcessPlan']['qc'] == 2 &&  $nextProcess == null){
            			$displayOptions[11] = 'Merge & Close';
            			unset($displayOptions[5]);
            			echo $this->Form->hidden('merge_close',array('default'=>1));
            		}
            		
            		if(in_array($projectFile['ProjectFile']['current_status'], $newCurrStatus)){
            			// if($projectFile['ProjectFile']['current_status'])
            			if($currentProcesses['FileProcess']['hold_type_id'] && !$currentProcesses['FileProcess']['hold_end_time']){	
            				echo $this->Form->input('current_status',array('required'=>'required', 'legend'=>'Update Status', 'type'=>'radio', 'default'=>7, 'options'=>$displayOptions,'disabled'=>'disabled'));
            				$disabledSubmit = true;
            			}else{
            				echo $this->Form->input('current_status',array('required'=>'required', 'legend'=>'Update Status', 'type'=>'radio', 'default'=>$def, 'options'=>$displayOptions));
            			}
            		}else{		                			
            			echo $this->Form->hidden('current_status',array());
            		} 
							?>
						<div id="checklistm">
							<div class="modal fade" id="ChecklistModal" role="dialog">
							    <div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
													<h4 class="modal-title">Update Checklist</h4>
												</div>
											<div class="modal-body" id="loadherec">	 
												<?php echo $this->Form->input('ProjectChecklist.name',array('options'=>$projectChecklists,'multiple'=>'checkbox'));?>
											</div>
											<div class="modal-footer">
												<?php 
							            if($disabledSubmit == true){
							            	echo "Can not submit while file is on hold!";
							            }else if(!$currentProcesses['FileProcess']['start_time']){
							    					echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-success btn-lg',  'id'=>'checklistClose1')); 	
							    				}else{
							    					echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-success btn-lg', 'id'=>'checklistClose2')); 	
							    				}
						    				?>								                
											</div>
										</div>
									</div>
								</div>
							</div>
						</td>
						<td class="text-center"><br />
							<?php if(!$currentProcesses['FileProcess']['start_time']){ ?>
								<button type="button" class="btn btn-info hide" id="file_submit_id">Checklist</button>
							<?php } else { ?> 
								<button type="button" class="btn btn-info"  id="file_submit_id">Checklist</button>
							<?php } ?>
							<?php 
	        				if(!$currentProcesses['FileProcess']['start_time']){
	        					
	        				}else{
	        					
	        				}
							?>
							<?php echo $this->Form->end(); ?>
						</td>
					</tr>
					<tr>
						<?php if($otherUnits){
							$o = 0;
							foreach($otherUnits as $otherUnitId => $otherUnit){
								
								echo "<td>". $this->Form->hidden('OtherMeasurableUnitValue.'.$o.'.id',array('label'=>$otherUnitId, 'default'=>$otherUnitId, 'required'=>'required'));
								echo $this->Form->input('OtherMeasurableUnitValue.'.$o.'.value',array('label'=>$otherUnit,'required'=>'required'))."</td>";
								$o++;
							}
						}?>
						
					</tr>
				
					<tr>
					<td colspan="6">
						<?php if($fileErrors){ ?>
							<div class="table-responsive">
								<div class="row">
									<div class="col-md-12">		
										<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
											<thead>
												<h4 class="text-danger">QC Errors</h4>
											</thead>
											<tr>											
												<th><?php echo __('Error'); ?></th>
												<th><?php echo __('Total Units'); ?></th>
												<th><?php echo __('Total Errors'); ?></th>
												<th><?php echo __('Prepared By'); ?></th>													
											</tr>
											<?php if($fileErrors){ ?>
												<?php foreach ($fileErrors as $fileError): ?>
													<tr>
														<td>
															<?php echo $this->Html->link($fileError['FileErrorMaster']['name'], array('controller' => 'file_error_masters', 'action' => 'view', $fileError['FileErrorMaster']['id'])); ?>
														</td>
														<td><?php echo h($fileError['FileError']['total_units']); ?>&nbsp;</td>
														<td><?php echo h($fileError['FileError']['total_errors']); ?>&nbsp;</td>
														<td><?php echo h($PublishedEmployeeList[$fileError['FileError']['prepared_by']]); ?>&nbsp;</td>													
													</tr>
												<?php endforeach; ?>
												<?php }else{ ?>
													<tr><td colspan="4">No results found</td></tr>
												<?php } ?>
												</table>											
											<?php } ?>
										</div>
									</div>	
			                	</td>
			                </tr>		                
											<tr>
			                	<td colspan="6">
			                	</td>
			                </tr>
			            </tbody>
			        </table>
						<?php } ?>
						<table class="table table-bordered table-responsive">
							<tr>
								<td>
									<div class="row">
					        	<div class="col-md-12">
					        		<div class="box box-danger collapsed-box resizable">
							            <div class="box-header with-border">
							            	<h4>Add/View Queries</h4>
							                <div class="btn-group box-tools pull-right">
							                    <button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
							                    <span class="btn btn-xs btn-danger"><?php echo count($projectQueries)?></span>
							                </div>
							            </div>
							            <div class="box-body" style="padding: 0px">
							            	<div class="row">
							            		<div class="col-md-12">
							            			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
														<tr>
															<th><?php echo __('Name'); ?></th>																	
															<th><?php echo __('Type'); ?></th>
															<th><?php echo __('From'); ?></th>
															<th><?php echo __('To'); ?></th>
															<th><?php echo __('Details'); ?></th>
															<th><?php echo __('Status'); ?></th>	
															<th>Action</th>
															
														</tr>
														<?php if($projectQueries){ ?>
															<?php foreach ($projectQueries as $projectQuery): ?>
																<tr>
																	<td><?php echo h($projectQuery['ProjectQuery']['name']); ?>&nbsp;</td>																			
																	<td>
																		<?php echo $this->Html->link($projectQuery['QueryType']['name'], array('controller' => 'query_types', 'action' => 'view', $projectQuery['QueryType']['id'])); ?>
																	</td>
																	<td>
																		<?php echo $this->Html->link($projectQuery['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $projectQuery['Employee']['id'])); ?>
																	</td>
																	<td><?php echo h($projectQuery['SentTo']['name']); ?>&nbsp;</td>
																	<td><?php echo h($projectQuery['ProjectQuery']['query']); ?>&nbsp;</td>
																	<td><?php echo h($projectQuery['ProjectQuery']['current_status']); ?>&nbsp;</td>
																	<td>
																		<div class="btn-group">
																			<div class="btn btn-warning btn-xs" id="q_add_<?php echo $projectQuery['ProjectQuery']['id']?>">Reply</div>
																			<?php echo $this->Html->link('View',
																				'#'
																				// array('controller'=>'project_queries','action'=>'view',$projectQuery['ProjectQuery']['id'])
																				,array('class'=>'btn btn-default btn-xs',
																				'id'=>'q_view_'.$projectQuery['ProjectQuery']['id']
																		));?>
																		</div>
																		<script type="text/javascript">
																			$("#q_view_<?php echo $projectQuery['ProjectQuery']['id']?>").on('click',function(){
																				$("#QueryModal").modal({show:true});
																				$("#q_query").load('<?php echo Router::url('/', true); ?>project_queries/view/<?php echo $projectQuery['ProjectQuery']['id'] ?>/1');
																			});

																			$("#q_add_<?php echo $projectQuery['ProjectQuery']['id']?>").on('click',function(){
																				$("#QueryModal").modal({show:true});
																				$("#q_query").load('<?php echo Router::url('/', true); ?>project_query_responses/add_ajax/<?php echo $projectQuery['ProjectQuery']['id'] ?>/1');
																			})

																		</script>
																	</td>																			
																</tr>
														<?php endforeach; ?>
														<?php }else{ ?>
															<tr><td colspan="6">No results found</td></tr>
														<?php } ?>
														</table>
							            		</div>
							            	</div>

							            	<div class="row" id="projectQueries_ajax">
										        <?php 
									            	echo $this->Form->create('ProjectQuery',array('type'=>'file', 'id'=>'ProjectQueryForm', 'role'=>'form','class'=>'form','default'=>false));
									            	echo "<div class='col-md-12'>".$this->Form->input('name',array('label'=>'Query Title')) . '</div>'; 
									            	echo "<div class='col-md-12'>".$this->Form->input('query',array('label'=>'Details')) . '</div>'; 
													echo "<div class='col-md-4'>".$this->Form->input('query_type_id',array()) . '</div>'; 
													echo "<div class='col-md-4'>".$this->Form->input('sent_to',array('options'=>$PublishedEmployeeList)) . '</div>'; 
													echo "<div class='col-md-4'>".$this->Form->input('current_status',array('options'=>$projectQueryStatuses)) . '</div>'; 
													
													echo "<div class='col-md-6'>".$this->Form->hidden('project_id',array('default'=>$projectFile['ProjectFile']['project_id'])) . '</div>'; 
													echo "<div class='col-md-6'>".$this->Form->hidden('milestone_id',array('default'=>$projectFile['ProjectFile']['milestone_id'])) . '</div>'; 
													echo "<div class='col-md-6'>".$this->Form->hidden('project_file_id',array('default'=>$projectFile['ProjectFile']['id'])) . '</div>'; 
													echo "<div class='col-md-6'>".$this->Form->hidden('project_process_plan_id',array('default'=>$projectFile['ProjectFile']['project_process_plan_id'])) . '</div>'; 
													echo "<div class='col-md-6'>".$this->Form->hidden('employee_id',array('default'=>$this->Session->Read('User.employee_id'))) . '</div>'; 
													
													echo "<div class='col-md-12 pull-left'><p><br /><br />" . $this->Form->file('Files.error_file_1') ,"</p></div>";
													echo "<div class='col-md-12 pull-left'><p>" . $this->Form->file('Files.error_file_2') ,"</p></div>";
													echo "<div class='col-md-12 pull-left'><p>" . $this->Form->file('Files.error_file_3') ,"</p></div>";?>
													<br /><br />
									            	<?php echo "<div class='col-md-12'>".$this->Form->submit('Add Query',array('div'=>false,'class'=>'btn btn-primary btn-danger pull-right','update'=>'#projectQueries_ajax','async' => 'false' ,'id'=>'q_submit_id' )); ?></div>
													<?php echo $this->Form->end(); ?>
													<?php echo $this->Js->writeBuffer();?>
												</div>
								           
								        </div>

								    </div>
							</div>
						</div>
						<script>
						    $.validator.setDefaults({
						    	ignore: null,
						    	errorPlacement: function(error, element) {
						            if (
						                
										$(element).attr('name') == 'data[ProjectQuery][query_type_id]' ||
										$(element).attr('name') == 'data[ProjectQuery][project_id]' ||
										$(element).attr('name') == 'data[ProjectQuery][milestone_id]' ||
										$(element).attr('name') == 'data[ProjectQuery][project_file_id]' ||
										$(element).attr('name') == 'data[ProjectQuery][project_process_plan_id]' ||
										$(element).attr('name') == 'data[ProjectQuery][sent_to]' ||
										$(element).attr('name') == 'data[ProjectQuery][employee_id]')
												{	
						                $(element).next().after(error);
						            } else {
						                $(element).after(error);
						            }
						        },
						        submitHandler: function(form) {
						            $("#ProjectQueryForm").ajaxSubmit({
						                url: "<?php echo Router::url('/', true); ?>project_queries/add_ajax",
						                type: 'POST',
						                target: '#projectQueries_ajax',
						                beforeSend: function(){
						                   $("#q_submit_id").prop("disabled",true);								                    
						                },
						                complete: function() {
						                   $("#q_submit_id").removeAttr("disabled");
						                   $("#projectQueries_ajax").html('<div class="col-md-12 success"><p>Query is saved.</p></div>');
						                },
						                error: function(request, status, error) {                    
						                    alert('Action failed!');
						                }
							    			});
						        }
						    });
								$().ready(function() {	
												$(".errsum").attr('readonly',true);
								        jQuery.validator.addMethod("greaterThanZero", function(value, element) {
								            return this.optional(element) || (parseFloat(value) > 0);
								        }, "Please select the value");
								        
								        $('#ProjectQueryForm').validate({
								            rules: {
																	"data[ProjectQuery][query_type_id]": {
								                    	greaterThanZero: true,
																	},
																	"data[ProjectQuery][project_id]": {
								                    	greaterThanZero: true,
																	},
																	"data[ProjectQuery][milestone_id]": {
								                    	greaterThanZero: true,
																	},
																	"data[ProjectQuery][project_file_id]": {
								                    	greaterThanZero: true,
																	},
																	"data[ProjectQuery][project_process_plan_id]": {
								                    	greaterThanZero: true,
																	},
																	"data[ProjectQuery][employee_id]": {
								                    	greaterThanZero: true,
																	},
																	"data[ProjectQuery][sent_to]": {
								                    	greaterThanZero: true,
																	},
								                
								            }
								        }); 

										$('#ProjectQueryQueryTypeId').change(function() {
											if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
												$(this).next().next('label').remove();
											}
										});
										$('#ProjectQueryProjectId').change(function() {
											if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
												$(this).next().next('label').remove();
											}
										});
										$('#ProjectQueryMilestoneId').change(function() {
											if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
												$(this).next().next('label').remove();
											}
										});
										$('#ProjectQueryProjectFileId').change(function() {
											if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
												$(this).next().next('label').remove();
											}
										});
										$('#ProjectQueryProjectProcessPlanId').change(function() {
											if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
												$(this).next().next('label').remove();
											}
										});
										$('#ProjectQueryEmployeeId').change(function() {
											if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
												$(this).next().next('label').remove();
											}
										}); 

										$('#ProjectQuerySentTo').change(function() {
											if ($(this).val() != -1 && $(this).next().next('label').hasClass("error")) {
												$(this).next().next('label').remove();
											}
										});       
						    });
						</script>
						</td>
					</tr>
				</table>
			<?php if($projectFile['FileProcess']){ ?> 
				<table class="table table-responsive table-bordered">
					<tr>
						<th>Process/Tasks</th>
						<th>Assigned To</th>
						<th>Assigned Time</th>
						<th>Start Time</th>
						<th>End time</th>
						<th>Estimated Time</th>
						<th>Hold start time</th>
						<th>Hold end time</th>			
						<th>Hold Time</th>
						<th>Reason for hold</th>						
						<th>Units Completed</th>
						<th>Actual Time</th>
						<th>Current Status</th>
						<th>Comments</th>
					</tr>
					<?php foreach ($projectFile['FileProcess'] as $pro) { 
						if($pro['employee_id'] != 'Not Assigned'){
					
							if($pro['current_status'] != 0 || $pro['pre_project_process_plan_id']){
								$class = '';
							}else{
								$class = 'success';
							}
							if($projectFile['ProjectFile']['employee_id'] == $pro['employee_id']){
								$tclass = 'text-bold';
							}else{
								$tclass = '';
							}

							$units_completed_so_far = $pro['units_completed'] + $units_completed_so_far;
					?>

						<tr class="<?php echo $class?> <?php echo $tclass?>">
							<td><?php echo $projectProcesses[$pro['project_process_plan_id']]?>&nbsp;</td>
							<td><?php echo $PublishedEmployeeList[$pro['employee_id']]?>&nbsp;</td>
							<td><?php if($pro['assigned_date'])echo date('y-m-d H:i',strtotime($pro['assigned_date']))?>&nbsp;</td>
							<td><?php if($pro['start_time'])echo date('y-m-d H:i',strtotime($pro['start_time']))?>&nbsp;</td>
							<td><?php if($pro['end_time'])echo date('y-m-d H:i',strtotime($pro['end_time']))?>&nbsp;</td>
							<td><?php echo $pro['estimated_time']?>&nbsp;</td>
							<td><?php if($pro['hold_start_time'])echo date('y-m-d H:i',strtotime($pro['hold_start_time']))?>&nbsp;</td>
							<td><?php if($pro['hold_end_time'])echo date('y-m-d H:i',strtotime($pro['hold_end_time']))?>&nbsp;</td>
							<td><?php if($pro['hold_time'])echo substr($pro['hold_time'], 0, -3);?>&nbsp;</td>
							<td><?php echo $holdTypes[$pro['hold_type_id']]?>&nbsp;</td>
							<td><?php echo $pro['units_completed']?>&nbsp;</td>
							<td><?php echo substr($pro['actual_time'], 0,-3)?>&nbsp;</td>
							<td><?php echo $currentStatuses[$pro['current_status']]?>&nbsp;</td>
							<td><?php echo $pro['comments']?></td>
						</tr>
					<?php }} ?>
				</table>
			<?php } ?>
	    </div>
	    <div class="box-footer">

	    	<?php 
				$date1 = new DateTime($currentProcesses['FileProcess']['assigned_date']);
				$date2 = new DateTime(date('Y-m-d H:i:s'));
				$diff = $date2->diff($date1);
				
				$days   = $diff->format('%D'); 
				$hours   = $diff->format('%H'); 
				$minutes = $diff->format('%i');
				$sec = $diff->format('%s');
				echo  'Time taken: '.($days . ':' . $hours . ':'. $minutes) .' (d:h:m)' ;
        	?>

	    	<?php  echo $currentProcesses['FileProcess']['estimated_time'] ?>
	    	<?php $et = date('Y/m/d H:i:s',strtotime('+'.$currentProcesses['FileProcess']['estimated_time'].' hours',strtotime($currentProcesses['FileProcess']['assigned_date'])));
	    	?>
	    	<div class="countdown pull-right" data-Date='<?php echo $et;?>' data-endText="Delayed">
			  	(days) p_days, (hours) p_hours, (minutes) p_minutes, (seconds) p_seconds  left!
			</div>
		</div>
	</div>	
</div>
<script type="text/javascript">

	function upadatestartend(id,status){
		
		if(status == 2){
		
			$('#HoldModal').modal({show:true});
			$("#ProjectFileCurrentStatus7").prop("checked", true);		
			
			$("#hold_submit").on('click',function(){
				if($("#hold_type_id").val() == -1){
					alert('Add hold type.');
					return false;
				}else if($("#hold_units_completed").val() == ''){
					alert('Add units completed so far.');
					return false;
				}
				<?php 
				$o = 0;
				foreach($otherUnits as $otherUnitId => $otherUnit){ ?>
					else if($("#OtherMeasurableUnitValueHold<?php echo $o;?>Value").val() == ''){
							alert('Add value for all the fields');
							return false;
					}
				<?php 
				$o++;
			}?>
				 else{
				 	var dataval = '';
				 	<?php 
						$o = 0;
						foreach($otherUnits as $otherUnitId => $otherUnit){ ?>
							dataval = dataval + "{\"id\":\""+$("#OtherMeasurableUnitValueHold<?php echo $o;?>Id").val()+"\",\"value\":\""+$("#OtherMeasurableUnitValueHold<?php echo $o;?>Value").val()+"\"},"
						<?php 
						$o++;
					}?>
					$.ajax({
		            type: "POST",
		            url: "<?php echo Router::url('/', true); ?>project_files/start_stop/"+id+"/"+status+"/"+$("#hold_type_id").val()+"/"+$("#hold_units_completed").val(),
		            
		            // data: $(this).serialize(),
		            data: {
		            			id:id,
		            			status:status,
		            			hold_type_id:$("#hold_type_id").val(),
		            			hold_units_completed:$("#hold_units_completed").val(),
		            			OtherMeasurableUnit:"{\"data\":["+dataval+"]}"

                  },
		            beforeSend: function(){
		            
		            },
		            complete: function() {
		            
		            },                    
		            success: function(responseText, statusText, xhr, $form) {
		            	console.log(dataval);

		               if(status == 0){
		               	$("#start_button").hide();
		               	$("#file_submit_id").removeClass('hide').addClass('show');
		               }

		               if(status == 2){
		               	$("#hold_button").hide();
		               	$("#file_submit_id").removeClass('show').addClass('hide');
		               }

		               if(status == 3){
		               	$("#release_button").hide();
		               }		

		               if(responseText == 'logout'){
		               		location.href = "<?php echo Router::url('/', true); ?>/users/logout";
		               }else{
		               	window.location.reload();
		               }
		            },
		            error: function (request, status, error) {		                
		                alert('Action failed!');
		            }
		    	})	
				}
				
			});

		}else{
			
			if(status == 2){
				$("#holdtypes").removeClass('hide').addClass('show');
				$("input[name=data[ProjectFile][current_status]]").val([2]);
			}else if(status == 3){
				$("#holdtypes").removeClass('show').addClass('hide');
			}
			
				$.ajax({
		            type: "GET",
		            url: "<?php echo Router::url('/', true); ?>project_files/start_stop/"+id+"/"+status,
		            data: $(this).serialize(),
		            beforeSend: function(){
		            
		            },
		            complete: function() {
		            
		            },                    
		            success: function(responseText, statusText, xhr, $form) {
		            	 if(status == 0){
		               	$("#start_button").hide();
		               	$("#file_submit_id").removeClass('hide').addClass('show');
		               }

		               if(status == 2){
		               	$("#hold_button").hide();
		               }

		               if(status == 3){
		               	$("#release_button").hide();
		               }
		               if(responseText == 'logout'){
		               		location.href = "<?php echo Router::url('/', true); ?>/users/logout";
		               }else{
		               	window.location.reload();
		               }
		            },
		            error: function (request, status, error) {		                
		                alert('Action failed!');
		            }
		    	})
		}
	}

	function unitset(){

		$("#ProjectFileUnitsCompleted").on('change',function(){
					$(".unitsum").each(function(){				
						$("#"+this.id).val($("#ProjectFileUnitsCompleted").val());
					});	

					$("#unittotal").html($("#ProjectFileUnitsCompleted").val());

					if($("#ProjectFileUnitsCompleted").val() > 0){
						$(".errsum").removeAttr('readonly');	
					}else{
						$(".errsum").attr('readonly',true);
					}
					
		})
		// var units;
		// 	$(".unitsum").on('change',function(){
		// 		console.log(1);
		// 		var units = this.value;
		// 		console.log(units);
		// 		$(".unitsum").each(function(){				
		// 			$("#"+this.id).val(units);
		// 		});	

		// 		$("#ProjectFileUnitsCompleted").val(units);

		// 	});	
		// 	console.log(units);
		// 	return units;
		}


	$(document).ready(function() {

		unitset();

		$("#tuc").html(<?php echo $units_completed_so_far;?>);
		$(".units_completed_1").val(<?php echo $units_completed_so_far;?>);

		$('#ProjectFileUnitsCompleted').keyup(function () { 
		    this.value = this.value.replace(/[^0-9\.]/g,'');
		});

		$("#ProjectFileCurrentStatus").on('change',function(){
			if($("#ProjectFileCurrentStatus").val() == 8){
				$("#holdtypes").removeClass('hide').addClass('show');	
			}
		});
	
		$("#file_submit_id").on('click',function(){
			$('#ChecklistModal').modal({show:true});
		});

		$("#checklistClose").on('click',function(){		
			$('#ChecklistModal').modal({show:false});
			return true;
		});


		var interval = setInterval(function() {
	        var momentNow = moment(new Date('<?php echo date('Y-m-d H:i:s',strtotime($currentProcesses['FileProcess']['assigned_date']))?>'));
	        $('#date-part').html(momentNow.format('YYYY-MM-DD') + '&nbsp;');
	        $('#time-part').html(momentNow.format('hh:mm:ss'));
	    }, 100);

	});
</script>	
<?php } ?>

<style type="text/css">
	.chosen-container{width: 100% !important}
</style>	
<script type="text/javascript">
	function calc(i){
		console.log(i);
		var cost = 0;
		cost = parseInt($("#ProjectTimesheet"+i+"Total").val()) * parseInt($("#ProjectTimesheet"+i+"ResourceCost").val());
		$("#ProjectTimesheet"+i+"TotalCost").val(cost);
	}

	$().ready(function(){
		$("#on_hold_since").datetimepicker();
		$("#dt1").datetimepicker();
		$("#pro-tabs").tabs();
		$(".chosen-select").chosen();		
	});
</script>

<?php } ?>

<?php if($releaseRequests){ ?>
<div class="">
  <div  class="col-md-12 no-padding">
  	<h3>Project Release Requests</h3>
  	<table class="table table-responsive table-bordered">
  		<tr>
  			<th>Request From</th>
  			<th>Current Project</th>
  			<th>New Project</th>
  			<th>Member's Name</th>
  			<th width="110">Action</th>
  		</tr>  		
  	<?php foreach ($releaseRequests as $project_id => $datas) { 
  		if($datas){ ?>  		
  		<tr>
  			<th colspan="5" class="warning"><?php echo $projects[$project_id] ?></th>
  		</tr>
  		<?php foreach ($datas as $data) { ?>
  			<tr id="<?php echo $data['ProjectReleaseRequest']['id'];?>">
	  			<td><?php echo $data['RequestFrom']['name']?></td>
	  			<td><?php echo $data['CurrentProject']['title']?></td>
	  			<td><?php echo $data['NewProject']['title']?></td>
	  			<td><?php echo $data['Employee']['name']?></td>
	  			<td>
	  				<div class="btn-group">
                    	<a href="javascript:void(0)" class="btn btn-xs btn-success" id="<?php echo $data['ProjectReleaseRequest']['id'];?>_btn_accept">Accept</a> 
                    	<a href="javascript:void(0)" class="btn btn-xs btn-danger" id="<?php echo $data['ProjectReleaseRequest']['id'];?>_btn_reject">Reject</a>
                    </div>

                    <script type="text/javascript">
	                    $().ready(function(){
	                      $("#<?php echo $data['ProjectReleaseRequest']['id'];?>_btn_accept").on('click',function(){	                       
	                          $.get("<?php echo Router::url('/', true); ?>projects/update_request/request_status:1/id:<?php echo $data['ProjectReleaseRequest']['id']?>" , function(data) {	                          
	                                    $("#<?php echo $data['ProjectReleaseRequest']['id'];?>_btn_accept").html('Added');
	                                    $("#<?php echo $data['ProjectReleaseRequest']['id'];?>_btn_accept").addClass('success');
	                                    $("#<?php echo $data['ProjectReleaseRequest']['id'];?>").html("<td colspan='5'>Member Released</td>");
	                                    return false;
	                              });
	                      });

	                      $("#<?php echo $data['ProjectReleaseRequest']['id'];?>_btn_reject").on('click',function(){	                        
	                          $.get("<?php echo Router::url('/', true); ?>projects/update_request/request_status:2/id:<?php echo $data['ProjectReleaseRequest']['id']?>" , function(data) {	                          
	                                    $("#<?php echo $data['ProjectReleaseRequest']['id'];?>_btn_reject").html('Request Sent');
	                                    $("#<?php echo $data['ProjectReleaseRequest']['id'];?>_btn_reject").addClass('danger');
	                                    $("#<?php echo $data['ProjectReleaseRequest']['id'];?>").html("<td colspan='5'>Request Denied</td>");
	                                    return false;
	                              });
	                      });
	                    });
					</script>
	  			</td>
	  		</tr>
  		<?php } ?>
  		 <?php } ?>
  	<?php } ?>
  	</table>
  </div>
</div>
 <?php } ?>

<div class="hide">
  <div  class="col-md-12 no-padding">
    <h2><?php echo __('Tasks Assigned To You')?> <small>(<?php echo __('Admin can see all tasks assigned to all the users')?>)</small></h2>
    <div id="task-tabs" class="nav-tabs-info">
      <ul class="nav nav-tabs">
     	<li>
          <?php $project_task_cnt = ($project_tasks)>0 ? ' <span class="badge btn-danger">'. $project_tasks_completed .'/'. $project_tasks.'</span>': '';?>
          <?php echo $this->Html->link(__('Project Tasks'). '&nbsp;&nbsp;<span class="badge btn-danger">'.$project_task_count.'</span>', array('controller' => 'tasks','action'=>'get_project_task'), array('escape' => false)); ?>
        </li>
        
        <li>
          <?php $task_cnt = ($tasks)>0 ? ' <span class="badge btn-danger">'.$tasks_completed.'/' .$tasks. '</span>': '';?>
          <?php echo $this->Html->link(__('Other Tasks') . '&nbsp;&nbsp;<span class="badge btn-danger">'.$task_count.'</span>', array('controller' => 'tasks','action'=>'get_task'), array('escape' => false)); ?>          
        </li>
        
        <li><?php echo $this->Html->image('indicator.gif', array('id' => 'todo-busy-indicator', 'class' => 'pull-right')); ?></li>
      </ul>
    </div>
  </div>

</div>


<div class="row">

	<div class="col-md-12">
	    <?php if($this->Session->read('User.is_mr') == false )echo "<h3>". __('Welcome '). $this->Session->read('User.name') ." <small> " . $this->Session->read('User.branch') ." " .__('Branch')." / " .$this->Session->read('User.department'). " ". __('Department')." </small></h3>"; ?>
	  <div class="box box-danger box-solid">
	    <div class="box-header with-border">
	      <h3 class="panel-title"><?php echo __("Pending Approvals"); ?> <span class="badge btn-danger pull-right"><?php echo $approvalsCount; ?></span></h3>
	    </div>
	    <div class="box-body">
	      <table class="table table-condensed">
	        <tr>
	          <th><?php echo __("From"); ?></th>
	          <th><?php echo __("About"); ?></th>
	          <th><?php if ($this->request->controller != 'customer_feedbacks') echo __("Comments"); ?></th>
	          <th><?php echo __("Date/Time"); ?></th>
	          <th width="42"><?php echo __("Act"); ?></th>
	        </tr>
	        <?php foreach ($approvals as $approval): ?>
	        <tr>
	          <td><?php echo $approval['From']['name']; ?></td>
	          <td><?php 
	          if($approval['Approval']['model_name'] == 'ChangeAdditionDeletionRequest')$m = 'ChangeRequest';
	          else $m = $approval['Approval']['model_name'];
	          echo $this->Html->link(Inflector::humanize($m), array('controller' => $approval['Approval']['controller_name'], 'action' => 'approve', $approval['Approval']['record'], $approval['Approval']['id'])); ?></td>
	          <td><?php if ($this->request->controller != 'customer_feedbacks') echo $approval['Approval']['comments']; ?></td>
	          <td><?php echo $approval['Approval']['created']; ?></td>
	          <td><?php echo $this->Html->link(__('Act'), array('controller' => $approval['Approval']['controller_name'], 'action' => 'approve', $approval['Approval']['record'], $approval['Approval']['id']), array('class' => 'badge btn-danger')) ?></td>
	        </tr>
	        <?php endforeach ?>
	      </table>
	    </div>
	  </div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<h3>Queries Assigned To you</h3>
		<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
			<tr>
				<th><?php echo __('Name'); ?></th>
				<th>Files</th>
				<th><?php echo __('Type'); ?></th>
				<th><?php echo __('From'); ?></th>
				<th><?php echo __('To'); ?></th>
				<th><?php echo __('Details'); ?></th>
				<th><?php echo __('Status'); ?></th>	
				<th>Action</th>
				
			</tr>
			<?php if($userProjectQueries){ ?>
				<?php foreach ($userProjectQueries as $projectQuery): ?>
					<tr>
						<td><?php echo h($projectQuery['ProjectQuery']['name']); ?>&nbsp;</td>
						<td>
							<div class="btn-group">
							  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							    Files <span class="caret"></span>
							  </button>
							  
							
							<?php 
								$folder_path = Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS . 'qurery_file' . DS . $projectQuery['ProjectQuery']['id'];
                    $dir = new Folder($folder_path);
                    $all_files = $dir->find();
                    
                    if($all_files){
                    echo "<ul class='dropdown-menu'>";
                    foreach($all_files as $file){                       
                        $file_path = $file_folder_path . DS . $file;
                        echo "<li class=''>" . 
                        $this->Html->link($file, array(
                            'controller' => 'file_uploads',
                            'action' => 'view_document_file',
                            'file_name' => $file,
                            'full_base' => base64_encode(str_replace(Configure::read('MediaPath').'files'. DS . $this->Session->read('User.company_id'). DS ,'',$file_path)),
                        ),array('target'=>'_blank','escape'=>TRUE)).
                        "</li>";
                        }
                    echo "</ul>";
                }
								?>
							</div>
						</td>
						<td>
							<?php echo $this->Html->link($projectQuery['QueryType']['name'], array('controller' => 'query_types', 'action' => 'view', $projectQuery['QueryType']['id'])); ?>
						</td>
						<td>
							<?php echo $this->Html->link($projectQuery['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $projectQuery['Employee']['id'])); ?>
						</td>
						<td><?php echo h($projectQuery['SentTo']['name']); ?>&nbsp;</td>
						<td><?php echo h($projectQuery['ProjectQuery']['query']); ?>&nbsp;</td>
						<td><?php echo h($projectQuery['ProjectQuery']['current_status']); ?>&nbsp;</td>
						<td>
							<div class="btn-group">
								<div class="btn btn-warning btn-xs" id="q_<?php echo $projectQuery['ProjectQuery']['id']?>">Reply</div>
								<?php echo $this->Html->link('View',array('controller'=>'project_queries','action'=>'view',$projectQuery['ProjectQuery']['id']),array('class'=>'btn btn-default btn-xs','target'=>'_blank'));?>
							</div>
							<script type="text/javascript">
								$("#q_<?php echo $projectQuery['ProjectQuery']['id']?>").on('click',function(){
									$("#QueryModal").modal({show:true});
									$("#q_query").load('<?php echo Router::url('/', true); ?>project_query_responses/add_ajax/<?php echo $projectQuery['ProjectQuery']['id'] ?>/1');
								})
							</script>
						</td>																			
					</tr>
			<?php endforeach; ?>
			<?php }else{ ?>
				<tr><td colspan="8">No results found</td>
		</tr>
	<?php } ?>
</table>
	</div>
</div>

<div id="holdwin">
	<div class="modal fade" id="HoldModal" role="dialog">
	    <div class="modal-dialog">
	        <!-- Modal content-->
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <h4 class="modal-title">Add Reason for hold</h4>
	            </div>
	            <div class="modal-body" id="loadhere">	            	
	            	<?php echo $this->Form->input('hold_type_id');?>
	            	<?php if($otherUnits){
								$o = 0;
								echo "<div class='row'>";
								foreach($otherUnits as $otherUnitId => $otherUnit){
									echo $this->Form->hidden('OtherMeasurableUnitValueHold.'.$o.'.id',array('label'=>$otherUnitId, 'default'=>$otherUnitId));
									echo "<div class='col-md-4'>".$this->Form->input('OtherMeasurableUnitValueHold.'.$o.'.value',array('label'=>$otherUnit,'type'=>'number'))."</div>";
									$o++;
								}
								echo "</div>";
							}?>
	            	<?php 
	            	if($currentProcesses['FileProcess']['start_time'] == ''){
	            		echo $this->Form->input('hold_units_completed',array('default'=>0, 'type'=>'number','readonly'=>'readonly'));
	            		echo "<p>Can not add units completed as start time is empty.</p>";
	            	}else{
	            		// $projectFile['ProjectFile']['unit'];
	            		// $units_completed_so_far;
	            		echo $this->Form->input('hold_units_completed',array('type'=>'number'));
	            		echo "<p>Enter Units Completed duting this session. (Balance Units : ". ($projectFile['ProjectFile']['unit'] - $units_completed_so_far).")</p>";
	            	}
	            	?>
	            </div>
	            <div class="modal-footer">
	            		<button type="button" id="hold_submit" class="btn btn-success" data-dismiss="modal">Update</button>
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
	        </div>
	    </div>
	</div>
</div>

<div id="queryq">
	<div class="modal fade" id="QueryModal" role="dialog">
	    <div class="modal-dialog">
	        <!-- Modal content-->
	        <div class="modal-content">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	                <h4 class="modal-title">Query Details</h4>
	            </div>
	            <div class="modal-body" id="q_query">	 
	            	
	            	
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal" id="checklistClose">Close</button>
	            </div>
	        </div>
	    </div>
	</div>
</div>

<div id="user_files">
	<div  class="text-center"><span class="btn btn-sm" id="loadhistory"><h4>Load History</h4></span></div>
	<script type="text/javascript">
		$("#loadhistory").on('click',function(){
			$("#user_files").load("<?php echo Router::url('/', true); ?>/projects/user_time_sheet/<?php echo $this->Session->read('User.employee_id');?>");
		});		
	</script>
</div>

<div id="blockuser_ajax" class="task_main">
    <?php if (isset($blockedUser) && $blockedUser != null) echo $this->element('blockeduser',array('users'=>$blockedUser)); ?>
  </div>

<script type="text/javascript">
function openqc(n){
	if(n > 0){
		$('#qcqabox').show(500).animate('linear');
	}else{
		$('#qcqabox').hide(500).animate('linear');
	}
}
</script>