<fieldset>
	<?php
	for($i = 1; $i<=10;$i++){
		echo "<div class='col-md-12'><h4> Step " . $i ."</h4></div>";
		echo $this->Form->hidden($branch_id . '.AutoApprovalStep'.'.'.$i.'.step_number',array('value'=>$i,'label'=>'&nbsp;')); 
		echo "<div class='col-md-6'>".$this->Form->input($branch_id . '.AutoApprovalStep'.'.'.$i.'.name',array('value'=>'Step '. $i)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input($branch_id . '.AutoApprovalStep'.'.'.$i.'.user_id',array('class'=>'chosen-select','label'=>'User to send','options'=>$fwd_users)) . '</div>';
		echo "<div class='col-md-12'>".$this->Form->input($branch_id . '.AutoApprovalStep'.'.'.$i.'.details',array('label'=>'Any other details/notes  <small>(Optional)</small>')) . '</div>'; 
		echo "<div class='col-md-7'>".$this->Form->input($branch_id . '.AutoApprovalStep'.'.'.$i.'.allow_approval',array('label'=>'Allow approval at this stage and skip rest of the stages?')) . '</div>';
		echo "<div class='col-md-5'>".$this->Form->input($branch_id . '.AutoApprovalStep'.'.'.$i.'.show_details',array('label'=>'Share details/notes with the user?')) . '</div>';
	
		echo $this->Form->hidden($branch_id . '.AutoApprovalStep'.'.'.$i.'.branch_id',array('value'=>$branch_id));
	}
		
	?>
</fieldset>
<script type="text/javascript">
$().ready(function(){
	jQuery(".chosen-select").chosen();
})
</script>