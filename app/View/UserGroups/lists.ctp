<div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="userGroups ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'User Groups','modelClass'=>'UserGroup','options'=>array("sr_no"=>"Sr No","title"=>"Title","group_leader"=>"Group Leader","group_members"=>"Group Members","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'pluralVar'=>'userGroups'))); ?>
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('New User Group'), array('action' => 'add_ajax')); ?></li>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="userGroups_tab_ajax"></div>
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","group_leader"=>"Group Leader","group_members"=>"Group Members","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","group_leader"=>"Group Leader","group_members"=>"Group Members","record_status"=>"Record Status","approved_by"=>"Approved By","prepared_by"=>"Prepared By"))); ?>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>