<div class="main">
	<div class="row">
		<div class="col-md-12">
			<div id="clauses-tabs">	
				<ul>
					<?php
						foreach ($standards as $key => $value) {
							echo "<li>". $this->Html->link($value, array('action' => 'index',$key,'jqload'=>0),array('id'=>'document-standard-'.$key))."</li>";
						}
					?>
					<!-- <li><?php echo $this->Html->link(__('ISO-9001:2008'), array('action' => 'index','2008')); ?></li>
					<li><?php echo $this->Html->link(__('ISO-9001:2015'), array('action' => 'index','2015')); ?></li>
					<li><?php echo $this->Html->link(__('ISO-14001'), array('action' => 'index','14001')); ?></li>
					<li><?php echo $this->Html->link(__('ISO-27001'), array('action' => 'index','27001')); ?></li> -->
					<li><?php echo $this->Html->image('indicator.gif', array('id' => 'busy-indicator','class'=>'pull-right')); ?></li>
				</ul>
			</div>
			<div id="documents_clause_tab_ajax"></div>
		</div>
	</div>
</div>
<script>
  $(function() {
    $( "#clauses-tabs" ).tabs({
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
