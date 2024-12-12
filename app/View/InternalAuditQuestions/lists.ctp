<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="internalAuditQuestions ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Internal Audit Questions','modelClass'=>'InternalAuditQuestion','options'=>array("sr_no"=>"Sr No","clause"=>"Clause","title"=>"Title","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'internalAuditQuestions'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Internal Audit Question'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Approved By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Department'), array('controller' => 'departments', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Status User Id'), array('controller' => 'users', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="internalAuditQuestions_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","clause"=>"Clause","title"=>"Title","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","clause"=>"Clause","title"=>"Title","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>