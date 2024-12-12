<h2><?php  echo __('Competency Mapping'); ?></h2>
	<table cellpadding="2" cellspacing="1" bgcolor="#D4D4D4">
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Employee'); ?></td>
		<td>
			<?php echo $competencyMapping['Employee']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Education'); ?></td>
		<td>
			<?php echo $competencyMapping['Education']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Experience Required'); ?></td>
		<td>
			<?php echo h($competencyMapping['CompetencyMapping']['experience_required']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Skills Required'); ?></td>
		<td>
			<?php echo h($competencyMapping['CompetencyMapping']['skills_required']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Actual Education'); ?></td>
		<td>
			<?php echo $competencyMapping['ActualEducation']['title']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Actual Experience'); ?></td>
		<td>
			<?php echo h($competencyMapping['CompetencyMapping']['actual_experience']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Skills Possesed'); ?></td>
		<td>
			<?php echo h($competencyMapping['CompetencyMapping']['skills_possesed']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Remarks'); ?></td>
		<td>
			<?php echo h($competencyMapping['CompetencyMapping']['remarks']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Created'); ?></td>
		<td>
			<?php echo h($competencyMapping['CompetencyMapping']['created']); ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Approved By'); ?></td>
		<td>
			<?php echo $competencyMapping['ApprovedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Prepared By'); ?></td>
		<td>
			<?php echo $competencyMapping['PreparedBy']['name']; ?>
			&nbsp;
		</td></tr>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Modified'); ?></td>
		<td>
			<?php echo h($competencyMapping['CompetencyMapping']['modified']); ?>
			&nbsp;
		</td></tr>
                <?php if(!empty($competencyMapping['MasterListOfFormat']['title'])){ ?>
		<tr bgcolor="#FFFFFF"><td class="head-strong"><?php echo __('Master List Of Format'); ?></td>
		<td>
			<?php echo $competencyMapping['MasterListOfFormat']['title']; ?>
			&nbsp;
		</td></tr>
                <?php } ?>
	</table>
	<br />