 <div id="evaluationCriterias_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="evaluationCriterias form col-md-8">
<h4><?php echo __('Edit Evaluation Criteria'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('EvaluationCriteria',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		$options = array('Very Low','Low','Medium','High','Very High','Negligible','Moderate','Severe','Fatal','Within acceptable limit','Marginal at acceptable limit','Out of acceptable limit');
		echo "<div class='col-md-6'>".$this->Form->input('name',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('aspect_category_id',array()) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_1',array('value'=>1)) . '</div>';
		echo "<div class='col-md-6'>".$this->Form->input('scale_1_value',array('options'=>$options)) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_2',array('value'=>2)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('scale_2_value',array('options'=>$options)) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_3',array('value'=>3)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('scale_3_value',array('options'=>$options)) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_4',array('value'=>4)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('scale_4_value',array('options'=>$options)) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_5',array('value'=>5)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('scale_5_value',array('options'=>$options)) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_6',array('value'=>6)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('scale_6_value',array('options'=>$options)) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_7',array('value'=>7)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('scale_7_value',array('options'=>$options)) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_8',array('value'=>8)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('scale_8_value',array('options'=>$options)) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_9',array('value'=>9)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('scale_9_value',array('options'=>$options)) . '</div></div>'; 
		
		echo "<div class='row'><div class='col-md-6'>".$this->Form->input('scale_10',array('value'=>10)) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('scale_10_value',array('options'=>$options)) . '</div></div>';		 
	?>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#evaluationCriterias_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults();
    $().ready(function() {
        $('#EvaluationCriteriaEditForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#EvaluationCriteriaEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#EvaluationCriteriaEditForm').submit();
            }

        });
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>