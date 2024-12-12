
<script>
	function getVals(){
		
	var checkedValue = null;
	$("#recs_selected").val(null);
	var inputElements = document.getElementsByTagName('input');
	
	for(var i=0; inputElements[i]; ++i){
		
	      if(inputElements[i].className==="rec_ids" && 
		 inputElements[i].checked){
		   $("#recs_selected").val($("#recs_selected").val() + '+' + inputElements[i].value);
		   
	      }
	}
	}
</script>


<div  id="incidentAffectedPersonals_ajax">
<?php echo $this->Session->flash();?>	
	<div class="incidentAffectedPersonals ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('IncidentAffectedPersonal',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#incidentAffectedPersonals_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('Incident Affected Personals'); ?>
					<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
					<?php echo $this->Html->link(__('Add'), array('action' => 'add'),array('id'=>'addrecord','class'=>'label btn-primary')); ?>
					<?php echo $this->Html->link(__('Export'), '#export',array('class'=>'label btn-warning','data-toggle'=>'modal','onClick'=>'getVals()')); ?>
					<?php echo $this->Html->link('', '#advanced_search',array('class'=>'fa fa-search h4-title','data-toggle'=>'modal')); ?>
					<?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
					
					</h4>
				    
				</div>
			</div>
		</div>
	
		<div class="table-responsive">
		<?php echo $this->Form->create(array('class'=>'no-padding no-margin no-background'));?>				
			<table cellpadding="0" cellspacing="0" class="table table-bordered table table-striped table-hover">
				<tr>
					<th></th>
					<th></th>
					
				<th><?php echo $this->Paginator->sort('sr_no'); ?></th>
				<th><?php echo $this->Paginator->sort('incident_id'); ?></th>
				<th><?php echo $this->Paginator->sort('person_type'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('address'); ?></th>
				<th><?php echo $this->Paginator->sort('phone'); ?></th>
				<th><?php echo $this->Paginator->sort('department_id'); ?></th>
				<th><?php echo $this->Paginator->sort('designation_id'); ?></th>
				<th><?php echo $this->Paginator->sort('age'); ?></th>
				<th><?php echo $this->Paginator->sort('gender'); ?></th>
				<th><?php echo $this->Paginator->sort('first_aid_provided'); ?></th>
				<th><?php echo $this->Paginator->sort('first_aid_details'); ?></th>
				<th><?php echo $this->Paginator->sort('first_aid_provided_by'); ?></th>
				<th><?php echo $this->Paginator->sort('follow_up_action_taken'); ?></th>
				<th><?php echo $this->Paginator->sort('other'); ?></th>
				<th><?php echo $this->Paginator->sort('illhealth_reported'); ?></th>
				<th><?php echo $this->Paginator->sort('normal_work_affected'); ?></th>
				<th><?php echo $this->Paginator->sort('number_of_work_affected_dates'); ?></th>
				<th><?php echo $this->Paginator->sort('incident_investigator_id'); ?></th>
				<th><?php echo $this->Paginator->sort('date_of_interview'); ?></th>
				<th><?php echo $this->Paginator->sort('investigation_interview_findings'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('record_status'); ?></th>
				<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				<th><?php echo $this->Paginator->sort('company_id'); ?></th>
				
				</tr>
				<?php if($incidentAffectedPersonals){ ?>
<?php foreach ($incidentAffectedPersonals as $incidentAffectedPersonal): ?>
	<tr class="on_page_src">
                    <td width="15"><?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$incidentAffectedPersonal['IncidentAffectedPersonal']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></td>		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidance'), array('action' => 'view', $incidentAffectedPersonal['IncidentAffectedPersonal']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $incidentAffectedPersonal['IncidentAffectedPersonal']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $incidentAffectedPersonal['IncidentAffectedPersonal']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $incidentAffectedPersonal['IncidentAffectedPersonal']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $incidentAffectedPersonal['IncidentAffectedPersonal']['id']),array('class'=>''), __('Are you sure ?', $incidentAffectedPersonal['IncidentAffectedPersonal']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['sr_no']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Incident']['title'], array('controller' => 'incidents', 'action' => 'view', $incidentAffectedPersonal['Incident']['id'])); ?>
		</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['person_type']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $incidentAffectedPersonal['Employee']['id'])); ?>
		</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['name']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['address']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['phone']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Department']['name'], array('controller' => 'departments', 'action' => 'view', $incidentAffectedPersonal['Department']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Designation']['name'], array('controller' => 'designations', 'action' => 'view', $incidentAffectedPersonal['Designation']['id'])); ?>
		</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['age']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['gender']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_provided']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_details']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['first_aid_provided_by']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['follow_up_action_taken']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['other']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['illhealth_reported']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['normal_work_affected']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['number_of_work_affected_dates']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['IncidentInvestigator']['name'], array('controller' => 'incident_investigators', 'action' => 'view', $incidentAffectedPersonal['IncidentInvestigator']['id'])); ?>
		</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['date_of_interview']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['investigation_interview_findings']); ?>&nbsp;</td>

		<td width="60">
			<?php if($incidentAffectedPersonal['IncidentAffectedPersonal']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($incidentAffectedPersonal['IncidentAffectedPersonal']['status_user_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $incidentAffectedPersonal['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $incidentAffectedPersonal['DepartmentIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentAffectedPersonal['ApprovedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $incidentAffectedPersonal['PreparedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($incidentAffectedPersonal['Company']['name'], array('controller' => 'companies', 'action' => 'view', $incidentAffectedPersonal['Company']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=38>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#incidentAffectedPersonals_ajax',
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#incidentAffectedPersonals_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#incidentAffectedPersonals_ajax')));?>

<?php echo $this->Js->writeBuffer();?>


		<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Export Data</h4>
		</div>
<div class="modal-body">
<?php echo $this->Form->create('incidentAffectedPersonals',array('action'=>'report','target'=>'_blank','class'=>'no-padding no-margin no-background zero-height'));?>
<?php echo $this->Form->hidden('rec_selected',array('id'=>'recs_selected'));?>
<?php echo $this->Form->submit('Export selcted records in pdf format',array('div'=>false,'class'=>'btn btn-link'));?>
<?php echo $this->Form->end(); ?>
</div>
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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","person_type"=>"Person Type","name"=>"Name","address"=>"Address","phone"=>"Phone","age"=>"Age","gender"=>"Gender","first_aid_provided"=>"First Aid Provided","first_aid_details"=>"First Aid Details","first_aid_provided_by"=>"First Aid Provided By","follow_up_action_taken"=>"Follow Up Action Taken","other"=>"Other","illhealth_reported"=>"Illhealth Reported","normal_work_affected"=>"Normal Work Affected","number_of_work_affected_dates"=>"Number Of Work Affected Dates","date_of_interview"=>"Date Of Interview","investigation_interview_findings"=>"Investigation Interview Findings","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
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
