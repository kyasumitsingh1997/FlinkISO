
<div  id="employeeKras_ajax">
<?php echo $this->Session->flash();?>	
	<div class="employeeKras ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('EmployeeKra',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","title"=>"Title","description"=>"Description","target"=>"Target","target_achieved"=>"Target Achieved","final_rating"=>"Final Rating","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#employeeKras_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('Employee Kras'); ?>
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
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('description'); ?></th>
				<th><?php echo $this->Paginator->sort('target'); ?></th>
				<th><?php echo $this->Paginator->sort('target_achieved'); ?></th>
				<th><?php echo $this->Paginator->sort('final_rating'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('record_status'); ?></th>
				<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				<th><?php echo $this->Paginator->sort('division_id'); ?></th>
				<th><?php echo $this->Paginator->sort('company_id'); ?></th>
				
				</tr>
				<?php if($employeeKras){ ?>
<?php foreach ($employeeKras as $employeeKra): ?>
	<tr>
		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View'), array('action' => 'view', $employeeKra['EmployeeKra']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $employeeKra['EmployeeKra']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $employeeKra['EmployeeKra']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $employeeKra['EmployeeKra']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Upload Evedance'), '#uploadevidance',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $employeeKra['EmployeeKra']['id']),array('class'=>''), __('Are you sure ?', $employeeKra['EmployeeKra']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($employeeKra['EmployeeKra']['sr_no']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($employeeKra['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $employeeKra['Employee']['id'])); ?>
		</td>
		<td><?php echo h($employeeKra['EmployeeKra']['title']); ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['description']); ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['target']); ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['target_achieved']); ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['final_rating']); ?>&nbsp;</td>

		<td width="60">
			<?php if($employeeKra['EmployeeKra']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($employeeKra['EmployeeKra']['status_user_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($employeeKra['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $employeeKra['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($employeeKra['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $employeeKra['DepartmentIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($employeeKra['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $employeeKra['ApprovedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($employeeKra['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $employeeKra['PreparedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($employeeKra['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $employeeKra['Division']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($employeeKra['Company']['name'], array('controller' => 'companies', 'action' => 'view', $employeeKra['Company']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=24>No results found</td></tr>
<?php } ?>
			</table>
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#employeeKras_ajax',
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#employeeKras_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#employeeKras_ajax')));?>

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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","description"=>"Description","target"=>"Target","target_achieved"=>"Target Achieved","final_rating"=>"Final Rating","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
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