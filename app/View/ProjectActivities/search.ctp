
<div  id="projectActivities_ajax">
<?php echo $this->Session->flash();?>	
	<div class="projectActivities ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('ProjectActivity',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","sequence"=>"Sequence","current_status"=>"Current Status","users"=>"Users","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#projectActivities_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('Project Activities'); ?>
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
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('details'); ?></th>
				<th><?php echo $this->Paginator->sort('estimated_cost'); ?></th>
				<th><?php echo $this->Paginator->sort('start_date'); ?></th>
				<th><?php echo $this->Paginator->sort('end_date'); ?></th>
				<th><?php echo $this->Paginator->sort('sequence'); ?></th>
				<th><?php echo $this->Paginator->sort('current_status'); ?></th>
				<th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('users'); ?></th>
				<th><?php echo $this->Paginator->sort('user_session_id'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('record_status'); ?></th>
				<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				<th><?php echo $this->Paginator->sort('company_id'); ?></th>
				
				</tr>
				<?php if($projectActivities){ ?>
<?php foreach ($projectActivities as $projectActivity): ?>
	<tr>
		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View'), array('action' => 'view', $projectActivity['ProjectActivity']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $projectActivity['ProjectActivity']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $projectActivity['ProjectActivity']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $projectActivity['ProjectActivity']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Upload Evedance'), '#uploadevidance',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $projectActivity['ProjectActivity']['id']),array('class'=>''), __('Are you sure ?', $projectActivity['ProjectActivity']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($projectActivity['ProjectActivity']['sr_no']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectActivity['Project']['title'], array('controller' => 'projects', 'action' => 'view', $projectActivity['Project']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectActivity['Milestone']['title'], array('controller' => 'milestones', 'action' => 'view', $projectActivity['Milestone']['id'])); ?>
		</td>
		<td><?php echo h($projectActivity['ProjectActivity']['title']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['details']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['estimated_cost']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['start_date']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['end_date']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['sequence']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['current_status']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectActivity['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $projectActivity['Branch']['id'])); ?>
		</td>
		<td><?php echo h($projectActivity['ProjectActivity']['users']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectActivity['UserSession']['id'], array('controller' => 'user_sessions', 'action' => 'view', $projectActivity['UserSession']['id'])); ?>
		</td>

		<td width="60">
			<?php if($projectActivity['ProjectActivity']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($projectActivity['ProjectActivity']['status_user_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($projectActivity['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $projectActivity['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectActivity['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $projectActivity['DepartmentIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectActivity['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectActivity['ApprovedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectActivity['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $projectActivity['PreparedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($projectActivity['Company']['name'], array('controller' => 'companies', 'action' => 'view', $projectActivity['Company']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=28>No results found</td></tr>
<?php } ?>
			</table>
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#projectActivities_ajax',
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#projectActivities_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#projectActivities_ajax')));?>

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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","details"=>"Details","estimated_cost"=>"Estimated Cost","start_date"=>"Start Date","end_date"=>"End Date","sequence"=>"Sequence","current_status"=>"Current Status","users"=>"Users","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
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