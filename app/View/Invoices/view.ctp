<div id="invoices_ajax">
<?php echo $this->Session->flash();?>   
<div class="nav panel panel-default">
<div class="invoices form col-md-8">
<h4><?php echo __('Edit Invoice'); ?>       
        <?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
        <?php //echo $this->Html->link(__('Download PDF'), array('action' => 'view',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
        <?php echo $this->Html->link(__('Create PDF'), array('action' => 'generate_pdf',$this->request->params['pass'][0].'.pdf'),array('id'=>'pdf','class'=>'label btn-info')); ?>
        
        </h4>
			

<table width="100%">
    <tr valign ="top" ><td>        
<?php
    $style = '';    
    $coId = $this->Session->read('User.company_id');
    if (($companyDetails['Company']['logo'] == 1) && (!empty($companyDetails['Company']['company_logo']))) {
        if (file_exists(WWW_ROOT  . DS . 'img' .DS . 'logo'. DS . $companyDetails['Company']['company_logo'])) {
            $logo = 'logo' .DS . $companyDetails['Company']['company_logo'];
            $style = 'margin-top: 5px;';
            $logo = 'logo' .DS . $companyDetails['Company']['company_logo'];
            echo $this->Html->image($logo,array('fullBase' => true, 'width'=>'80'));
        }
    }
?>
  <h1><?php echo $invoice_settings['InvoiceSetting']['company_name']; ?></h1>    
  </td>
  <td valign ="top" align="right">
    <h1>Invoice</h1>
</td>
</tr>
</table>
<br />
<table class="table table-responsive">
    <tr bgcolor="#ffffff">
    	<th>Bill To</th>
    	<td rowspan="2">
        	<table class="table table-responsive">
	            <tr><td width="30%">Date:</td><td><?php echo $invoice['Invoice']['invoice_date']; ?></td></tr>
	            <tr><td>Invoice #:</td><td><?php echo $invoice['Invoice']['invoice_number']; ?></td></tr>
	            <tr><td>Customer ID:</td><td><?php echo $invoice['Customer']['customer_code']; ?></td></tr>
	            <tr><td>Work Order #:</td><td><?php echo $invoice['Invoice']['work_order_number']; ?></td></tr>
	            <tr><td>Payment Due by:</td><td><?php echo $invoice['Invoice']['invoice_due_date']; ?></td></tr>
	            <tr><td>Vat Number:</td><td><?php echo $invoice['Invoice']['vat_number']; ?></td></tr>
	        </table>
    	</td>
    </tr>
    <tr bgcolor="#ffffff"><td valign="top">
        <?php
            echo '<strong>'.$invoice['CustomerContact']['name'] ."</strong><br />";
            echo $invoice['Customer']['name'] ."<br />";
            echo $invoice['Customer']['residence_address'] ."<br />";
        ?>
    </td></tr>
</table>
<table width="100%" class="table table-responsive table-bordered">
    <tr bgcolor="#FFFFFF">
        <th width="4%"><?php echo __('#'); ?></th>
        <th width="56%"><?php echo __('Description'); ?></th>
        <th width="40%"><?php echo __('Line Total'); ?> (<?php echo $invoice['Currency']['name'];?>)</th>        
    </tr>
    <?php $subtotal = 0; $i = 1; ?>                        
    <?php foreach ($invoice['InvoiceDetail'] as $invoiceDetail) { ?>
    <tr bgcolor="#FFFFFF">
        <td width="4%"><?php echo $invoiceDetail['item_number'];?></td>
        <td width="56%"><?php
            //print_r($invoiceDetail);

            if ($invoiceDetail['product_id'] != -1) {
                echo $purchaseOrderDetail['name'];
            } elseif ($invoiceDetail['device_id'] != -1) {
                echo $purchaseOrderDetail['name'];
            } elseif ($invoiceDetail['material_id'] != -1) {
                echo $purchaseOrderDetail['name'];
            } elseif ($invoiceDetail['other'] != NULL) {
                echo($invoiceDetail['other']);
            }
            ?>
            &nbsp; 
            <?php echo h($invoiceDetail['description']); ?> &nbsp;
            <?php echo h($invoiceDetail['item_number']); ?> &nbsp; 
            <?php echo h($invoiceDetail['quantity']); ?> &nbsp; 
            <?php
            if ($invoiceDetail['discount'] != NULL) {
                echo h($invoiceDetail['discount']) . "%";
            } else {
                echo '&#8212;';
            }
            ?>
            &nbsp; </td>
        <td  align="right"  width="40%"><?php echo h($invoiceDetail['total']); ?> &nbsp; </td>
        <?php $subtotal = $subtotal + $invoiceDetail['total']; ?>
    </tr>
    <?php $i++; } ?>
</table>
<br />
<table width="100%" class="table table-responsive table-bordered">
    <tr bgcolor="#FFFFFF">
        <td  width="60%" valign="top">
            <strong><?php echo __('Banking Details'); ?></strong><br />
            <?php echo nl2br($invoice['Invoice']['banking_details']); ?>
        </td>
        <td width="40%">
            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
                <tr bgcolor="#FFFFFF">
                    <td width="50%"><?php echo __('Subtotal'); ?>(<?php echo $invoice['Currency']['name'];?>)</td>
                    <td width="50%" align="right" ><?php echo $subtotal; ?></td></tr>
                <tr bgcolor="#FFFFFF">
                    <td width="50%"><?php echo __('Vat'); ?></td>
                    <td width="50%" align="right" ><?php echo $invoice['Invoice']['vat']; ?> %</td></tr>
                <tr bgcolor="#FFFFFF">
                    <td width="50%"><?php echo __('Sales Tax'); ?></td>
                    <td width="50%" align="right" ><?php echo $invoice['Invoice']['sales_tax']; ?> %</td></tr>
                <tr bgcolor="#FFFFFF">
                    <td width="50%"><?php echo __('Discount'); ?></td>
                    <td width="50%" align="right" ><?php echo $invoice['Invoice']['discount']; ?> %</td></tr>
                <tr bgcolor="#FFFFFF">
                    <td width="50%"><strong><?php echo __('Total'); ?> (<?php echo $invoice['Currency']['name'];?>)</strong></td>
                    <td width="50%" align="right" ><strong><?php echo $invoice['Invoice']['total']; ?></strong></td></tr>
            </table>
        </td>
    </tr>
</table>    
<table cellpadding="2" cellspacing="1" bgcolor="#ffffff" border="0" width="100%">
    <tr bgcolor="#FFFFFF">
        <td width="100%" align="center">
            <p><?php echo nl2br($invoice_settings['InvoiceSetting']['footer']); ?></p>
            <p><?php echo nl2br($invoice_settings['InvoiceSetting']['contact_details']); ?><br /></p>
        </td>
    </tr>
</table>
</center>

			<?php echo $this->element('upload-edit', array('usersId' => $invoice['Invoice']['created_by'], 'recordId' => $invoice['Invoice']['id'])); ?>
		</div>			
		<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>	
