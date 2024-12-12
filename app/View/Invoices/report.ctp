<?php echo $this->Html->script(array('openwysiwyg/scripts/wysiwyg','openwysiwyg/scripts/wysiwyg-settings','jquery-ui'));
 ?><?php echo $this->Html->css(array('openwysiwyg/styles/wysiwyg'));
 ?><script type="text/javascript">
    WYSIWYG.attach('ReportDetails',full);
</script>
<div id="reports_ajax">
    <?php echo $this->Session->flash();?>
	
        <div class="nav">
            <div class="reports form col-md-8">
            <h4>Add Report            <?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info'));
 ?>	    <?php echo $this->Html->link('', '#advanced_search',array('class'=>'fa fa-search h4-title','data-toggle'=>'modal')); ?>
	    <?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator')); ?>
	    </h4>
            <?php echo $this->Form->create('Report',array('controller'=>'reports','action'=>'add'),array('role'=>'form','class'=>'form'));
 ?>	<fieldset>
    	<div class="panel panel-default">
            <div class="panel-body">
            Below is the system generated report. You can modify this report and save it under report center. <br/>
            Report is rendered in a Text Editor so that you can make the required changes.<br/>
            You can either publish this report as it is or keep it ready in reports center.
            </div>
        </div>	
        <textarea id="ReportDetails" name="data[Report][details]" style="float:left; clear:both">        
	<table cellpadding="2" cellspacing="2" border="1" width="100%" style="font-size:10px">
		<tr>
			<td colspan="4"><h2>Company Name (ISO CERTFICATION DOCUMENTS) :</h2>Powered by : FlinkISO ™</td>
		</tr>
		<tr>
			<td>Document Title </td><td>&nbsp;<?php echo h($invoice['MasterListOfFormat']['title']); ?></td>
			<td>Document Number</td><td>&nbsp;<?php echo h($invoice['MasterListOfFormat']['document_number']); ?></td>
		</tr>
		<tr>
			<td>Revision Number </td><td>&nbsp;<?php echo h($invoice['MasterListOfFormat']['revision_number']); ?></td>
			<td>Revision Date</td><td>&nbsp;<?php echo h($invoice['MasterListOfFormat']['revision_date']); ?></td>
		</tr>
                </table>
	<table cellpadding="2" cellspacing="2" border="1" width="100%" style="font-size:10px">
		<tr>
				<th><?php echo h(Inflector::humanize("sr_no"));?></th>
				<th><?php echo h(Inflector::humanize("purchase_order_id"));?></th>
				<th><?php echo h(Inflector::humanize("invoice_number"));?></th>
				<th><?php echo h(Inflector::humanize("work_order_number"));?></th>
				<th><?php echo h(Inflector::humanize("customer_id"));?></th>
				<th><?php echo h(Inflector::humanize("customer_contact_id"));?></th>
				<th><?php echo h(Inflector::humanize("invoice_date"));?></th>
				<th><?php echo h(Inflector::humanize("details"));?></th>
				<th><?php echo h(Inflector::humanize("banking_details"));?></th>
				<th><?php echo h(Inflector::humanize("subtotal"));?></th>
				<th><?php echo h(Inflector::humanize("vat"));?></th>
				<th><?php echo h(Inflector::humanize("sales_tax"));?></th>
				<th><?php echo h(Inflector::humanize("discount"));?></th>
				<th><?php echo h(Inflector::humanize("total"));?></th>
				<th><?php echo h(Inflector::humanize("notes"));?></th>
				<th><?php echo h(Inflector::humanize("invoice_due_date"));?></th>
				<th><?php echo h(Inflector::humanize("vat_number"));?></th>
				<th><?php echo h(Inflector::humanize("send_to_customer"));?></th>
				<th><?php echo h(Inflector::humanize("publish"));?></th>
				<th><?php echo h(Inflector::humanize("record_status"));?></th>
				<th><?php echo h(Inflector::humanize("status_user_id"));?></th>
				<th><?php echo h(Inflector::humanize("branchid"));?></th>
				<th><?php echo h(Inflector::humanize("departmentid"));?></th>
				<th><?php echo h(Inflector::humanize("approved_by"));?></th>
				<th><?php echo h(Inflector::humanize("prepared_by"));?></th>
				<th><?php echo h(Inflector::humanize("division_id"));?></th>
				<th><?php echo h(Inflector::humanize("company_id"));?></th>
				
				</tr>
	<tr>
	<?php foreach ($invoices as $invoice): ?>
	<tr>
		<td width="50"><?php echo h($invoice['Invoice']['sr_no']); ?>&nbsp;</td>
		<td width="50"><?php echo h($invoice['PurchaseOrder']['name']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['invoice_number']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['work_order_number']); ?>&nbsp;</td>
		<td width="50"><?php echo h($invoice['Customer']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($invoice['CustomerContact']['name']); ?>&nbsp;</td>
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
		<td><?php echo h($invoice['Invoice']['vat_number']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['send_to_customer']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['publish']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['record_status']); ?>&nbsp;</td>
		<td width="50"><?php echo h($invoice['StatusUser']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($invoice['BranchIds']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($invoice['DepartmentIds']['name']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['approved_by']); ?>&nbsp;</td>
		<td><?php echo h($invoice['Invoice']['prepared_by']); ?>&nbsp;</td>
		<td width="50"><?php echo h($invoice['Division']['name']); ?>&nbsp;</td>
		<td width="50"><?php echo h($invoice['Company']['name']); ?>&nbsp;</td>
	</tr>
	<?php endforeach; ?>
			</table>
			<table cellpadding="2" cellspacing="2" border="1" width="100%" style="font-size:10px">
		                <tr>
		                    <td>Prepared By </td><td>&nbsp;</td>
		                    <td>Approved By</td><td>&nbsp;</td>
		                </tr>
			</table>
	        </textarea>
	<?php echo $this->Form->input('title');?>
<?php echo $this->Form->input('branch_id',array('style'=>'width:90%','options'=>$PublishedBranchList));?>
<?php echo $this->Js->link('Add New',array('controller'=>'branchs','action'=>'add'),array('class'=>'label btn-info','update'=>'#reports_ajax'));?>
<?php echo $this->Form->input('department_id',array('style'=>'width:90%','options'=>$PublishedDepartmentList));?>
<?php echo $this->Js->link('Add New',array('controller'=>'departments','action'=>'add'),array('class'=>'label btn-info','update'=>'#reports_ajax'));?>
<?php echo $this->Form->input('master_list_of_format_id',array('style'=>'width:90%'));?>
<?php echo $this->Js->link('Add New',array('controller'=>'master_list_of_formats','action'=>'add'),array('class'=>'label btn-info','update'=>'#reports_ajax'));?>
<?php echo $this->Form->input('description');?>
<?php echo $this->Form->input('report_date');?>
<?php echo $this->Form->input('publish');?>
<?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success'));?>
<?php echo $this->Form->end();?>
<?php echo $this->Js->writeBuffer();?>

</fieldset>
</div>
<script> $("#ReportReportDate").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>
<div class="col-md-4">

	<p><?php echo $this->element('helps');
 ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');
 ?><?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#reports_ajax')));
 ?>
<?php echo $this->Js->writeBuffer();
 ?>
		<div class="modal fade" id="advanced_search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h4 class="modal-title">Advanced Search</h4>
		</div>
<div class="modal-body"><?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","invoice_number"=>"Invoice Number","work_order_number"=>"Work Order Number","invoice_date"=>"Invoice Date","details"=>"Details","banking_details"=>"Banking Details","subtotal"=>"Subtotal","vat"=>"Vat","sales_tax"=>"Sales Tax","discount"=>"Discount","total"=>"Total","notes"=>"Notes","invoice_due_date"=>"Invoice Due Date","send_to_customer"=>"Send To Customer","record_status"=>"Record Status","branchid"=>"Branchid","departmentid"=>"Departmentid","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?></div>
		<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div></div></div></div>


</div></div></div>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
	
