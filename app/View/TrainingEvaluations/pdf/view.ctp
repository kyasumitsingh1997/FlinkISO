<h2><?php  echo __('Training Evaluation'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Training'); ?></td>
		<td>
			<?php echo $trainingEvaluation['Training']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Purpose Of Training'); ?></td>
		<td>
			<?php echo h($trainingEvaluation['TrainingEvaluation']['purpose_of_training']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Is It Fulfilled'); ?></td>
		<td>
			<?php echo h($trainingEvaluation['TrainingEvaluation']['is_it_fulfilled']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Informative'); ?></td>
		<td>
			<?php echo h($trainingEvaluation['TrainingEvaluation']['informative']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Improvement'); ?></td>
		<td>
			<?php echo h($trainingEvaluation['TrainingEvaluation']['improvement']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Content'); ?></td>
		<td>
			<?php echo h($trainingEvaluation['TrainingEvaluation']['content']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Elaboration'); ?></td>
		<td>
			<?php echo h($trainingEvaluation['TrainingEvaluation']['elaboration']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Comments'); ?></td>
		<td>
			<?php echo h($trainingEvaluation['TrainingEvaluation']['comments']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($trainingEvaluation['TrainingEvaluation']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $trainingEvaluation['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $trainingEvaluation['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($trainingEvaluation['TrainingEvaluation']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($trainingEvaluation['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $trainingEvaluation['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />