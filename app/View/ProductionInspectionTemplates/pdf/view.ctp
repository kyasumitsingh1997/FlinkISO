<h4><?php echo __('View Production Inspection Template'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
		<tr bgcolor="#FFFFFF">
			<td><?php echo __('Name'); ?></td>
			<td><?php echo h($productionInspectionTemplate['ProductionInspectionTemplate']['name']); ?>&nbsp;</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td colspan="2"><?php echo __('Template'); ?></td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td colspan="2"><?php echo $productionInspectionTemplate['ProductionInspectionTemplate']['template']; ?>&nbsp;</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><?php echo __('Prepared By'); ?></td>
			<td><?php echo h($productionInspectionTemplate['ApprovedBy']['name']); ?>&nbsp;</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><?php echo __('Approved By'); ?></td>
			<td><?php echo h($productionInspectionTemplate['ApprovedBy']['name']); ?>&nbsp;</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><?php echo __('Publish'); ?></td>
			<td>
				<?php if($productionInspectionTemplate['ProductionInspectionTemplate']['publish'] == 1) { ?>
					<span class="fa fa-check"></span>
				<?php } else { ?>
					<span class="fa fa-ban"></span>
				<?php } ?>&nbsp;
			</td>
		</tr>
		<tr bgcolor="#FFFFFF">
			<td><?php echo __('Soft Delete'); ?></td>
			<td>
				<?php if($productionInspectionTemplate['ProductionInspectionTemplate']['soft_delete'] == 1) { ?>
					<span class="fa fa-check"></span>
				<?php } else { ?>
					<span class="fa fa-ban"></span>
				<?php } ?>&nbsp;
			</td>
	</tr>
</table>
