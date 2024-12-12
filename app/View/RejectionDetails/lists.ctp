<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="rejectionDetails ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Rejection Details','modelClass'=>'RejectionDetail','options'=>array("sr_no"=>"Sr No","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'rejectionDetails'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Rejection Detail'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Production Rejection'), array('controller' => 'production_rejections', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Value Driver'), array('controller' => 'value_drivers', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Defect Type'), array('controller' => 'defect_types', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Performance Indicator'), array('controller' => 'performance_indicators', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="rejectionDetails_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>