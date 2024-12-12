<div class="environmentChecklistAnswers index">
	<h2><?php echo __('Environment Checklist Answers'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('sr_no'); ?></th>
			<th><?php echo $this->Paginator->sort('environment_checklist_id'); ?></th>
			<th><?php echo $this->Paginator->sort('environment_questionnaire_id'); ?></th>
			<th><?php echo $this->Paginator->sort('environment_questionnaire_category_id'); ?></th>
			<th><?php echo $this->Paginator->sort('details'); ?></th>
			<th><?php echo $this->Paginator->sort('answer'); ?></th>
			<th><?php echo $this->Paginator->sort('publish'); ?></th>
			<th><?php echo $this->Paginator->sort('record_status'); ?></th>
			<th><?php echo $this->Paginator->sort('status_user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('soft_delete'); ?></th>
			<th><?php echo $this->Paginator->sort('branchid'); ?></th>
			<th><?php echo $this->Paginator->sort('departmentid'); ?></th>
			<th><?php echo $this->Paginator->sort('created_by'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_by'); ?></th>
			<th><?php echo $this->Paginator->sort('approved_by'); ?></th>
			<th><?php echo $this->Paginator->sort('prepared_by'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th><?php echo $this->Paginator->sort('system_table_id'); ?></th>
			<th><?php echo $this->Paginator->sort('master_list_of_format_id'); ?></th>
			<th><?php echo $this->Paginator->sort('company_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($environmentChecklistAnswers as $environmentChecklistAnswer): ?>
	<tr>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['id']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['sr_no']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($environmentChecklistAnswer['EnvironmentChecklist']['id'], array('controller' => 'environment_checklists', 'action' => 'view', $environmentChecklistAnswer['EnvironmentChecklist']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($environmentChecklistAnswer['EnvironmentQuestionnaire']['title'], array('controller' => 'environment_questionnaires', 'action' => 'view', $environmentChecklistAnswer['EnvironmentQuestionnaire']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($environmentChecklistAnswer['EnvironmentQuestionnaireCategory']['name'], array('controller' => 'environment_questionnaire_categories', 'action' => 'view', $environmentChecklistAnswer['EnvironmentQuestionnaireCategory']['id'])); ?>
		</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['details']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['answer']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['publish']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['record_status']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['status_user_id']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['soft_delete']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['branchid']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['departmentid']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($environmentChecklistAnswer['CreatedBy']['name'], array('controller' => 'users', 'action' => 'view', $environmentChecklistAnswer['CreatedBy']['id'])); ?>
		</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['created']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($environmentChecklistAnswer['ModifiedBy']['name'], array('controller' => 'users', 'action' => 'view', $environmentChecklistAnswer['ModifiedBy']['id'])); ?>
		</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['approved_by']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['prepared_by']); ?>&nbsp;</td>
		<td><?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['modified']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($environmentChecklistAnswer['SystemTable']['name'], array('controller' => 'system_tables', 'action' => 'view', $environmentChecklistAnswer['SystemTable']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($environmentChecklistAnswer['MasterListOfFormat']['title'], array('controller' => 'master_list_of_formats', 'action' => 'view', $environmentChecklistAnswer['MasterListOfFormat']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($environmentChecklistAnswer['Company']['name'], array('controller' => 'companies', 'action' => 'view', $environmentChecklistAnswer['Company']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $environmentChecklistAnswer['EnvironmentChecklistAnswer']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $environmentChecklistAnswer['EnvironmentChecklistAnswer']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $environmentChecklistAnswer['EnvironmentChecklistAnswer']['id']), array('confirm' => __('Are you sure you want to delete # %s?', $environmentChecklistAnswer['EnvironmentChecklistAnswer']['id']))); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Environment Checklist Answer'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Environment Checklists'), array('controller' => 'environment_checklists', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Environment Checklist'), array('controller' => 'environment_checklists', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Environment Questionnaires'), array('controller' => 'environment_questionnaires', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Environment Questionnaire'), array('controller' => 'environment_questionnaires', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Environment Questionnaire Categories'), array('controller' => 'environment_questionnaire_categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Environment Questionnaire Category'), array('controller' => 'environment_questionnaire_categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List System Tables'), array('controller' => 'system_tables', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New System Table'), array('controller' => 'system_tables', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Master List Of Formats'), array('controller' => 'master_list_of_formats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Master List Of Format'), array('controller' => 'master_list_of_formats', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Companies'), array('controller' => 'companies', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Company'), array('controller' => 'companies', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Created By'), array('controller' => 'users', 'action' => 'add')); ?> </li>
	</ul>
</div>
