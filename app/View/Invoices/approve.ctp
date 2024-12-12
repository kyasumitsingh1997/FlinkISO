 <div id="invoices_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="invoices form col-md-8">
<h4><?php echo __('Approve Invoice'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('Invoice',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<table class="table table-responsive">
                        <tr><th>Bill To : 
                            <?php echo $this->Form->input('customer_contact_id',array('label'=>false,'options'=>$customerContacts,'default'=>'')); ?>
                            <?php
                                echo $purchaseOrder['Customer']['name'] ."<br />";
                                echo $purchaseOrder['Customer']['residence_address'] ."<br />";
                            ?>
                            <?php echo $this->Form->hidden('customer_id',array('value'=>$purchaseOrder['Customer']['id'],'label'=>false)); ?>
                            <?php echo $this->Form->hidden('details',array('value'=>$purchaseOrder['PurchaseOrder']['details'],'label'=>false)); ?>
                            <?php echo $this->Form->hidden('purchase_order_id',array('value'=>$purchaseOrder['PurchaseOrder']['id'],'label'=>false)); ?>
                        </th><th>
                            <table class="table table-responsive table-bordered">
                                <tr>
                                    <td>Date:</td>
                                    <td><?php echo $this->Form->input('invoice_date',array('value'=>date('Y-m-d'),'label'=>false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Invoice #:</td>
                                    <td><?php echo $this->Form->input('invoice_number',array('label'=>false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Customer ID:</td>
                                    <td><?php echo $this->Form->input('work_order_number',array('label'=>false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Work Order #:</td>
                                    <td><?php echo $purchaseOrder['PurchaseOrder']['purchase_order_number']; ?></td>
                                </tr>
                                <tr>
                                    <td>Payment Due by:</td>
                                    <td><?php echo $this->Form->input('invoice_due_date',array('label'=>false)); ?></td>
                                </tr>
                                <tr>
                                    <td>Vat Number:</td>
                                    <td><?php echo $this->Form->input('vat_number',array('label'=>false)); ?></td>
                                </tr>
                            </table>
                        </th></tr>                    
                    </table>
                    <table class="table table-responsive table-bordered">
                        <tr bgcolor="#FFFFFF">
                            <th>#</th>
                            <th width="75%"><?php echo __('Description'); ?></th>
                            <th><?php echo __('Line Total'); ?> (<?php echo $purchaseOrder['Currency']['name'];?>)</th>        
                        </tr>
                        <?php $i = 1; ?>
                        <?php foreach ($invoiceDetails as $invoiceDetail) { ?>
                        <tr bgcolor="#FFFFFF">
                            <td><?php echo $this->Form->checkbox('InvoiceDetail.'.$i.'.invoice_details',array('label'=>false,'checked')); ?></td>
                            <td><?php
                                            if ($invoiceDetail['InvoiceDetail']['product_id'] != -1) {
                                                echo $purchaseOrderDetail['Product']['name'];
                                            } elseif ($invoiceDetail['InvoiceDetail']['device_id'] != -1) {
                                                echo $purchaseOrderDetail['Device']['name'];
                                            } elseif ($invoiceDetail['InvoiceDetail']['material_id'] != -1) {
                                                echo $purchaseOrderDetail['Material']['name'];
                                            } elseif ($invoiceDetail['InvoiceDetail']['other'] != NULL) {
                                                echo($invoiceDetail['InvoiceDetail']['other']);
                                            }
                                            ?>
                                &nbsp; 
                                <?php echo h($invoiceDetail['InvoiceDetail']['description']); ?> &nbsp;
                                <?php echo h($invoiceDetail['InvoiceDetail']['item_number']); ?> &nbsp; 
                                <?php echo h($invoiceDetail['InvoiceDetail']['quantity']); ?> &nbsp; 
                                <?php
                                            if ($invoiceDetail['InvoiceDetail']['discount'] != NULL) {
                                                echo h($invoiceDetail['InvoiceDetail']['discount']) . "%";
                                            } else {
                                                echo '&#8212;';
                                            }
                                            ?>
                                &nbsp; </td>
                            <td  align="right"><?php echo h($invoiceDetail['InvoiceDetail']['total']); ?> &nbsp; </td>
                            <?php $subtotal = $subtotal + $invoiceDetail['InvoiceDetail']['total']; ?>
                        </tr>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.purchase_order_id',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['purchase_order_id'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.purchase_order_detail_id',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['id'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.product_id',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['product_id'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.device_id',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['device_id'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.material_id',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['material_id'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.other',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['other'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.description',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['description'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.item_number',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['item_number'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.quantity',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['quantity'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.rate',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['rate'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.discount',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['discount'])); ?>
    <?php echo $this->Form->hidden('InvoiceDetail.'.$i.'.total',array('label'=>false,'value'=>$invoiceDetail['InvoiceDetail']['total'])); ?>
                        <?php $i++; } ?>
                    </table>                
                    <table class="table table-responsive table-bordered">
                        <tr>
                            <td  width="60%" valign="top">
                                <strong><?php echo __('Banking Details'); ?></strong><br />
                                <?php echo $this->Form->input('banking_details',array('label'=>false)); ?>
                            </td>
                            <td>
                                <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
                                    <tr><td><?php echo __('Subtotal'); ?>(<?php echo $purchaseOrder['Currency']['name'];?>)</td><td><span id="subt"><?php echo $subtotal; ?></span></td></tr>
                                    <tr><td><?php echo __('Vat'); ?></td><td><?php echo $this->Form->input('vat',array('label'=>false,'value'=>0)); ?></td></tr>
                                    <tr><td><?php echo __('Sales Tax'); ?></td><td><?php echo $this->Form->input('sales_tax',array('label'=>false,'value'=>0)); ?><td></td></tr>
                                    <tr><td><?php echo __('Discount'); ?></td><td><?php echo $this->Form->input('discount',array('label'=>false,'value'=>0)); ?></td></tr>
                                    <tr><td><?php echo __('Total'); ?> (<?php echo $purchaseOrder['Currency']['name'];?>)</td><td><?php echo $this->Form->input('total',array('label'=>false, 'value'=>$subtotal)); ?></td></tr>
                                </table>
                            </td>
                        </tr>
                    </table> 
                    <?php echo $this->Form->input('notes',array()); ?>   
                    <p class="text-center">Make all payments to Junto Group</p>
                    <p class="text-center"><strong>Thank you for your business!</strong></p>
                    <p class="text-center">Should you have any enquiries concerning this invoice, please contact Finance <br />
                        Madison Offices, No 1, 5th Street, Northwold<br />Tel: 011 651 6305 Fax: 0865824582; E-mail:info@junto.co.za;  Web: www.junto.co.za<br />
                    </p>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="row">
		

<?php

		
	if ($showApprovals && $showApprovals['show_panel'] == true) {
		
		echo $this->element('approval_form');
		
	} else {
		
		echo $this->Form->input('publish', array('label' => __('Publish')));
		
	}
?>
<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#invoices_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/approve/<?php echo $this->request->params['pass'][0] ?>/<?php echo $this->request->params['pass'][1] ?>",
                type: 'POST',
                target: '#invoices_ajax',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    });
        }
    });
	$().ready(function() {            
        $('#InvoiceVat').addClass(' change_tax');
        $('#InvoiceSalesTax').addClass(' change_tax');
        $('#InvoiceDiscount').addClass(' change_tax');
        
        $('.change_tax').on('blur',function(){
            var total = parseInt(parseInt($('#InvoiceStotal').val()) + ( (parseInt($('#InvoiceStotal').val()) * parseInt($('#InvoiceVat').val()) / 100) + (parseInt($('#InvoiceStotal').val()) * parseInt($('#InvoiceSalesTax').val() / 100)) - parseInt($('#InvoiceStotal').val()) * parseInt($('#InvoiceDiscount').val()) / 100 ));
            $('#InvoiceTotal').val(total);            
        });

        $("#submit-indicator").hide();
        $('#InvoiceApproveForm').validate();        
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
