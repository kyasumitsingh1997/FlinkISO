<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="internalAuditDetails ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Internal Audit Details','modelClass'=>'InternalAuditDetail','options'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'pluralVar'=>'internalAuditDetails'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Internal Audit Detail'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Approved By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Internal Audit'), array('controller' => 'internal_audits', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Status User Id'), array('controller' => 'users', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="internalAuditDetails_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","nc_found"=>"Nc Found","question"=>"Question","findings"=>"Findings","opportunities_for_improvement"=>"Opportunities For Improvement","clause_number"=>"Clause Number","current_status"=>"Current Status","comments"=>"Comments","approved_by"=>"Approved By","prepared_by"=>"Prepared By","record_status"=>"Record Status"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>