<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectQueryResponses ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Query Responses','modelClass'=>'ProjectQueryResponse','options'=>array("sr_no"=>"Sr No","name"=>"Name","level"=>"Level","raised_by"=>"Raised By","response"=>"Response","sent_to_client"=>"Sent To Client","client_response"=>"Client Response","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'projectQueryResponses'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Project Query Response'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Project Query'), array('controller' => 'project_queries', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="projectQueryResponses_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","level"=>"Level","raised_by"=>"Raised By","response"=>"Response","sent_to_client"=>"Sent To Client","client_response"=>"Client Response","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","level"=>"Level","raised_by"=>"Raised By","response"=>"Response","sent_to_client"=>"Sent To Client","client_response"=>"Client Response","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>