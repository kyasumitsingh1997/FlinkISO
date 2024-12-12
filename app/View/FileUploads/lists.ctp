<style type="text/css">
	#fileUploads_tab_ajax{padding: 20px;}
</style>
<div>
<?php echo $this->Session->flash();?>	
	<div class="fileUploads ">		
		<div class="nav">
			<div id="tabs">	
				<ul>
					<li><?php echo $this->Html->link(__('Files By User'), array('action' => 'by_users')); ?></li>
					<li><?php echo $this->Html->link(__('Files By Approval'), array('action' => 'by_approvals')); ?></li>
					<li><?php echo $this->Html->link(__('Files By Records'), array('action' => 'by_table')); ?></li>
					<li><?php echo $this->Html->link(__('Archived Files'), array('action' => 'index','archived'=>1)); ?></li>
					<li><?php echo $this->Html->link(__('Deleted Files'), array('action' => 'index','deleted'=>0)); ?></li>
					<li><?php echo $this->Html->link(__('Unpublished Files'), array('action' => 'index','unpublished'=>0)); ?></li>
					<!-- <li><?php echo $this->Html->link(__('Advanced Search'), array('action' => 'file_advanced_search')); ?></li> -->
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
		</div>
	<div id="fileUploads_tab_ajax"></div>
</div>

<script>
  $(function() {
    $( "#tabs" ).tabs({
    	cache: false
    });
  });
</script>
</div>
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
