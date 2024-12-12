<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="capaInvestigations ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Capa Investigations','modelClass'=>'CapaInvestigation','options'=>array("sr_no"=>"Sr No","details"=>"Details","target_date"=>"Target Date","proposed_action"=>"Proposed Action","completed_on_date"=>"Completed On Date","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'pluralVar'=>'capaInvestigations'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Capa Investigation'), array('action' => 'add_ajax', $capaId)); ?></li>
					<li><?php // echo $this->Html->link(__('Add Approved By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Corrective Preventive Action Id'), array('controller' => 'corrective_preventive_actions', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Status User Id'), array('controller' => 'users', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="capaInvestigations_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","details"=>"Details","target_date"=>"Target Date","proposed_action"=>"Proposed Action","completed_on_date"=>"Completed On Date","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","details"=>"Details","target_date"=>"Target Date","proposed_action"=>"Proposed Action","completed_on_date"=>"Completed On Date","current_status"=>"Current Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>