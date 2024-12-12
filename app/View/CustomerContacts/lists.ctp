<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="customerContacts ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Customer Contacts','modelClass'=>'CustomerContact','options'=>array("sr_no"=>"Sr No","name"=>"Name","phone"=>"Phone","mobile"=>"Mobile","email"=>"Email","address"=>"Address","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'customerContacts'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Customer Contact'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Customer'), array('controller' => 'customers', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Proposal Followup'), array('controller' => 'proposal_followups', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Proposal'), array('controller' => 'proposals', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="customerContacts_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","phone"=>"Phone","mobile"=>"Mobile","email"=>"Email","address"=>"Address","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","name"=>"Name","phone"=>"Phone","mobile"=>"Mobile","email"=>"Email","address"=>"Address","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>