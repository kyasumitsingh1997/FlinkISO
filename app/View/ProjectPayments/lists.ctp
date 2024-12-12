<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projectPayments ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Project Payments','modelClass'=>'ProjectPayment','options'=>array("sr_no"=>"Sr No","amount"=>"Amount","amount_received"=>"Amount Received","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'pluralVar'=>'projectPayments'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New Project Payment'), array('action' => 'add_ajax','
						project_id'=>$this->request->params['named']['project_id'],
						'milestone_id'=>$this->request->params['named']['milestone_id'],
						'invoice_id'=>$this->request->params['named']['invoice_id'],
						)); ?></li>
					<li><?php // echo $this->Html->link(__('Add Project'), array('controller' => 'projects', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Milestone'), array('controller' => 'milestones', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Purchase Order'), array('controller' => 'purchase_orders', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Invoice'), array('controller' => 'invoices', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="projectPayments_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","amount"=>"Amount","amount_received"=>"Amount Received","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","amount"=>"Amount","amount_received"=>"Amount Received","record_status"=>"Record Status","prepared_by"=>"Prepared By","approved_by"=>"Approved By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>