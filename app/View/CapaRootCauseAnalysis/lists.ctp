<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="capaRootCauseAnalysis ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Capa Root Cause Analysis','modelClass'=>'CapaRootCauseAnalysi','options'=>array("sr_no"=>"Sr No","root_cause_details"=>"Root Cause Details","determined_by"=>"Determined By","determined_on_date"=>"Determined On Date","root_cause_remarks"=>"Root Cause Remarks","proposed_action"=>"Proposed Action","action_assigned_to"=>"Action Assigned To","action_completed_on_date"=>"Action Completed On Date","action_completion_remarks"=>"Action Completion Remarks","effectiveness"=>"Effectiveness","closure_remarks"=>"Closure Remarks","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'pluralVar'=>'capaRootCauseAnalysis'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Capa Root Cause Analysi'), array('action' => 'add_ajax', $capaId)); ?></li>
					<li><?php // echo $this->Html->link(__('Add Corrective Preventive Action'), array('controller' => 'corrective_preventive_actions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="capaRootCauseAnalysis_tab_ajax"></div>
</div>

<script>
  $(function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
	ui.jqXHR.error(function() {
	  ui.panel.html(
	    "Error Loading ... " +
	    "Please contact administrator." );
	});
      }
    });
  });
</script>

<?php echo $this->element('export'); ?>
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","root_cause_details"=>"Root Cause Details","determined_by"=>"Determined By","determined_on_date"=>"Determined On Date","root_cause_remarks"=>"Root Cause Remarks","proposed_action"=>"Proposed Action","action_assigned_to"=>"Action Assigned To","action_completed_on_date"=>"Action Completed On Date","action_completion_remarks"=>"Action Completion Remarks","effectiveness"=>"Effectiveness","closure_remarks"=>"Closure Remarks","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","root_cause_details"=>"Root Cause Details","determined_by"=>"Determined By","determined_on_date"=>"Determined On Date","root_cause_remarks"=>"Root Cause Remarks","proposed_action"=>"Proposed Action","action_assigned_to"=>"Action Assigned To","action_completed_on_date"=>"Action Completed On Date","action_completion_remarks"=>"Action Completion Remarks","effectiveness"=>"Effectiveness","closure_remarks"=>"Closure Remarks","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>