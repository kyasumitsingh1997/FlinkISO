<h2><?php  echo __('Product'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Name'); ?></td>
		<td>
			<?php echo h($product['Product']['name']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Description'); ?></td>
		<td>
			<?php echo h($product['Product']['description']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Branch'); ?></td>
		<td>
			<?php echo $product['Branch']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Department'); ?></td>
		<td>
			<?php echo $product['Department']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($product['Product']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $product['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $product['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($product['Product']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($product['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $product['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<p>&nbsp;</p>

	<h3><?php echo __('Related Product Materials'); ?></h3>
	<?php if (!empty($product['ProductMaterial'])): ?>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
		<th><?php echo __('Material'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Approved By'); ?></th>
		<th><?php echo __('Prepared By'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<!--<th><?php // echo __('Master List Of Format Id'); ?></th>-->
	</tr>
	<?php
		$i = 0;
		foreach ($prodMatDetails as $productMaterial): ?>
		<tr bgcolor="#FFFFFF">
			<td><?php echo $productMaterial['Material']['name']; ?></td>
			<td><?php echo $productMaterial['Material']['created']; ?></td>
			<td><?php echo $productMaterial['ApprovedBy']['name']; ?></td>
			<td><?php echo $productMaterial['PreparedBy']['name']; ?></td>
			<td><?php echo $productMaterial['Material']['modified']; ?></td>
			<!--<td><?php //echo $productMaterial['MasterListOfFormat']['title']; ?></td>-->
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>