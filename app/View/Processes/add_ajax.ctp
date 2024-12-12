


<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>


<div id="processes_ajax">
<?php echo $this->Session->flash();?><div class="nav">
<div class="processes form col-md-8">
<h4>Add Process</h4>
<?php echo $this->Form->create('Process',array('role'=>'form','class'=>'form','default'=>false)); ?>
<div class="row">
		<fieldset>
			<?php
		echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('objective_id',array('default'=>$objective['Objective']['id'])) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('clauses',array('value'=>$objective['Objective']['clauses'])) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('process_requirments',array('label'=>'Process Requirement')) . '</div>'; 
		echo "<div class='col-md-4'>".$this->Form->input('input_process_id',array('label'=>__('Input Process <small>(Optional)</small>'))) . '</div>'; 
		echo "<div class='col-md-4'>".$this->Form->input('output_process_id',array('label'=>__('Out Process <small>(Optional)</small>'))) . '</div>'; 
		echo "<div class='col-md-4'>".$this->Form->input('schedule_id',array('label'=>__('Monitoring Schedule'))) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('owner_id',array('label'=>__('Process Owner'))) . '</div>';  
        echo "<div class='col-md-6'>".$this->Form->input('applicable_to',array('options'=>array('Branch','Department','Both'))) . '</div>'; 
	?>
</fieldset>
</div>
<h4>Add Team <small>You can add one more teams</small></h4>
<p>If the process is related to branch, select single branch and multiple departments, if process is related to department, select single department & multiple branches. </p>
<div class="row well" id="team">
    
    <?php
        echo "<div class='col-md-12'>". $this->Form->input('ProcessTeam.name',array('label'=>'Team Name')) . '</div>';
        echo "<div class='col-md-6'>".$this->Form->input('ProcessTeam.branch_id',array('name'=>'data[ProcessTeam][branch_id][]', 'options'=>$PublishedBranchList,'multiple')) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('ProcessTeam.department_id',array('name'=>'data[ProcessTeam][department_id][]','options'=>$PublishedDepartmentList,'multiple')) . '</div>'; 
        echo "<div class='col-md-12'>".$this->Form->input('ProcessTeam.team',array('name'=>'data[ProcessTeam][team][]','options'=>$PublishedUserList,'multiple')) . '</div>'; 
        echo "<div class='col-md-12'>".$this->Form->input('ProcessTeam.measurement_details',array('type'=>'textarea')) . '<p class="help">Describe how would you measure the target</p></div>'; 
        echo "<div class='col-md-12'>".$this->Form->input('ProcessTeam.target',array('type'=>'textarea')) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('ProcessTeam.start_date',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('ProcessTeam.end_date',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('ProcessTeam.system_table',array()) . '</div>'; 

        //hidden fields
        $this->Form->input('ProcessTeam.process_id',array('value'=>$this->request->params['pass'][0]))
    ?>

</div>
<script type="text/javascript">
    $('#add_team').click(function(){
        $('#team').clone().prependTo( "#additional_divs" );
    });
</script>
<div id="additional_divs"></div>
<div class="col-md-12 hide"  id="add_team"><span class="glyphicon glyphicon-plus pull-right"></span></div>
<div class="row">
<?php
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
            ?><?php echo $this->Form->submit('Submit',array('div'=>false,'class'=>'btn btn-primary btn-success','update'=>'#processes_ajax','async' => 'false')); ?>
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
    <p><?php echo $this->element('objectiv_details'); ?></p>
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
        $('#ProcessAddAjaxForm').validate();        
    });
</script><script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
