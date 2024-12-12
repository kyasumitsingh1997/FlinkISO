<h2><?php echo __('Env Evaluation'); ?></h2>
<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4" width="100%">
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Title'); ?></td>
		<td><?php echo h($envEvaluation['EnvEvaluation']['title']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Env Activity'); ?></td>
		<td><?php echo $envEvaluation['EnvActivity']['title']; ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Env Identification'); ?></td>
		<td><?php echo $envEvaluation['EnvIdentification']['title']; ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Score'); ?></td>
		<td><?php echo h($envEvaluation['EnvEvaluation']['score']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td colspan="2">
			<table class="table table-responsive table-condesed">
				<tr bgcolor="#FFFFFF">
					<?php foreach ($scores as $score) {
						echo "<td>". $score['EvaluationCriteria']['name'] ."</td>";
					} ?></tr>
					<tr bgcolor="#FFFFFF">
				<?php foreach ($scores as $score) {				
					echo "<td>".$score['EnvEvaluationScore']['score'] ."</td>";
					} ?></tr>				
			</table>			
		</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Aspect Details'); ?></td>
		<td><?php echo h($envEvaluation['EnvEvaluation']['aspect_details']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Impact Details'); ?></td>
		<td><?php echo h($envEvaluation['EnvEvaluation']['impact_details']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Prepared By'); ?></td>
		<td><?php echo h($envEvaluation['ApprovedBy']['name']); ?>&nbsp;</td>
	</tr>
	<tr bgcolor="#FFFFFF">
		<td><?php echo __('Approved By'); ?></td>
		<td><?php echo h($envEvaluation['ApprovedBy']['name']); ?>&nbsp;</td>
	</tr>
</table>