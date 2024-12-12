<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="listOfKpis ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'List Of Kpis','modelClass'=>'ListOfKpi','options'=>array("sr_no"=>"Sr No","title"=>"Title","kpi_details"=>"Kpi Details","other_details"=>"Other Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'listOfKpis'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New List Of Kpi'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Branch'), array('controller' => 'branches', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Department'), array('controller' => 'departments', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Employee'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="listOfKpis_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","kpi_details"=>"Kpi Details","other_details"=>"Other Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","kpi_details"=>"Kpi Details","other_details"=>"Other Details","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>