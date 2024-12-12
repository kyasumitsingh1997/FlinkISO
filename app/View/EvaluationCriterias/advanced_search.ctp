
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


<div  id="evaluationCriterias_ajax">
<?php echo $this->Session->flash();?>	
	<div class="evaluationCriterias ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('EvaluationCriteria',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#evaluationCriterias_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('EvaluationCriterias'); ?>
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
				<th><?php echo $this->Paginator->sort('name'); ?></th>
				<th><?php echo $this->Paginator->sort('aspect_category_id'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_1'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_1_value'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_2'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_2_value'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_3'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_3_value'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_4'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_4_value'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_5'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_5_value'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_6'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_6_value'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_7'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_7_value'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_8'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_8_value'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_9'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_9_value'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_10'); ?></th>
				<th><?php echo $this->Paginator->sort('scale_10_value'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('record_status'); ?></th>
				<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				
				</tr>
				<?php if($evaluationCriterias){ ?>
<?php foreach ($evaluationCriterias as $aspect): ?>
	<tr>
<td width="15"><?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$aspect['EvaluationCriteria']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></td>		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidance'), array('action' => 'view', $aspect['EvaluationCriteria']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $aspect['EvaluationCriteria']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $aspect['EvaluationCriteria']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $aspect['EvaluationCriteria']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $aspect['EvaluationCriteria']['id']),array('class'=>''), __('Are you sure ?', $aspect['EvaluationCriteria']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($aspect['EvaluationCriteria']['sr_no']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($aspect['EvaluationCriteriaCategory']['name'], array('controller' => 'aspect_categories', 'action' => 'view', $aspect['EvaluationCriteriaCategory']['id'])); ?>
		</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_1']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_1_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_2']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_2_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_3']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_3_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_4']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_4_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_5']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_5_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_6']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_6_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_7']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_7_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_8']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_8_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_9']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_9_value']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_10']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['scale_10_value']); ?>&nbsp;</td>

		<td width="60">
			<?php if($aspect['EvaluationCriteria']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($aspect['EvaluationCriteria']['status_user_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($aspect['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $aspect['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($aspect['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $aspect['DepartmentIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($aspect['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $aspect['ApprovedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($aspect['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $aspect['PreparedBy']['id'])); ?>
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
			'update' => '#evaluationCriterias_ajax',
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#evaluationCriterias_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#evaluationCriterias_ajax')));?>

<?php echo $this->Js->writeBuffer();?>


		<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Export Data</h4>
		</div>
<div class="modal-body">
<?php echo $this->Form->create('EvaluationCriterias',array('action'=>'report','target'=>'_blank','class'=>'no-padding no-margin no-background zero-height'));?>
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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","scale_1"=>"Scale 1","scale_1_value"=>"Scale 1 Value","scale_2"=>"Scale 2","scale_2_value"=>"Scale 2 Value","scale_3"=>"Scale 3","scale_3_value"=>"Scale 3 Value","scale_4"=>"Scale 4","scale_4_value"=>"Scale 4 Value","scale_5"=>"Scale 5","scale_5_value"=>"Scale 5 Value","scale_6"=>"Scale 6","scale_6_value"=>"Scale 6 Value","scale_7"=>"Scale 7","scale_7_value"=>"Scale 7 Value","scale_8"=>"Scale 8","scale_8_value"=>"Scale 8 Value","scale_9"=>"Scale 9","scale_9_value"=>"Scale 9 Value","scale_10"=>"Scale 10","scale_10_value"=>"Scale 10 Value","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
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