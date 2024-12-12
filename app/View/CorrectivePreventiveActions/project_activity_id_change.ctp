<?php echo $this->Form->input('project_activity_id', array(
	'name'=>'data[CorrectivePreventiveAction][project_activity_id]',
	'id'=>'CorrectivePreventiveActionProjectActivityId',
	'options'=>$projectActivities, 
	'style' => 'width:100%', 
	'label' => __('Select Project Activity'))); ?>

<script type="text/javascript">
	$("#CorrectivePreventiveActionProjectActivityId").chosen();
</script>	