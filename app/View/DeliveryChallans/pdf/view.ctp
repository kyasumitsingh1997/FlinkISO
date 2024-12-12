<h2><?php  echo __('Delivery Challan'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Branch'); ?></td>
                    <td>
                        <?php echo $deliveryChallan['Branch']['name']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Department'); ?></td>
                    <td>
                        <?php echo $deliveryChallan['Department']['name']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Purchase Order Number'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['PurchaseOrder']['purchase_order_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Challan Number'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['challan_number']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Challan Date'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['challan_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prices'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['prices']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Ship By'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['ship_by']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Shipping Details'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['shipping_details']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Insurance'); ?></td>
                    <td>
                        <?php echo $deliveryChallan['DeliveryChallan']['insurance']; ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Shipping Date'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['shipping_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Ship To'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['ship_to']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Payment Details'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['payment_details']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Invoice To'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['invoice_to']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Acknowledgement Details'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['acknowledgement_details']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Acknowledgement Date'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['DeliveryChallan']['acknowledgement_date']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['PreparedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
                    <td>
                        <?php echo h($deliveryChallan['ApprovedBy']['name']); ?>
                        &nbsp;
                    </td>
                </tr>
                <?php if(!empty($deliveryChallan['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $deliveryChallan['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
        <p>&nbsp;</p>

	<h3><?php echo __('Related Delivery Challan Details'); ?></h3>
	<?php if (!empty($deliveryChallan['DeliveryChallanDetail'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
	   <tr bgcolor="#FFFFFF">
                    <th><?php echo __('Number'); ?></th>
                    <th><?php echo __('Product / Device/ Material/ Other'); ?></th>
                    <th><?php echo __('Quantity Ordered'); ?></th>
                    <th><?php echo __('Quantity Received'); ?></th>
                    <th><?php echo __('Rate'); ?></th>
                    <th><?php echo __('Discount'); ?></th>
                    <th style="text-align: center"><?php echo __('Material QC Required?'); ?></th>
                    <th><?php echo __('Total'); ?></th>	</tr>
	<?php
		$i = 0;
		foreach ($deliveryChallanDetails as $deliveryChallanDetail):  ?>
		<tr bgcolor="#FFFFFF">
                    <td>
                        <?php echo h($deliveryChallanDetail['DeliveryChallanDetail']['item_number']); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo $deliveryChallanDetail['Product']['name']; ?>
                        <?php echo $deliveryChallanDetail['Device']['name']; ?>
                        <?php echo $deliveryChallanDetail['Material']['name']; ?>
                        <?php echo h($deliveryChallanDetail['PurchaseOrderDetail']['other']); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo h($deliveryChallanDetail['DeliveryChallanDetail']['quantity']); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo h($deliveryChallanDetail['DeliveryChallanDetail']['quantity_received']); ?>
                        &nbsp;
                    </td>

                    <td>
                        <?php echo h($deliveryChallanDetail['DeliveryChallanDetail']['rate']); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo h($deliveryChallanDetail['DeliveryChallanDetail']['discount']) . "%"; ?>
                        &nbsp;
                    </td>
                    <td style="text-align: center">
                        <?php echo h($deliveryChallanDetail['DeliveryChallanDetail']['material_qc_required']) ? __('Yes') : __('No'); ?>
                        &nbsp;
                    </td>
                    <td>
                        <?php echo h($deliveryChallanDetail['DeliveryChallanDetail']['total']); ?>
                        &nbsp;
                    </td>
                </tr>
                <tr bgcolor="#FFFFFF">
                    <td colspan="8">
                        <?php foreach ($deliveryChallanDetail['QcSteps'] as $materialQualityCheckDetail) { ?>                        
                            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
                                <tr bgcolor="#FFFFFF"><td width="25%"><?php echo __('Performed By'); ?></td>
                                    <td>
                                        <?php echo $this->Html->link($materialQualityCheckDetail['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $materialQualityCheckDetail['Employee']['id'])); ?>
                                        &nbsp;
                                    </td></tr>
                                <tr bgcolor="#FFFFFF"><td><?php echo __('Check Performed Date'); ?></td>
                                    <td>
                                        <?php echo h($materialQualityCheckDetail['MaterialQualityCheckDetail']['check_performed_date']); ?>
                                        &nbsp;
                                    </td></tr>
                                <tr bgcolor="#FFFFFF"><td><?php echo __('Quantity Received'); ?></td>
                                    <td>
                                        <?php echo h($materialQualityCheckDetail['MaterialQualityCheckDetail']['quantity_received']); ?>
                                        &nbsp;
                                    </td></tr>
                                <tr bgcolor="#FFFFFF"><td><?php echo __('Quantity Accepted'); ?></td>
                                    <td>
                                        <?php echo h($materialQualityCheckDetail['MaterialQualityCheckDetail']['quantity_accepted']); ?>
                                        &nbsp;
                                    </td></tr>
                                <tr bgcolor="#FFFFFF">
                                    <td colspan="2">
                                        <?php echo $materialQualityCheckDetail['MaterialQualityCheckDetail']['qc_template']; ?>
                                    </td>
                                </tr>
                                <tr bgcolor="#FFFFFF"><td><?php echo __('Final Report'); ?></td>
                                    <td>
                                        <?php echo h($materialQualityCheckDetail['MaterialQualityCheckDetail']['qc_report']); ?>
                                        &nbsp;
                                    </td></tr>
                                <tr bgcolor="#FFFFFF">                                          
                            </table>
                            <?php if($materialQualityCheckDetail['Approval']){ ?> 
                            <h4><?php echo __('Approvals');?></h4>
                            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
                                <tr bgcolor="#FFFFFF">
                                    <th><?php echo __('From');?></th>
                                    <th><?php echo __('To');?></th>
                                    <th><?php echo __('Comment');?></th>
                                    <th><?php echo __('Date');?></th>
                                    <th><?php echo __('Status');?></th>
                                </tr>
                                <?php foreach ($materialQualityCheckDetail['Approval'] as $approvals) { ?>
                                    <tr bgcolor="#FFFFFF">
                                        <td><?php echo h($approvals['From']['name'])?></td>
                                        <td><?php echo h($approvals['To']['name'])?></td>
                                        <td><?php echo h($approvals['Approval']['comments'])?></td>
                                        <td><?php echo h($approvals['Approval']['created'])?></td>
                                        <td><?php echo h($approvals['Approval']['status'])?></td>
                                </tr>
                                <?php } ?>
                            </table>
                            <?php } ?>
                        <?php } ?>
                    </td>
                </tr>

	<?php endforeach; ?>
	</table>
<?php endif; ?>


