 <div id="milestones_ajax">
<?php echo $this->Session->flash();?>	
<div class="nav panel panel-default">
<div class="milestones form col-md-8">
<h4><?php echo __('Approve Milestone'); ?>		
		<?php echo $this->Html->link(__('List'), array('action' => 'index'),array('id'=>'list','class'=>'label btn-info')); ?>
		
		</h4>
<?php echo $this->Form->create('Milestone',array('role'=>'form','class'=>'form')); ?>
<div class="row">
			<?php
		echo "<div class='col-md-6'>".$this->Form->input('project_id',array()) . '</div>'; 
		echo "<div class='col-md-6'>".$this->Form->input('title',array()) . '</div>'; 
    echo "<div class='col-md-12'>".$this->Form->input('details',array()) . '</div>'; 
    echo "<div class='col-md-12'>".$this->Form->input('challenges',array()) . '</div>'; 
    echo "<div class='col-md-3'>".$this->Form->input('estimated_cost',array()) . '<div class="text-danger small" id="cost_adjust"></div></div>'; 
    echo "<div class='col-md-6'>".$this->Form->input('start_date',array('Label'=>'Date Range')) . '</div>'; 
    // echo "<div class='col-md-3'>".$this->Form->input('end_date',array()) . '</div>'; 
    if($this->Session->read('User.is_mr') or $this->Session->read('User.is_approver'))echo "<div class='col-md-6'>".$this->Form->input('current_status',array('options'=>array('Open','Close'))) . '</div>'; 
    else echo "<div class='col-md-6'>".$this->Form->input('current_status',array('options'=>array('Open','Close'),'disabled')) . '</div>'; 
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
	<?php echo $this->element('projecttimeline',array('project_details'=>$project_details));?>
	<p><?php echo $this->element('helps'); ?></p>
</div>
</div>
<?php echo $this->Js->get('#list');?>
<?php echo $this->Js->event('click',$this->Js->request(array('action' => 'index', 'ajax'),array('async' => true, 'update' => '#milestones_ajax')));?>

<?php echo $this->Js->writeBuffer();?>
</div>
<script>
    $.validator.setDefaults({
        submitHandler: function(form) {
            $(form).ajaxSubmit({
                url: "<?php echo Router::url('/', true); ?><?php echo $this->request->params['controller'] ?>/approve/<?php echo $this->request->params['pass'][0] ?>/<?php echo $this->request->params['pass'][1] ?>",
                type: 'POST',
                target: '#milestones_ajax',
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
        $('#MilestoneApproveForm').validate();        
    });
</script>
<?php
  $startDate = $this->request->data['Milestone']['start_date'];
  $endDate = $this->request->data["Milestone"]["end_date"];
?>

    <script> 
    $("#MilestoneStartDate").daterangepicker({
        format: 'MM/DD/YYYY',
        startDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["Milestone"]["start_date"]))?>',
        endDate: '<?php echo date("yyyy-MM-dd",strtotime($this->data["Milestone"]["end_date"]))?>',              
        minDate: '<?php echo date("yyyy-MM-dd",strtotime($project_details[0]["Project"]["start_date"]))?>',
        maxDate: '<?php echo date("yyyy-MM-dd",strtotime($project_details[0]["Project"]["end_date"]))?>',
        locale: {
            format: 'MM/DD/YYYY'
        },
    // startDate: 'd',
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
