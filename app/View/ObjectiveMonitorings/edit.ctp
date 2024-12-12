 <div id="objectiveMonitorings_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="objectiveMonitorings form col-md-8">
<h4><?php echo __('Edit Objective Monitoring'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('ObjectiveMonitoring',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('objective_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('process_id',array('style'=>'')) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('analysis') . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('improvements_required') . '</div>'; 
	?>
	<?php if($this->request->data['ObjectiveMonitoring']['objective_id'] != '' && $$this->request->data['ObjectiveMonitoring']['process_id'] != ''){ ?>
        <script>
          
          $(function() {
            
            $( "#completion_slider" ).slider({
              range: "max",
              min: 1,
              max: 100,
              value:<?php echo $$this->request->data['ObjectiveMonitoring']['completion'] ?>,       
              slide: function( event, ui ) {
                $( "#ObjectiveMonitoringCompletion" ).val( ui.value );
                $( "#ObjectiveMonitoringCompletionh1" ).html("<h1>" + ui.value + "%</h1>");
              }
            });
            $( "#ObjectiveMonitoringCompletion" ).val( $( "#completion_slider" ).slider( "value" ) );
          });
        </script>
        <?php } else { ?> 
        <script type="text/javascript">
        $(function() {
          $('#ObjectiveMonitoringObjectiveId').on('change',function(){
            $('#objectiveMonitorings_ajax').load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax/objective_id:' + $('#ObjectiveMonitoringObjectiveId').val());
          });
          $('#ObjectiveMonitoringProcessId').on('change',function(){
            $('#objectiveMonitorings_ajax').load('<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/add_ajax/objective_id:' + $('#ObjectiveMonitoringObjectiveId').val() + '/process_id:' + $('#ObjectiveMonitoringProcessId').val());
          });
          });
        </script>
        <?php } ?>
        
    
    <?php if($this->request->data['ObjectiveMonitoring']['objective_id'] != '' && $$this->request->data['ObjectiveMonitoring']['process_id'] != ''){ ?>
        <div class="col-md-12"><h4><?php echo __('Task Completion'); ?></h4></div>  
        <div class="col-md-10">
          <p><?php echo $this->Form->hidden('completion',array()) ; ?></p>
          <div id="completion_slider"></div>
        </div>
        <div class="col-md-2" id = "ObjectiveMonitoringCompletionh1"><h1><?php echo $result ?>%</h1></div>
    <?php }else{ ?> 
    <div class="col-md-12" id = "">Select Process First</div>
    <?php } ?> 
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
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#objectiveMonitorings_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults();
    $().ready(function() {
        $('#ObjectiveMonitoringEditForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#ObjectiveMonitoringEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#ObjectiveMonitoringEditForm').submit();
            }

        });
    });
</script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
