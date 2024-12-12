
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


<div  id="productionRejections_ajax">
<?php echo $this->Session->flash();?>	
	<div class="productionRejections ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('ProductionRejection',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#productionRejections_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('Production Rejections'); ?>
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
				<th><?php echo $this->Paginator->sort('production_id'); ?></th>
				<th><?php echo $this->Paginator->sort('product_id'); ?></th>
				<th><?php echo $this->Paginator->sort('production_inspection_template_id'); ?></th>
				<th><?php echo $this->Paginator->sort('total_quantity'); ?></th>
				<th><?php echo $this->Paginator->sort('sample_quantity'); ?></th>
				<th><?php echo $this->Paginator->sort('quality_check_date'); ?></th>
				<th><?php echo $this->Paginator->sort('start_sr_number'); ?></th>
				<th><?php echo $this->Paginator->sort('end_sr_number'); ?></th>
				<th><?php echo $this->Paginator->sort('number_of_rejections'); ?></th>
				<th><?php echo $this->Paginator->sort('employee_id'); ?></th>
				<th><?php echo $this->Paginator->sort('supplier_registration_id'); ?></th>
				<th><?php echo $this->Paginator->sort('customer_contact_id'); ?></th>
				<th><?php echo $this->Paginator->sort('publish'); ?></th>
				<th><?php echo $this->Paginator->sort('record_status'); ?></th>
				<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
				<th><?php echo $this->Paginator->sort('branchid'); ?></th>
				<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
				<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
				<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
				<th><?php echo $this->Paginator->sort('company_id'); ?></th>
				
				</tr>
				<?php if($productionRejections){ ?>
<?php foreach ($productionRejections as $productionRejection): ?>
	<tr>
<td width="15"><?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$productionRejection['ProductionRejection']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></td>		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><span class=" glyphicon glyphicon-wrench"></span></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidance'), array('action' => 'view', $productionRejection['ProductionRejection']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $productionRejection['ProductionRejection']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $productionRejection['ProductionRejection']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $productionRejection['ProductionRejection']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $productionRejection['ProductionRejection']['id']),array('class'=>''), __('Are you sure ?', $productionRejection['ProductionRejection']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($productionRejection['ProductionRejection']['sr_no']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['name']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($productionRejection['Production']['batch_number'], array('controller' => 'productions', 'action' => 'view', $productionRejection['Production']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['Product']['name'], array('controller' => 'products', 'action' => 'view', $productionRejection['Product']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['ProductionInspectionTemplate']['name'], array('controller' => 'production_inspection_templates', 'action' => 'view', $productionRejection['ProductionInspectionTemplate']['id'])); ?>
		</td>
		<td><?php echo h($productionRejection['ProductionRejection']['total_quantity']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['sample_quantity']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['quality_check_date']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['start_sr_number']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['end_sr_number']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['number_of_rejections']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($productionRejection['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $productionRejection['Employee']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['SupplierRegistration']['title'], array('controller' => 'supplier_registrations', 'action' => 'view', $productionRejection['SupplierRegistration']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['CustomerContact']['name'], array('controller' => 'customer_contacts', 'action' => 'view', $productionRejection['CustomerContact']['id'])); ?>
		</td>

		<td width="60">
			<?php if($productionRejection['ProductionRejection']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($productionRejection['ProductionRejection']['status_user_id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($productionRejection['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $productionRejection['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $productionRejection['DepartmentIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['ApprovedBy']['name'], array('controller' => 'employees', 'action' => 'view', $productionRejection['ApprovedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['PreparedBy']['name'], array('controller' => 'employees', 'action' => 'view', $productionRejection['PreparedBy']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($productionRejection['Company']['name'], array('controller' => 'companies', 'action' => 'view', $productionRejection['Company']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=30>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#productionRejections_ajax',
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#productionRejections_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#productionRejections_ajax')));?>

<?php echo $this->Js->writeBuffer();?>


		<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Export Data</h4>
		</div>
<div class="modal-body">
<?php echo $this->Form->create('productionRejections',array('action'=>'report','target'=>'_blank','class'=>'no-padding no-margin no-background zero-height'));?>
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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","total_quantity"=>"Total Quantity","sample_quantity"=>"Sample Quantity","quality_check_date"=>"Quality Check Date","start_sr_number"=>"Start Sr Number","end_sr_number"=>"End Sr Number","number_of_rejections"=>"Number Of Rejections","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
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