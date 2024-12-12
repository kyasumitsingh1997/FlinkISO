 <div id="environmentChecklists_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="environmentChecklists form col-md-8">
<h4><?php echo __('Approve Environment Checklist'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('EnvironmentChecklist',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('date_created') . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('branch_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('department_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('style'=>'')) . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('environment_questionnaire_category_id',array('style'=>'')) . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('environment_questionnaire_id',array('style'=>'')) . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('answer') . '</div>'; 
		// echo "<div class='col-md-12'>".$this->Form->input('details') . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('division_id',array('style'=>'')) . '</div>'; 
		echo "</div><div class='row'><div class='col-md-12'>";
    
    echo "<table classs='table table-responsive table-bordered'>";
    $i=0;
    foreach ($questions as $key => $question) {
      echo "<tr><th colspan='2'><h4>".$question['name']."</h4></th></tr>";
      foreach ($question['questions'] as $q) {
      debug($q);      
        echo "<tr>";
        echo "<td><strong>".$q['EnvironmentQuestionnaire']['title']."</strong></td>";
        echo "<td width='120px'>".$this->Form->input('EnvironmentChecklistAnswer.'.$i.'.answer',array('legend'=>false, 'type'=>'radio', 'options'=>array( 0=>'No',1=>'Yes'), 'default'=>$q['EnvironmentChecklistAnswer']['answer'])) . '</td>'; 
        echo $this->Form->hidden('EnvironmentChecklistAnswer.'.$i.'.environment_questionnaire_category_id',array('value'=>$key));
        echo $this->Form->hidden('EnvironmentChecklistAnswer.'.$i.'.environment_questionnaire_id',array('value'=>$q['EnvironmentChecklistAnswer']['environment_questionnaire_id']));
        echo "</tr><tr><td colspan='2'>".$this->Form->input('EnvironmentChecklistAnswer.'.$i.'.details',array('rows'=>2, 'label'=>false,'default'=>$q['EnvironmentChecklistAnswer']['details'])) . '</td>'; 
        echo "</tr>";
        $i++;
      }
      
      //echo "<div class='col-md-6'>".$this->Form->input('environment_questionnaire_category_id',array()) . '</div>'; 
      //echo "<div class='col-md-6'>".$this->Form->input('environment_questionnaire_id',array()) . '</div>'; 
      //echo "<div class='col-md-6'>".$this->Form->input('answer',array()) . '</div>'; 
      //echo "<div class='col-md-6'>".$this->Form->input('details',array()) . '</div>'; 
    
    }
    echo "</table>";
    echo "</div>";
	?>

<div class="">
		

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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#environmentChecklists_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/approve/<?php echo $this->request->params['pass'][0] ?>/<?php echo $this->request->params['pass'][1] ?>",
                type: 'POST',
                target: '#environmentChecklists_ajax',
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
        $('#EnvironmentChecklistApproveForm').validate();        
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
