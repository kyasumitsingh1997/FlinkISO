<?php echo $this->Session->flash();?>
<div class="main">
	<div class="row">
		<div class="col-md-12">
			<h3><?php echo __('Standard-wise Documents');?><small class="btn-group pull-right">
				<?php echo $this->Html->link('Add New Standard',array('controller'=>'standards', 'action'=>'lists'),array('class'=>'btn btn-xs btn-info '));?>
				<?php echo $this->Html->link('Add New Clause',array('action'=>'lists'),array('class'=>'btn btn-xs btn-info'));?></small></h3>
		</div>
		<div class="col-md-12">
			<div id="standards_tabs">	
				<ul>
					<?php
						foreach ($standards as $key => $value) {
							echo "<li>". $this->Html->link($value, array('action' => 'documents',$key,'jqload'=>0),array('id'=>'document-standard-'.$key))."</li>";
						}
					?>
					<li><?php // echo $this->Html->link(__('Add Company'), array('controller' => 'companies', 'action' => 'add_ajax')); ?> </li>
					<li><?php // echo $this->Html->link(__('Add Prepared By'), array('controller' => 'employees', 'action' => 'add_ajax')); ?> </li>
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
			<div id="documents_tab_ajax"></div>
		</div>
	</div>
</div>
<script>
  $(function() {
    $( "#standards_tabs" ).tabs({
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
<script>$.ajaxSetup({beforeSend:function(){$("#busy-indicator").show();},complete:function(){$("#busy-indicator").hide();}});</script>
