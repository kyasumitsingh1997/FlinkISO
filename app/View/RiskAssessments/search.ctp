
<div  id="riskAssessments_ajax">
<?php echo $this->Session->flash();?>	
	<div class="riskAssessments ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('RiskAssessment',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#riskAssessments_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('Risk Assessments'); ?>
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
				<th><?php echo $this->Paginator->sort('title'); ?></th>
				<th><?php echo $this->Paginator->sort('process_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branch_id'); ?></th>
				<th><?php echo $this->Paginator->sort('task'); ?></th>
				<th><?php echo $this->Paginator->sort('ra_date'); ?></th>
				<th><?php echo $this->Paginator->sort('ra_expert_1'); ?></th>
				<th><?php echo $this->Paginator->sort('ra_expert_2'); ?></th>
				<th><?php echo $this->Paginator->sort('management'); ?></th>
				<th><?php echo $this->Paginator->sort('technical_expert'); ?></th>
				<th><?php echo $this->Paginator->sort('risk_control_exprt'); ?></th>
				<th><?php echo $this->Paginator->sort('reference_number'); ?></th>
				<th><?php echo $this->Paginator->sort('hazard_type_id'); ?></th>
				<th><?php echo $this->Paginator->sort('hazard_source_id'); ?></th>
				<th><?php echo $this->Paginator->sort('what_could_happan'); ?></th>
				<th><?php echo $this->Paginator->sort('accident_type_id'); ?></th>
				<th><?php echo $this->Paginator->sort('severiry_type_id'); ?></th>
				<th><?php echo $this->Paginator->sort('existing_controls'); ?></th>
				<th><?php echo $this->Paginator->sort('likelihood'); ?></th>
				<th><?php echo $this->Paginator->sort('risk_rating_id'); ?></th>
				<th><?php echo $this->Paginator->sort('additional_control_needed'); ?></th>
				<th><?php echo $this->Paginator->sort('person_responsible'); ?></th>
				<th><?php echo $this->Paginator->sort('target_date'); ?></th>
				<th><?php echo $this->Paginator->sort('completions_date'); ?></th>
				<th><?php echo $this->Paginator->sort('process_notes'); ?></th>
				<th><?php echo $this->Paginator->sort('task_notes'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('record_status'); ?></th>
				<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				<th><?php echo $this->Paginator->sort('company_id'); ?></th>
				
				</tr>
				<?php if($riskAssessments){ ?>
<?php foreach ($riskAssessments as $riskAssessment): ?>
	<tr>
		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View'), array('action' => 'view', $riskAssessment['RiskAssessment']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $riskAssessment['RiskAssessment']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $riskAssessment['RiskAssessment']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $riskAssessment['RiskAssessment']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Upload Evedance'), '#uploadevidance',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $riskAssessment['RiskAssessment']['id']),array('class'=>''), __('Are you sure ?', $riskAssessment['RiskAssessment']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($riskAssessment['RiskAssessment']['sr_no']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['title']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['Process']['title'], array('controller' => 'processes', 'action' => 'view', $riskAssessment['Process']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $riskAssessment['Branch']['id'])); ?>
		</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['task']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['ra_date']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['ra_expert_1']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['ra_expert_2']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['management']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['technical_expert']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['risk_control_exprt']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['reference_number']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['HazardType']['name'], array('controller' => 'hazard_types', 'action' => 'view', $riskAssessment['HazardType']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['HazardSource']['name'], array('controller' => 'hazard_sources', 'action' => 'view', $riskAssessment['HazardSource']['id'])); ?>
		</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['what_could_happan']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['AccidentType']['name'], array('controller' => 'accident_types', 'action' => 'view', $riskAssessment['AccidentType']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['SeveriryType']['name'], array('controller' => 'severiry_types', 'action' => 'view', $riskAssessment['SeveriryType']['id'])); ?>
		</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['existing_controls']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['likelihood']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['risk_rating_id']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['additional_control_needed']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['person_responsible']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['target_date']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['completions_date']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['process_notes']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['task_notes']); ?>&nbsp;</td>

		<td width="60">
			<?php if($riskAssessment['RiskAssessment']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($riskAssessment['RiskAssessment']['status_user_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $riskAssessment['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $riskAssessment['DepartmentIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $riskAssessment['ApprovedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $riskAssessment['PreparedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($riskAssessment['Company']['name'], array('controller' => 'companies', 'action' => 'view', $riskAssessment['Company']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=42>No results found</td></tr>
<?php } ?>
			</table>
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#riskAssessments_ajax',
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#riskAssessments_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#riskAssessments_ajax')));?>

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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","task"=>"Task","ra_date"=>"Ra Date","ra_expert_1"=>"Ra Expert 1","ra_expert_2"=>"Ra Expert 2","management"=>"Management","technical_expert"=>"Technical Expert","risk_control_exprt"=>"Risk Control Exprt","reference_number"=>"Reference Number","what_could_happan"=>"What Could Happan","existing_controls"=>"Existing Controls","likelihood"=>"Likelihood","additional_control_needed"=>"Additional Control Needed","person_responsible"=>"Person Responsible","target_date"=>"Target Date","completions_date"=>"Completions Date","process_notes"=>"Process Notes","task_notes"=>"Task Notes","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
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