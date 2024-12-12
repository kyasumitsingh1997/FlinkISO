<?php if($stock['Stock']['type'] == 1){ ?>
    <h2><?php  echo __('Incoming Stock'); ?></h2>
<?php }else { ?>
    <h2><?php  echo __('Add Stock to Batch'); ?></h2>
<?php } ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Material'); ?></td>
		<td>
			<?php echo $stock['Material']['name']; ?>
			&nbsp;
		</td></tr>
            <?php if($stock['Stock']['type'] == 1){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Supplier Registration'); ?></td>
		<td>
			<?php echo $stock['SupplierRegistration']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Purchase Order'); ?></td>
		<td>
			<?php echo $purchaseOrder[$stock['DeliveryChallan']['purchase_order_id']]; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Delivery Challan'); ?></td>
		<td>
			<?php echo $stock['DeliveryChallan']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Received Date'); ?></td>
		<td>
			<?php echo h($stock['Stock']['received_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Quantity'); ?></td>
		<td>
			<?php echo h($stock['Stock']['quantity']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $stock['Branch']['name']; ?>
			&nbsp;
		</td></tr>
            <?php } else {?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Batch No.'); ?></td>
		<td>
			<?php echo $stock['Production']['batch_number']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Production Date'); ?></td>
		<td>
			<?php echo h($stock['Stock']['production_date']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Quantity Consumed'); ?></td>
		<td>
			<?php echo h($stock['Stock']['quantity_consumed']); ?>
			&nbsp;
		</td></tr>
            <?php } ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Remarks'); ?></td>
		<td>
			<?php echo h($stock['Stock']['remarks']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($stock['Stock']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $stock['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $stock['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($stock['Stock']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($stock['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $stock['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<h3><?php echo __('Quality Check Performed'); ?></h3>
            <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
                <?php $step = 1;  foreach ($checks as $check) { ?>
                    <tr bgcolor="#FFFFFF">
                        <td colspan="2"><h3><?php echo __('Step'); ?> : <?php echo $step; ?> : <?php echo $check['MaterialQualityCheck']['name']; ?></h3></td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td class="head-strong"><?php echo __('QC Performaed By'); ?></td>
                        <td><?php echo $check['Employee']['name'] ; ?></td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td class="head-strong"><?php echo __('Check Performed Date'); ?></td>
                        <td><?php echo $check['MaterialQualityCheckDetail']['check_performed_date']; ?></td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td class="head-strong"><?php echo __('Quantity Received'); ?></td>
                        <td><?php echo $check['MaterialQualityCheckDetail']['quantity_received']; ?></td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td class="head-strong"><?php echo __('Auantity Accepted'); ?></td>
                        <td><?php echo $check['MaterialQualityCheckDetail']['quantity_accepted']; ?></td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td class="head-strong"><?php echo __('QC Report'); ?></td>
                        <td><?php echo $check['MaterialQualityCheckDetail']['qc_report']; ?></td>
                    </tr>
                    <tr bgcolor="#FFFFFF">
                        <td class="head-strong"><?php echo __('Delivery Challan'); ?></td>
                        <td><?php echo $check['DeliveryChallan']['challan_number']; ?>/<?php echo $check['DeliveryChallan']['challan_date']; ?></td>
                    </tr>
                <?php $step++; } ?>    
            </table>
	<br />
