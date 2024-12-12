<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fileTrackings ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'File Trackings','modelClass'=>'FileTracking','options'=>array("sr_no"=>"Sr No","from"=>"From","to"=>"To","by"=>"By","current_status"=>"Current Status","changed_on"=>"Changed On","changetype"=>"Changetype","function"=>"Function","comment"=>"Comment","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'fileTrackings'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New File Tracking'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Project File'), array('controller' => 'project_files', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Project'), array('controller' => 'projects', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Milestone'), array('controller' => 'milestones', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="fileTrackings_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","from"=>"From","to"=>"To","by"=>"By","current_status"=>"Current Status","changed_on"=>"Changed On","changetype"=>"Changetype","function"=>"Function","comment"=>"Comment","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","from"=>"From","to"=>"To","by"=>"By","current_status"=>"Current Status","changed_on"=>"Changed On","changetype"=>"Changetype","function"=>"Function","comment"=>"Comment","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>