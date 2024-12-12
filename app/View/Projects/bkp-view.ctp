<div id="projects_ajax">
	<?php echo $this->Html->script(array('plugins/chartjs/Chart-2.min')); ?>
	<?php echo $this->fetch('script'); ?>

<style type="text/css">
.table-bordered>thead>tr>th, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>tbody>tr>td, .table-bordered>tfoot>tr>td {
    border: 1px solid #c1c0c0;
}
.m-div{
	background-color:#dfdfdf;
}
.summary td{
	padding: 12px !important;
	font-weight: 800;
	font-size: 18px;
}
</style>
	<?php echo $this->Session->flash();?>	
	<div class="nav panel panel-default">
		<div class="projects form col-md-6">
			<h4><?php echo __('View Project'); ?>		
				<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
				<?php echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
				<?php echo $this->Html->link(__('Edit'), '#edit',array('id'=>'edit','class'=>'label btn-info','data-toggle'=>'modal')); ?>
				<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
			</h4>
<?php 
$qucipro = $this->requestAction('projects/projectdates/'.$project['Project']['id']);
echo $this->element('projectdates',array('qucipro'=>$qucipro));?>
			<table class="table table-responsive">
				<tr><td width="25%"><?php echo __('Title'); ?></td>
				<td>
					<?php echo h($project['Project']['title']); ?>
					&nbsp;
				</td></tr>
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
				<tr><td><?php echo __('Start Date'); ?></td>
				<td>
					<?php echo h($project['Project']['start_date']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('End Date'); ?></td>
				<td>
					<?php echo h($project['Project']['end_date']); ?>
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Current Status'); ?></td>
				<td>
					<?php echo ($project['Project']['current_status']? 'Closed':'Open'); ?>&nbsp;
					&nbsp;
				</td></tr>
				<tr><td><?php echo __('Project Status'); ?></td>
				
				
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
				<tr><td><?php echo __('Soft Delete'); ?></td>

				<td>
					<?php if($project['Project']['soft_delete'] == 1) { ?>
					<span class="fa fa-check"></span>
					<?php } else { ?>
					<span class="fa fa-ban"></span>
					<?php } ?>&nbsp;</td>
				&nbsp;</td></tr>
				<tr><td><?php echo __('Project Leader'); ?></td>
				<td>
					<?php echo $this->Html->link($project['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $project['Employee']['id'])); ?>
					&nbsp;
				</td></tr>
				<tr>
					<td colspan="2">
						<h4>Estimated Resource Cost</h4>
						<table class="table table-responsive table-condensed table-bordered">
							<tr class="warning">
								<th>Milestone</th>
								<th>User</th>
								<th>Mandays</th>
								<th>Resource Cost</th>
								<th>Sub Total</th>
							</tr>
							<?php 
							$total = 0;
							$subT = 0;
							foreach ($projectResources as $projectResource) { ?>
								<tr class="warning">
									<td><?php echo $projectResource['Milestone']['title']?></td>
									<td><?php echo $projectResource['User']['name']?></td>
									<td><?php echo $projectResource['ProjectResource']['mandays']?></td>
									<td><?php echo $projectResource['ProjectResource']['resource_cost']?></td>
									<td><?php echo $this->Number->currency($projectResource['ProjectResource']['resource_sub_total'],'INR. ')?></td>
								</tr>
							<?php 
							$subT = $subT + $projectResource['ProjectResource']['resource_sub_total'];
						} 
						$total = $subT;
						?>
							<tr class="warning">
								<th colspan="4" class="text-right"><h4>Total : </h4></th>
								<th class="text-right"><h4><?php echo $this->Number->currency($subT,'INR. ');?></h4></th>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<?php if($projectTimesheets){ ?>
							<h4>Project Timesheet <small>Actual Resource Cost</small></h4>
							<table class="table table-responsive table-bordered table-condensed">
								<tr class="danger">
									<th>User</th>
									<th>Project Acitivity</th>
									<th>Activity Description</th>
									<th>Start Time</th>
									<th>End Time</th>
									<th>Mandays</th>				
									<th>Total Cost</th>
								</tr>
								<?php foreach ($projectTimesheets as $projectTimesheet) { 
									$final[$projectTimesheet['User']['name']] = $final[$projectTimesheet['User']['name']] + $projectTimesheet['ProjectTimesheet']['total_cost'];
								?>
									<tr class="danger">
										<td><?php echo $projectTimesheet['User']['name']?></td>
										<td><?php echo $projectTimesheet['ProjectActivity']['title']?></td>
										<td><?php echo $projectTimesheet['ProjectTimesheet']['activity_description']?></td>
										<td><?php echo date('Y-m-d',strtotime($projectTimesheet['ProjectTimesheet']['start_time']))?></td>
										<td><?php echo date('Y-m-d',strtotime($projectTimesheet['ProjectTimesheet']['end_time']))?></td>
										<td><?php echo $projectTimesheet['ProjectTimesheet']['total']?></td>
										<td><?php echo $this->Number->currency($projectTimesheet['ProjectTimesheet']['total_cost'],'INR. ')?></td>
									</tr>
								<?php 
									$mandays = $mandays + $projectTimesheet['ProjectTimesheet']['total'];
									$mandaycost = $mandaycost + $projectTimesheet['ProjectTimesheet']['total_cost'];
								} ?>
								<tr class="danger">
									<th colspan="5">Total</th>
									<th><?php echo $mandays?></th>
									<th><?php echo $this->Number->currency($mandaycost,'INR. ')?></th>
								</tr>
							</table>
							
						<?php } ?>
						<?php echo $this->Html->link('Add Timesheet Details',array('controller'=>'project_timesheets','action'=>'project_timesheet_ajax'),array('class'=>'btn btn-xl btn-warning pull-right'));?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<h4>Other Estimated Costs</h4>
						<table class="table table-responsive table-condensed table-bordered">
							<tr class="warning">
								<th>Milestone</th>
								<th>Cost Category</th>
								<th>Cost</th>
								<th>Details</th>								
							</tr>
							<?php 
							$subT = 0;
							
							foreach ($projectEstimates as $head) { ?>
								<tr class="warning">
									<td><?php echo $head['Milestone']['title']?></td>
									<td><?php echo $head['CostCategory']['name']?></td>
									<td><?php echo $this->Number->currency($head['ProjectEstimate']['cost'],'INR. ')?></td>
									<td><?php echo $head['ProjectEstimate']['details']?></td>
								</tr>
							<?php 
							$subT = $subT + $head['ProjectEstimate']['cost'];
						} 
						$total = $total + $subT;
						?>
							<tr class="warning">
								<th colspan="4" class="text-right"><h4>Total : <?php echo $this->Number->currency($subT,'INR. ');?></h4></th>
							</tr>
						</table>
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
<div class="row">	
	<div class="col-md-12">
		<br /><?php echo $this->Html->link('Add Customer PO',array('controller'=>'purchase_orders','action'=>'lists',
		'project_id'=>$project['Project']['id'],
		'customer_id'=>$project['Project']['customer_id'],
		'type'=>0,
	),array('class'=>'btn btn-xl btn-success pull-right'));?>		
	</div>
</div>

<?php if($project_details['PurchaseOrder']['out']) { ?>
	<h4>Outbound POs <small>(To Vendors/supplers etc)</small></h4>
<div class="row">
	<div class="col-md-12">
		<table class="table table-responsive table-bordered table-condensed">
			<tr>
				<th>Supplier/Vendor</th>
				<th>Cost Category</th>
				<th>PO#</th>
				<th>Title</th>
				<th>Order Date</th>
				<th>Total</th>
				<th>Action</th>
			</tr>
			<?php foreach ($project_details['PurchaseOrder']['out'] as $purchaseOrder) { 
				// if($purchaseOrder['PurchaseOrder']['type'] == 1)$class = 'success'; else $class = 'warning';
				$final[$costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']]] = $costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']] + $purchaseOrder['PurchaseOrder']['po_total'];
			?>
				<tr class="danger">
					<td><?php echo $suppliers[$purchaseOrder['PurchaseOrder']['supplier_registration_id']]?></td>
					<td><?php echo $costCategories[$purchaseOrder['PurchaseOrder']['cost_category_id']]?></td>
					<td><?php echo $purchaseOrder['PurchaseOrder']['purchase_order_number']?></td>
					<td><?php echo $purchaseOrder['PurchaseOrder']['title']?></td>
					<td><?php echo $purchaseOrder['PurchaseOrder']['order_date']?></td>
					<td><?php echo $this->Number->currency($purchaseOrder['PurchaseOrder']['po_total'],'INR. ')?></td>
					<td><?php echo $this->Html->Link('View',array('controller'=>'purchase_orders','action'=>'view',$purchaseOrder['PurchaseOrder']['id']),array('class'=>'btn btn-xs btn-default pull-right','target'=>'_blank'));?></td>
				</tr>
			<?php 
			$pototal = $pototal + $purchaseOrder['PurchaseOrder']['po_total'];
		} ?>
		</table>		
	</div>
</div>	
<?php } ?>
<div class="col-md-12"><br /><?php echo $this->Html->link('Add Vendor PO',array('controller'=>'purchase_orders','action'=>'lists',
		'project_id'=>$project['Project']['id'],
		// 'customer_id'=>$project['Project']['customer_id'],
		'type'=>1,
	),array('class'=>'btn btn-xl btn-danger pull-right'));?></div>
</div>


<div class="col-md-6">		
	<?php echo $this->element('projecttimeline',array('project_details'=>$project_details,'project_id'=>$this->request->params['pass'][0]));?>
</div>
<div class="clear-fix"></div>
<div class="col-md-12" style="padding: 0px">
	<div class="col-md-6"><h2>Project Summary</h2>
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
	<div class="col-md-6">
		<?php
			foreach ($final as $key => $value) {
				$flabels[] = $key;
				$fdata[] = $value;
				$fcolors[] = '#'.str_pad(dechex(rand(0x000000, 0xFFFFFF)), 6, 0, STR_PAD_LEFT);
			}

		?>
		<ul class="list-group">
			<li class="list-group-item"><h3 class="text-center">Costwise Breakup (Actual)</h3></li>
			<li class="list-group-item">
				<div style="width:100%"><canvas id="fcost-wise"></canvas></div>

					<script>
						var config6 = {
							type: 'pie',
							data: {
								datasets: [{
									data: <?php echo json_encode($fdata,JSON_NUMERIC_CHECK);?>,
									backgroundColor:<?php echo json_encode($fcolors);?>,
									label: 'Dataset 1'
								}],							
								labels: <?php echo json_encode($flabels);?>
							},
							options: {
								responsive: true,
								legend: {
									fullWidth : true,
									display: true,
									position: 'bottom',
									labels: {
										// fontColor: 'rgb(255, 99, 132)'
									}
								},
							}
						};

						// window.onload = function() {
						// 	var ctx6 = document.getElementById('fcost-wise').getContext('2d');
						// 	window.myPie6 = new Chart(ctx6, config6);
						// };

						
					</script>
				</li>
			</ul>
		</div>
</div>
<div class="col-md-12"><?php echo $this->element('projectgraphs');?></div>
<div class="">
	
</div>
<div class="">
<div class="col-md-12">
	<table class="table table-responsive table-bordered table-condensed">
	<tr>
		<th colspan="2">Milestones</th>
		<th>Sequence</th>
		<th>Estimated Cost</th>
		<th>From</th>
		<th>To</th>
		<th>Requirements</th>
		<th>Manpower</th>
		<th>Current Status</th>
		<th>Action</th>
	</tr>
	<?php
	// Configure::write('debug',1);
	// debug($project_details);
		unset($project_details['PurchaseOrder']);
		foreach ($project_details as $project_detail) { 
			$cost = 0;
			$manpower = 0;
			?>
			<tr class="m-div">
				<td colspan="8"><h4><?php echo $project_detail['Milestone']['title'];?> <small><?php echo $this->Number->currency($project_detail['Milestone']['estimated_cost'],'INR. ');?></small></h4></td>
				<td class="text-center"><h4><?php echo ($project_detail['Milestone']['current_status']?'Completed' : 'On-going');?></h4></td>
				<td class="text-center">
						<div class="btn-group">
							<?php 
							echo $this->Html->link('View','#',
								// array('controller'=>'milestones','action'=>'view',$project_detail['Milestone']['id']),
								// array('class'=>'btn btn-xs btn-default')
								array('class'=>'btn btn-default btn-xs','onclick'=>'openmodel("milestones","view","'.$project_detail['Milestone']['id'].'",null,null,null)')
							);
							echo $this->Html->link('Edit','#',
								// array('controller'=>'milestones','action'=>'edit',$project_detail['Milestone']['id']),
								// array('class'=>'btn btn-xs btn-warning')
								array('class'=>'btn btn-warning btn-xs','onclick'=>'openmodel("milestones","edit","'.$project_detail['Milestone']['id'].'","'.$project_id.'","'.$project_detail['Milestone']['id'].'")')
							);
							?>
						</div>
					</td>
			</tr>
			<?php foreach ($project_detail['Milestone']['ProjectActivity'] as $activity) { ?>
			<?php 
				$cost = $cost + $activity['ProjectActivity']['estimated_cost'];
				//$manpower = 0;
			?>
				<tr>
					<td>Activities:</td>
					<td><strong><?php echo $activity['ProjectActivity']['title'];?></strong></td>
					<td><?php echo $activity['ProjectActivity']['sequence'];?></td>
					<td class="text-right"><?php echo $this->Number->currency($activity['ProjectActivity']['estimated_cost'],'INR. ');?></td>
					<td><?php echo $activity['ProjectActivity']['start_date'];?></td>
					<td><?php echo $activity['ProjectActivity']['end_date'];?></td>
					<td></td>
					<td></td>
					<td class="text-center"><?php echo ($activity['ProjectActivity']['current_status']?'Completed' : 'On-going');?></td>
					<td class="text-center">
						<div class="btn-group">
							<?php 
							echo $this->Html->link('View','#',
								// array('controller'=>'project_activities','action'=>'edit',$activity['ProjectActivity']['id']),
								// array('class'=>'btn btn-xs btn-warning')
								array('class'=>'btn btn-default btn-xs','onclick'=>'openmodel("project_activities","view","'.$activity['ProjectActivity']['id'].'",null,null,null)')
							);
							echo $this->Html->link('Edit','#',
								// array('controller'=>'project_activities','action'=>'edit',$activity['ProjectActivity']['id']),
								// array('class'=>'btn btn-xs btn-warning')
								array('class'=>'btn btn-warning btn-xs','onclick'=>'openmodel("project_activities","edit","'.$activity['ProjectActivity']['id'].'",null,null,null)')
							);
							?>
						</div>
					</td>
				</tr>
				<?php 
				//debug($activity['ProjectActivityRequirement']);
				foreach ($activity['ProjectActivityRequirement'] as $requirement) { 
					$manpower = $manpower + $requirement['ProjectActivityRequirement']['manpower'];
					?>
				<tr>
					<td colspan="5"></td>
					<td class="active"><span  class="pull-right">Requirements:</span></td>
					<td class="active"><?php echo $requirement['ProjectActivityRequirement']['title'];?></td>
					<td class="active text-center"><span class="default badge btn-warning"><?php echo $requirement['ProjectActivityRequirement']['manpower'];?></span></td>
					<td></td>
					<td class="text-center">
						<div class="btn-group">
							<?php 
							echo $this->Html->link('View',array('controller'=>'project_activity_requirements','action'=>'view',$requirement['ProjectActivityRequirement']['id']),array('class'=>'btn btn-xs btn-default'));
							echo $this->Html->link('Edit',array('controller'=>'project_activity_requirements','action'=>'edit',$requirement['ProjectActivityRequirement']['id']),array('class'=>'btn btn-xs btn-warning'));
							?>
						</div>
					</td>
				</tr>
			<?php } ?>				
			<?php } ?>
			<tr class="active">
					<td><strong>Sub-total</strong></td>
					<td></td>
					<td></td>
					<td class="text-right"><strong><?php echo $this->Number->currency($cost,'INR. '); ?></strong></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="text-center"><span class="default badge btn-warning"><strong><?php echo $manpower; ?></strong></span></td>
					<td></td>
					<td></td>
				</tr>
				<!-- <tr>
					<td colspan="10"></td>				
				</tr> -->
				
		<?php } ?>
</table>

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
            <div class="modal-footer hide">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>

  <script type="text/javascript">
    function openmodel(controller,action,id,project_id,milestone_id,project_activity_id){     
      $(".modal-body").load("<?php echo Router::url('/', true); ?>"+controller+"/"+action+"/project_id:"+project_id+"/milestone_id:"+milestone_id+"/project_activity_id:"+project_activity_id+"/"+id);
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
  

<?php echo $this->element('upload-edit', array('usersId' => $project['Project']['created_by'], 'recordId' => $project['Project']['id'], 'showUpload'=>'no')); ?>

</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projects_ajax')));?>

<?php echo $this->Js->get('#edit');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'edit',$project['Project']['id'] ,'ajax'),array('async' => true, 'update' => '#projects_ajax')));?>


<?php echo $this->Js->writeBuffer();?>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
