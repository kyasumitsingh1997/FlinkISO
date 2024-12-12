<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" border="1" width="100%">
    <tr><th width="60%">Bill To</th><td rowspan="2">
        <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" border="1" width="100%">
            <tr><td width="30%">Date:</td><td><?php echo $invoice['Invoice']['invoice_date']; ?></td></tr>
            <tr><td>Invoice #:</td><td><?php echo $invoice['Invoice']['invoice_number']; ?></td></tr>
            <tr><td>Customer ID:</td><td><?php echo $invoice['Customer']['customer_code']; ?></td></tr>
            <tr><td>Work Order #:</td><td><?php echo $invoice['Invoice']['work_order_number']; ?></td></tr>
            <tr><td>Payment Due by:</td><td><?php echo $invoice['Invoice']['invoice_due_date']; ?></td></tr>
        </table>
    </td></tr>
    <tr><td valign="top">
        <?php
            echo '<strong>'.$invoice['CustomerContact']['name'] ."</strong><br />";
            echo $invoice['Customer']['name'] ."<br />";
            echo $invoice['Customer']['residence_address'] ."<br />";
        ?>
    </td></tr>
</table>
<table cellpadding="4" cellspacing="1" bgcolor="#D4D4D4" border="1" width="100%">
    <tr bgcolor="#FFFFFF">
        <th width="75%"><?php echo __('Description'); ?></th>
        <th><?php echo __('Line Total'); ?>(<?php echo $purchaseOrder['Currency']['name'];?>)</th>        
    </tr>
    <?php $subtotal = 0; $i = 1; ?>                        
    <?php foreach ($invoice['InvoiceDetail'] as $invoiceDetail) { ?>
    <tr bgcolor="#FFFFFF">
        <td><?php
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
        <td  align="right"><?php echo h($invoiceDetail['total']); ?> &nbsp; </td>
        <?php $subtotal = $subtotal + $invoiceDetail['total']; ?>
    </tr>
    <?php $i++; } ?>
</table>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
    <tr>
        <td  width="60%" valign="top">
            <strong><?php echo __('Banking Details'); ?></strong><br />
            Add Address
        </td>
        <td>
            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
                <tr><td><?php echo __('Subtotal'); ?>(<?php echo $purchaseOrder['Currency']['name'];?>)</td><td><?php echo $subtotal; ?></td></tr>
                <tr><td><?php echo __('Vat'); ?></td><td><?php echo $invoice['Invoice']['vat']; ?></td></tr>
                <tr><td><?php echo __('Sales Tax'); ?></td><?php echo $invoice['Invoice']['sales_tax']; ?><td></td></tr>
                <tr><td><?php echo __('Discount'); ?></td><td><?php echo $invoice['Invoice']['discount']; ?></td></tr>
                <tr><td><?php echo __('Total'); ?> (<?php echo $purchaseOrder['Currency']['name'];?>)</td><td><?php echo $invoice['Invoice']['total']; ?></td></tr>
            </table>
        </td>
    </tr>
</table>    
<p class="text-center">Make all payments to Junto Group</p>
<p class="text-center"><strong>Thank you for your business!</strong></p>
<p class="text-center">Should you have any enquiries concerning this invoice, please contact Finance <br />
    Madison Offices, No 1, 5th Street, Northwold<br />Tel: 011 651 6305 Fax: 0865824582; E-mail:info@junto.co.za;  Web: www.junto.co.za<br />
</p