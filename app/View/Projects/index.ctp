<style type="text/css">
.progress.xs, .progress-xs{height: 2px;}
</style>
<?php echo $this->element('checkbox-script'); ?><div  id="main">
<?php echo $this->Session->flash();?>	
	<div class="projects ">
		<?php echo $this->element('nav-header-lists',array('postData'=>array('pluralHumanName'=>'Projects','modelClass'=>'Project','options'=>array("sr_no"=>"Sr No","title"=>"Title","goal"=>"Goal","scope"=>"Scope","success_criteria"=>"Success Criteria","challenges"=>"Challenges","project_cost"=>"Project Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","employees"=>"Employees","customers"=>"Customers","suppliers_vendors"=>"Suppliers Vendors","others"=>"Others","users"=>"Users"),'pluralVar'=>'projects'))); ?>

<script type="text/javascript">
$(document).ready(function(){
$('table th a, .pag_list li span a').on('click', function() {
	var url = $(this).attr("href");
	$('#main').load(url);
	return false;
});
});
</script>			
			<div class="row">
				<div class="col-md-12">
					<div id="status_tabs">
						<ul>
							<?php foreach ($currentStatuses as $key => $value) { ?>
								<li aria-controls = "<?php echo $value;?>" id = "<?php echo $key;?>-<?php echo $value;?>">
									<?php echo $this->Html->link($value, array('action' => 'index_projects','current_status'=>$key,'aria-controls'=>$key),array()); ?></li>
							<?php } ?>
							<li><?php echo $this->Html->link('Unpublished', array('action' => 'index_projects','published'=>0,)); ?></li>
							<li><?php echo $this->Html->link('Deleted', array('action' => 'index_projects','soft_delete'=>1)); ?></li>
						</ul>
					</div>
				</div>
			</div>
<script>
  $(function() {
    $( "#status_tabs" ).tabs({
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
<?php echo $this->element('advanced-search',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","goal"=>"Goal","scope"=>"Scope","success_criteria"=>"Success Criteria","challenges"=>"Challenges","project_cost"=>"Project Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","employees"=>"Employees","customers"=>"Customers","suppliers_vendors"=>"Suppliers Vendors","others"=>"Others","users"=>"Users"),'PublishedBranchList'=>array($PublishedBranchList))); ?>
<?php echo $this->element('import',array('postData'=>array("sr_no"=>"Sr No","title"=>"Title","goal"=>"Goal","scope"=>"Scope","success_criteria"=>"Success Criteria","challenges"=>"Challenges","project_cost"=>"Project Cost","start_date"=>"Start Date","end_date"=>"End Date","current_status"=>"Current Status","employees"=>"Employees","customers"=>"Customers","suppliers_vendors"=>"Suppliers Vendors","others"=>"Others","users"=>"Users"))); ?>
<?php echo $this->element('approvals'); ?>
<?php echo $this->element('common'); ?>
</div>

<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
