


<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>


<div id="objectives_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="objectives form col-md-8">
<h4><?php echo __('Edit Objective'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('Objective',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('list_of_kpi_id',array()) . '</div>';
                    echo "<div class='col-md-12'>".$this->Form->input('list_of_kpi_ids',array('name'=>'data[Objective][list_of_kpi_ids][]', 'label'=>'Related KIPs', 'options'=>$listOfKpis,'multiple','selected'=>json_decode($this->data['Objective']['list_of_kpi_ids']))) . '</div>';
                    echo "<div class='col-md-6'>".$this->Form->input('clauses',array()) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('master_list_of_format_id',array('label'=>'Select Format (optional)')) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('objective',array()) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('desired_output',array()) . '</div>'; 
                    echo "<div class='col-md-12 hide'>".$this->Form->input('team',array()) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('requirments',array()) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('resource_requirments',array()) . '</div>'; 
                    //echo "<div class='col-md-6'>".$this->Form->input('owner_id',array()) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('schedule_id',array('label'=>'Monitoring Schedule')) . '</div>';
                    echo "<div class='col-md-6'>".$this->Form->input('branch_id',array('label'=>'Assigned to branch')) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('department_id',array('label'=>'Assigned to department')) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('employee_id',array('label'=>'Assigned to employee')) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('target_date',array()) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('current_status',array('options'=>array(0=>'Open',1=>'Closed'),'default'=>0)) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('evaluation_method',array()) . '</div>'; 
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#objectives_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults();
    $().ready(function() {
        $('#ObjectiveEditForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ObjectiveEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ObjectiveEditForm').submit();
            }

        });
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
