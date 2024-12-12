<h2><?php  echo __('Production'); ?></h2>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
    <tr bgcolor="#FFFFFF"><td><?php echo __('Product'); ?></td>
        <td>
            <?php echo $this->Html->link($production['Product']['name'], array('controller' => 'products', 'action' => 'view', $production['Product']['id'])); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Batch Number'); ?></td>
        <td>
            <?php echo h($production['Production']['batch_number']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Production Date'); ?></td>
        <td>
            <?php echo h($production['Production']['production_date']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Production Weekly plan'); ?></td>
        <td>
            <?php echo h($production['ProductionWeeklyPlan']['name']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Details'); ?></td>
        <td>
            <?php echo h($production['Production']['details']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Branch'); ?></td>
        <td>
            <?php echo $this->Html->link($production['Branch']['name'], array('controller' => 'branches', 'action' => 'view', $production['Branch']['id'])); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Supervisor'); ?></td>
        <td>
            <?php echo $this->Html->link($production['Employee']['name'], array('controller' => 'employees', 'action' => 'view', $production['Employee']['id'])); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Week'); ?></td>
        <td>
            <?php echo $this->Html->link($production['ProductionWeeklyPlan']['week'], array('controller' => 'production_weekly_plans', 'action' => 'view', $production['ProductionWeeklyPlan']['id'])); ?>
            &nbsp;
        </td>
    </tr>
    <!-- <tr bgcolor="#FFFFFF"><td><?php echo __('End Date'); ?></td>
        <td>
            <?php echo h(date('d M Y',strtotime($production['Production']['end_date']))); ?>
            &nbsp;
        </td>
    </tr> -->
    <tr bgcolor="#FFFFFF"><td><?php echo __('Production Planned'); ?></td>
        <td>
            <?php echo h($production['ProductionWeeklyPlan']['production_planned']); ?>
            &nbsp;
        </td>
    </tr><tr bgcolor="#FFFFFF"><td><?php echo __('Actual Production Number'); ?></td>
        <td>
            <?php echo h($production['Production']['actual_production_number']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Current Status'); ?></td>
        <td>
            <?php echo h($currentStatus[$production['Production']['current_status']]); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Remarks'); ?></td>
        <td>
            <?php echo h($production['Production']['remarks']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Prepared By'); ?></td>
        <td>
            <?php echo h($production['PreparedBy']['name']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Approved By'); ?></td>
        <td>
            <?php echo h($production['ApprovedBy']['name']); ?>
            &nbsp;
        </td>
    </tr>
    <tr bgcolor="#FFFFFF"><td><?php echo __('Publish'); ?></td>
        <td>
            <?php if ($production['Production']['publish'] == 1) { ?>
                <span class="fa fa-check"></span>
            <?php } else { ?>
                <span class="fa fa-ban"></span>
            <?php } ?>&nbsp;</td>
        &nbsp;
    </tr>
</table>
<h2><?php echo __('Production Rejection Details');?></h2>
    <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
        <tr bgcolor="#FFFFFF">
            <th><?php echo __('Inspection Template'); ?></th>
            <th><?php echo __('Defect Type'); ?></th>
            <th><?php echo __('Sample Quantity'); ?></th>
            <th><?php echo __('Quality Check Date'); ?></th>
            <th><?php echo __('Start Sr Number'); ?></th>
            <th><?php echo __('End Sr Number'); ?></th>
            <th><?php echo __('# Of Rejections'); ?></th>
            <th><?php echo __('Employee'); ?></th>
            <th><?php echo __('Publish'); ?></th>            
        </tr>
		<?php if($production['ProductionRejection']){ 
			$total = 0; ?>
			<?php foreach ($production['ProductionRejection'] as $productionRejections):?>
	            <tr bgcolor="#FFFFFF">
	                <td colspan="9"><?php echo $productionInspectionTemplates[$productionRejections['production_inspection_template_id']]?></td>	                
	            </tr>
	                <?php foreach ($productionRejections['RejectionDetail'] as $newProductionRejection) { ?>
	                	<tr bgcolor="#FFFFFF">
	                    	<td><?php echo $productionInspectionTemplates[$productionRejections['production_inspection_template_id']]?></td>
	                        <td><?php echo h($newProductionRejection['DefectType']['name']); ?>&nbsp;</td>
	                        <td><?php echo h($productionRejections['sample_quantity']); ?>&nbsp;</td>
	                        <td><?php echo h($productionRejections['quality_check_date']); ?>&nbsp;</td>
	                        <td><?php echo h($productionRejections['start_sr_number']); ?>&nbsp;</td>
	                        <td><?php echo h($productionRejections['end_sr_number']); ?>&nbsp;</td>
	                        <td><?php echo h($newProductionRejection['RejectionDetail']['number_of_rejections']); ?>&nbsp;</td>
	                        <?php 
	                            $total = $newProductionRejection['RejectionDetail']['number_of_rejections'] + $total;
	                        ?>
	                        <td>
	                            <?php echo $PublishedEmployeeList[$productionRejections['employee_id']]; ?>
	                        </td>
	                        <td width="60">
	                            <?php if($newProductionRejection['RejectionDetail']['publish'] == 1) { ?>
	                            <span class="fa fa-check"></span>
	                            <?php } else { ?>
	                            <span class="fa fa-ban"></span>
	                            <?php } ?>&nbsp;
	                        </td>	                        
	                    </tr>	                    	                   
	                <?php } ?>
	            <?php endforeach; ?>
	                <tr bgcolor="#FFFFFF">
	                    <th colspan="4">&nbsp;</th>
	                    <th colspan="3" class="text-danger"><h3><?php echo __('Total Rejections');?></h3></th>
	                    <th colspan="2" class="text-danger"><h3><?php echo $total;?></h3></th>
	                </tr>
	                <?php if($total == 0){ ?>
	                <tr bgcolor="#FFFFFF">
	                    <td colspan="9"></td>
	                    <td>
	                        
					<?php } ?>
	                    </td>
	                </tr>
	            <?php }else{ ?>
	                
	            <?php } ?>
            </table>
            <?php if($production['ProductionRejection']){ 
			$total = 0; ?>
			<?php foreach ($production['ProductionRejection'] as $productionRejections):?>	            
	                <?php foreach ($productionRejections['RejectionDetail'] as $newProductionRejection) { ?>
						<div style="page-break-after:always"><span style="display:none">&nbsp;</span></div>
						<h2><?php echo $productionRejections['name']; ?></h2>
						<?php echo $productionRejections['inspection_report'];?>	                            
					<?php } ?>
	            <?php endforeach; ?>	                
			<?php } ?>
	<h2><?php echo __('Batch Details');?></h2>
	    <table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
	        <tr bgcolor="#FFFFFF">
	            <th><?php echo __('Batch Number');?></th>
	            <th><?php echo __('Material');?></th>
	            <th><?php echo __('Production Date');?></th>
	            <th><?php echo __('Quantity Consumed');?></th>
	        </tr>
	        <?php foreach ($production['Stock'] as $stocks) { ?>
	            <tr bgcolor="#FFFFFF">
	                <td><?php echo $production['Production']['batch_number'];?></td>
	                <td><?php echo $materials[$stocks['material_id']];?></td>
	                <td><?php echo $stocks['production_date'];?></td>
	                <td><?php echo $stocks['quantity_consumed'];?></td>                        
	            </tr>
	        <?php } ?>
	    </table>
