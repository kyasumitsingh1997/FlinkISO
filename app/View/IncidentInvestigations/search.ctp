
<div  id="incidentInvestigations_ajax">
<?php echo $this->Session->flash();?>	
	<div class="incidentInvestigations ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('IncidentInvestigation',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#incidentInvestigations_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('Incident Investigations'); ?>
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
				<th><?php echo $this->Paginator->sort('incident_id'); ?></th>
				<th><?php echo $this->Paginator->sort('incident_affected_personal_id'); ?></th>
				<th><?php echo $this->Paginator->sort('incident_witness_id'); ?></th>
				<th><?php echo $this->Paginator->sort('reference_number'); ?></th>
				<th><?php echo $this->Paginator->sort('incident_investigator_id'); ?></th>
				<th><?php echo $this->Paginator->sort('investigation_date_from'); ?></th>
				<th><?php echo $this->Paginator->sort('investigation_date_to'); ?></th>
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('control_measures_currently_in_place'); ?></th>
				<th><?php echo $this->Paginator->sort('summery_of_findings'); ?></th>
				<th><?php echo $this->Paginator->sort('reason_for_incidence'); ?></th>
				<th><?php echo $this->Paginator->sort('immediate_action_taken'); ?></th>
				<th><?php echo $this->Paginator->sort('risk_assessment'); ?></th>
				<th><?php echo $this->Paginator->sort('investigation_reviewd_by'); ?></th>
				<th><?php echo $this->Paginator->sort('action_taken'); ?></th>
				<th><?php echo $this->Paginator->sort('corrective_preventive_action_id'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('record_status'); ?></th>
				<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				<th><?php echo $this->Paginator->sort('company_id'); ?></th>
				
				</tr>
				<?php if($incidentInvestigations){ ?>
<?php foreach ($incidentInvestigations as $incidentInvestigation): ?>
	<tr>
		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View'), array('action' => 'view', $incidentInvestigation['IncidentInvestigation']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $incidentInvestigation['IncidentInvestigation']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $incidentInvestigation['IncidentInvestigation']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $incidentInvestigation['IncidentInvestigation']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Upload Evedance'), '#uploadevidance',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $incidentInvestigation['IncidentInvestigation']['id']),array('class'=>''), __('Are you sure ?', $incidentInvestigation['IncidentInvestigation']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($incidentInvestigation['IncidentInvestigation']['sr_no']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentInvestigation['Incident']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['IncidentAffectedPersonal']['name'], array('controller' => 'incident_affected_personals', 'action' => 'view', $incidentInvestigation['IncidentAffectedPersonal']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['IncidentWitness']['name'], array('controller' => 'incident_witnesses', 'action' => 'view', $incidentInvestigation['IncidentWitness']['id'])); ?>
		</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['reference_number']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['IncidentInvestigator']['name'], array('controller' => 'incident_investigators', 'action' => 'view', $incidentInvestigation['IncidentInvestigator']['id'])); ?>
		</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_date_from']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_date_to']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['title']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['control_measures_currently_in_place']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['summery_of_findings']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['reason_for_incidence']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['immediate_action_taken']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['risk_assessment']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['investigation_reviewd_by']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['action_taken']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['CorrectivePreventiveAction']['name'], array('controller' => 'corrective_preventive_actions', 'action' => 'view', $incidentInvestigation['CorrectivePreventiveAction']['id'])); ?>
		</td>

		<td width="60">
			<?php if($incidentInvestigation['IncidentInvestigation']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($incidentInvestigation['IncidentInvestigation']['status_user_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $incidentInvestigation['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $incidentInvestigation['DepartmentIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentInvestigation['ApprovedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentInvestigation['PreparedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentInvestigation['Company']['name'], array('controller' => 'companies', 'action' => 'view', $incidentInvestigation['Company']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=33>No results found</td></tr>
<?php } ?>
			</table>
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#incidentInvestigations_ajax',
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentInvestigations_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#incidentInvestigations_ajax')));?>

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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","reference_number"=>"Reference Number","investigation_date_from"=>"Investigation Date From","investigation_date_to"=>"Investigation Date To","title"=>"Title","control_measures_currently_in_place"=>"Control Measures Currently In Place","summery_of_findings"=>"Summery Of Findings","reason_for_incidence"=>"Reason For Incidence","immediate_action_taken"=>"Immediate Action Taken","risk_assessment"=>"Risk Assessment","investigation_reviewd_by"=>"Investigation Reviewd By","action_taken"=>"Action Taken","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
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