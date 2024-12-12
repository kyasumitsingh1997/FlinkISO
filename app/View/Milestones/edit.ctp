<?php
 if($project)$project_id = $project['Project']['id'];
 else $project_id = $project_details[0]['Project']['id'];?>
<div id="milestones_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="milestones form col-md-8">
<h4><?php echo __('Edit Milestone'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Current Project Details'), array('controller'=>'projects','action' => 'view',$project_id),array('id'=>'current_view','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Edit Project'), array('controller'=>'projects','action' => 'edit',$project_id),array('id'=>'current_view','class'=>'label btn-info')); ?>
		<?php echo $this->Html->link(__('Back To Projects'), array('controller'=>'projects','action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php 
  $milestone_cost = 0;
  $balance_cost = 0;
  if($milestones){
  	foreach ($milestones as $milestone) 
  { 
      $milestone_cost = $milestone['Milestone']['estimated_cost'] + $milestone_cost;      
  }
    $balance_cost = $project['Project']['project_cost'] - $milestone_cost;
  }else{
  	$balance_cost = $project['Project']['project_cost'];	
  }
?>		
<?php echo $this->Form->create('Milestone',array('role'=>'form','class'=>'form')); ?>
<div class="row">
		<?php
		if(isset($this->request->params['named']['project_id'])){ 
      echo $this->Form->hidden('project_id',array('default'=>$this->request->params['named']['project_id'])) ; 
		}else echo "<div class='col-md-6'>".$this->Form->input('project_id',array()) . '</div>';  
		echo "<div class='col-md-12'>".$this->Form->input('title',array()) . '</div>'; 
        echo "<div class='col-md-12'>".$this->Form->input('hidden',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>'; 
		echo "<div class='col-md-12'>".$this->Form->input('challenges',array()) . '</div>'; 
		echo "<div class='col-md-3'>".$this->Form->input('currency_id',array()). '</div>'; 
        echo "<div class='col-md-3'>".$this->Form->input('estimated_cost',array()) . '<div class="text-danger small" id="cost_adjust"></div></div>'; 
		echo "<div class='col-md-3'>".$this->Form->input('estimated_invoice',array()). '</div>'; 
        echo "<div class='col-md-3'>".$this->Form->input('start_date',array('Label'=>'Date Range')) . '</div>'; 

    // echo "<div class='col-md-12'>".$this->Form->input('Milestone.'.$key.'.Milestone.title',array()). '</div>';
    // $units = array('KLM','GRID','PO');
    echo "<div class='col-md-3'>".$this->Form->input('unit_id',array('options'=>$deliverableUnits)) . '</div>';
    // $milestoneTypes = array('KLM','Area','Files','Process','Tower');
    echo "<div class='col-md-3'>".$this->Form->input('milestone_type_id',array('options'=>$milestoneTypes)) . '</div>';
    
    echo "<div class='col-md-3'>".$this->Form->input('acceptable_errors',array('label'=>'Acceptable Errors (%)')). '</div>'; 

		// echo "<div class='col-md-3'>".$this->Form->input('acceptable_errors',array('label'=>'Acceptable Errors (%)')) . '</div>'; 
		if($this->Session->read('User.is_mr') or $this->Session->read('User.is_approver'))echo "<div class='col-md-3'>".$this->Form->input('current_status',array('options'=>$currentStatuses)) . '</div>'; 
    else echo "<div class='col-md-3'>".$this->Form->input('current_status',array('options'=>$currentStatuses)) . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('branch_id',array('style'=>'')) . '</div>'; 
		// echo "<div class='col-md-12'>".$this->Form->input('users') . '</div>'; 
		// echo "<div class='col-md-6'>".$this->Form->input('user_session_id',array('style'=>'')) . '</div>'; 
	?>
<?php
		echo $this->Form->input('id');
		echo $this->Form->hidden('History.pre_post_values', array('value'=>json_encode($this->data)));
		echo $this->Form->input('branchid', array('type' => 'hidden', 'value' => $this->Session->read('User.branch_id')));
		echo $this->Form->input('departmentid', array('type' => 'hidden', 'value' => $this->Session->read('User.department_id')));
		echo $this->Form->input('master_list_of_format_id', array('type' => 'hidden', 'value' => $documentDetails['MasterListOfFormat']['id']));
		?>

</div>
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
<div class="col-md-4">  
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#milestones_ajax')));?>
<?php echo $this->Js->writeBuffer();?>
</div>
<script>
	// $("#MilestoneEstimatedCost").on('change',function(){
 //      if($("#MilestoneEstimatedCost").val() > <?php echo $balance_cost; ?>){
 //        $("#MilestoneEstimatedCost").val(<?php echo $balance_cost; ?>);
 //        $("#cost_adjust").fadeIn();
 //        $("#cost_adjust").html('Cost can not be more than project cost.');
 //        $("#submit_id").prop("disabled",true);	
 //      }else{
 //        $("#cost_adjust").fadeOut();
 //        $("#submit_id").prop("disabled",false);
 //      }
 //    });
    $.validator.setDefaults();
    $().ready(function() {
        $('#MilestoneEditForm').validate();
        $("#submit-indicator").hide();
        $("#submit_id").click(function(){
            if($('#MilestoneEditForm').valid()){
                 $("#submit_id").prop("disabled",true);
                 $("#submit-indicator").show();
                $('#MilestoneEditForm').submit();
            }
        });
    });
</script>

    <script> 
    $("#MilestoneStartDate").daterangepicker({
        format: 'MM/DD/YYYY',
        startDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["Milestone"]["start_date"]))?>',
        endDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["Milestone"]["end_date"]))?>',              
        // minDate: '<?php echo date("yyyy-MM-dd",strtotime($project_details[0]["Project"]["start_date"]))?>',
        // maxDate: '<?php echo date("yyyy-MM-dd",strtotime($project_details[0]["Project"]["end_date"]))?>',
        locale: {
            format: 'MM/DD/YYYY'
        },
    // startDate: 'd',
    showDropdowns: true,
    maxYear: parseInt(moment().format('YYYY'),10),
    autoclose:true,


}); 
      // $("#MilestoneStartDate").datepicker({
      //     changeMonth: true,
      //     changeYear: true,
      //     format: 'yyyy-mm-dd',
      //     autoclose:true,
      //     todayHighlight:true,
      //     // startDate: '<?php echo date("Y-m-d",strtotime($startDate)); ?>',
      //     // endDate: '<?php echo date("Y-m-d",strtotime($endDate)); ?>',
      //   });
      // $("#MilestoneEndDate").datepicker({
      //     changeMonth: true,
      //     changeYear: true,
      //     format: 'yyyy-mm-dd',
      //     autoclose:true,
      //     // startDate: '<?php echo date("Y-m-d",strtotime($startDate)); ?>',
      //     // endDate: '<?php echo date("Y-m-d",strtotime($endDate)); ?>',
      //   });
      
      // $("#MilestoneStartDate").val("<?php echo $startDate; ?>");
      // $("#MilestoneEndDate").val("<?php echo $endDate; ?>");
    </script>
<?php echo $this->Html->script(array('jquery.validate.min', 'jquery-form.min')); ?>
<?php echo $this->fetch('script'); ?>
