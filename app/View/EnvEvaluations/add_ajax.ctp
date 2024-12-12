<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
<div id="envEvaluations_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="envEvaluations form col-md-8">
<h4>Add Env Evaluation</h4>
<?php echo $this->Form->create('EnvEvaluation',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
			<?php
		echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('env_activity_id',array()) . '</div>'; 
		echo "<div class='col-md-12' id='idendification'></div>"; 
    $i = 0;
    foreach ($cats as $key => $value) {
      echo "<div class='nav'><div class='count' id='count'>";
      echo "<div class='col-md-8'><br /><br /><strong>".$value."</strong></div>";
      echo "<div class='col-md-4'>".$this->Form->input('EnvEvaluationScore.'.$i.'.score',array('value'=>0, 'id'=>$key,'onChange'=>'sum()')) . '</div>'; 
      echo "</div></div>";
      echo $this->Form->hidden('EnvEvaluationScore.'.$i.'.evaluation_criteria_id',array('value'=>$key));
      $i++;
    }
    echo "<div class='col-md-8'><br /><strong>Final Score</strong></div>"; 
    echo "<div class='col-md-4'>".$this->Form->input('score',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('aspect_details',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('impact_details',array()) . '</div>'; 
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

	}?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#envEvaluations_ajax','async' => 'false')); ?>
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

  $().ready(function(){
    $('#EnvEvaluationEnvActivityId').change(function(){
      $('#idendification').load("<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/get_identifications/"+ $('#EnvEvaluationEnvActivityId').val());
    });
  });

      function sum () {
        var n;
        n = 0;
        $('#count input').each(function() {
          if(parseInt(this.value) > 10){
            alert(this.value);
            this.value = 0;
            return false;
          }
          n = n + parseInt(this.value);
        });        
        $("#EnvEvaluationScore").val(n);
      }      
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
        $('#EnvEvaluationAddAjaxForm').validate();        
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>