<div class="environmentChecklistAnswers view">
<h2><?php echo __('Environment Checklist Answer'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sr No'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['sr_no']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Environment Checklist'); ?></dt>
		<dd>
			<?php echo $this->Html->link($environmentChecklistAnswer['EnvironmentChecklist']['id'], array('controller' => 'environment_checklists', 'action' => 'view', $environmentChecklistAnswer['EnvironmentChecklist']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Environment Questionnaire'); ?></dt>
		<dd>
			<?php echo $this->Html->link($environmentChecklistAnswer['EnvironmentQuestionnaire']['title'], array('controller' => 'environment_questionnaires', 'action' => 'view', $environmentChecklistAnswer['EnvironmentQuestionnaire']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Environment Questionnaire Category'); ?></dt>
		<dd>
			<?php echo $this->Html->link($environmentChecklistAnswer['EnvironmentQuestionnaireCategory']['name'], array('controller' => 'environment_questionnaire_categories', 'action' => 'view', $environmentChecklistAnswer['EnvironmentQuestionnaireCategory']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Details'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['details']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Answer'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['answer']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Publish'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['publish']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Record Status'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['record_status']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Status User Id'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['status_user_id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Soft Delete'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['soft_delete']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Branchid'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['branchid']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Departmentid'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['departmentid']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created By'); ?></dt>
		<dd>
			<?php echo $this->Html->link($environmentChecklistAnswer['CreatedBy']['name'], array('controller' => 'users', 'action' => 'view', $environmentChecklistAnswer['CreatedBy']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified By'); ?></dt>
		<dd>
			<?php echo $this->Html->link($environmentChecklistAnswer['ModifiedBy']['name'], array('controller' => 'users', 'action' => 'view', $environmentChecklistAnswer['ModifiedBy']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Approved By'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['approved_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Prepared By'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['prepared_by']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($environmentChecklistAnswer['EnvironmentChecklistAnswer']['modified']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('System Table'); ?></dt>
		<dd>
			<?php echo $this->Html->link($environmentChecklistAnswer['SystemTable']['name'], array('controller' => 'system_tables', 'action' => 'view', $environmentChecklistAnswer['SystemTable']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Master List Of Format'); ?></dt>
		<dd>
			<?php echo $this->Html->link($environmentChecklistAnswer['MasterListOfFormat']['title'], array('controller' => 'master_list_of_formats', 'action' => 'view', $environmentChecklistAnswer['MasterListOfFormat']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Company'); ?></dt>
		<dd>
			<?php echo $this->Html->link($environmentChecklistAnswer['Company']['name'], array('controller' => 'companies', 'action' => 'view', $environmentChecklistAnswer['Company']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Environment Checklist Answer'), array('action' => 'edit', $environmentChecklistAnswer['EnvironmentChecklistAnswer']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Environment Checklist Answer'), array('action' => 'delete', $environmentChecklistAnswer['EnvironmentChecklistAnswer']['id']), array(), __('Are you sure you want to delete # %s?', $environmentChecklistAnswer['EnvironmentChecklistAnswer']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Environment Checklist Answers'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Environment Checklist Answer'), array('action' => 'add')); ?> </li>
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
