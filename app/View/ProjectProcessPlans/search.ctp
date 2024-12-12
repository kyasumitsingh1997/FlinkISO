
<div  id="projectProcessPlans_ajax">
<?php echo $this->Session->flash();?>	
	<div class="projectProcessPlans ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('ProjectProcessPlan',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","process"=>"Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","prepared_by"=>"Prepared By","approved_by"=>"Approved By")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#projectProcessPlans_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('Project Process Plans'); ?>
					<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
					<?php echo $this->Html->link(__('Add'), array('action' => 'add'),array('id'=>'addrecord','class'=>'label btn-primary')); ?>
					<?php echo $this->Html->link(__('Export'), '#export',array('class'=>'label btn-warning','data-toggle'=>'modal')); ?>
					<?php echo $this->Html->link(__('Import'), '#import',array('class'=>'label btn-info','data-toggle'=>'modal')); ?>
					<?php echo $this->Html->link('', '#advanced_search',array('class'=>'fa fa-search h4-title','data-toggle'=>'modal')); ?>
					<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
					
					</h4>
				    
				</div>
			</div>
		</div>
	
		<div class="table-responsive">
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th></th>
					
				<th><?php echo $this->Paginator->sort('sr_no'); ?></th>
				<th><?php echo $this->Paginator->sort('project_id'); ?></th>
				<th><?php echo $this->Paginator->sort('milestone_id'); ?></th>
				<th><?php echo $this->Paginator->sort('project_overall_plan_id'); ?></th>
				<th><?php echo $this->Paginator->sort('process'); ?></th>
				<th><?php echo $this->Paginator->sort('estimated_units'); ?></th>
				<th><?php echo $this->Paginator->sort('overall_metrics'); ?></th>
				<th><?php echo $this->Paginator->sort('start_date'); ?></th>
				<th><?php echo $this->Paginator->sort('end_date'); ?></th>
				<th><?php echo $this->Paginator->sort('estimated_resource'); ?></th>
				<th><?php echo $this->Paginator->sort('estimated_manhours'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('record_status'); ?></th>
				<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				
				</tr>
				<?php if($projectProcessPlans){ ?>
<?php foreach ($projectProcessPlans as $projectProcessPlan): ?>
	<tr>
		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View'), array('action' => 'view', $projectProcessPlan['ProjectProcessPlan']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectProcessPlan['ProjectProcessPlan']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectProcessPlan['ProjectProcessPlan']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $projectProcessPlan['ProjectProcessPlan']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Upload Evedance'), '#uploadevidance',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $projectProcessPlan['ProjectProcessPlan']['id']),array('class'=>''), __('Are you sure ?', $projectProcessPlan['ProjectProcessPlan']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($projectProcessPlan['ProjectProcessPlan']['sr_no']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectProcessPlan['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectProcessPlan['Milestone']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['ProjectOverallPlan']['id'], array('controller' => 'project_overall_plans', 'action' => 'view', $projectProcessPlan['ProjectOverallPlan']['id'])); ?>
		</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['process']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_units']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['overall_metrics']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['start_date']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['end_date']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_resource']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['estimated_manhours']); ?>&nbsp;</td>

		<td width="60">
			<?php if($projectProcessPlan['ProjectProcessPlan']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($projectProcessPlan['ProjectProcessPlan']['status_user_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $projectProcessPlan['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $projectProcessPlan['DepartmentIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectProcessPlan['PreparedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectProcessPlan['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectProcessPlan['ApprovedBy']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=26>No results found</td></tr>
<?php } ?>
			</table>
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#projectProcessPlans_ajax',
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
	</div>
	</div>	

<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectProcessPlans_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#projectProcessPlans_ajax')));?>

<?php echo $this->Js->writeBuffer();?>


		<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Export Data</h4>
		</div>
<div class="modal-body"><?php echo $this->element('export'); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>

		<div class="modal fade" id="advanced_search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Select Date range</h4>
		</div>
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","process"=>"Process","estimated_units"=>"Estimated Units","overall_metrics"=>"Overall Metrics","start_date"=>"Start Date","end_date"=>"End Date","estimated_resource"=>"Estimated Resource","estimated_manhours"=>"Estimated Manhours","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>
		<div class="modal fade" id="makerchecker" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Send for Approval</h4>
		</div>
<div class="modal-body"><?php echo $this->element('makerchecker'); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>

		<div class="modal fade" id="uploadevidance" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Send for Approval</h4>
		</div>
<div class="modal-body"><?php echo $this->element('makerchecker'); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>


		<div class="modal fade" id="import" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Import from file (excel & csv formats only)</h4>
		</div>
<div class="modal-body"><?php echo $this->element('import'); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>

</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>