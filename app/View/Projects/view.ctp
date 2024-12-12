<?php 
echo $this->Form->create('Project',array()); 
echo $this->Form->hidden('start_date',array('default'=>$project['Project']['start_date']));
echo $this->Form->hidden('end_date',array('default'=>$project['Project']['end_date']));
echo $this->Form->end();
?>
<div id="projects_ajax">
<?php 
$activities = array(
	1=>'1',
    2=>'2',
    3=>'3',
    4=>'4',
    5=>'5',
    6=>'6',
    7=>'7',
    8=>'8',
    9=>'9',
    10=>'10',
    11=>'11',
    12=>'12',
    13=>'13',
    14=>'14',
    15=>'15',
    16=>'16',
    17=>'17',
    18=>'18',
    19=>'19',
    20=>'20',
);
?>


	<?php 
	echo $this->Html->script(array(
		'plugins/chartjs/Chart-2.min',
		'timeknots-master/src/d3.v2.min',
    	'timeknots-master/src/timeknots-min',
    	'Lightweight-jQuery-Timeline-Plugin-jqtimeline/js/jquery.jqtimeline',
    	'PapaParse-5.0.2/papaparse.min',
    	'bootstrap-editable.min',
    	'jquery.validate.min', 'jquery-form.min','js-xlsx-master/dist/xlsx.core.min', 
	    'Blob.js-master/Blob.min', 
	    'FileSaver.js-master/FileSaver.min', 
	    'TableExport-master/src/stable/js/tableexport.min',
	    'tablesorter-master/js/jquery.tablesorter',
	    'tablesorter-master/js/jquery.tablesorter.widgets',
    )); 
    echo $this->fetch('script'); 

    echo $this->Html->css(array(
		'Lightweight-jQuery-Timeline-Plugin-jqtimeline/css/jquery.jqtimeline',
		'bootstrap-editable'
    )); 
    echo $this->fetch('css'); 
   ?>

<style type="text/css">
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #c1c0c0;
}
.m-div{
	background-color:#dfdfdf;
}
.summary td{
	padding: 5px !important;
	font-weight: 800;
	font-size: 15px;
}
.box-header h4:before{
	content: '';
}
</style>
	<?php echo $this->Session->flash();?>

<div class="row">
	<div class="col-md-12">
		
	</div>
</div>

<?php 
Configure::write('debug',1);

	$x = 0;
	foreach ($project_details as $milestone) {
		// debug($milestone['Milestone']);
		// foreach ($milestone as $m) {
			// debug($m);
			// date('c', $milestone['Milestone']['start_date'])); 
      // or for objects
      		// $dateTimeObject->format('c');
			
			$result[$x] = array(
				'id'=>$milestone['Milestone']['id'],
				'name'=>$milestone['Milestone']['title'],
				'on'=> date('c', strtotime($milestone['Milestone']['start_date']))
			);

		// }
		$x++;
		$str .= '{id:'.$x.',name:"'.$milestone['Milestone']['title'].'",on: new Date('.date('Y,m,d', strtotime($milestone['Milestone']['start_date'])).')},';
		$x++;
	}
	$str = substr($str,0,-1);
	
?>
<!-- <?php echo json_encode($result)?> -->


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
<div class="nav panel panel-default">
		<div class="projects form col-md-12">
			<h4><?php echo __('View Project'); ?>		
				<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
				<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
				<?php echo $this->Html->link('Edit',array('action'=>'edit',$this->request->params['pass'][0]),array('class'=>'label btn-info'));?>
				<?php echo $this->Html->link(__('MIS'), array('action'=>'mis',$this->request->params['pass'][0]),array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
				<?php echo $this->Html->link(__('Reports'), array('action'=>'daily_time_log_daily',$this->request->params['pass'][0]),array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
				<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
			</h4>
<?php 
$qucipro = $this->requestAction('projects/projectdates/'.$project['Project']['id']);
echo $this->element('projectdates',array('qucipro'=>$qucipro));?>
<?php if($project['Project']['soft_delete'] == 0 && $project['Project']['publish'] == 1){ ?>

<?php 
$startcolor = '#00a65a';
$endcolor = '#dd4b39';
$endcolor = '#cccccc';
// Configure::write('debug',1);
$aa = 0;
foreach ($project_details as $miledates) {
	// debug($miledates['Milestone']['id']);
	
	$agenda[$miledates['Milestone']['id']][] = array('name'=>'Project Starts','date'=>$project['Project']['start_date'],'color'=>$startcolor);
	
	$color = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
	$agenda[$miledates['Milestone']['id']][] = array('name'=>$miledates['Milestone']['title'] .' Starts' ,'date'=>$miledates['Milestone']['start_date'],'color'=>$color);
	$agenda[$miledates['Milestone']['id']][] = array('name'=>$miledates['Milestone']['title'] . ' Ends' ,'date'=>$miledates['Milestone']['end_date'],'color'=>$endcolor);
	$agenda[$miledates['Milestone']['id']][] = array('name'=>'Project Ends','date'=>$project['Project']['end_date'],'color'=>$endcolor);
	$aa++;
}

// debug($agenda);

?>
<div class="row hide">
	<div class="col-md-12">
		<ul class="list-group">				
			<!-- <li class="list-group-item"><h4 class="">Timeline</h4></li> -->
			<li class="list-group-item" style="overflow: auto">
				<div id="myTimeline"></div>					
			</li>
		</ul>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<ul class="list-group">				
				<li class="list-group-item"><h4 class="text-center">Milestone Timeline</h4></li>
				<li class="list-group-item" id="tm" style="overflow: auto">
					<?php foreach ($agenda as $key => $value) { ?>
						<div id="timeline<?php echo $key?>" class="pull-left" style="width:auto;height:20px; margin-top:30px;  clear: both; position: relative;top:-10px;float: left;"></div>						
						<script type="text/javascript">
							var agenda = new Array();
							var agenda = '';
							agenda = <?php echo json_encode($value)?>;							
							TimeKnots.draw("#timeline<?php echo $key?>", agenda, 
								{
									showLabels: true, 
									dateFormat: "%Y/%m/%d", 
									labelFormat:"%Y/%m/%d", 
									radius: 5,
									width:$("#tm").width(),
									// addNow: true,
								});
						</script>
					<?php } ?>
					<br /><br />&nbsp;<br />
				</li>
			</ul>
	</div>
</div>
<?php $yr = date('Y',strtotime($project['Project']['start_date'])) - date('Y',strtotime($project['Project']['end_date']));
$yr = $yr + 2;
?>
<script type="text/javascript">
			var tl = $('#myTimeline').jqtimeline({
							events : [<?php echo $str?>],
							numYears:<?php echo $yr;?>,
							startYear:<?php echo date('Y',strtotime($project['Project']['start_date']))?>,
							// click:function(e,event){
							// 	alert(event.name);
							// }
						});
		</script>


			<table class="table table-responsive">
				<tr><td><?php echo __('Customer'); ?></td>
				<td>
					<?php echo h($project['Customer']['name']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Goal'); ?></td>
				<td>
					<?php echo h($project['Project']['goal']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Scope'); ?></td>
				<td>
					<?php echo h($project['Project']['scope']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Success Criteria'); ?></td>
				<td>
					<?php echo h($project['Project']['success_criteria']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Challenges'); ?></td>
				<td>
					<?php echo h($project['Project']['challenges']); ?>
					&nbsp;
				</td></tr>

				<tr><td><?php echo __('Daily Hours'); ?></td>
				<td>
					<?php echo ($project['Project']['daily_hours'])? '12 Hours' : '8 hours'; ?>
					&nbsp;
				</td></tr>

				<tr><td><?php echo __('Weekends'); ?></td>
				<td>
					<ul>
					<?php 
					foreach (json_decode($project['Project']['weekends']) as $key => $value) {
						echo "<li>".$weekends[$value]."</li>";
					}
					?>
					</ul>
					&nbsp;
				</td></tr>


				<tr><td><?php echo __('Challenges'); ?></td>
				<td>
					<?php echo h($project['Project']['challenges']); ?>
					&nbsp;
				</td></tr>

				<tr><td><?php echo __('Current Status'); ?></td>
					<td>
						<?php echo ($currentStatuses[$project['Project']['current_status']]); ?>&nbsp;
						&nbsp;
					</td>
				</tr>
				
				
				<tr><td><?php echo __('Prepared By'); ?></td><td><?php echo h($project['PreparedBy']['name']); ?>&nbsp;</td></tr>
				<tr><td><?php echo __('Approved By'); ?></td><td><?php echo h($project['ApprovedBy']['name']); ?>&nbsp;</td></tr>

			
				<tr><td><?php echo __('Publish'); ?></td>

				<td>
					<?php if($project['Project']['publish'] == 1) { ?>
					<span class="fa fa-check"></span>
					<?php } else { ?>
					<span class="fa fa-ban"></span>
					<?php } ?>&nbsp;</td>
				&nbsp;</td></tr>
				<!-- <tr><td><?php echo __('Soft Delete'); ?></td>

				<td>
					<?php if($project['Project']['soft_delete'] == 1) { ?>
					<span class="fa fa-check"></span>
					<?php } else { ?>
					<span class="fa fa-ban"></span>
					<?php } ?>&nbsp;</td>
				&nbsp;</td></tr> -->
				<tr><td><?php echo __('Project Manager'); ?></td>
					<td>	
						<?php 
						// echo $project['Project']['team_leader_id'];
						$tls = json_decode($project['Project']['employee_id'],true);
						foreach ($tls as $key => $value) {
							echo $PublishedEmployeeList[$value] .', ';
						}?>						
						&nbsp;
					</td>
				</tr>
				<tr><td><?php echo __('Team Leaders'); ?></td>
					<td>
						<?php 
						// echo $project['Project']['team_leader_id'];
						$tls = json_decode($project['Project']['team_leader_id'],true);
						foreach ($tls as $key => $value) {
							echo $PublishedEmployeeList[$value] .', ';
						}?>
						&nbsp;
					</td>
				</tr>
				<tr><td><?php echo __('Project Leaders'); ?></td>
					<td>
						<?php $tls = json_decode($project['Project']['project_leader_id'],true);
						foreach ($tls as $key => $value) {
							echo $PublishedEmployeeList[$value] .', ';
						}
						?>&nbsp;
						&nbsp;
					</td>
				</tr>
			</table>
			

<?php if($project_details['PurchaseOrder']['in']) { ?>
	<h4>Inbound PO <small>(From Customer)</small></h4>
<div class="row">	
	<div class="col-md-12">
		<table class="table table-responsive table-bordered table-condensed">
			<tr>			
				<th>PO#</th>
				<th>Title</th>
				<th>Order Date</th>
				<th>Total</th>
				<th>Action</th>
			</tr>
			<?php foreach ($project_details['PurchaseOrder']['in'] as $purchaseOrder) { 
				// if($purchaseOrder['PurchaseOrder']['type'] == 1)$class = 'success'; else $class = 'warning';
				$cpototal = $cpototal + $purchaseOrder['PurchaseOrder']['po_total'];
			?>
				<tr class="success">
					<td><?php echo $purchaseOrder['PurchaseOrder']['purchase_order_number']?></td>
					<td><?php echo $purchaseOrder['PurchaseOrder']['title']?></td>
					<td><?php echo $purchaseOrder['PurchaseOrder']['order_date']?></td>
					<td><?php echo $this->Number->currency($purchaseOrder['PurchaseOrder']['po_total'],'INR. ')?></td>
					<td><?php echo $this->Html->Link('View',array('controller'=>'purchase_orders','action'=>'view',$purchaseOrder['PurchaseOrder']['id']),array('class'=>'btn btn-xs btn-default pull-right','target'=>'_blank'));?></td>
				</tr>
			<?php } ?>
		</table>
	</div>
</div>
<?php } ?> 


<!-- </div> -->


<!-- <div class="col-md-4">		
	<h3>Graphs</h3>
	<?php // echo $this->element('projectgraphs');?>
</div> -->
<div class="row">
	<div class="col-md-12">
		<div class="box box-primary collapsed-box resizable">
	            <div class="box-header with-border" data-widget="collapse" onclick="membertabsopenclose();"><h4>All Member's board</h4>
	                <div class="btn-group box-tools pull-right">
	                    <button type="button" class="btn btn-xs btn-default" data-widget="collapse"><i class="fa fa-plus"></i></button>
	                </div>
	            </div>
	            <div class="box-body" style="padding: 0px">
	            	<div id="mebtabs">	
						<ul>
							<li><?php echo $this->Html->link(__('Available Members'), array('action' => 'pro_meb_details','current_project_id' => $project['Project']['id'])); ?></li>
							<?php foreach ($allProjects as $pkey => $pvalue) { ?>
								<li id="aa_<?php echo $pkey;?>" aria-controls="<?php echo $pkey;?>" aria-labelledby="<?php echo $pkey;?>">
									<?php echo $this->Html->link($pvalue, 
										array('action' => 'pro_meb_details','project_id'=>$pkey, 'current_project_id' => $project['Project']['id']),
										array(
											// 'aria-controls'=>$pkey,
											// 'aria-id'=>$pkey,
											// 'aria-labelledby'=>$pkey,
										)
									); ?>
								</li>	
							<?php } ?>
							
							<!-- <li><?php echo $this->Html->image('indicator.gif', array('id' => 'meb-busy-indicator','class'=>'pull-right')); ?></li> -->
						</ul>
					</div>
					<script>
						function membertabsopenclose(){
							$(function() {
							  	$("#mebtabs .ui-tabs-nav a").removeData("cache.tabs");

							    $( "#mebtabs" ).tabs({
							        active: 0,					    	
							      beforeLoad: function( event, ui) {
							      	ui.jqXHR.error(function() {					      		
									  ui.panel.html(
									    "Error Loading ... " +
									    "Please contact administrator." );
									});
							      }
							    });
							  });
						}					  
					</script>

	              <?php // print_r($PublishedEmployeeList);?>
	                <table class="table table-responsive table-condensed table-bordered draggable hide">
	                  <tr>
	                    <th>Member</th>
	                    <th>Department</th>
	                    <th>Designation</th>                  
	                    <th>Project</th>
	                    <th>TL</th>
	                    <th>PL</th>
	                    <th>Locked From</th>
	                    <th>Locked Till</th>
	                    <th></th>
	                  </tr>
	                  <?php 
	                  // Configure::write('debug',1);
	                  // debug($allMembers);
	                  foreach ($allMembers as $employee) { 
	                    if($employee['Employee']['curr_project'])$procheckclass  = 'warning';
	                    else $procheckclass  = '';
	                    // echo ">> " . $employee['Employee']['tl'];
	                    // echo ">> " . $employee['Employee']['pm'];
	                    ?>
	                    <tr id="<?php echo $employee['Employee']['id']?><?php echo $pop?>" class="<?php echo $procheckclass?>">
	                        <td><?php echo $employee['Employee']['name'];?></td>
	                        <td><?php echo $employee['Department']['name'];?></td>
	                        <td><?php echo $employee['Designation']['name'];?></td>
	                        <td>
	                        	<?php echo $allProjects[$employee['Employee']['curr_project']];?>
	                        	<!-- <?php echo $employee['Employee']['curr_project']?></td> -->
	                        <?php if($employee['Employee']['curr_project'] && ($employee['Employee']['tl'] || $employee['Employee']['pm'])){ ?>
	                        	<td><?php echo $PublishedEmployeeList[$employee['Employee']['tl']];?></td>
	                        	<td><?php echo $PublishedEmployeeList[$employee['Employee']['pm']];?></td>
	                        <?php }elseif($employee['Employee']['curr_project'] && ($employee['Employee']['tl'] = -1 || $employee['Employee']['pm'] = -1)){ ?>
	                        	<td colspan="2" class="success">Task Not Assigned</td>
	                        <?php }else{ ?> 
	                        	<td></td>
	                        	<td></td>
	                        <?php } ?>
	                        
	                        <td><?php echo $employee['Employee']['locked_from'];?></td>
	                        <td><?php echo $employee['Employee']['locked_till'];?></td>
	                        <td>
	                        	<?php echo $employee['Employee']['curr_project'] ?>
	                          <div class="btn-group">
	                            <?php if(isset($employee['Employee']['curr_project'])) { ?>
	                              <a href="javascript:void(0)" class="btn btn-xs btn-warning" id="<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn_release">Request Release</a> 
	                            <?php }else{ ?>
	                              <a href="javascript:void(0)" class="btn btn-xs btn-success" id="<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn">Add</a>                              
	                            <?php } ?>
	                              
	                              
	                          
	                          </div>
	                        </td>
	                      </tr>
	                      <script type="text/javascript">
	                        $().ready(function(){
	                          $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn").on('click',function(){
	                            // alert('a');
	                              $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_employee_to_project/project_id:<?php echo $project['Project']['id'];?>/employee_id:<?php echo $employee['Employee']['id']?>/milestone_id:<?php echo $milestone['Milestone']['id']?>" , function(data) {
	                              //           console.log(data);
	                                        $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn").html('Added');
	                                        $("#<?php echo $employee['Employee']['id']?><?php echo $pop?>").addClass('success');
	                                        return false;
	                                  });
	                          });

	                          $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn_release").on('click',function(){
	                            // alert('a');
	                              $.get("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/send_release_request/current_project_id:<?php echo $employee["Employee"]["curr_project"];?>/new_project_id:<?php echo $project['Project']['id']?>/employee_id:<?php echo $employee['Employee']['id']?>/request_from_id:<?php echo $this->Session->read('User.employee_id')?>/project_employee_id:<?php echo $employee['Employee']['pro_emp_id']?>" , function(data) {
	                              //           console.log(data);
	                                        $("#<?php echo $employee['Employee']['id'];?><?php echo $pop?>_btn_release").html('Request Sent');
	                                        $("#<?php echo $employee['Employee']['id']?><?php echo $pop?>_btn_release").addClass('success');
	                                        return false;
	                                  });
	                          });
	                        });


	                      </script>
	                  <?php } ?>
	                </table>

	            </div>
	            
	          </div>
	      </div>
	  </div>

<div class="row">
	<div class="col-md-12">
		<div id="project_planning" class="">
		    <h3 style="margin-left: 12px">Planning Board <small>Add new milestones</small></h3>
		    <fieldset>		        
		        <?php		        
		        	if($project_details){
		            for($i = count($project_details) + 1; $i <= 20; $i++){ ?>
		            	<div class="btn btn-info btn-xl float-left" id="<?php echo $i?>" style="margin: 5px 5px 5px 12px;"><?php echo $i?></div>                
			                <script type="text/javascript">
			                    $("#<?php echo $i?>").click(function(){
			                        $("#<?php echo $i?>").removeClass("btn-info").addClass("btn-success");

			                        if($("#<?php echo $i?>_div").html()==''){
			                            $("#<?php echo $i?>_div").load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/updatemilestone/project_id:<?php echo $project["Project"]["id"]?>/' + <?php echo $i?> + '/' + btoa("<?php echo $i?>"), function(response, status, xhr) {
			                                // if (response != "") {
			                                //     $('#EmployeeOfficeEmail').val('');
			                                //     $('#EmployeeOfficeEmail').addClass('error');
			                                // } else {
			                                //     $('#EmployeeOfficeEmail').removeClass('error');
			                                // }
			                            });
			                        }else{
			                            $("#panel_body_<?php echo $i?>").toggle(500);
			                        }
			                    });
			                </script>
		            <?php } 
						}?>		            
		            <?php
		            $i = 0;
		            if($project_details){
		            for($i = count($project_details) + 1; $i <= 20; $i++){ ?>
		                <div id="<?php echo $i?>_div" class="float-left"></div>		            
		            <?php } 
		            }?> 
		    </fieldset>
		</div>
	</div>
	<div class="col-md-12">		
		<?php 
		if($project['Project']['soft_delete'] == 0 && $project['Project']['publish'] == 1){
			echo $this->element('projecttimeline',array(
				'project_details'=>$project_details,
				'project_id'=>$this->request->params['pass'][0],
				'PublishedEmployeeList'=>$PublishedEmployeeList,
				'PublishedDepartmentList'=>$PublishedDepartmentList,
				'PublishedDesignationList'=>$PublishedDesignationList,
				// 'EstimatedMilestoneUnits'=>$PublishedDesignationList,
			));	
		}
		?>
	</div>
</div>

<div class="clear-fix"></div>
<div class="col-md-12" style="padding: 0px">
	<div class="col-md-6 hide"><h2>Project Summary</h2>
		<table class="table table-responsive table-bordered table-condensed">
			<tr class=""><td width="45%"><h4><?php echo __('Project Completion'); ?><br /><small>As per tasks assigned</small></h4></td>
				<td>
					<?php $completion = $this->requestAction('task_statuses/task_completion/'.$project['Project']['id']); ?>
							<span class='label label-info pull-right'><?php echo round($completion);?>%</span>&nbsp;
							<div class="progress-group">
				                <div class="progress sm">
				                    <?php
				                        if($completion <= 100 )$class = ' progress-bar-success';
				                        if($completion <= 80 )$class = ' progress-bar-aqua';
				                        if($completion <= 60 )$class = ' progress-bar-yellow';
				                        if($completion <= 40)$class = ' progress-bar-red';
				                    ?>
				                  <div style="width: <?php echo $completion;?>%" class="progress-bar <?php echo $class;?>"></div>
				                </div>
				            </div>
				        </td>
				    </tr>
			<tr class="success"><td><h4><?php echo __('Estimated Project Cost'); ?><br /><small>Estimated Resource cost + Other costs</small></h4></td>
				<td>
					<h4><?php 
						echo $this->Number->currency($total,'INR. ');
						// echo h($project['Project']['estimated_project_cost']); 
					?></h4>
					&nbsp;
				</td>
			</tr>
			<tr class="info"><td><h4><?php echo __('Total Mandays'); ?><br /><small>As per timesheet</small></h4></td>
				<td>
					<h4><?php 
						echo $mandays;
						// echo h($project['Project']['estimated_project_cost']); 
					?> Mandays</h4>
					&nbsp;
				</td>
			</tr>
			<tr class="warning"><td><h4><?php echo __('Resource Cost '); ?><br /><small>As per timesheet</small></h4></td>
				<td>
					<h4><?php 
						echo $this->Number->currency($mandaycost, 'INR. ');
						// echo h($project['Project']['estimated_project_cost']); 
					?></h4>
					&nbsp;
				</td>
			</tr>
			<tr class="warning"><td><h4><?php echo __('Other Costs'); ?><br /><small>As per out-bound POs</small></h4></td>
				<td>
					<h4><?php 
						echo $this->Number->currency($pototal,'INR. ');
						// echo h($project['Project']['estimated_project_cost']); 
					?></h4>
					&nbsp;
				</td>
			</tr>
			<tr class="danger"><td><h4><?php echo __('Total Expense'); ?><br /><small>Resource cost + Out-bound POs</small></h4></td>
				<td>
					<h4><?php 
						echo $this->Number->currency($mandaycost + $pototal,'INR. ');
						// echo h($project['Project']['estimated_project_cost']); 
					?></h4>
					&nbsp;
				</td>
			</tr>
			
			<tr class="success"><td><h4><?php echo __('Balance'); ?><br /><small>Against Estimated Cost</small></h4></td>
				<td>
					<h4><strong><?php 
						echo $this->Number->currency($total - $mandaycost - $pototal,'INR. ');
						// echo h($project['Project']['estimated_project_cost']); 
					?></strong></h4>
					&nbsp;
				</td>
			</tr>

			<tr class="info"><td><h4><?php echo __('Actual Balance'); ?><br /><small>Against Received Customer POs</small></h4></td>
				<td>
					<h4><strong><?php 
						echo $this->Number->currency($cpototal - $mandaycost - $pototal,'INR. ');
						// echo h($project['Project']['estimated_project_cost']); 
					?></strong></h4>
					&nbsp;
				</td>
			</tr>
			<tr class="info"><td><h4><?php echo __('Project Status'); ?><br /><small></small></h4></td>
				<td>
					<h4><strong><?php			
					if($project['Project']['current_status'] == 0 &&  date('Y-m-d',strtotime($project['Project']['end_date'])) < date('Y-m-d')){
						echo "Delayed";
						// $time = new Time($project['Project']['end_date']);
						// Outputs 'in about a day'
						// $result = $time->timeAgoInWords([
						//     'accuracy' => 'day'
						// ]);	
						// echo $result;
						// echo $this->Time->timeAgoInWords(
						//     'Aug 22, 2011',
						//     array('format' => 'F jS, Y', 'end' => '+1 year')
						// );


						// echo CakeTime::timeAgoInWords(
						//     'Aug 22, 2011',
						//     array('format' => 'F jS, Y', 'end' => '+1 year')
						// );
						// echo CakeTime::timeAgoInWords($timestamp, array(
						//     'accuracy' => array('month' => 'month'),
						//     'end' => '1 year'
						// ));
						// echo CakeTime::timeAgoInWords(
						//     'Aug 22, 2011',
						//     array('format' => 'F jS, Y')
						// );
					}else{
						echo "On going";
					} ?>&nbsp;</strong></h4>
					&nbsp;
				</td>
			</tr>
			
		</table>	
	</div>
	<!-- <div class="col-md-12">
		<h2>Milestone Summary</h2>
		<?php 
			$result = array();
			$milestonewise = $this->requestAction('projects/milestonewise/'.$project['Project']['id']);
			// Configure::write('debug',1);
			
			foreach ($milestonewise as $milestone) {
				$pcost = 0;
				foreach ($milestone['PurchaseOrder'] as $pos) {
					$pcost = $pcost + $pos['out'];
				}
				$result[$milestone['Milestone']['title']] = $pcost + $milestone['Milestone']['tcost'];
				$aresult[$milestone['Milestone']['title']]['estimate'] = $milestone['Milestone']['rcost'] + $milestone['Milestone']['ecost'];
				$aresult[$milestone['Milestone']['title']]['actual'] = $result[$milestone['Milestone']['title']];
			}
			// debug($aresult);
		?>
		
	</div> -->
</div>

<?php } else{ ?>
	<div class="alert alert-danger">This project is either not published or deleted.</div>
	<?php }?>



  <script type="text/javascript">

  // 	function releaseemp(id,employee_id,pop){
  // 		var txt;
		// var r = confirm("Do you want to release this mumber?");
		// if (r == true) {
		//   $.ajax({
  //                   type: "POST",
  //                   url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/removeemployee/"+ id +"/" + employee_id,
  //                   target: "#"+employee_id+""+pop,
  //                   data: $(this).serialize(),
  //                   beforeSend: function(){
  //                       // $("#submit_id").prop("disabled",true);
  //                       // $("#submit-indicator").show();
  //                       // $('#investigationModal').modal('hide');
  //                   },
  //                   complete: function() {
  //                      // $("#submit_id").removeAttr("disabled");
  //                      // $("#submit-indicator").hide();                       
  //                   },                    
  //                   success: function(responseText, statusText, xhr, $form) {
  //                   	alert(responseText);
  //                      // $("#main_index").html(responseText);
  //                      if(responseText == 1){
  //                      	$("#remove_"+employee_id+""+pop).html('<td colspan="9" class="text-danger">Member can not be Released. Re-assign files assigned to this member first.</td>');
  //                      }else{
  //                      	$("#remove_"+employee_id+""+pop).html('<td colspan="9" class="text-danger">Member Removed</td>');	
  //                      }
                       
  //                      // alert(statusText);
  //                   },
  //                   error: function (request, status, error) {
  //                       // alert(request.responseText);
  //                       // alert('Action failed!');
  //                       $("#"+employee_id+""+pop).html('<td colspan="9" class="text-danger">Action Failed!</td>');
  //                   }
  //           }) 
		// } else {
		//   return false;
		// }
  // 	}

  	function releaseemp(id,employee_id,pop,pr){
  		var txt;
		var r = confirm("Do you want to release this mumber?");
		if (r == true) {
		  $.ajax({
                    type: "POST",
                    url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/release_member/"+ id +"/" + employee_id +"/<?php echo $project['Project']['id'];?>",
                    // target: "#release_"+employee_id+""+pop+""+pr,
                    data: $(this).serialize(),
                    beforeSend: function(){
                        // $("#submit_id").prop("disabled",true);
                        // $("#submit-indicator").show();
                        // $('#investigationModal').modal('hide');
                    },
                    complete: function() {
                       // $("#submit_id").removeAttr("disabled");
                       // $("#submit-indicator").hide();                       
                    },                    
                    success: function(responseText, statusText, xhr, $form) {
                    	// alert("release_"+employee_id+"_"+pop+"_"+pr);
                       // $("#main_index").html(responseText);
                       if(responseText == 1){
                       	$("#release_"+employee_id+"_"+pop+"_"+pr).html('<div class="text-danger">Member can not be Released. Re-assign files assigned to this member first.</div>');
                       }else{
                       	$("#release_"+employee_id+"_"+pop+"_"+pr).html('<div class="text-success">Member Released</div>');	
                       }
                       
                       // alert(statusText);
                    },
                    error: function (request, status, error) {
                        // alert(request.responseText);
                        // alert('Action failed!');
                        $("#release_"+employee_id+"+"+pop+"+"+pr).html('<div class="text-danger">Action Failed!</div>');
                    }
            }) 
		} else {
		  return false;
		}
  	}
  	

  	function changeuser(file_id,employee_id,process_id){
	   $.ajax({
            url: "<?php echo Router::url('/', true); ?>file_processes/changeuser/"+file_id+"/" +employee_id +"/" + process_id,
            success: function(data, result) {
            	console.log(data);
            	if(data == 1){
            		alert('File is assigned to a new member');	
            	}else{
            		alert('File can not be assigned.');	
            	}
                
            }
        });
  	}

  	function updatedepedancy(did,id){
  		$.get("<?php echo Router::url('/', true); ?>project_process_plans/updatedepedancy/"+did+"/" +id , function(data) {
	            // $('#mainPanel_ajax').append(data);
	    });
  	}


    function openmodel(controller,action,id,project_id,milestone_id,project_activity_id,project_overall_plan_id){

    	$.ajaxSetup({cache:false,});

      $(".modal-body").load("<?php echo Router::url('/', true); ?>"+controller+"/"+action+"/project_id:"+project_id+"/milestone_id:"+milestone_id+"/project_activity_id:"+project_activity_id+"/project_overall_plan_id:"+project_overall_plan_id+"/"+id);
      $('#producionModal').modal({show:true,cache:false});

      $('body').on('click', '.modal-toggle', function (event) {        
            event.preventDefault();
            $('.modal-content').empty();
            $('#producionModal')
                .removeData('bs.modal')
                .modal({remote: $(this).attr('href') });
        });
   }
  </script>
  
</div></div>
<?php // echo $this->element('upload-edit', array('usersId' => $project['Project']['created_by'], 'recordId' => $project['Project']['id'], 'showUpload'=>'no')); ?>

</div>
</div>


<?php echo $this->Js->writeBuffer();?>


</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
