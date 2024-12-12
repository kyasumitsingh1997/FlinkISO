<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="fmeaActions ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Fmea Actions','modelClass'=>'FmeaAction','options'=>array("sr_no"=>"Sr No","actions_recommended"=>"Actions Recommended","target_date"=>"Target Date","action_taken"=>"Action Taken","action_taken_date"=>"Action Taken Date","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'fmeaActions'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Fmea Action'), array('action' => 'add_ajax','fmea_id'=>$this->request->params['named']['fmea_id'])); ?></li>
					<li><?php // echo $this->Html->link(__('Add Fmea'), array('controller' => 'fmeas', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Fmea Severity Type'), array('controller' => 'fmea_severity_types', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Fmea Occurence'), array('controller' => 'fmea_occurences', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Fmea Detection'), array('controller' => 'fmea_detections', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="fmeaActions_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","actions_recommended"=>"Actions Recommended","target_date"=>"Target Date","action_taken"=>"Action Taken","action_taken_date"=>"Action Taken Date","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","actions_recommended"=>"Actions Recommended","target_date"=>"Target Date","action_taken"=>"Action Taken","action_taken_date"=>"Action Taken Date","rpn"=>"Rpn","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>