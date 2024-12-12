 <div id="envEvaluations_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="envEvaluations form col-md-8">
<h4><?php echo __('Approve Env Evaluation'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('EnvEvaluation',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-12'>".$this->Form->input('title') . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('env_activity_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('env_identification_id',array('style'=>'')) . '</div>';
		if($scores){
		$i = 0;
		foreach ($scores as $score) {
		 	echo "<div class='count' id='count'>";
		      echo "<div class='nav'><div class='col-md-8'><br /><br /><strong>".$score['EvaluationCriteria']['name']."</strong></div>";
		      echo "<div class='col-md-4'>".$this->Form->input('EnvEvaluationScore.'.$i.'.score',array('type'=>'text', 'value'=>$score['EnvEvaluationScore']['score'], 'id'=>$score['EvaluationCriteria']['id'],'onChange'=>'sum()')) . '</div>'; 
		      echo "</div></div>";
		      echo $this->Form->hidden('EnvEvaluationScore.'.$i.'.evaluation_criteria_id',array('value'=>$score['EvaluationCriteria']['id']));
		      echo $this->Form->hidden('EnvEvaluationScore.'.$i.'.id',array('value'=>$score['EnvEvaluationScore']['id']));
		      $i++;
		 } 	
		}else{
			$i = 0;
			foreach ($cats as $key => $value) {
		      echo "<div class='count' id='count'>";
		      echo "<div class='nav'><div class='col-md-8'><br /><br /><strong>".$value."</strong></div>";
		      echo "<div class='col-md-4'>".$this->Form->input('EnvEvaluationScore.'.$i.'.score',array('value'=>0, 'type'=>'text', 'id'=>$key,'onChange'=>'sum()')) . '</div>'; 
		      echo "</div></div>";
		      echo $this->Form->hidden('EnvEvaluationScore.'.$i.'.evaluation_criteria_id',array('value'=>$key));
		      $i++;
		    }
		}
	    echo "<div class='col-md-8'><br /><strong>Final Score</strong></div>"; 
		echo "<div class='col-md-4'>".$this->Form->input('score',array('type'=>'text')) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('aspect_details') . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('impact_details') . '</div>'; 
	?>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
<div class="row">
		

<?php

		
	if ($showApprovals && $showApprovals['show_panel'] == true) {
		
		echo $this->element('approval_form');
		
	} else {
		
		echo $this->Form->input('publish', array('label' => __('Publish')));
		
	}
?>
<?php echo $this->Form->submit(__('Submit'), array('div' => false, 'class' => 'btn btn-primary btn-success','id'=>'submit_id')); ?>
<?php echo $this->Html->image('indicator.gif', array('id' => 'submit-indicator')); ?>
<?php echo $this->Form->end(); ?>

<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> $("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); </script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#envEvaluations_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/approve/<?php echo $this->request->params['pass'][0] ?>/<?php echo $this->request->params['pass'][1] ?>",
                type: 'POST',
                target: '#envEvaluations_ajax',
                beforeSend: function(){
                   $("#submit_id").prop("disabled",true);
                    $("#submit-indicator").show();
                },
                complete: function() {
                   $("#submit_id").removeAttr("disabled");
                   $("#submit-indicator").hide();
                },
                error: function(request, status, error) {                    
                    alert('Action failed!');
                }
	    });
        }
    });
		$().ready(function() {
    $("#submit-indicator").hide();
        $('#EnvEvaluationApproveForm').validate();        
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>