<div id="processes_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="processes form col-md-8">
<h4><?php echo __('Edit Process'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
            <?php echo $this->Form->create('Process',array('role'=>'form','class'=>'form')); ?>
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
            <?php if($processTeams){ ?> 
            <?php foreach($processTeams as $teams): ?>
            <div class="col-md-12 well" id="team">
                
                <?php    
                
                    echo "<div class='col-md-12'>". $this->Form->input('ProcessTeam.name',array('label'=>'Team Name','value'=>$teams['ProcessTeam']['name'])) . '</div>';
                    echo "<div class='col-md-6'>".$this->Form->input('ProcessTeam.branch_id',array('name'=>'ProcessTeam.branch_id[]','options'=>$PublishedBranchList,'multiple','default'=>json_decode($teams['ProcessTeam']['branch_id']))) . '</div>'; 
                    echo "<div class='col-md-6'>".$this->Form->input('ProcessTeam.department_id',array('name'=>'ProcessTeam.department_id[]','options'=>$PublishedDepartmentList,'multiple','default'=>json_decode($teams['ProcessTeam']['department_id']))) . '</div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('ProcessTeam.team',array('name'=>'ProcessTeam.team[]','options'=>$PublishedUserList,'multiple','default'=>json_decode($teams['ProcessTeam']['team']))) . '</div>';         
                    echo "<div class='col-md-12'>".$this->Form->input('ProcessTeam.measurement_details',array('type'=>'textarea','value'=>$teams['ProcessTeam']['measurement_details'])) . '<p class="help">Describe how would you measure the target</p></div>'; 
                    echo "<div class='col-md-12'>".$this->Form->input('ProcessTeam.target',array('type'=>'textarea','value'=>$teams['ProcessTeam']['target'])) . '</div>'; 
                    
                    if($teams['ProcessTeam']['start_date'] == '1970-01-01 00:00:00' || $teams['ProcessTeam']['start_date'] == NULL){
                        echo "<div class='col-md-4'>".$this->Form->input('start_date') . '</div>';     
                    }else{
                        echo "<div class='col-md-4'>".$this->Form->input('start_date',array('value'=>date('Y-m-d',strtotime($teams['ProcessTeam']['start_date'])))) . '</div>'; 
                    }
                    if($teams['ProcessTeam']['end_date'] == '1970-01-01 00:00:00' || $teams['ProcessTeam']['end_date'] == NULL){
                        echo "<div class='col-md-4'>".$this->Form->input('end_date') . '</div>';     
                    }else{
                        echo "<div class='col-md-4'>".$this->Form->input('end_date',array('value'=>date('Y-m-d',strtotime($teams['ProcessTeam']['end_date'])))) . '</div>'; 
                    }
                    
                    echo "<div class='col-md-4'>".$this->Form->input('ProcessTeam.system_table',array('default'=>$teams['ProcessTeam']['system_table'])) . '</div>'; 
                    echo $this->Form->input('ProcessTeam.id',array('default'=>$teams['ProcessTeam']['id'])); 

        //hidden fields
        $this->Form->input('ProcessTeam.process_id',array('value'=>$this->request->params['pass'][0]))
    ?>

</div>
<?php endforeach; ?>
<?php }else{ ?> 
<div class="row well" id="team">
    
    <?php
        echo "<div class='col-md-12'>". $this->Form->input('ProcessTeam.name',array('label'=>'Team Name','value'=>$this->request->data['Process']['title'] .' team')) . '</div>';
        echo "<div class='col-md-6'>".$this->Form->input('ProcessTeam.branch_id',array('name'=>'ProcessTeam.branch_id[]','options'=>$PublishedBranchList,'multiple')) . '</div>'; 
        echo "<div class='col-md-6'>".$this->Form->input('ProcessTeam.department_id',array('name'=>'ProcessTeam.department_id[]','options'=>$PublishedDepartmentList,'multiple')) . '</div>'; 
        echo "<div class='col-md-12'>".$this->Form->input('ProcessTeam.team',array('name'=>'ProcessTeam.team[]','options'=>$PublishedUserList,'multiple')) . '</div>';         
        echo "<div class='col-md-12'>".$this->Form->input('ProcessTeam.measurement_details',array('type'=>'textarea')) . '<p class="help">Describe how would you measure the target</p></div>'; 
        echo "<div class='col-md-12'>".$this->Form->input('ProcessTeam.target',array('type'=>'textarea')) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('start_date',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('end_date',array()) . '</div>'; 
        echo "<div class='col-md-4'>".$this->Form->input('ProcessTeam.system_table',array()) . '</div>'; 
    ?>

</div>
<?php } ?>
<script type="text/javascript">
    $('#add_team').click(function(){
        $('#team').clone().prependTo( "#additional_divs" );
    });
</script>

<div id="additional_divs"></div>
<!-- <div class="col-md-12"  id="add_team"><span class="glyphicon glyphicon-plus pull-right"></span></div> -->
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
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
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#processes_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults();
    $().ready(function() {
        $('#ProcessEditForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ProcessEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ProcessEditForm').submit();
            }

        });
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
