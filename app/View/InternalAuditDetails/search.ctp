
<div  id="internalAuditDetails_ajax">
<?php echo $this->Session->flash();?>	
	<div class="internalAuditDetails ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('InternalAuditDetail',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#internalAuditDetails_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('Internal Audit Details'); ?>
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
				<th><?php echo $this->Paginator->sort('internal_audit_id'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('nc_found'); ?></th>
				<th><?php echo $this->Paginator->sort('question'); ?></th>
				<th><?php echo $this->Paginator->sort('findings'); ?></th>
				<th><?php echo $this->Paginator->sort('opportunities_for_improvement'); ?></th>
				<th><?php echo $this->Paginator->sort('clause_number'); ?></th>
				<th><?php echo $this->Paginator->sort('current_status'); ?></th>
				<th><?php echo $this->Paginator->sort('comments'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('record_status'); ?></th>
				<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('division_id'); ?></th>
				<th><?php echo $this->Paginator->sort('company_id'); ?></th>
				
				</tr>
				<?php if($internalAuditDetails){ ?>
<?php foreach ($internalAuditDetails as $internalAuditDetail): ?>
	<tr>
		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View'), array('action' => 'view', $internalAuditDetail['InternalAuditDetail']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $internalAuditDetail['InternalAuditDetail']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $internalAuditDetail['InternalAuditDetail']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $internalAuditDetail['InternalAuditDetail']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Upload Evedance'), '#uploadevidance',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $internalAuditDetail['InternalAuditDetail']['id']),array('class'=>''), __('Are you sure ?', $internalAuditDetail['InternalAuditDetail']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($internalAuditDetail['InternalAuditDetail']['sr_no']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($internalAuditDetail['InternalAudit']['start_time'], array('controller' => 'internal_audits', 'action' => 'view', $internalAuditDetail['InternalAudit']['id'])); ?>
		</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['employee_id']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['nc_found']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['question']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['findings']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['opportunities_for_improvement']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['clause_number']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['current_status']); ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['comments']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($internalAuditDetail['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $internalAuditDetail['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($internalAuditDetail['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $internalAuditDetail['DepartmentIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($internalAuditDetail['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $internalAuditDetail['ApprovedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($internalAuditDetail['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $internalAuditDetail['PreparedBy']['id'])); ?>
		</td>

		<td width="60">
			<?php if($internalAuditDetail['InternalAuditDetail']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['record_status']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($internalAuditDetail['StatusUserId']['name'], array('controller' => 'users', 'action' => 'view', $internalAuditDetail['StatusUserId']['id'])); ?>
		</td>
		<td><?php echo h($internalAuditDetail['InternalAuditDetail']['division_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($internalAuditDetail['Company']['name'], array('controller' => 'companies', 'action' => 'view', $internalAuditDetail['Company']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=27>No results found</td></tr>
<?php } ?>
			</table>
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#internalAuditDetails_ajax',
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#internalAuditDetails_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#internalAuditDetails_ajax')));?>

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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
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