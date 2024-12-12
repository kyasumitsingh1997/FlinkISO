<h2><?php echo $pdf_model; ?></h2>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" style="font-size:14px">
	<tr bgcolor="#FFFFFF">
	<?php foreach ($records['fields'] as $fields): ?>
		<th><strong><?php echo h($fields); ?></strong>&nbsp;</th>
	<?php endforeach; ?>
	</tr>
	<?php
		foreach ($records['records'] as $recs): ?>
		<tr bgcolor="#FFFFFF">
			<?php foreach ($recs as $value): ?>
				<td><?php echo $value; ?>&nbsp;</td>
			<?php endforeach; ?>
		</tr>
	<?php endforeach; ?>
</table>