<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
	<tr bgcolor="#FFFFFF">
	<?php foreach ($records['fields'] as $fields): ?>
		<th><strong><?php echo h($fields); ?></strong>&nbsp;</th>	
	<?php endforeach; ?>	
	</tr>
	<?php
		foreach ($records['records'] as $recs): ?>
		<tr bgcolor="#FFFFFF">
			<?php foreach ($recs as $value): ?>
				<td><?php echo h($value); ?>&nbsp;</td>	
			<?php endforeach; ?>	
		</tr>	
	<?php endforeach; ?>
</table>


	
