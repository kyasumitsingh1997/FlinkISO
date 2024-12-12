<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="environmentChecklists_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="environmentChecklists form col-md-8">
<h4>Add Environment Checklist</h4>
<?php echo $this->Form->create('EnvironmentChecklist',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('date_created',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('branch_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('department_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('employee_id',array()) . '</div>'; 
    echo "</div><div class='row'><div class='col-md-12'>";
    
    echo "<table classs='table table-responsive table-bordered'>";
    $i=0;
    foreach ($questions as $key => $question) {
      echo "<tr><th colspan='2'><h4>".$question['name']."</h4></th></tr>";
      foreach ($question['questions'] as $q_key=>$q_name) {      
        echo "<tr>";
        echo "<td><strong>".$q_name."</strong></td>";
        echo "<td width='120px'>".$this->Form->input('EnvironmentChecklistAnswer.'.$i.'.answer',array('legend'=>false, 'type'=>'radio', 'options'=>array( 0=>'No',1=>'Yes'), 'default'=>false)) . '</td>'; 
        echo $this->Form->hidden('EnvironmentChecklistAnswer.'.$i.'.environment_questionnaire_category_id',array('value'=>$key));
        echo $this->Form->hidden('EnvironmentChecklistAnswer.'.$i.'.environment_questionnaire_id',array('value'=>$q_key));
        echo "</tr><tr><td colspan='2'>".$this->Form->input('EnvironmentChecklistAnswer.'.$i.'.details',array('rows'=>2, 'label'=>false)) . '</td>'; 
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
		//echo "<div class='col-md-6'>".$this->Form->input('division_id',array()) . '</div>'; 
	?>
</fieldset>
<?php
    echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
    echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
    echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?></div>
<div class="">
<?php


	if ($showApprovals && $showApprovals['show_panel'] == true) {

		echo $this->element('approval_form');

	} else {

		echo $this->Form->input('publish', array('label' => __('Publish')));

	}?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#environmentChecklists_ajax','async' => 'false')); ?>
<?php echo $this->Form->end(); ?>
<?php echo $this->Js->writeBuffer();?>
</div>
</div>
<script> 
	$("[name*='date']").datepicker({
      changeMonth: true,
      changeYear: true,
      format: 'yyyy-mm-dd',
      autoclose:true,
    }); 
</script>
<div class="col-md-4">
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax",
                type: 'POST',
                target: '#main',
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
        $('#EnvironmentChecklistAddAjaxForm').validate();        
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
