<div class="environmentChecklistAnswers form">
<?php echo $this->Form->create('EnvironmentChecklistAnswer'); ?>
	<fieldset>
		<legend><?php echo __('Add Environment Checklist Answer'); ?></legend>
	<?php
		echo $this->Form->input('sr_no');
		echo $this->Form->input('environment_checklist_id');
		echo $this->Form->input('environment_questionnaire_id');
		echo $this->Form->input('environment_questionnaire_category_id');
		echo $this->Form->input('details');
		echo $this->Form->input('answer');
		echo $this->Form->input('publish');
		echo $this->Form->input('record_status');
		echo $this->Form->input('status_user_id');
		echo $this->Form->input('soft_delete');
		echo $this->Form->input('branchid');
		echo $this->Form->input('departmentid');
		echo $this->Form->input('created_by');
		echo $this->Form->input('modified_by');
		echo $this->Form->input('approved_by');
		echo $this->Form->input('prepared_by');
		echo $this->Form->input('system_table_id');
		echo $this->Form->input('master_list_of_format_id');
		echo $this->Form->input('company_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Environment Checklist Answers'), array('action' => 'index')); ?></li>
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
