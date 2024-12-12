	<br /><h3><?php echo __('Purchase Order'); ?></h3>
            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
                <tr  bgcolor="#FFFFFF">
                    <td width="25%"><?php echo __('Title'); ?></td>
                    <td>
                        <?php echo h($purchaseOrder['PurchaseOrder']['title']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr  bgcolor="#FFFFFF">
                    <td><?php echo __('Purchase Order Number'); ?></td>
                    <td>
                        <?php echo h($purchaseOrder['PurchaseOrder']['purchase_order_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr  bgcolor="#FFFFFF">
                    <td><?php echo __('Order Date'); ?></td>
                    <td>
                        <?php echo h($purchaseOrder['PurchaseOrder']['order_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr  bgcolor="#FFFFFF">
                    <td><?php echo __('Type'); ?></td>
                    <td>
                        <?php
                        if ($purchaseOrder['PurchaseOrder']['type'] == 0)
                            echo __('Inbound');
                        else if ($purchaseOrder['PurchaseOrder']['type'] == 1)
                            echo __('Outbound');
                        else
                            echo __('Other');
                        ?>
                        &nbsp;
                    </td>
                </tr>
                <tr  bgcolor="#FFFFFF">
                    <td><?php
                            if ($purchaseOrder['PurchaseOrder']['type'] == 1)
                                echo __('Supplier');
                            else if ($purchaseOrder['PurchaseOrder']['type'] == 0)
                                echo __('Customer');
                            else
                                echo __('Other');
                        ?>
                    <td>
                        <?php if ($purchaseOrder['PurchaseOrder']['type'] == 0) echo $purchaseOrder['Customer']['name']; ?>
                        <?php if ($purchaseOrder['PurchaseOrder']['type'] == 1) echo $purchaseOrder['SupplierRegistration']['title']; ?>
                        <?php if ($purchaseOrder['PurchaseOrder']['type'] == 2) echo h($purchaseOrder['PurchaseOrder']['other']); ?>
                    </td>
                </tr>

                <tr  bgcolor="#FFFFFF">
                    <td><?php echo __('Details'); ?></td>
                    <td>
                        <?php echo h($purchaseOrder['PurchaseOrder']['details']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr  bgcolor="#FFFFFF">
                    <td><?php echo __('Intimation'); ?></td>
                    <td>
                        <?php echo $purchaseOrder['PurchaseOrder']['intimation']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr  bgcolor="#FFFFFF">
                    <td><?php echo __('Expected Delivery Date'); ?></td>
                    <td>
                        <?php echo $purchaseOrder['PurchaseOrder']['expected_delivery_date']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr  bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($purchaseOrder['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr  bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($purchaseOrder['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr  bgcolor="#FFFFFF">
                    <td><?php echo __('Publish'); ?></td>
                    <td>
                        <?php if ($purchaseOrder['PurchaseOrder']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;</td>
                </tr>

            </table>    
            <br /><h3><?php echo __('Purchase Order Details'); ?></h3>        
            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">                
                <tr  bgcolor="#FFFFFF">
                    <th><?php echo __('Product/ Device/ Material/ Other'); ?></th>
                    <th><?php echo __('Item Number'); ?></th>
                    <th><?php echo __('Quantity'); ?></th>
                    <th><?php echo __('Rate'); ?> (<?php echo h($purchaseOrder['Currency']['name']); ?>)</th>
                    <th><?php echo __('Discount'); ?></th>
                    <th><?php echo __('Total'); ?> (<?php echo h($purchaseOrder['Currency']['name']); ?>)</th>
                    <th><?php echo __('Description'); ?></th>
                    <th><?php echo __('Publish'); ?></th>
                </tr>

                <?php $i = 1; ?>
                <?php foreach ($purchaseOrderDetails as $purchaseOrderDetail) { ?>
                <tr  bgcolor="#FFFFFF">
                    <td>
                        <?php
                        if ($purchaseOrderDetail['PurchaseOrderDetail']['product_id'] != -1) {
                            echo $purchaseOrderDetail['Product']['name'];
                        } elseif ($purchaseOrderDetail['PurchaseOrderDetail']['device_id'] != -1) {
                            echo $purchaseOrderDetail['Device']['name'];
                        } elseif ($purchaseOrderDetail['PurchaseOrderDetail']['material_id'] != -1) {
                            echo $purchaseOrderDetail['Material']['name'];
                        } elseif ($purchaseOrderDetail['PurchaseOrderDetail']['other'] != NULL) {
                            echo($purchaseOrderDetail['PurchaseOrderDetail']['other']);
                        }
                        ?>&nbsp;
                    </td>
                    <td>
                        <?php echo h($purchaseOrderDetail['PurchaseOrderDetail']['item_number']); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo h($purchaseOrderDetail['PurchaseOrderDetail']['quantity']); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo h($purchaseOrderDetail['PurchaseOrderDetail']['rate']); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php
                        if ($purchaseOrderDetail['PurchaseOrderDetail']['discount'] != NULL) {
                            echo h($purchaseOrderDetail['PurchaseOrderDetail']['discount']) . "%";
                        } else {
                            echo '&#8212;';
                        }
                        ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo h($purchaseOrderDetail['PurchaseOrderDetail']['total']); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo h($purchaseOrderDetail['PurchaseOrderDetail']['description']); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php if ($purchaseOrderDetail['PurchaseOrderDetail']['publish'] == 1) { ?>
                            <span class="fa fa-check"></span>
                        <?php } else { ?>
                            <span class="fa fa-ban"></span>
                        <?php } ?>&nbsp;</td>
                    &nbsp;</tr>

                <?php $i++; } ?>
            </table>        
        <?php if($purchaseOrder['DeliveryChallan']){ ?> 
        <div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>            
                        <?php foreach ($purchaseOrder['DeliveryChallan'] as $dc) { ?>
                             <br /><h3><?php echo __('Delivery Challan : ' . $dc['challan_number']);?></h3>
                                <?php 
                                    $dcDetails  = $this->requestAction('delivery_challans/view/'.$dc['id']);
                                    echo $this->element('dcdetailspdf',array(
                                            'deliveryChallan'=>$dcDetails['challan'],
                                            'deliveryChallanDetails' => $dcDetails['details']
                                        ));
                                ?>
                             
                             <div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>
                        <?php } ?>                    
                
         <?php } ?>
