  <div id="subtabs" class="nav-tabs-info">
    <ul class="nav nav-tabs">      
      <?php if ($this->Session->read('User.is_mr') != false) { ?>
      <li><?php echo $this->Html->link(__('Record Graph'), array('controller' => 'histories', 'action' => 'graph_data')); ?></li>
      <li><?php echo $this->Html->link(__('Branch Graph'), array('controller' => 'histories', 'action' => 'graph_data_branches')); ?></li>
      <li><?php echo $this->Html->link(__('Department Graph'), array('controller' => 'histories', 'action' => 'graph_data_departments')); ?></li>
      <li><?php echo $this->Html->link(__('Proposal Followup Graph'), array('controller' => 'proposals', 'action' => 'proposal_graph')); ?></li>      
      <?php } ?>
      <li><?php echo $this->Html->link(__('Timeline'), array('controller' => 'timelines', 'action' => 'timeline')); ?></li>
      <li><?php echo $this->Html->image('indicator.gif', array('id' => 'subtabs-busy-indicator', 'class' => 'pull-right')); ?></li>
    </ul>
  </div>

<div id="subtabs_ajax"></div>
<script>
$(document).ready(function() {
	$.ajaxSetup({
    cache:false,
   
    });

    $( "#subtabs" ).tabs({
        load: function( event, ui ) {
            $("#subtabs-busy-indicator").hide();
        },
        ajaxOptions: {
            error: function( xhr, status, index, anchor ) {
                $( anchor.hash ).html(
                    "Couldn't load this tab. We'll try to fix this as soon as possible. " +
                    "If this wouldn't be a demo." );
            }
        }
    });

	$( "#subtabs li" ).click(function() {
  		$("#subtabs-busy-indicator").show();
	});
});
</script>
