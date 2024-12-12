
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


<div  id="invoices_ajax">
<?php echo $this->Session->flash();?>	
	<div class="invoices ">
		<div class="container">
			<div class="row">
				<div class="col-md-4 pull-right">
					<div class="input-group pull-left">
						<?php echo $this->Form->create('Invoice',array('role'=>'form','class'=>'no-padding no-margin','id'=>'search-form','action'=>'search')); ?>
  
						<?php echo $this->Form->input(__('search'),array('label'=>false,'placeholder'=>'search','role'=>'form','class'=>'form-control','action'=>'search')); ?>
  	    
							<div class="input-group-btn">
								<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Select Fields <span class="caret"></span></button>
								<ul class="dropdown-menu pull-right">
									<li class="search-dropdown"><?php echo $this->Form->input('search_field',array('multiple'=>'checkbox','options'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By")));?></li>		
									<li><?php echo $this->Js->submit('Submit',array('url'=>'search','div'=>false,'class'=>'btn btn-primary btn-success','update' => '#invoices_ajax','type'=>'data','method'=>'post')); ?>
;									<?php echo $this->Form->end(); ?></li>
								</ul>
							</div><!-- /btn-group -->
					</div><!-- /input-group -->
				</div><!-- /.col-lg-3 -->
			
				<div class="col-md-8">
					<h4><?php echo __('Invoices'); ?>
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
				<th><?php echo $this->Paginator->sort('purchase_order_id'); ?></th>
				<th><?php echo $this->Paginator->sort('invoice_number'); ?></th>
				<th><?php echo $this->Paginator->sort('work_order_number'); ?></th>
				<th><?php echo $this->Paginator->sort('customer_id'); ?></th>
				<th><?php echo $this->Paginator->sort('customer_contact_id'); ?></th>
				<th><?php echo $this->Paginator->sort('invoice_date'); ?></th>
				<th><?php echo $this->Paginator->sort('details'); ?></th>
				<th><?php echo $this->Paginator->sort('banking_details'); ?></th>
				<th><?php echo $this->Paginator->sort('subtotal'); ?></th>
				<th><?php echo $this->Paginator->sort('vat'); ?></th>
				<th><?php echo $this->Paginator->sort('sales_tax'); ?></th>
				<th><?php echo $this->Paginator->sort('discount'); ?></th>
				<th><?php echo $this->Paginator->sort('total'); ?></th>
				<th><?php echo $this->Paginator->sort('notes'); ?></th>
				<th><?php echo $this->Paginator->sort('invoice_due_date'); ?></th>
				<th><?php echo $this->Paginator->sort('send_to_customer'); ?></th>
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
				<?php if($invoices){ ?>
<?php foreach ($invoices as $invoice): ?>
	<tr>
<td width="15"><?php echo $this->Form->checkbox('rec_ids_'.$i,array('label'=>false,'value'=>$invoice['Invoice']['id'],'multiple'=>'checkbox','class'=>'rec_ids')); ?></td>		<td class=" actions">
		<div class="btn-group">
		<button type="button" data-toggle="dropdown" class="dropdown-toggle btn  btn-sm btn-default "><i class="fa fa-wrench" aria-hidden="true"></i></button>
			</button>
				<ul class="dropdown-menu" role="menu">
				<li><?php echo $this->Html->link(__('View / Upload Evidance'), array('action' => 'view', $invoice['Invoice']['id'])); ?></li>
				<li><?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $invoice['Invoice']['id'])); ?></li>
				<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $invoice['Invoice']['id']),array('class'=>''), __('Are you sure you want to delete this record ?', $invoice['Invoice']['id'])); ?></li>
				<li class="divider"></li>
				<li><?php echo $this->Html->link(__('Send for Approval'), '#makerchecker',array('data-toggle'=>'modal')); ?></li>
				<li><?php echo $this->Form->postLink(__('Email Record'), array('controller'=>'message','action' => 'add', $invoice['Invoice']['id']),array('class'=>''), __('Are you sure ?', $invoice['Invoice']['id'])); ?></li>
			</ul>
		</div>
		</td>
		<td width="50"><?php echo h($invoice['Invoice']['sr_no']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($invoice['PurchaseOrder']['name'], array('controller' => 'purchase_orders', 'action' => 'view', $invoice['PurchaseOrder']['id'])); ?>
		</td>
		<td><?php echo h($invoice['Invoice']['invoice_number']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['work_order_number']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($invoice['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $invoice['Customer']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($invoice['CustomerContact']['name'], array('controller' => 'customer_contacts', 'action' => 'view', $invoice['CustomerContact']['id'])); ?>
		</td>
		<td><?php echo h($invoice['Invoice']['invoice_date']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['details']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['banking_details']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['subtotal']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['vat']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['sales_tax']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['discount']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['total']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['notes']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['invoice_due_date']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['send_to_customer']); ?>&nbsp;</td>

		<td width="60">
			<?php if($invoice['Invoice']['publish'] == 1) { ?>
			<span class="fa fa-check"></span>
			<?php } else { ?>
			<span class="fa fa-ban"></span>
			<?php } ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['record_status']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($invoice['StatusUser']['name'], array('controller' => 'users', 'action' => 'view', $invoice['StatusUser']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($invoice['BranchIds']['name'], array('controller' => 'branches', 'action' => 'view', $invoice['BranchIds']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($invoice['DepartmentIds']['name'], array('controller' => 'departments', 'action' => 'view', $invoice['DepartmentIds']['id'])); ?>
		</td>
		<td><?php echo h($invoice['Invoice']['approved_by']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['prepared_by']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($invoice['Division']['name'], array('controller' => 'divisions', 'action' => 'view', $invoice['Division']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($invoice['Company']['name'], array('controller' => 'companies', 'action' => 'view', $invoice['Company']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
<?php }else{ ?>
	<tr><td colspan=34>No results found</td></tr>
<?php } ?>
			</table>
<?php echo $this->Form->end();?>			
		</div>
			<p>
			<?php
			echo $this->Paginator->options(array(
			'update' => '#invoices_ajax',
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#invoices_ajax')));?>

<?php echo $this->Js->get('#addrecord');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'add', 'ajax'),array('async' => true, 'update' => '#invoices_ajax')));?>

<?php echo $this->Js->writeBuffer();?>


		<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Export Data</h4>
		</div>
<div class="modal-body">
<?php echo $this->Form->create('invoices',array('action'=>'report','target'=>'_blank','class'=>'no-padding no-margin no-background zero-height'));?>
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
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
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